<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class AuthorizeResponse extends AbstractResponse
{
    public function __construct(
        RequestInterface $request,
        array $jsonData
    ) {
        parent::__construct($request, $jsonData);
    }

    public function isSuccessful(): bool
    {
        return !empty($this->data['id']);

    }

    public function isRedirect(): bool
    {
        return false;
    }

    public function getTransactionReference(): string
    {
        return $this->data['id'] ?? '';
    }

    /**
     * @return int|null
     */
    public function getExpiresAt()
    {
        return $this->data['expiresAt'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return 'Authed with gateway, expires '.$this->getExpiresAt();
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return '';
    }

}
