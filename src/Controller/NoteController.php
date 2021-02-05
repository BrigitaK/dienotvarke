<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Status;
use App\Entity\Note;

class NoteController extends AbstractController
{
    /**
     * @Route("/note", name="note_index")
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findBy([],['title' => 'asc']);

        $notes = $this->getDoctrine()
        ->getRepository(Note::class);
          if(null !== $r->query->get('title')){
            $notes = $notes->findBy(['title' => $r->query->get('title')]);
        }
        elseif ($r->query->get('title') == 0) {
            $notes = $notes->findAll(); 
        }
        else {
            $notes = $notes->findAll();
        };
        
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
            'statuss' => $statuss,
            'outfitType' => $r->query->get('type') ?? 'default',
            'sortBy' => $r->query->get('sort') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/note/create", name="note_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $note_title = $r->getSession()->getFlashBag()->get('note_title', []);
        $note_priority = $r->getSession()->getFlashBag()->get('note_priority', []);
        $note_note = $r->getSession()->getFlashBag()->get('note_note', []);

        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findBy([],['title' => 'asc']);

        $errors = $validator->validate($note);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('note_title', $r->request->get('note_title'));
            $r->getSession()->getFlashBag()->add('note_priority', $r->request->get('note_priority'));
            $r->getSession()->getFlashBag()->add('note_note', $r->request->get('note_note'));

            return $this->redirectToRoute('note_create');
        }

        return $this->render('note/create.html.twig', [
            'statuss' => $statuss,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'note_title' => $note_title[0] ?? '',
            'note_priority' => $note_priority[0] ?? '',
            'note_note' => $note_note[0] ?? ''
        ]);
    }
    /**
     * @Route("/note/store", name="note_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $status = $this->getDoctrine()
        ->getRepository(Status::class)
        ->find((int)$r->request->get('note_status_id'));

        $note = New Note;
        $note->
        setTitle($r->request->get('note_title'))->
        setPriority($r->request->get('note_priority'))->
        setNote((int)$r->request->get('note_note'))->
        setStatus($status);
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($note);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'note sekmingai pridetas.'); 

        return $this->redirectToRoute('note_index');
    }
    /**
     * @Route("/note/edit/{id}", name="note_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $note = $this->getDoctrine()
        ->getRepository(Note::class)
        ->find($id);

        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findAll();

        $note_title = $r->getSession()->getFlashBag()->get('note_title', []);
        $note_priority = $r->getSession()->getFlashBag()->get('note_priority', []);
        $note_note = $r->getSession()->getFlashBag()->get('note_note', []);

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'statuss' => $statuss,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'note_title' => $note_title[0] ?? '',
            'note_priority' => $note_priority[0] ?? '',
            'note_note' => $note_note[0] ?? ''
        ]);
    }
       /**
     * @Route("/note/update/{id}", name="note_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $note = $this->getDoctrine()
        ->getRepository(Note::class)
        ->find($id);

        $status = $this->getDoctrine()
         ->getRepository(Status::class)
         ->find((int)$r->request->get('note_status_id'));

         $note->
        setTitle($r->request->get('note_title'))->
        setPriority($r->request->get('note_priority'))->
        setNote((int)$r->request->get('note_note'))->
        setStatus($status);

        $errors = $validator->validate($outfit);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('note_title', $r->request->get('note_title'));
            $r->getSession()->getFlashBag()->add('note_priority', $r->request->get('note_priority'));
            $r->getSession()->getFlashBag()->add('note_note', $r->request->get('note_note'));

            return $this->redirectToRoute('note_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($note);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'note sekmingai pakeistas.');

        //grazinu redirect
        return $this->redirectToRoute('note_index');
    }
      /**
     * @Route("/note/delete/{id}", name="note_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $note = $this->getDoctrine()
        ->getRepository(Note::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($note);
        $entityManager->flush();

        //grazinu redirect
        return $this->redirectToRoute('note_index');
    }

}
