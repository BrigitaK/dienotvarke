<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Status;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status_index", methods={"GET"})
     */
    public function index(): Response
    {
        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findAll();
        
        return $this->render('status/index.html.twig', [
            'statuss' => $statuss,
        ]);
    }
    /**
     * @Route("/status/create", name="status_create", methods={"GET"})
     */
    public function create(): Response
    {
        return $this->render('status/create.html.twig', [
        ]);
    }
     /**
     * @Route("/status/store", name="status_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        $status= New Status;
        $status->
        setTitle($r->request->get('status_title'));

       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($status);
        $entityManager->flush();

        return $this->redirectToRoute('status_index');
    }
    /**
     * @Route("/status/edit/{id}", name="status_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find($id);

        return $this->render('status/edit.html.twig', [
            'status' => $status,
        ]);
    }
     /**
     * @Route("/status/update/{id}", name="status_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find($id);

        $status->
        setTitle($r->request->get('status_title'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($status);
        $entityManager->flush();

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

        // if ($status->getOutfits()->count() > 0) {
        //     return new Response('Šio statuso ištrinti negalima, nes turi gaminių.');
        // }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($status);
        $entityManager->flush();

        return $this->redirectToRoute('status_index');
    }

}
