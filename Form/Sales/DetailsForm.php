<?php

namespace App\Entity\Form\Sales;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Details
 * @package App\Entity\Form\Sale
 *
 * @property string $storeId
 * @property string $retailShiftId
 * @property int $clientId
 * @property float $cashSum
 * @property float $cashlessSum
 * @property int $saleId
 */
class DetailsForm
{
    public $storeId;
    public $retailShiftId;
    public $clientId;
    public $cashSum;
    public $cashlessSum;
    public $saleId;

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min="0")
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @Assert\Range(min="0")
     */
    public function getCashSum()
    {
        return $this->cashSum;
    }

    public function setCashSum($cashSum)
    {
        $this->cashSum = $cashSum ?: 0;
    }

    /**
     * @Assert\Range(min="0")
     */
    public function getCashlessSum()
    {
        return $this->cashlessSum;
    }

    public function setCashlessSum($cashlessSum)
    {
        $this->cashlessSum = $cashlessSum ?: 0;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public function getRetailShiftId()
    {
        return $this->retailShiftId;
    }

    public function setRetailShiftId($retailShiftId)
    {
        $this->retailShiftId = $retailShiftId;
    }

    public function getSaleId()
    {
        return $this->saleId;
    }

    public function setSaleId($saleId)
    {
        $this->saleId = $saleId;
    }
}
