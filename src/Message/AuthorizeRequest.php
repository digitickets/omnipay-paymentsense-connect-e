<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use RuntimeException;

class AuthorizeRequest extends AbstractConnectERequest
{
    const TRANSACTION_TYPE = 'SALE';
    const URL_PATH = '/v1/access-tokens';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'card');

        $card = $this->getCard();

        if (($card->getBillingCountry() && !is_numeric($card->getBillingCountry())) ||
            ($card->getBillingCountry() && !is_numeric($card->getBillingCountry()))) {
            throw new RuntimeException("Country must be a numeric ISO 3166-1 code");
        }

        return [
            'merchantUrl' => $this->getMerchantUrl(),
            'currencyCode' => $this->getCurrencyNumeric(),
            'amount' => $this->getAmountInteger(),
            'transactionType' => self::TRANSACTION_TYPE,
            'orderId' => $this->getTransactionId(),
            'orderDescription' => $this->getDescription(),
            'userEmailAddress' => $card->getEmail(),
            'userPhoneNumber' => $card->getBillingPhone(),
            'userIpAddress' => $this->getClientIp(),
            'userAddress1' => $card->getBillingAddress1(),
            'userAddress2' => $card->getBillingAddress2(),
            'userCity' => $card->getBillingCity(),
            'userState' => $card->getBillingState(),
            'userPostcode' => $card->getBillingPostcode(),
            'userCountryCode' => $card->getBillingCountry(),
            'webhookUrl' => $this->getReturnUrl() ?: $this->getNotifyUrl(),
            'shippingDetails' => [
                'name' => $this->getCard()->getShippingName(),
                'address' => [
                    'address1' => $card->getShippingAddress1(),
                    'address2' => $card->getShippingAddress2(),
                    'city' => $card->getShippingCity(),
                    'state' => $card->getShippingState(),
                    'postcode' => $card->getShippingPostcode(),
                    'countryCode' => $card->getShippingCountry(),
                ],
            ],
            'waitPreExecute' => $this->getWaitPreExecute(),
        ];
    }

    public function setWaitPreExecute(bool $value)
    {
        return $this->setParameter('waitPreExecute', $value);
    }

    /**
     * @return bool|null
     */
    public function getWaitPreExecute()
    {
        return $this->getParameter('waitPreExecute');
    }

    public function sendData($data): AuthorizeResponse
    {
        $jsonData = json_encode($data);

        $httpResponse = $this->httpClient->post(
            $this->getEndpoint().static::URL_PATH,
            $this->getHeaders(),
            $jsonData
        )->send();

        return $this->response = new AuthorizeResponse(
            $this,
            $httpResponse->json()
        );
    }


}
