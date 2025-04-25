<?php

namespace Casdorio\GatewayPayment\Gateways\AuthorizeNet;

use net\authorize\api\contract\v1 as AnetAPI;
use Casdorio\GatewayPayment\Exceptions\PaymentException;

trait ApiResponseHandler
{
    /**
     * Trata a resposta da API do AuthorizeNet.
     *
     * @param AnetAPI\ANetApiResponseType $response Resposta da API do AuthorizeNet.
     * @return array Array padronizado com o resultado da transação.
     * @throws PaymentException Em caso de erro na transação.
     */
    protected function handleResponse(AnetAPI\ANetApiResponseType $response): array
    {
        if ($response === null) {
            throw new PaymentException("Null response object", PaymentException::NULL_RESPONSE_OBJECT); // Mudança aqui: passando 0 como código
        }

        $messages = $response->getMessages();
        if ($messages === null) {
            throw new PaymentException("Null response from AuthorizeNet API", PaymentException::NULL_RESPONSE); // Mudança aqui: passando 0 como código
        }

        if ($messages->getResultCode() == "Ok") {
            return $this->handleSuccessResponse($response);
        } else {
            return $this->handleErrorResponse($response);
        }
    }

    /**
     * Trata uma resposta bem-sucedida da API.
     *
     * @param AnetAPI\ANetApiResponseType $response
     * @return array
     */
    private function handleSuccessResponse(AnetAPI\ANetApiResponseType $response): array
    {
        $transactionResponse = $response->getTransactionResponse();
        if ($transactionResponse !== null && $transactionResponse->getMessages() !== null) {
            $message = $transactionResponse->getMessages()[0];
            return [
                'status' => 'success',
                'transaction_id' => $transactionResponse->getTransId(),
                'auth_code' => $transactionResponse->getAuthCode(),
                'message' => $message->getDescription(),
                'code' => $message->getCode(),
                // Adicione outros dados relevantes da resposta, se necessário
            ];
        } else {
            return [
                'status' => 'success',
                'transaction_id' => null,
                'auth_code' => null,
                'message' => "Transaction successful, but no details available.",
                'code' => "OK",
            ];
        }
    }

    /**
     * Trata uma resposta de erro da API.
     *
     * @param AnetAPI\ANetApiResponseType $response
     * @return array
     * @throws PaymentException
     */
    private function handleErrorResponse(AnetAPI\ANetApiResponseType $response): array
    {
        $transactionResponse = $response->getTransactionResponse();
        if ($transactionResponse !== null && $transactionResponse->getErrors() !== null) {
            $error = $transactionResponse->getErrors()[0];
            $errorCode = $error->getErrorCode();
            $errorText = $error->getErrorText();
        } else {
             $message = $response->getMessages()->getMessage()[0];
            $errorCode = $message->getCode();
            $errorText = $message->getText();
        }

        throw new PaymentException($errorText, (int)$errorCode); // Mudança aqui: casting para int
    }
}