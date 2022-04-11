<?php

namespace DigiTickets\OmnipayPaymentsenseConnectE\Test;

use DigiTickets\OmnipayPaymentsenseConnectE\Message\AuthorizeRequest;
use DigiTickets\OmnipayPaymentsenseConnectE\Message\AuthorizeResponse;
use Mockery;
use Omnipay\Tests\TestCase;

class AuthorizeResponseTest extends TestCase
{
    /**
     * @dataProvider creationProvider
     */
    public function testCreatingAuthorizeResponse(
        array $data,
        bool $expectedSuccess
    ) {
        $request = Mockery::mock(AuthorizeRequest::class);
        $response = new AuthorizeResponse($request, $data);

        $this->assertEquals($expectedSuccess, $response->isSuccessful());
    }

    /**
     * @return array
     */
    public function creationProvider()
    {
        return [
            'success' => [
                ['id' => '123', 'expiresAt' => '2021-01-01 10:52:01'],
                true,
            ],
            'declined' => [
                ['id' => ''],
                false,
            ],
        ];
    }
}
