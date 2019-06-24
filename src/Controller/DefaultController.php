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

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(BookingRepository $bookingRepository , Request $request): Response
    {

        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $_SESSION['start'] = $request->query->get('check_in_date');
            $_SESSION['end'] = $request->query->get('check_out_date');
            $bookingRepository->findReservationsBetween($_SESSION['start'], $_SESSION['end']);

            return $this->redirectToRoute('room_index');
        }

        return $this->render('default/index.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

}
