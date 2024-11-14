<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Guzzle\Http\Exception\ClientErrorResponseException;
use RuntimeException;

/**
 * Pass in the auth token retrieved from AuthorizeResponse (aka id)
 * Gets the payment details (so we can confirm if it was paid or not)
 */
class AcceptNotificationRequest extends AbstractConnectERequest
{
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
            try {
                $this->httpClient->post(
                    $this->getEndpoint().$path,
                    $this->getHeaders()
                )->send();
            } catch (ClientErrorResponseException $e) {
                if ($e->getResponse() && $e->getResponse()->getStatusCode() === 400) {
                    // This can fail with a 400 due to a race condition if you call this twice (e.g. through a redirect then a webhook arriving).
                    // e.g. the webhook can arrive just before the page redirects, causing the redirect to fail.
                    // We just get the current status and return that instead below.
                } else {
                    throw $e;
                }
            }

            $json = $this->getStatusJsonFromEndpoint($token);
        }

        return $this->response = new AcceptNotificationResponse(
            $this,
            $json
        );
    }


}
