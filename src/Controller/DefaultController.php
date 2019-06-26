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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(BookingRepository $bookingRepository , Request $request): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
    * @Route("/rooms", name="room_check", methods={"POST"})
    */
    public function vrijekamers(BookingRepository $BookingRepository): Response
    {
        // $value = ['checkin' => '2014-01-01', 'checkout' => '2014-01-01'];

        $StartDate = $_POST['StartDate'];
        $EndDate = $_POST['EndDate'];
        
        $value = array('checkin' => $StartDate, 'checkout' => $EndDate);

        $reserveringen = $BookingRepository->findvrijekamers($value);


        $em = $this->getDoctrine()->getManager();
        $kamers = $em->getRepository('App:Room')->findAll();

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
