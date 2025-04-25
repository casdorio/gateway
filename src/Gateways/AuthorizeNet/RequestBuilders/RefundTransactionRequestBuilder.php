<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\CardInfo;
use net\authorize\api\contract\v1 as AnetAPI;

class RefundTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("refundTransaction");
        $transactionRequestType->setAmount($payment->amount);
        $transactionRequestType->setPayment($this->createPaymentType($payment->card_info));
        $transactionRequestType->setRefTransId($params[0]); // O primeiro parâmetro é o transactionId
        return $transactionRequestType;
    }

    private function createPaymentType(CardInfo $card): AnetAPI\PaymentType
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card->card_number);
        $creditCard->setExpirationDate($card->expiration_date);
        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setCreditCard($creditCard);
        return $paymentType;
    }
}