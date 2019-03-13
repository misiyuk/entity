<?php

namespace App\Entity\Form\SupplyActs;

use App\Entity\InternalCode;
use App\Entity\Product;

class SupplyProductForm
{
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
     * @var InternalCode
     */
    private $internalCodeEntity;

    /**
     * @return int
     */
    public function getProduct(): ?int
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
     * @return Product
     */
    public function getProductEntity(): ?Product
    {
        return $this->productEntity;
    }

    /**
     * @param Product $product
     */
    public function setProductEntity(?Product $product): void
    {
        $this->productEntity = $product;
    }

    /**
     * @return int
     */
    public function getQty(): ?int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     */
    public function setQty($qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return string|null
     */
    public function getInternalCode(): ?string
    {
        return $this->internalCode;
    }

    /**
     * @param string $internalCode
     */
    public function setInternalCode($internalCode): void
    {
        $this->internalCode = $internalCode;
    }

    /**
     * @return InternalCode|null
     */
    public function getInternalCodeEntity(): ?InternalCode
    {
        return $this->internalCodeEntity;
    }

    /**
     * @param InternalCode $internalCode
     */
    public function setInternalCodeEntity(?InternalCode $internalCode): void
    {
        $this->internalCodeEntity = $internalCode;
    }
}
