<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="payment_index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('payment/index.html.twig', [
                'payments' => $paymentRepository->findAll(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/new", name="payment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            $payment = new Payment();
            $form = $this->createForm(PaymentType::class, $payment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($payment);
                $entityManager->flush();

                return $this->redirectToRoute('payment_index');
            }

            return $this->render('payment/new.html.twig', [
                'payment' => $payment,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="payment_show", methods={"GET"})
     */
    public function show(Payment $payment): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('payment/show.html.twig', [
                'payment' => $payment,
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}/edit", name="payment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Payment $payment): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(PaymentType::class, $payment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('payment_index', [
                    'id' => $payment->getId(),
                ]);
            }

            return $this->render('payment/edit.html.twig', [
                'payment' => $payment,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="payment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Payment $payment): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($payment);
                $entityManager->flush();
            }

        return $this->redirectToRoute('payment_index');
        } else {
            return $this->redirectToRoute('default');
        }
    }
}
