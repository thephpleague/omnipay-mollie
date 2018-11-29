<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\CreateShipmentResponse;

/**
 * Create a shipment with the Mollie API.
 *
 * @see https://docs.mollie.com/reference/v2/shipments-api/create-shipment
 * @method CreateShipmentResponse send()
 */
class CreateShipmentRequest extends AbstractMollieRequest
{
    /**
     * @return array
     */
    public function getTracking()
    {
        return $this->getParameter('tracking');
    }

    /**
     * @param array $value
     * @return $this
     */
    public function setTracking(array $value)
    {
        return $this->setParameter('tracking', $value);
    }


    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];
        $data['lines'] = [];

        if ($items = $this->getItems()) {
            foreach ($items as $item) {
                $data['lines'][] = array_filter([
                    'id' => $item->getId(),
                    'quantity' => $item->getQuantity(),
                ]);
            }
        }

        if ($tracking = $this->getTracking()) {
            $data['tracking'] = $tracking;
        }

        return $data;
    }

    /**
     * @param array $data
     * @return CreateShipmentResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::POST, '/orders/' . $this->getTransactionReference() . '/shipments', $data);

        return $this->response = new CreateShipmentResponse($this, $response);
    }
}
