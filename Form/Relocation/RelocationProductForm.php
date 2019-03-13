<?php

namespace App\Entity\Form\Relocation;

use App\Entity\Product;

class RelocationProductForm
{
    /** @var int */
    private $qty;

    /** @var Product */
    private $product;

    /** @var RelocationForm */
    private $relocation;

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setRelocation(?RelocationForm $relocation): self
    {
        $this->relocation = $relocation;

        return $this;
    }

    public function getRelocation(): ?RelocationForm
    {
        return $this->relocation;
    }
}
