<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet\RequestBuilders;

use Casdorio\GatewayPayment\Entities\Payment;
use net\authorize\api\contract\v1 as AnetAPI;

interface RequestBuilderInterface
{
    /**
     * Constrói o objeto de requisição da API do AuthorizeNet.
     *
     * @param Payment|null $payment Os dados do pagamento, se aplicável.
     * @param mixed ...$params Parâmetros adicionais específicos para o tipo de requisição.
     * @return AnetAPI\TransactionRequestType O objeto de requisição construído.
     */
    public function build(?Payment $payment, ...$params): AnetAPI\TransactionRequestType;
}