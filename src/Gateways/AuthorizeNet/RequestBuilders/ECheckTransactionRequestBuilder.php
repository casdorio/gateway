<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\ECheckInfo; // Importando a entidade ECheckInfo
use net\authorize\api\contract\v1 as AnetAPI;

class ECheckTransactionRequestBuilder implements RequestBuilderInterface
{
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType
    {
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("echeck.Authorize.Net"); // Mantendo o tipo de transação como echeck
        $transactionRequestType->setPayment($this->createECheckPaymentType($payment->echeck_info));
        $transactionRequestType->setAmount($payment->amount);
        return $transactionRequestType;
    }

    private function createECheckPaymentType(ECheckInfo $echeckInfo): AnetAPI\PaymentType
    {
        $bankAccount = new AnetAPI\BankAccountType();
        $bankAccount->setRoutingNumber($echeckInfo->routing_number);
        $bankAccount->setAccountNumber($echeckInfo->account_number);
        $bankAccount->setNameOnAccount($echeckInfo->name_on_account);
        $bankAccount->setEcheckType($echeckInfo->echeck_type); // Mantendo echeck type
        $bankAccount->setBankName($echeckInfo->bank_name);

        $paymentType = new AnetAPI\PaymentType();
        $paymentType->setBankAccount($bankAccount);
        return $paymentType;
    }
}