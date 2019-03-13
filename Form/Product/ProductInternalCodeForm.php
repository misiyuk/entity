<?php

namespace App\Entity\Form\Product;

use App\Entity\ActInvoice;

class ProductInternalCodeForm
{
    private $value;
    private $actInvoice;

    /**
     * @var ActInvoice
     */
    private $actInvoiceEntity;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getActInvoice()
    {
        return $this->actInvoice;
    }

    /**
     * @param mixed $actInvoice
     */
    public function setActInvoice($actInvoice): void
    {
        $this->actInvoice = $actInvoice;
    }

    public function setActInvoiceEntity(?ActInvoice $actInvoice)
    {
        $this->actInvoiceEntity = $actInvoice;

        return $this;
    }

    public function getActInvoiceEntity(): ?ActInvoice
    {
        return $this->actInvoiceEntity;
    }
}
