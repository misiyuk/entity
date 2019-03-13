<?php

namespace App\Entity\Form\WriteOff;

use App\Entity\Stock;
use App\Entity\WriteOff;
use Doctrine\Common\Collections\Collection;

/**
 * Class WriteOffForm
 * @package App\Entity\Form\WriteOff
 *
 * @property Stock $stock
 * @property string $comment
 * @property Collection|WriteOffProductForm[] $products
 */
class WriteOffForm
{
    private $stock;
    private $comment;
    private $products;

    public function __construct(?WriteOff $writeOff = null)
    {
        if ($writeOff) {
            $this->stock = $writeOff->getAct()->getStock();
            $this->comment = $writeOff->getComment();
            foreach ($writeOff->getAct()->getActInvoices() as $actInvoice) {
                $productForm = new WriteOffProductForm();
                $productForm->setProduct($actInvoice->getProduct()->getId());
                $productForm->setQty($actInvoice->getQty());
                $this->products[] = $productForm;
            }
        }
    }

    /**
     * @param WriteOffProductForm[]|Collection $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

    /**
     * @return WriteOffProductForm[]|Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Stock $stock
     */
    public function setStock(?Stock $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return Stock
     */
    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
