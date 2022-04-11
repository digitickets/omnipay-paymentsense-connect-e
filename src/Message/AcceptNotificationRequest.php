<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use RuntimeException;

/**
 * Pass in the auth token retrieved from AuthorizeResponse (aka id)
 * Gets the payment details (so we can confirm if it was paid or not)
 */
class AcceptNotificationRequest extends AbstractConnectERequest
{
    const URL_PATH = '/v1/payments/{id}';

    /**
     * @throws RuntimeException
     */
    public function getData(): array
    {
        if (empty($this->getTransactionReference())) {
            throw new RuntimeException("Missing parameter transactionReference");
        }

        return ["transactionReference" => $this->getTransactionReference()];
    }

    /**
     * @throws RuntimeException
     */
    public function sendData($data): AcceptNotificationResponse
    {
        $token = $data["transactionReference"];
        $path = str_replace('{id}', $token, static::URL_PATH);

        $httpResponse = $this->httpClient->get(
            $this->getEndpoint().$path,
            $this->getHeaders()
        )->send();

        return $this->response = new AcceptNotificationResponse(
            $this,
            $httpResponse->json()
        );
    }

}
