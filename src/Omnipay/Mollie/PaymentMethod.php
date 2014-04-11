<?php

namespace Omnipay\Mollie;

class PaymentMethod extends \Omnipay\Common\PaymentMethod
{
    /**
     * @var array
     */
    protected $amount;

    /**
     * @var array
     */
    protected $image;

    /**
     * Create a new PaymentMethod
     *
     * @param string $id     The identifier of this payment method
     * @param string $name   The name of this payment method
     * @param array  $amount The minimum and maximum amount of this payment method
     * @param array  $image  The image of this payment method
     */
    public function __construct($id, $name, array $amount, array $image)
    {
        parent::__construct($id, $name);

        $this->amount = $amount;
        $this->image = $image;
    }

    /**
     * The minimum and maximum amount of this payment method
     *
     * @return array
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * The image of this payment method
     *
     * @return array
     */
    public function getImages()
    {
        return $this->image;
    }
}
