<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractConnectERequest extends AbstractRequest
{

    const STATUS_WAITING_PRE_EXECUTE = 99;
    const STATUS_PATH = '/v1/payments/{id}';

    protected $liveEndpoint = 'https://e.connect.paymentsense.cloud';
    protected $testEndpoint = 'https://e.test.connect.paymentsense.cloud';

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->getJwt(),
            'Content-Type' => 'application/json',
        ];
    }

    public function setMerchantUrl(string $value)
    {
        return $this->setParameter('merchantUrl', $value);
    }

    /**
     * @return string|null
     */
    public function getMerchantUrl()
    {
        return $this->getParameter('merchantUrl');
    }

    public function setJwt(string $value)
    {
        return $this->setParameter('jwt', $value);
    }

    /**
     * @return string|null
     */
    public function getJwt()
    {
        return $this->getParameter('jwt');
    }


    public function getStatusJsonFromEndpoint(string $token): array
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
