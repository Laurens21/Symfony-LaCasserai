<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {   
        if ($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('booking/index.html.twig', [
                'bookings' => $bookingRepository->findAll(),
            ]);
        } elseif($this->security->isGranted('ROLE_CUSTOMER')) {
            $user = $this->getUser()->getId();
            return $this->render('booking/index.html.twig', [
                'bookings' => $bookingRepository->findBy(['user_id' => $user]),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/new", name="booking_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('booking/show.html.twig', [
                'booking' => $booking,
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Booking $booking): Response
    {
        if($this->security->isGranted('ROLE_ADMIN')){
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index', [
                'id' => $booking->getId(),
            ]);
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);

        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if($this->security->isGranted('ROLE_ADMIN')){
            if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($booking);
                $entityManager->flush();
            }
        return $this->redirectToRoute('booking_index');
        } else {
            return $this->redirectToRoute('default');
        }
    }
}
