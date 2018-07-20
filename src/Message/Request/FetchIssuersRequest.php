<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\FetchIssuersResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Mollie Fetch Issuers Request
 *
 * @method FetchIssuersResponse send()
 */
class FetchIssuersRequest extends AbstractMollieRequest
{

    protected $endpoint = '/methods/ideal?include=issuers';

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        return [];
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchIssuersResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, $this->endpoint);

        return $this->response = new FetchIssuersResponse($this, $response);
    }
}
