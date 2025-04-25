<?php

namespace Casdorio\GatewayPayment\Entities;

use Casdorio\GatewayPayment\Gateways\AuthorizeNet\AuthorizeNetGateway;
use Casdorio\GatewayPayment\Gateways\Stripe\StripeGateway;
use InvalidArgumentException;

class PaymentGatewayType
{
    public const STRIPE = 'stripe';
    public const AUTHORIZE_NET = 'authorize_net';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): self
    {
        if (!in_array($value, [self::STRIPE, self::AUTHORIZE_NET], true)) {
            throw new InvalidArgumentException("Invalid payment gateway type: {$value}");
        }
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getGatewayClass(): string
    {
        switch ($this->value) {
            case self::STRIPE:
                return StripeGateway::class;
            case self::AUTHORIZE_NET:
                return AuthorizeNetGateway::class;
            default:
                throw new InvalidArgumentException("Unsupported payment gateway type: {$this->value}");
        }
    }

     public function __toString(): string
    {
        return $this->value;
    }
}