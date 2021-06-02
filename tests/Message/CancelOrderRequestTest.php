<?php

namespace Omnipay\Mollie\Test\Message;

use Omnipay\Common\Http\ClientInterface;
use Omnipay\Mollie\Message\Request\CancelOrderRequest;
use Omnipay\Mollie\Message\Response\CancelOrderResponse;
use Omnipay\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

final class CancelOrderRequestTest extends TestCase
{
    /**
     * @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    /**
     * @var CancelOrderRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->request = new CancelOrderRequest($this->httpClient, $this->getHttpRequest());
    }

    public function insufficientDataProvider()
    {
        return [
            [['apiKey' => 'mykey']],
            [['transactionReference' => 'myref']],
        ];
    }

    public function responseDataProvider()
    {
        return [
            [['id' => 'ord_kEn1PlbGa'], false],
            [['status' => 'paid', 'id' => 'ord_kEn1PlbGa'], false],
            [['status' => 'canceled', 'id' => 'ord_kEn1PlbGa'], true],
        ];
    }

    /**
     * @dataProvider insufficientDataProvider
     *
     * @param array $input
     */
    public function testGetDataWillValidateRequiredData(array $input)
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->request->initialize($input);
        $this->request->getData();
    }

    public function testGetDataWillReturnEmptyArray()
    {
        $this->request->initialize(['apiKey' => 'mykey', 'transactionReference' => 'myref']);
        self::assertEquals([], $this->request->getData());
    }

    /**
     * @dataProvider responseDataProvider
     */
    public function testSendData(array $responseData, $success)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::once())
            ->method('getBody')
            ->willReturn(\json_encode($responseData));

        $this->httpClient->expects(self::once())
            ->method('request')
            ->with(
                'DELETE',
                'https://api.mollie.com/v2/orders/ord_kEn1PlbGa',
                $this->callback(function ($headers) {
                    return $headers['Authorization'] == 'Bearer mykey';
                })
            )->willReturn($response);

        $this->request->initialize(['apiKey' => 'mykey', 'transactionReference' => 'ord_kEn1PlbGa']);
        $voidResponse = $this->request->sendData([]);

        $this->assertInstanceOf(CancelOrderResponse::class, $voidResponse);
        $this->assertEquals($success, $voidResponse->isSuccessful());
        $this->assertSame('ord_kEn1PlbGa', $voidResponse->getTransactionReference());
    }
}
