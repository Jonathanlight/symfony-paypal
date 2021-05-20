<?php

namespace App\Controller;

use App\Manager\ProductManager;
use App\Services\PaypalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PaypalService $paypalService, ProductManager $productManager): Response
    {
        //dd($paypalService->paypal('100.00', 'EUR'));

        return $this->render('default/index.html.twig', [
            'paypalService' => $paypalService,
            'products' => $productManager->collection()
        ]);
    }

    /**
     * @Route("/response_paypal_redirect", name="response_paypal_redirect")
     */
    public function responsePaypalRedirect(Request $request): Response
    {
        dd($request);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/response_paypal_return_cancel", name="response_paypal_cancel")
     */
    public function responsePaypalCancel(Request $request): Response
    {
        dd($request);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
