<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\FetchIssuersResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Returns issuers available for the ideal payment method.
 *
 * @see https://docs.mollie.com/reference/v2/methods-api/get-method
 * @see https://docs.mollie.com/reference/v2/methods-api/get-method#mollie-connect-oauth-parameters
 */
class ConnectFetchIssuersRequest extends FetchIssuersRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'profileId');

        $data = [];
        $data['profileId'] = $this->getProfileId();

        if ($this->getTestMode()) {
            $data['testmode'] = var_export($this->getTestMode(), true);
        }

        if (empty($data['profileId'])) {
            throw new InvalidRequestException("The profileId parameter is required");
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|FetchIssuersResponse
     */
    public function sendData($data)
    {
        $queryString = isset($data['testmode']) ? '&testmode=' . $data['testmode'] : '';

        $response = $this->sendRequest(self::GET, $this->endpoint . $queryString);

        return $this->response = new FetchIssuersResponse($this, $response);
    }
}
