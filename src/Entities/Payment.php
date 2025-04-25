<?php

namespace Casdorio\GatewayPayment\Entities;

use CodeIgniter\Entity\Entity;

class Payment extends Entity
{
    public function __construct(
        public ?string $amount = null,
        public ?string $invoice_number = null,
        public ?string $description = null,
        public ?Customer $customer = null,
        public ?Item $items = null,
        public ?Address $delivery_address = null,
        public ?Address $billing_address = null,
        public ?CardInfo $card_info = null
    ) {}
}