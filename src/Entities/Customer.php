<?php

namespace Casdorio\GatewayPayment\Entities;

use CodeIgniter\Entity\Entity;

class Customer extends Entity
{
    public function __construct(
        public ?string $email = null,
        public ?string $description = null,
        public ?string $merchantCustomerId = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $company = null,
        public ?string $phone = null,
        public ?Address $billingAddress = null,
        public ?Address $shippingAddress = null,
        public ?CardInfo $paymentInfo = null,
        public ?string $profileId = null,
        public array $paymentProfileIds = [],
        public array $shippingProfileIds = []
    ) {}
}