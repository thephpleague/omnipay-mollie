<?php

namespace Omnipay\Mollie;

class Item extends \Omnipay\Common\Item
{
    public function getId()
    {
        return $this->getParameter('id');
    }

    public function setId($value)
    {
        return $this->setParameter('id', $value);
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
}
