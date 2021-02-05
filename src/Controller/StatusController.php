<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Status;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $statuss = $this->getDoctrine()
        ->getRepository(Status::class);
       if('title_az' == $r->query->get('sort')) {
            $statuss = $statuss->findBy([],['title' => 'asc']);
        }
        else {
            $statuss = $statuss->findAll();
        }
        
        return $this->render('status/index.html.twig', [
            'statuss' => $statuss,
            'sortBy' => $r->query->get('sort') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/status/create", name="status_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $status_title = $r->getSession()->getFlashBag()->get('status_title', []);

        return $this->render('status/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'status_title' => $status_title[0] ?? ''
        ]);
    }
     /**
     * @Route("/status/store", name="status_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        if (!$this->isCsrfTokenValid('', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('status_create');
        } 

        $status= New Status;
        $status->
        setTitle($r->request->get('status_title'));

        $errors = $validator->validate($status);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }

            $r->getSession()->getFlashBag()->add('status_title', $r->request->get('status_title'));

            return $this->redirectToRoute('status_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($status);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'status sekmingai pridetas');

        return $this->redirectToRoute('status_index');
    }
    /**
     * @Route("/status/edit/{id}", name="status_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find($id);

        $status_title = $r->getSession()->getFlashBag()->get('status_title', []);

        return $this->render('status/edit.html.twig', [
            'status' => $status,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'status_title' => $status_title[0] ?? ''
        ]);
    }
     /**
     * @Route("/status/update/{id}", name="status_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find($id);

        $status->
        setTitle($r->request->get('status_title'));

        $errors = $validator->validate($master);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }

            $r->getSession()->getFlashBag()->add('status_title', $r->request->get('status_title'));

            return $this->redirectToRoute('status_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($status);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'status sekmingai pakeistas');

        return $this->redirectToRoute('status_index');
    }
     /**
     * @Route("/status/delete/{id}", name="status_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find($id);

        if ($status->getNotes()->count() > 0) {
            return new Response('Šio statuso ištrinti negalima, nes turi gaminių.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($status);
        $entityManager->flush();

        return $this->redirectToRoute('status_index');
    }

}
