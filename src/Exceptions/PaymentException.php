<?php

namespace Casdorio\GatewayPayment\Exceptions;

use Exception;

/**
 * Exceção para erros relacionados a pagamentos.
 */
class PaymentException extends Exception
{
    // Defina códigos de erro como constantes
    public const UNSUPPORTED_TRANSACTION_TYPE = 100;
    public const NULL_RESPONSE = 200;
    public const NULL_RESPONSE_OBJECT = 201;
    // Adicione mais códigos conforme necessário

    /**
     * @param string $message Mensagem de erro da exceção.
     * @param int $code Código do erro da exceção.
     * @param \Exception|null $previous Exceção anterior para encadeamento de exceções.
     */
    public function __construct(string $message = "", int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}