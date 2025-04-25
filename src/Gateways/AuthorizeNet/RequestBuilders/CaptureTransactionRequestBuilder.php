<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;

use net\authorize\api\contract\v1 as AnetAPI;

class CaptureTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("priorAuthCaptureTransaction");
        $transactionRequestType->setRefTransId($params[0]); // O primeiro parâmetro é o transactionId
        $transactionRequestType->setAmount($params[1]); // O segundo parâmetro é o amount
        return $transactionRequestType;
    }
}