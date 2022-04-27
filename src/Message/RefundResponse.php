<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class RefundResponse extends AbstractResponse
{
    public function __construct(
        RequestInterface $request,
        array $jsonData
    ) {
        parent::__construct($request, $jsonData);
    }

    public function isSuccessful(): bool
    {
        return ($this->data['statusCode'] ?? null) === 0;
    }

    public function isRedirect(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->data["message"] ?? '';
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data["statusCode"] ?? '';
    }

    /**
     * @return string|null
     */
    public function getAuthCode()
    {
        return $this->data["authCode"] ?? '';
    }

}
