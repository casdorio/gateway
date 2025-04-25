<?php

namespace Casdorio\GatewayPayment\Controllers;

use CodeIgniter\Controller;
use Casdorio\GatewayPayment\Services\PaymentGatewayFactory;
use Casdorio\GatewayPayment\Services\PaymentService;
use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\Gateway;
use Exception;

class PaymentController extends Controller
{
    /**
     * Inicializa o PaymentService com base na configuração do gateway.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @return PaymentService|array Instância do PaymentService ou array de erro.
     */
    private static function initializePaymentService(array $gatewayConfig)
    {
        if (!$gatewayConfig) {
            return [
                'status' => 'error',
                'code' => 'CONFIG_NOT_FOUND',
                'gatewayName' => '',
                'message' => 'Nenhuma configuração de gateway de pagamento encontrada para este escritório.',
            ];
        }

        $gatewayEntity = new Gateway(
            name: $gatewayConfig['gateway_name'],
            login_id: $gatewayConfig['login_id'],
            transaction_key: $gatewayConfig['transaction_key'],
            sandbox: $gatewayConfig['environment'] === 'sandbox',
        );

        try {
            $gateway = PaymentGatewayFactory::create($gatewayEntity);
            return new PaymentService($gateway);
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'code' => 'GATEWAY_CREATION_FAILED',
                'gatewayName' => $gatewayConfig['gateway_name'],
                'message' => 'Falha ao criar serviço de gateway de pagamento: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Processa o pagamento com cartão de crédito.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação.
     */
    public static function chargeCreditCard(array $gatewayConfig, Payment $payment): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService; // Retorna o array de erro
        }

        return $paymentService->chargeCreditCard($payment);
    }

    /**
     * Processa o pagamento com cartão de débito.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação.
     */
    public static function chargeDebitCard(array $gatewayConfig, Payment $payment): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->chargeDebitCard($payment);
    }

    /**
     * Autoriza um pagamento com cartão de crédito.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação de autorização.
     */
    public static function authorize(array $gatewayConfig, Payment $payment): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->authorize($payment);
    }

    /**
     * Captura fundos de uma transação previamente autorizada.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param string $transactionId ID da transação a ser capturada.
     * @param float $amount Valor a ser capturado.
     * @return array Resultado da captura.
     */
    public static function captureTransaction(array $gatewayConfig, Payment $payment, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->capture($payment, $transactionId);
    }

    /**
     * Realiza um reembolso de uma transação.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do reembolso.
     * @param string $transactionId ID da transação a ser reembolsada.
     * @return array Resultado do reembolso.
     */
    public static function refund(array $gatewayConfig, Payment $payment, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->refund($payment, $transactionId);
    }

    /**
     * Cancela (void) uma transação.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param string $transactionId ID da transação a ser cancelada.
     * @return array Resultado do cancelamento.
     */
    public static function voidTransaction(array $gatewayConfig, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->void($transactionId);
    }

    /**
     * Obtém os detalhes de uma transação.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param string $transactionId ID da transação a ser consultada.
     * @return array Detalhes da transação.
     */
    public static function getTransactionDetails(array $gatewayConfig, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);

        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->getTransactionDetails($transactionId);
    }

    /**
     * Processa um pagamento via e-check.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da transação e-check.
     */
    public static function chargeECheck(array $gatewayConfig, Payment $payment): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }
        return $paymentService->chargeECheck($payment);
    }

    /**
     * Estorna um pagamento via e-check.
     *
     * @param array $gatewayConfig
     * @param string $transactionId
     * @return array
     */
    public static function voidECheck(array $gatewayConfig, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }
        return $paymentService->voidECheck($transactionId);
    }

     /**
     * Inicia um pagamento com PayPal.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @return array Resultado da inicialização do pagamento PayPal.
     */
    public static function createPayPalPayment(array $gatewayConfig, Payment $payment): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }
        return $paymentService->createPayPalPayment($payment);
    }

    /**
     * Captura um pagamento previamente autorizado do PayPal.
     *
     * @param array $gatewayConfig
     * @param string $transactionId
     * @return array
     */
    public static function capturePayPalPayment(array $gatewayConfig, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }
        return $paymentService->capturePayPalPayment($transactionId);
    }

    /**
     * Executa a transação de pagamento do PayPal após a aprovação do usuário.
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param string $token Token de pagamento do PayPal.
     * @param string $payerId ID do pagador no PayPal.
     * @return array Resultado da execução do pagamento PayPal.
     */
    public static function executePayPalPayment(array $gatewayConfig, string $token, string $payerId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->executePayPalPayment($token, $payerId);
    }

    /**
    * Reembolsa um pagamento do PayPal
    *
    * @param array $gatewayConfig
    * @param string $transactionId
    * @param float $amount
    * @return array
    */
    public static function refundPayPalPayment(array $gatewayConfig, Payment $payment, string $transactionId): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }
        return $paymentService->refundPayPalPayment($payment, $transactionId);
    }

    /**
     * Processa um pagamento móvel (ex: Apple Pay, Google Pay).
     *
     * @param array $gatewayConfig Configuração do gateway de pagamento.
     * @param Payment $payment Entidade Payment com os detalhes do pagamento.
     * @param string $token Token de pagamento fornecido pelo dispositivo móvel.
     * @param string $paymentMethod 'applepay' ou 'googlepay'
     * @return array Resultado da transação de pagamento móvel.
     */
    public static function chargeMobilePayment(array $gatewayConfig, Payment $payment, string $token, string $paymentMethod): array
    {
        $paymentService = self::initializePaymentService($gatewayConfig);
        if (is_array($paymentService)) {
            return $paymentService;
        }

        return $paymentService->chargeMobilePayment($payment, $token, $paymentMethod);
    }
}