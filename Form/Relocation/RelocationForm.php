<?php

namespace App\Entity\Form\Relocation;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Stock;

class RelocationForm
{
    /** @var int */
    private $id;

    /** @var \Doctrine\Common\Collections\Collection */
    private $relocationProducts;

    /** @var Stock */
    private $from;

    /** @var Stock */
    private $to;

    /**
     * Relocation constructor.
     * @throws
     */
    public function __construct()
    {
        $this->relocationProducts = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return RelocationProductForm[]|ArrayCollection
     */
    public function getRelocationProducts(): \Countable
    {
        return $this->relocationProducts;
    }

    /**
     * @param RelocationProductForm[]|ArrayCollection $relocationProducts
     *
     * @return RelocationForm
     */
    public function setRelocationProducts($relocationProducts)
    {
        foreach ($relocationProducts as $relocationProduct) {
            $relocationProduct->setRelocation($this);
        }

        $this->relocationProducts = $relocationProducts;

        return $this;
    }

    /**
     * @param Stock $from
     * @return RelocationForm
     */
    public function setFrom(Stock $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return Stock
     */
    public function getFrom(): ?Stock
    {
        return $this->from;
    }

    /**
     * @param Stock $to
     * @return RelocationForm
     */
    public function setTo(Stock $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Stock
     */
    public function getTo(): ?Stock
    {
        return $this->to;
    }

    public function removeRelocationProduct(RelocationProductForm $relocationProduct): self
    {
        if ($this->relocationProducts->contains($relocationProduct)) {
            $this->relocationProducts->removeElement($relocationProduct);
            // set the owning side to null (unless already changed)
            if ($relocationProduct->getRelocation() === $this) {
                $relocationProduct->setRelocation(null);
            }
        }

        return $this;
    }

    public function addRelocationProduct(RelocationProductForm $relocationProduct): self
    {
        if (!$this->relocationProducts->contains($relocationProduct)) {
            $this->relocationProducts[] = $relocationProduct;
            $relocationProduct->setRelocation($this);
        }

        return $this;
    }
}
