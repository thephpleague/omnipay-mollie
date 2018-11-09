<?php

namespace Omnipay\Mollie;

class Item extends \Omnipay\Common\Item
{
    /**
     * Check if a Amount object is used, store the value
     *
     * @param $key
     * @param $value
     * @return $this
     */
    protected function setParameter($key, $value)
    {
        if (is_array($value) && isset($value['value'])) {
            $value = $value['value'];
        }

        $this->parameters->set($key, $value);

        return $this;
    }

    public function getId()
    {
        return $this->getParameter('id');
    }

    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }

    public function getUnitPrice()
    {
        return $this->getParameter('unitPrice');
    }

    public function setUnitPrice($value)
    {
        return $this->setParameter('unitPrice', $value);
    }

    public function getDiscountAmount()
    {
        return $this->getParameter('discountAmount');
    }

    public function setDiscountAmount($value)
    {
        return $this->setParameter('discountAmount', $value);
    }

    public function getTotalAmount()
    {
        return $this->getParameter('totalAmount');
    }

    public function setTotalAmount($value)
    {
        return $this->setParameter('totalAmount', $value);
    }

    public function getVatRate()
    {
        return $this->getParameter('vatRate');
    }

    public function setVatRate($value)
    {
        return $this->setParameter('vatRate', $value);
    }

    public function getVatAmount()
    {
        return $this->getParameter('vatAmount');
    }

    public function setVatAmount($value)
    {
        return $this->setParameter('vatAmount', $value);
    }

    public function getSku()
    {
        return $this->getParameter('sku');
    }

    public function setSku($value)
    {
        return $this->setParameter('sku', $value);
    }

    public function getType()
    {
        return $this->getParameter('type');
    }

    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    public function getProductUrl()
    {
        return $this->getParameter('productUrl');
    }

    public function setProductUrl($value)
    {
        return $this->setParameter('productUrl', $value);
    }

    public function getImageUrl()
    {
        return $this->getParameter('imageUrl');
    }

    public function setImageUrl($value)
    {
        return $this->setParameter('imageUrl', $value);
    }

    public function getResource()
    {
        return $this->getParameter('resource');
    }

    public function setResource($value)
    {
        return $this->setParameter('resource', $value);
    }

    public function getStatus()
    {
        return $this->getParameter('status');
    }

    public function setStatus($value)
    {
        return $this->setParameter('status', $value);
    }


    public function getIsCancelable()
    {
        return $this->getParameter('isCancelable');
    }

    public function setIsCancelable($value)
    {
        return $this->setParameter('isCancelable', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getQuantityShipped()
    {
        return $this->getParameter('quantityShipped');
    }

    public function setQuantityShipped($value)
    {
        return $this->setParameter('quantityShipped', $value);
    }

    public function getAmountShipped()
    {
        return $this->getParameter('amountShipped');
    }

    public function setAmountShipped($value)
    {
        return $this->setParameter('amountShipped', $value);
    }

    public function getQuantityRefunded()
    {
        return $this->getParameter('quantityRefunded');
    }

    public function setQuantityRefunded($value)
    {
        return $this->setParameter('quantityRefunded', $value);
    }

    public function getAmountRefunded()
    {
        return $this->getParameter('amountRefunded');
    }

    public function setAmountRefunded($value)
    {
        return $this->setParameter('amountRefunded', $value);
    }

    public function getQuantityCanceled()
    {
        return $this->getParameter('quantityCanceled');
    }

    public function setQuantityCanceled($value)
    {
        return $this->setParameter('quantityCanceled', $value);
    }

    public function getAmountCanceled()
    {
        return $this->getParameter('amountCanceled');
    }

    public function setAmountCanceled($value)
    {
        return $this->setParameter('amountCanceled', $value);
    }

    public function getShippableQuantity()
    {
        return $this->getParameter('shippableQuantity');
    }

    public function setShippableQuantity($value)
    {
        return $this->setParameter('shippableQuantity', $value);
    }

    public function getRefundableQuantity()
    {
        return $this->getParameter('refundableQuantity');
    }

    public function setRefundableQuantity($value)
    {
        return $this->setParameter('refundableQuantity', $value);
    }

    public function getCancelableQuantity()
    {
        return $this->getParameter('cancelableQuantity');
    }

    public function setCancelableQuantity($value)
    {
        return $this->setParameter('cancelableQuantity', $value);
    }

    public function getCreatedAt()
    {
        return $this->getParameter('createdAt');
    }

    public function setCreatedAt($value)
    {
        return $this->setParameter('createdAt', $value);
    }
}
