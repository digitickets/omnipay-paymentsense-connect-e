<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Returns the payment details (so we can confirm if it was paid or not)
 * TransactionReference will return the connect-e crossreference ID (aka Request ID) for later refund use
 */
class AcceptNotificationResponse extends AbstractResponse
{

    public function isSuccessful(): bool
    {
        return ($this->data['statusCode'] ?? null) === 0;
    }

    public function getTransactionId(): string
    {
        /** @var AcceptNotificationRequest $request */
        $request = $this->getRequest();

        return $request->getTransactionId();
    }

    /**
     * Used for later refund use if needed
     * @return string
     */
    public function getTransactionReference(): string
    {
        // Note that paymentsense also call this Request ID
        return $this->data["crossReference"] ?? '';
    }

    public function getMessage(): string
    {
        return $this->data["message"] ?? '';
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['statusCode'] ?? null;
    }

}
