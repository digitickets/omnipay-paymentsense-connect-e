<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use RuntimeException;

class RefundRequest extends AbstractConnectERequest
{
    const URL_PATH = '/v1/cross-reference-payments/{id}';

    /**
     * @throws RuntimeException
     */
    public function getData(): array
    {
        if (empty($this->getCrossReference())) {
            throw new RuntimeException("Missing parameter crossReference");
        }

        return ["crossReference" => $this->getCrossReference()];
    }


    public function setCrossReference(string $value)
    {
        return $this->setParameter('crossReference', $value);
    }

    /**
     * @return string|null
     */
    public function getCrossReference()
    {
        return $this->getParameter('crossReference');
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data): RefundResponse
    {
        $token = $this->getTransactionReference();
        $path = str_replace('{id}', $token, static::URL_PATH);

        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().$path,
            $this->getHeaders(),
            json_encode($data)
        )->send();

        return $this->response = new RefundResponse(
            $this,
            $httpResponse->json()
        );
    }


}
