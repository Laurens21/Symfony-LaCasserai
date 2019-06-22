<?php

namespace App\Controller;

use App\Entity\Paymentmethod;
use App\Form\PaymentmethodType;
use App\Repository\PaymentmethodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/paymentmethod")
 */
class PaymentmethodController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="paymentmethod_index", methods={"GET"})
     */
    public function index(PaymentmethodRepository $paymentmethodRepository): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('paymentmethod/index.html.twig', [
                'paymentmethods' => $paymentmethodRepository->findAll(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/new", name="paymentmethod_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            $paymentmethod = new Paymentmethod();
            $form = $this->createForm(PaymentmethodType::class, $paymentmethod);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($paymentmethod);
                $entityManager->flush();

                return $this->redirectToRoute('paymentmethod_index');
            }

            return $this->render('paymentmethod/new.html.twig', [
                'paymentmethod' => $paymentmethod,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="paymentmethod_show", methods={"GET"})
     */
    public function show(Paymentmethod $paymentmethod): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return $this->render('paymentmethod/show.html.twig', [
                'paymentmethod' => $paymentmethod,
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}/edit", name="paymentmethod_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Paymentmethod $paymentmethod): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(PaymentmethodType::class, $paymentmethod);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('paymentmethod_index', [
                    'id' => $paymentmethod->getId(),
                ]);
            }

            return $this->render('paymentmethod/edit.html.twig', [
                'paymentmethod' => $paymentmethod,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('default');
        }
    }

    /**
     * @Route("/{id}", name="paymentmethod_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Paymentmethod $paymentmethod): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            if ($this->isCsrfTokenValid('delete'.$paymentmethod->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($paymentmethod);
                $entityManager->flush();
            }

            return $this->redirectToRoute('paymentmethod_index');
        } else {
            return $this->redirectToRoute('default');
        }
    }
}
