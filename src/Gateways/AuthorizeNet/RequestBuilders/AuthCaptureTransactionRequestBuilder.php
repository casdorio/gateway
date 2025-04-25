<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\CardInfo;
use net\authorize\api\contract\v1 as AnetAPI;

class AuthCaptureTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($payment->amount);
        $transactionRequestType->setOrder($this->createOrder($payment));
        $transactionRequestType->setPayment($this->createPaymentType($payment->card_info));
        $transactionRequestType->setBillTo($this->createCustomerAddress($payment));
        $transactionRequestType->setShipTo($this->createCustomerAddressShip($payment));
        $transactionRequestType->setCustomer($this->createCustomerData($payment));
        return $transactionRequestType;
    }

     private function createPaymentType(CardInfo $card, bool $capture = false): AnetAPI\PaymentType
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card->card_number);
        $creditCard->setExpirationDate($card->expiration_date);
        if ($capture) {
            $creditCard->setCardCode($card->cvv);
        }
        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setCreditCard($creditCard);
        return $paymentType;
    }

    private function createCustomerAddress(Payment $payment): AnetAPI\CustomerAddressType
    {
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($payment->customer->first_name);
        $customerAddress->setLastName($payment->customer->last_name);
        $customerAddress->setPhoneNumber($payment->customer->phoneNumber);
        $customerAddress->setAddress($payment->billing_address->address);
        $customerAddress->setCity($payment->billing_address->city);
        $customerAddress->setZip($payment->billing_address->zip_code);
        $customerAddress->setState($payment->billing_address->state);
        $customerAddress->setCountry($payment->billing_address->country);
        return $customerAddress;
    }

    private function createCustomerAddressShip(Payment $payment): AnetAPI\CustomerAddressType
    {
        $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName($payment->customer->first_name);
        $customerShippingAddress->setLastName($payment->customer->last_name);
        $customerShippingAddress->setPhoneNumber($payment->customer->phoneNumber);
        $customerShippingAddress->setAddress($payment->delivery_address->address);
        $customerShippingAddress->setCity($payment->delivery_address->city);
        $customerShippingAddress->setZip($payment->delivery_address->zip_code);
        $customerShippingAddress->setState($payment->delivery_address->state);
        $customerShippingAddress->setCountry($payment->delivery_address->country);
        return $customerShippingAddress;
    }

    private function createOrder(Payment $payment): AnetAPI\OrderType
    {
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($payment->invoice_number);
        $order->setDescription($payment->description);
        return $order;
    }

    private function createCustomerData(Payment $payment): AnetAPI\CustomerDataType
    {
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($payment->customer_id);
        $customerData->setEmail($payment->email);
        return $customerData;
    }
}