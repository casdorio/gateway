<?php

namespace Casdorio\GatewayPayment\Entities;

use CodeIgniter\Entity\Entity;

class ECheckInfo extends Entity
{
    public function __construct(
        public ?string $routing_number = null,
        public ?string $account_number = null,
        public ?string $name_on_account = null,
        public ?string $echeck_type = null,
        public ?string $bank_name = null
    ) {}
}