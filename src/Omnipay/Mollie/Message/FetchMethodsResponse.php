<?php

namespace Omnipay\Mollie\Message;

class FetchMethodsResponse extends AbstractResponse
{
	/**
	 * Return available methods as an associative array.
	 *
	 * @return array|null
	 */
	public function getMethods()
	{
		if (isset($this->data['data'])) {
            $result = array();

			foreach ($this->data['data'] as $method) {
				$result[] = array(
					'id' 		=> $method['id'],
					'name' 		=> $method['description'],
					'amount' 	=> array(
						'min'		=> $method['amount']['minimum'],
						'max'		=> $method['amount']['maximum']
					)
				);
			}

			return $result;
		}

		return null;
	}
}