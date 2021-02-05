<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Status;
use App\Entity\Note;

class NoteController extends AbstractController
{
    /**
     * @Route("/note", name="note_index")
     */
    public function index(): Response
    {
        $notes = $this->getDoctrine()
        ->getRepository(Note::class)
        ->findAll();
        
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }
    /**
     * @Route("/note/create", name="note_create", methods={"GET"})
     */
    public function create(): Response
    {
        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findAll();

        return $this->render('note/create.html.twig', [
            'statuss' => $statuss,
        ]);
    }
    /**
     * @Route("/note/store", name="note_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        // $status = $this->getDoctrine()
        // ->getRepository(Status::class)
        // ->find((int)$r->request->get('note_status_id'));

        $note = New Note;
        $note->
        setTitle($r->request->get('note_title'))->
        setPriority($r->request->get('note_priority'))->
        setNote((int)$r->request->get('note_note'))->
        setStatusId((int)$r->request->get('note_status_id'));
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($note);
        $entityManager->flush();

        return $this->redirectToRoute('note_index');
    }
    /**
     * @Route("/note/edit/{id}", name="note_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $note = $this->getDoctrine()
        ->getRepository(Note::class)
        ->find($id);

        $statuss = $this->getDoctrine()
        ->getRepository(Status::class)
        ->findAll();

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'statuss' => $statuss
        ]);
    }
       /**
     * @Route("/note/update/{id}", name="note_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {
        // $note = $this->getDoctrine()
        // ->getRepository(Note::class)
        // ->find($id);

        $status = $this->getDoctrine()
         ->getRepository(Status::class)
         ->find($r->request->get('note_status_id'));

         $note->
        setTitle($r->request->get('note_title'))->
        setPriority($r->request->get('note_priority'))->
        setNote((int)$r->request->get('note_note'))->
        setStatusId((int)$r->request->get('note_status_id'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($note);
        $entityManager->flush();

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
