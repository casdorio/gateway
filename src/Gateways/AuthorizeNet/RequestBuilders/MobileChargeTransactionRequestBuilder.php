<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use net\authorize\api\contract\v1 as AnetAPI;

class MobileChargeTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("mobile.Authorize.Net");
        $transactionRequestType->setPayment($this->createMobilePaymentType($params[0], $params[1])); // token, paymentMethod
        $transactionRequestType->setAmount($payment->amount);
        return $transactionRequestType;
    }

    private function createMobilePaymentType(string $token, string $paymentMethod): AnetAPI\PaymentType
    {
        $opaqueData = new AnetAPI\OpaqueDataType();
        $opaqueData->setDataValue($token);
        $opaqueData->setDataType($paymentMethod); // "applepay" or "googlepay"

        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setOpaqueData($opaqueData);
        return $paymentType;
    }
}