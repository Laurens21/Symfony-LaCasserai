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

    /**
    * @Route("/reserveringenvrij", name="reserveringenvrij")
    */
    public function vrijekamers(BookingRepository $BookingRepository): Response
    {
        $value = ['checkin' => '2015-01-01', 'checkout' => '2015-01-01'];
        $reserveringen = $BookingRepository->findvrijekamers($value);


        $em = $this->getDoctrine()->getManager();
        $kamers = $em->getRepository('App:Room')->findAll();

        dump ($kamers);
        $reskamers =[];
        foreach ($reserveringen as $reservering) {
            dump($reservering);
            array_push($reskamers, $reservering->getRoomId()->getId());
            dump($reskamers);
        }


        foreach ($kamers as $key => $kamer) {
            foreach ($reskamers as $reskam) {
                if ($kamer->getId() == $reskam) {
                    // verwijder de bezette kamers.
                    unset($kamers[$key]);  
                }
            }
        }

        dump($kamers);

        return $this->render('room/index.html.twig', [
            'rooms' => $kamers
        ]);

    }

}
