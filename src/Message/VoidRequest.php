<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Guzzle\Http\Exception\ClientErrorResponseException;

class VoidRequest extends AbstractConnectERequest
{

    public function getData(): array
    {
        return [];
    }

    /**
     * Note this will not actually void.
     * It returns success if the payment is in STATUS_WAITING_PRE_EXECUTE, which means that it will not be auto-confirmed.
     * If it returns failure, it means the payment is already confirmed, so can't be "voided" (i.e. can't be left in unconfirmed status).
     */
    public function sendData($data): VoidResponse
    {

        $token = $this->getTransactionReference();
        if(!$token){
            return $this->response = new VoidResponse($this, ['success' => false]);
        }

        $json = $this->getStatusJsonFromEndpoint($token);
        if (!$json || empty($json['statusCode']) || $json['statusCode'] != AcceptNotificationRequest::STATUS_WAITING_PRE_EXECUTE) {
            // This is not a "resumable" transaction (probably a wallet payment). This is already confirmed so couldn't be revoked (i.e. we'll need to confirm the payment)
            // Please continue to call acceptNotification() in this case, to pick up any other statuses properly.
            return $this->response = new VoidResponse(
                $this,
                ['success' => false, 'statusCode' => $json['statusCode'] ?? '']
            );
        }

        // If the status is STATUS_WAITING_PRE_EXECUTE, then we just won't call resume, and this will cause the payment to expire, unpaid.
        return $this->response = new VoidResponse(
            $this,
            ['success' => true, 'statusCode' => $json['statusCode']]
        );
    }


}
