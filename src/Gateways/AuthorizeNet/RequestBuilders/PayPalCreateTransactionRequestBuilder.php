<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use net\authorize\api\contract\v1 as AnetAPI;

class PayPalCreateTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("paypal.Authorize.Net");
        $transactionRequestType->setPayment($this->createPayPalType($payment));
        $transactionRequestType->setAmount($payment->amount);
        return $transactionRequestType;
    }

    private function createPayPalType(Payment $payment): AnetAPI\PayPalType
    {
        $payPalType = new AnetAPI\PayPalType();
        $payPalType->setCancelUrl($payment->paypal_cancel_url);
        $payPalType->setSuccessUrl($payment->paypal_success_url);
        $payPalType->setPayflowcolor('C0C0C0');
        return $payPalType;
    }
}