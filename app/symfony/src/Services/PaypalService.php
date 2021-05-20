<?php

namespace App\Services;

class PaypalService
{
    const PAYMENT_METHOD = 'paypal';
    protected $apiContext;

    public function __construct()
    {
        // After Step 1
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $_ENV['PAYPAL_CLIENT_ID'], // ClientId
                $_ENV['PAYPAL_SECRET'] // ClientSecret
            )
        );
    }

    /**
     * @param string $price
     * @param string $currency
     * @return array
     */
    public function paypal(string $price, string $currency): array
    {
        // After Step 2
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod(self::PAYMENT_METHOD);

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($price);
        $amount->setCurrency($currency);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls
            ->setReturnUrl("https://localhost:9200/response_paypal_redirect")
            ->setCancelUrl("https://localhost:9200/response_paypal_cancel")
        ;

        $payment = new \PayPal\Api\Payment();
        $payment
            ->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls)
        ;

        // After Step 3
        try {
            $payment->create($this->apiContext);
            return [
                "response" => true,
                "data" => $payment,
                "link" => $payment->getApprovalLink()
            ];
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return [
                "response" => false,
                "message" => $ex->getMessage()
            ];
        }
    }
}