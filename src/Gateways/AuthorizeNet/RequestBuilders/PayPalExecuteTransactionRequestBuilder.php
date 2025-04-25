<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;

use net\authorize\api\contract\v1 as AnetAPI;

class PayPalExecuteTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("paypal.Execute.Authorize.Net");
        $transactionRequestType->setRefTransId($params[0]); // Usando refTransId para passar o token
        $transactionRequestType->setAuthCode($params[1]);    // Usando authCode para passar o payerId
        return $transactionRequestType;
    }
}