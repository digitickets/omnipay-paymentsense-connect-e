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
     * Note this gateway tends to respond as plain text (which omnipay doesn't seem to support),
     *  so this won't return much useful
     *
     * @return string|null
     */
    public function getMessage()
    {
        return json_encode($this->data) ?? '';
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return '';
    }

}
