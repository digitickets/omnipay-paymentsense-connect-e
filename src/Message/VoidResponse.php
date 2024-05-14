<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;

class VoidResponse extends AbstractResponse
{

    public function isSuccessful(): bool
    {
        return true;
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
        return 'Revoked';
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return '';
    }

}
