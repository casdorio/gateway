<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet;

use Casdorio\GatewayPayment\Interfaces\PaymentGatewayInterface;
use Casdorio\GatewayPayment\Entities\Customer;
use Casdorio\GatewayPayment\Entities\PaymentResponse;
use Casdorio\GatewayPayment\Entities\Payment;
use Casdorio\GatewayPayment\Entities\Gateway;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetGateway implements PaymentGatewayInterface
{
    use ApiResponseHandler;

    protected Gateway $gateway;
    protected AnetAPI\MerchantAuthenticationType $merchantAuthentication;
    protected RequestBuilderFactory $requestBuilderFactory; // Usando a Factory

    public function __construct(Gateway $gateway, RequestBuilderFactory $requestBuilderFactory)
    {
        $this->gateway = $gateway;
        $this->merchantAuthentication = $this->createMerchantAuthentication();
        $this->requestBuilderFactory = $requestBuilderFactory; // Injetando a Factory
    }

    public function chargeCreditCard(Payment $payment): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('auth_capture'); // Obtendo o builder da Factory
        $transactionRequest = $requestBuilder->build($payment);
        return $this->processTransaction($transactionRequest);
    }

    public function authorize(Payment $payment): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('auth_only');
        $transactionRequest = $requestBuilder->build($payment);
        return $this->processTransaction($transactionRequest);
    }

    public function refund(Payment $payment, string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('refund');
        $transactionRequest = $requestBuilder->build($payment, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function void(string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('void');
        $transactionRequest = $requestBuilder->build(null, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function capture(Payment $payment, string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('capture');
        $transactionRequest = $requestBuilder->build($payment, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function getTransactionDetails(string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('transaction_details');
        $transactionRequest = $requestBuilder->build(null, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function chargeDebitCard(Payment $payment): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('auth_capture');
        $transactionRequest = $requestBuilder->build($payment);
        return $this->processTransaction($transactionRequest);
    }

    public function chargeECheck(Payment $payment): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('echeck');
        $transactionRequest = $requestBuilder->build($payment);
        return $this->processTransaction($transactionRequest);
    }

    public function voidECheck(string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('echeck_void');
        $transactionRequest = $requestBuilder->build(null, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function createPayPalPayment(Payment $payment): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('paypal_create');
        $transactionRequest = $requestBuilder->build($payment);
        return $this->processTransaction($transactionRequest);
    }

    public function capturePayPalPayment(string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('paypal_capture');
        $transactionRequest = $requestBuilder->build(null, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function executePayPalPayment(string $token, string $payerId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('paypal_execute');
        $transactionRequest = $requestBuilder->build(null, $token, $payerId);
        return $this->processTransaction($transactionRequest);
    }

    public function refundPayPalPayment(Payment $payment, string $transactionId): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('paypal_refund');
        $transactionRequest = $requestBuilder->build($payment, $transactionId);
        return $this->processTransaction($transactionRequest);
    }

    public function chargeMobilePayment(Payment $payment, string $token, string $paymentMethod): array
    {
        $requestBuilder = $this->requestBuilderFactory->create('mobile_charge');
        $transactionRequest = $requestBuilder->build($payment, null, null, $token, $paymentMethod);
        return $this->processTransaction($transactionRequest);
    }

    public function chargeCustomerProfile(string $profileId, float $amount): ?PaymentResponse
    {
        return null;
    }

    public function createCustomerProfile(Customer $customer): ?PaymentResponse
    {
        return null;
    }

    private function processTransaction(AnetAPI\TransactionRequestType $transactionRequest): array
    {
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($this->merchantAuthentication);
        $request->setRefId('ref' . time());
        $request->setTransactionRequest($transactionRequest);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(
            $this->gateway->sandbox ? \net\authorize\api\constants\ANetEnvironment::SANDBOX : \net\authorize\api\constants\ANetEnvironment::PRODUCTION
        );

        return $this->handleResponse($response);
    }

    private function createMerchantAuthentication(): AnetAPI\MerchantAuthenticationType
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->gateway->login_id);
        $merchantAuthentication->setTransactionKey($this->gateway->transaction_key);
        return $merchantAuthentication;
    }
}