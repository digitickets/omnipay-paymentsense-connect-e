<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

/**
 * Gets the auth token for a later refund request
 * Pass in the crossReference (which is stored in the transactionReference shared parameter)
 */
class AuthorizeRefundRequest extends AuthorizeRequest
{
    const TRANSACTION_TYPE = 'REFUND';

    public function getData(): array
    {
        return [
            'merchantUrl' => $this->getMerchantUrl(),
            'transactionType' => self::TRANSACTION_TYPE,
            'orderId' => (string) $this->getTransactionId(), // type cast is required
            'crossReference' => $this->getTransactionReference(),
            'currencyCode' => $this->getCurrencyNumeric(),
            'amount' => $this->getAmountInteger(),
        ];
    }


}
