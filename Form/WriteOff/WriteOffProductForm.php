<?php

namespace App\Entity\Form\WriteOff;

use App\Entity\Product;

/**
 * Class WriteOffProductForm
 * @package App\Entity\Form\WriteOff
 *
 * @property int $product
 * @property Product $productEntity
 * @property int $qty
 */
class WriteOffProductForm
{
    private $product;
    private $productEntity;
    private $qty;

    /**
     * @param int $qty
     */
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @return int
     */
    public function getQty(): ?int
    {
        return $this->qty;
    }

    /**
     * @param int $product
     */
    public function setProduct(int $product): void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getProduct(): ?int
    {
        return $this->product;
    }

    /**
     * @param Product|null $productEntity
     */
    public function setProductEntity(?Product $productEntity): void
    {
        $this->productEntity = $productEntity;
    }

    /**
     * @return Product|null
     */
    public function getProductEntity(): ?Product
    {
        return $this->productEntity;
    }
}
