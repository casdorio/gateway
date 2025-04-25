<?php

namespace Casdorio\GatewayPayment\Services;

use Casdorio\GatewayPayment\Interfaces\PaymentGatewayInterface;
use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Exceptions\PaymentException;

class PaymentService
{
    protected PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Processa um pagamento com cartão de crédito.
     *
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação.
     * @throws PaymentException Se a transação falhar.
     */
    public function chargeCreditCard(Payment $payment): array
    {
        try {
            return $this->gateway->chargeCreditCard($payment);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode()); // Lança a exceção para ser tratada no Controller
        }
    }

    /**
     * Processa um pagamento com cartão de débito.
     *
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação.
     * @throws PaymentException
     */
    public function chargeDebitCard(Payment $payment): array
    {
        try {
            return $this->gateway->chargeDebitCard($payment);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Autoriza um pagamento com cartão de crédito.
     *
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação.
     * @throws PaymentException
     */
    public function authorize(Payment $payment): array
    {
        try {
            return $this->gateway->authorize($payment);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Captura fundos de uma transação previamente autorizada.
     *
     * @param string $transactionId ID da transação a ser capturada.
     * @param float $amount Valor a ser capturado.
     * @return array Resultado da transação.
     * @throws PaymentException
     */
    public function capture(Payment $payment, string $transactionId): array
    {
        try {
            return $this->gateway->capture($payment, $transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Realiza um reembolso de uma transação.
     *
     * @param Payment $payment Entidade Payment com os detalhes do reembolso.
     * @param string $transactionId ID da transação a ser reembolsada.
     * @return array Resultado da transação.
     * @throws PaymentException
     */
    public function refund(Payment $payment, string $transactionId): array
    {
        try {
            return $this->gateway->refund($payment, $transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Cancela (void) uma transação.
     *
     * @param string $transactionId ID da transação a ser cancelada.
     * @return array Resultado da transação.
     * @throws PaymentException
     */
    public function void(string $transactionId): array
    {
        try {
            return $this->gateway->void($transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Obtém os detalhes de uma transação.
     *
     * @param string $transactionId ID da transação a ser consultada.
     * @return array Detalhes da transação.
     * @throws PaymentException
     */
    public function getTransactionDetails(string $transactionId): array
    {
        try {
            return $this->gateway->getTransactionDetails($transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    // Métodos para E-Check

    /**
     * Processa um pagamento via e-check.
     *
     * @param Payment $payment
     * @return array
     * @throws PaymentException
     */
    public function chargeECheck(Payment $payment): array
    {
        try {
            return $this->gateway->chargeECheck($payment);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

     /**
     * Cancela um pagamento via e-check.
     *
     * @param string $transactionId
     * @return array
      * @throws PaymentException
     */
    public function voidECheck(string $transactionId): array
    {
        try {
            return $this->gateway->voidECheck($transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    // Métodos para PayPal

    /**
     * Inicia um pagamento com PayPal.
     *
     * @param Payment $payment
     * @return array
     * @throws PaymentException
     */
    public function createPayPalPayment(Payment $payment): array
    {
        try {
            return $this->gateway->createPayPalPayment($payment);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Captura um pagamento do PayPal.
     *
     * @param string $transactionId
     * @return array
     * @throws PaymentException
     */
    public function capturePayPalPayment(string $transactionId): array
    {
        try {
            return $this->gateway->capturePayPalPayment($transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Executa um pagamento com PayPal.
     *
     * @param string $token
     * @param string $payerId
     * @return array
     * @throws PaymentException
     */
    public function executePayPalPayment(string $token, string $payerId): array
    {
        try {
            return $this->gateway->executePayPalPayment($token, $payerId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Reembolsa um pagamento do PayPal.
     *
     * @param string $transactionId
     * @param float $amount
     * @return array
     * @throws PaymentException
     */
    public function refundPayPalPayment(Payment $payment, string $transactionId): array
    {
        try {
            return $this->gateway->refundPayPalPayment($payment, $transactionId);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }

    // Métodos para Pagamentos Móveis
     /**
     * Processa um pagamento móvel (ex: Apple Pay, Google Pay).
     *
     * @param Payment $payment
     * @param string $token
     * @param string $paymentMethod
     * @return array
     * @throws PaymentException
     */
    public function chargeMobilePayment(Payment $payment, string $token, string $paymentMethod): array
    {
        try {
            return $this->gateway->chargeMobilePayment($payment, $token, $paymentMethod);
        } catch (PaymentException $e) {
            throw new PaymentException($e->getMessage(), $e->getCode());
        }
    }
}