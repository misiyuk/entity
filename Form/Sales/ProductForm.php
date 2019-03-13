<?php

namespace App\Entity\Form\Sales;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package App\Entity\Form\Sale
 *
 * @property int $id
 * @property int $qty
 * @property float $costPrice
 * @property float $discount
 */
class ProductForm
{
    public $id;
    public $qty;
    public $costPrice;
    public $discount;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1)
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0)
     *
     * @return float
     */
    public function getCostPrice()
    {
        return $this->costPrice;
    }

    public function setCostPrice($costPrice)
    {
        $this->costPrice = $costPrice;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0)
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }
}