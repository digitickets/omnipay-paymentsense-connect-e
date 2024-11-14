<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;

class VoidResponse extends AbstractResponse
{

    public function isSuccessful(): bool
    {
        return !empty($this->data['success']);
    }

    public function isRedirect(): bool
    {
        return false;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return !empty($this->data['success']) ? 'Revoked' : 'Failed Revoke';
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['statusCode'] ?? '';
    }

}
