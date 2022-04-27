<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Test;

use DigiTickets\OmnipayPaymentsenseConnectE\Message\RefundRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\RefundResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    /**
     * @dataProvider creationProvider
     */
    public function testCreatingRefundResponse(
        array $data,
        bool $expectedSuccess,
        string $expectedMessage,
        string $expectedAuthCode
    ) {
        $request = Mockery::mock(RefundRequest::class);
        $response = new RefundResponse($request, $data);

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
        $this->assertEquals($expectedMessage, $response->getMessage());
        $this->assertEquals($expectedAuthCode, $response->getAuthCode());
    }

    /**
     * @return array
     */
    public function creationProvider()
    {
        return [
            'success' => [
                ['message' => "ok", 'statusCode' => 0, 'authCode' => "abc"],
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
