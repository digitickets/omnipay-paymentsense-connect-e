<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class AbstractConnectERequest extends AbstractRequest
{
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
}
