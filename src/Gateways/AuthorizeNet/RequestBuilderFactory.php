<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet;

use Casdorio\GatewayPayment\Exceptions\PaymentException;
use Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;


class RequestBuilderFactory
{
    public function create(string $type): RequestBuilders\RequestBuilderInterface
    {
        switch ($type) {
            case 'auth_capture':
                return new RequestBuilders\AuthCaptureTransactionRequestBuilder();
            case 'auth_only':
                return new RequestBuilders\AuthOnlyTransactionRequestBuilder();
            case 'refund':
                return new RequestBuilders\RefundTransactionRequestBuilder();
            case 'void':
                return new RequestBuilders\VoidTransactionRequestBuilder();
            case 'capture':
                return new RequestBuilders\CaptureTransactionRequestBuilder();
            case 'transaction_details':
                return new RequestBuilders\GetTransactionDetailsRequestBuilder();
            case 'echeck':
                return new RequestBuilders\ECheckTransactionRequestBuilder();
            case 'echeck_void':
                return new RequestBuilders\ECheckVoidTransactionRequestBuilder();
            case 'paypal_create':
                return new RequestBuilders\PayPalCreateTransactionRequestBuilder();
            case 'paypal_capture':
                return new RequestBuilders\PayPalCaptureTransactionRequestBuilder();
            case 'paypal_execute':
                return new RequestBuilders\PayPalExecuteTransactionRequestBuilder();
            case 'paypal_refund':
                return new RequestBuilders\PayPalRefundTransactionRequestBuilder();
            case 'mobile_charge':
                return new RequestBuilders\MobileChargeTransactionRequestBuilder();
            default:
                throw new PaymentException("Unsupported transaction type: {$type}", PaymentException::UNSUPPORTED_TRANSACTION_TYPE);
        }
    }
}