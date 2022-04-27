<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Test;

use DigiTickets\OmnipayPaymentsenseConnectE\Message\AcceptNotificationRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\AcceptNotificationResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class AcceptNotificationResponseTest extends TestCase
{
    /**
     * @dataProvider creationProvider
     */
    public function testCreatingAcceptNotificationResponse(
        array $data,
        bool $expectedSuccess,
        string $expectedMessage,
        string $expectedTransactionRef
    ) {
        $request = Mockery::mock(AcceptNotificationRequest::class);
        $response = new AcceptNotificationResponse($request, $data);

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
        $this->assertEquals($expectedMessage, $response->getMessage());
        $this->assertEquals($expectedTransactionRef, $response->getTransactionReference());
    }

    /**
     * @return array
     */
    public function creationProvider()
    {
        return [
            'success' => [
                ['message' => 'ok', 'statusCode' => 0, 'crossReference' => 'abc'],
                true,
                'ok',
                'abc',
            ],
            'declined' => [
                ['message' => 'FAILED', 'statusCode' => '5'],
                false,
                'FAILED',
                '',
            ],
        ];
    }
}
