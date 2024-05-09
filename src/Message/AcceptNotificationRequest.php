<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use RuntimeException;

/**
 * Pass in the auth token retrieved from AuthorizeResponse (aka id)
 * Gets the payment details (so we can confirm if it was paid or not)
 */
class AcceptNotificationRequest extends AbstractConnectERequest
{
    const STATUS_WAITING_PRE_EXECUTE = 99;
    const STATUS_PATH = '/v1/payments/{id}';
    const AUTH_PATH = '/v1/payments/{id}/resume';

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
        $token = $data['transactionReference'];

        $json = $this->getStatusJsonFromEndpoint($token);

        // Check if we are using the pre-execute route, rather than the standard route.
        if ($json && !empty($json['statusCode']) && $json['statusCode'] === self::STATUS_WAITING_PRE_EXECUTE) {
            // We need to "authorise" this transaction (which is just called "resume" really)
            // Note if we don't call this, it will time out after 15mins.
            $path = str_replace('{id}', $token, static::AUTH_PATH);
            $this->httpClient->post(
                $this->getEndpoint().$path,
                $this->getHeaders()
            )->send();

            $json = $this->getStatusJsonFromEndpoint($token);
        }

        return $this->response = new AcceptNotificationResponse(
            $this,
            $json
        );
    }

    private function getStatusJsonFromEndpoint(string $token): array
    {
        $path = str_replace('{id}', $token, static::STATUS_PATH);
        $httpResponse = $this->httpClient->get(
            $this->getEndpoint().$path,
            $this->getHeaders()
        )->send();
        $json = $httpResponse->json();

        return $json;
    }

}
