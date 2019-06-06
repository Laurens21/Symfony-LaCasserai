<?php

namespace App\Controller;

use App\Entity\Roomtype;
use App\Form\RoomtypeType;
use App\Repository\RoomtypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roomtype")
 */
class RoomtypeController extends AbstractController
{
    /**
     * @Route("/", name="roomtype_index", methods={"GET"})
     */
    public function index(RoomtypeRepository $roomtypeRepository): Response
    {
        return $this->render('roomtype/index.html.twig', [
            'roomtypes' => $roomtypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="roomtype_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $roomtype = new Roomtype();
        $form = $this->createForm(RoomtypeType::class, $roomtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($roomtype);
            $entityManager->flush();

            return $this->redirectToRoute('roomtype_index');
        }

        return $this->render('roomtype/new.html.twig', [
            'roomtype' => $roomtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="roomtype_show", methods={"GET"})
     */
    public function show(Roomtype $roomtype): Response
    {
        return $this->render('roomtype/show.html.twig', [
            'roomtype' => $roomtype,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="roomtype_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Roomtype $roomtype): Response
    {
        $form = $this->createForm(RoomtypeType::class, $roomtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('roomtype_index', [
                'id' => $roomtype->getId(),
            ]);
        }

        return $this->render('roomtype/edit.html.twig', [
            'roomtype' => $roomtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="roomtype_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Roomtype $roomtype): Response
    {
        if ($this->isCsrfTokenValid('delete'.$roomtype->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($roomtype);
            $entityManager->flush();
        }

        return $this->redirectToRoute('roomtype_index');
    }
}
