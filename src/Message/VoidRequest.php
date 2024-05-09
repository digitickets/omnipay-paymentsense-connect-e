<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Guzzle\Http\Exception\ClientErrorResponseException;

class VoidRequest extends AbstractConnectERequest
{
    const URL_PATH = '/v1/access-tokens/{id}/revoke';

    public function getData(): array
    {
        return [];
    }

    public function sendData($data): VoidResponse
    {
        $token = $this->getTransactionReference();
        $path = str_replace('{id}', $token, static::URL_PATH);

        try {
            $this->httpClient->post(
                $this->getEndpoint().$path,
                $this->getHeaders()
            )->send();
        } catch (ClientErrorResponseException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 400) {
                // just continue as this token has been consumed, or is no longer available
            } else {
                throw $e;
            }
        }

        return $this->response = new VoidResponse(
            $this,
            []
        );
    }


}
