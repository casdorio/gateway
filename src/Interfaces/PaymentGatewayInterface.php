<?php

namespace Casdorio\GatewayPayment\Interfaces;

use Casdorio\GatewayPayment\Entities\Payment;

interface PaymentGatewayInterface
{
    /**
     * Processa um pagamento com cartão de crédito.
     *
     * @param Payment $payment Detalhes do pagamento.
     * @return array Resultado da transação.
     */
    public function chargeCreditCard(Payment $payment): array;

    /**
     * Processa um pagamento com cartão de débito.
     *
     * @param Payment $payment Detalhes do pagamento.
     * @return array Resultado da transação.
     */
    public function chargeDebitCard(Payment $payment): array;

    /**
     * Autoriza um pagamento com cartão de crédito.
     *
     * @param Payment $payment Detalhes do pagamento.
     * @return array Resultado da autorização.
     */
    public function authorize(Payment $payment): array;

    /**
     * Captura fundos de uma transação previamente autorizada.
     *
     * @param string $transactionId ID da transação a ser capturada.
     * @param float $amount Valor a ser capturado.
     * @return array Resultado da captura.
     */
    public function capture(Payment $payment, string $transactionId): array;

    /**
     * Realiza um reembolso de uma transação.
     *
     * @param Payment $payment Detalhes do pagamento.
     * @param string $transactionId ID da transação a ser reembolsada.
     * @return array Resultado do reembolso.
     */
    public function refund(Payment $payment, string $transactionId): array;

    /**
     * Cancela (void) uma transação.
     *
     * @param string $transactionId ID da transação a ser cancelada.
     * @return array Resultado do cancelamento.
     */
    public function void(string $transactionId): array;

    /**
     * Obtém os detalhes de uma transação.
     *
     * @param string $transactionId ID da transação a ser consultada.
     * @return array Detalhes da transação.
     */
    public function getTransactionDetails(string $transactionId): array;

    /**
     * Processa um pagamento via e-check.
     *
     * @param Payment $payment
     * @return array
     */
    public function chargeECheck(Payment $payment): array;

    /**
     * Cancela um pagamento via e-check.
     *
     * @param string $transactionId
     * @return array
     */
    public function voidECheck(string $transactionId): array;

    /**
     * Inicia um pagamento com PayPal.
     *
     * @param Payment $payment
     * @return array
     */
    public function createPayPalPayment(Payment $payment): array;

     /**
     * Captura um pagamento do PayPal.
     *
     * @param string $transactionId
     * @return array
     */
    public function capturePayPalPayment(string $transactionId): array;

    /**
     * Executa um pagamento do PayPal.
     *
     * @param string $token
     * @param string $payerId
     * @return array
     */
    public function executePayPalPayment(string $token, string $payerId): array;

    /**
    * Reembolsa um pagamento do PayPal.
    *
    * @param string $transactionId
    * @param float $amount
    * @return array
    */
    public function refundPayPalPayment(Payment $payment, string $transactionId): array;

    /**
     * Processa um pagamento móvel (ex: Apple Pay, Google Pay).
     *
     * @param Payment $payment
     * @param string $token
     * @param string $paymentMethod
     * @return array
     */
    public function chargeMobilePayment(Payment $payment, string $token, string $paymentMethod): array;
}