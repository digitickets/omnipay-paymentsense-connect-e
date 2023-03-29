<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Returns the payment details (so we can confirm if it was paid or not)
 * TransactionReference will return the connect-e crossreference ID (aka Request ID) for later refund use
 */
class AcceptNotificationResponse extends AbstractResponse
{

    const STATUSES = [
        0 => "successful payment",
        3 => "The card holder has not completed 3DS, this status will only be seen on the REST API.",
        4 => "The card issuer has parked the transaction awaiting contact with the customer before proceeding to authorise or decline the transaction",
        5 => "The transaction was declined by the card owner",
        20 => "The transaction which was processed was a duplicate. Ensure each transaction has a unique OrderId",
        30 => "Error executing transaction",
        40 => "The transaction is currently being processed, please check status again. This status will only be seen from the REST API if called before the JavaScript promise resolves or the Webhook is called",
        90 => "The access token was revoked while the cardholder was completing 3DS authentication. The transaction was stopped before being sent for processing",
        99 => "The transaction has been paused pre-execution using the waitPreExecute flag; a call to resume the transaction is expected within 15 minutes",
        400 => "The request has failed validation by our servers and the transaction has not been submitted to the gateway. Possible causes for this are invalid transaction type or other data in the request",
        401 => "The access token being used is not valid, the transaction has not been submitted to the gateway. This can be caused if the access token has already been used or the 30 minute expiry time has elapsed",
        404 => "No access token has been supplied to Connect-E. Transaction has not been submitted to the gateway",
        500 => "There's been an error submitting the transaction, please check the REST API for the status of the transaction"
    ];

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

    /**
     * displays failure message to the user for
     * failed payment
     */
    public function getMessage(): string
    {
        if($this->getCode() != null) {
            $message = self::STATUSES[$this->getCode()] ?? '';
            return ($this->data["message"] ?? '') . " - " . $message;
        } else {
            return $this->data["message"] ?? '';
        }
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->data['statusCode'] ?? null;
    }

}
