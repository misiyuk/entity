<?php

namespace App\Entity\Form\Purchase;

use App\Entity\ActInvoice;
use App\Entity\InternalCode;
use App\Entity\Purchase;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Collection;

class PurchaseForm
{
    /**
     * @var float
     */
    private $deliveryCost;

    /**
     * @var Collection|PurchaseProductForm[]
     */
    private $products;

    /**
     * @var bool
     */
    private $confirmed;

    /**
     * @var string|null
     */
    private $name;

    public function __construct(?Purchase $purchase = null)
    {
        if ($purchase) {
            $this->deliveryCost = $purchase->getDeliveryCost();
            $this->confirmed = $purchase->getConfirmed();
            $this->name = $purchase->getName();
            $products = new ArrayCollection();
            foreach ($purchase->getAct()->getActInvoices() as $actInvoice) {
                $productForm = new PurchaseProductForm();
                $productForm->setCostPrice($actInvoice->getCostPrice());
                $productForm->setQty($actInvoice->getQty());
                $productForm->setProduct($actInvoice->getProduct()->getId());
                $productForm->setInternalCode(
                    $actInvoice->getInternalCode() ? $actInvoice->getInternalCode()->getValue() : ''
                );
                $products->add($productForm);
            }
            $this->products = $products;
        }
    }

    public function deserialize(Purchase $purchase)
    {
        $purchase->setDeliveryCost($this->deliveryCost);
        $purchase->setConfirmed($this->confirmed ?? false);
        $purchase->setName($this->name);
        $purchase->getAct()->actInvoicesClear();
        foreach ($this->products as $productForm) {
            $actInvoice = new ActInvoice();
            $actInvoice->setCostPrice($productForm->getCostPrice());
            $actInvoice->setQty($productForm->getQty());
            $actInvoice->setProduct($productForm->getProductEntity());
            $actInvoice->setInternalCode(
                $this->getInternalCode($productForm)
            );
            $purchase->getAct()->addActInvoice($actInvoice);
        }
    }

    private function getInternalCode(PurchaseProductForm $productForm)
    {
        foreach ($productForm->getProductEntity()->getInternalCodes() as $internalCode) {
            if ($productForm->getInternalCode() == $internalCode->getValue()) {
                return $internalCode;
            }
        }

        return (new InternalCode())
            ->setValue(
                $productForm->getInternalCode()
            )
            ->setProduct(
                $productForm->getProductEntity()
            );
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($products): void
    {
        $this->products = $products;
    }

    public function getDeliveryCost()
    {
        return $this->deliveryCost;
    }

    public function setDeliveryCost($deliveryCost): void
    {
        $this->deliveryCost = $deliveryCost;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     */
    public function setConfirmed($confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
