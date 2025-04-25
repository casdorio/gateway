<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;

use net\authorize\api\contract\v1 as AnetAPI;

class PayPalRefundTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("paypal.Refund.Authorize.Net");
        $transactionRequestType->setAmount($params[0]);       // O primeiro parâmetro é o amount
        $transactionRequestType->setRefTransId($params[1]); // O segundo parâmetro é o transactionId
        return $transactionRequestType;
    }
}