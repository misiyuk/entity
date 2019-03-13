<?php

namespace App\Entity\Form\Purchase;

use App\Entity\Product;

class PurchaseProductForm
{
    /**
     * @var float
     */
    private $costPrice;

    /**
     * @var integer
     */
    private $qty;

    /**
     * @var int
     */
    private $product;

    /**
     * @var Product
     */
    private $productEntity;

    /**
     * @var string
     */
    private $internalCode;

    /**
     * @return int
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $product
     */
    public function setProduct($product): void
    {
        $this->product = $product;
    }

    /**
     * @return float
     */
    public function getCostPrice(): ?float
    {
        return $this->costPrice;
    }

    /**
     * @return int
     */
    public function getQty(): ?int
    {
        return $this->qty;
    }

    /**
     * @param float $costPrice
     */
    public function setCostPrice(float $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    /**
     * @param int $qty
     */
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return string
     */
    public function getInternalCode(): ?string
    {
        return $this->internalCode;
    }

    /**
     * @param string $internalCode
     */
    public function setInternalCode(string $internalCode): void
    {
        $this->internalCode = $internalCode;
    }

    /**
     * @return Product
     */
    public function getProductEntity()
    {
        return $this->productEntity;
    }

    /**
     * @param Product $productEntity
     */
    public function setProductEntity(Product $productEntity): void
    {
        $this->productEntity = $productEntity;
    }
}
