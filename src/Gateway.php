<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE;

use DigiTickets\OmnipayPaymentsenseConnectE\Message\AcceptNotificationRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\AuthorizeRefundRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\AuthorizeRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\AuthorizeResponse;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\RefundRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\VoidRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use RuntimeException;

class Gateway extends AbstractGateway
{

    public function getName(): string
    {
        return 'PaymentsenseConnectE';
    }

    public function getDefaultParameters(): array
    {
        return [
            'jwt' => '',
            'merchant_url' => '',
        ];
    }

    /**
     * Call out to the gateway to retrieve an auth token.
     * Pass in the customer details, and payment amounts.
     *
     * @param array $options
     *
     * @return RequestInterface
     */
    public function authorize(array $options = []): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);

        return $this->createRequest(
            AuthorizeRequest::class,
            $options
        );
    }

    /**
     * Once the customer has paid, call the gateway to get details of the payment.
     * Pass in the token retrieved from authorize().
     *
     * @param array $options
     *
     * @return RequestInterface
     */
    public function acceptNotification(array $options = []): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);

        return $this->createRequest(
            AcceptNotificationRequest::class,
            $options
        );
    }

    /**
     * Call the gateway to refund a payment. This is 2 separate API calls - both handled in this one function.
     * Pass in amount, and the crossReference retrieved from acceptNotification() - pass this in via the transactionReference parameter.
     *
     * @param array $options
     *
     * @return RequestInterface
     */
    public function refund(array $options = array()): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);
        $options["crossReference"] = $options["transactionReference"];

        $request = $this->createRequest(
            AuthorizeRefundRequest::class,
            $options
        );
        /** @var AuthorizeResponse $response */
        $response = $request->send();
        if (!$response->isSuccessful()) {
            throw new RuntimeException("Could not retrieve auth code - ".$response->getMessage());
        }
        // Send the retrieved auth code to the refund endpoint
        $options["transactionReference"] = $response->getTransactionReference();

        return $this->createRequest(
            RefundRequest::class,
            $options
        );
    }

    public function void(array $options = array()): RequestInterface
    {
        $options = array_merge($this->getParameters(), $options);

        return $this->createRequest(
            VoidRequest::class,
            $options
        );

    }


}
