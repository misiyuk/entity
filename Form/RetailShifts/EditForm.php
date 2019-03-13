<?php

namespace App\Entity\Form\RetailShifts;

use App\Entity\Cashbox;
use App\Entity\RetailShift;
use App\Entity\Stock;
use App\Repository\CashboxRepository;
use App\Repository\StockRepository;
use App\UseCases\RetailShiftService;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EditForm
 * @package App\Entity\RetailShifts
 *
 * @property string $retailShiftId
 * @property string $openAt
 * @property string $closeAt
 * @property $cashBoxId
 * @property $revenue
 * @property $cashRevenue
 * @property $noCashRevenue
 * @property $stockId
 *
 * @property CashboxRepository $cashBoxRepository
 * @property StockRepository $stockRepository
 */
class EditForm
{
    private $cashBoxRepository;
    private $stockRepository;

    public $retailShiftId;
    public $openAt;
    public $closeAt;
    public $cashBoxId;
    public $revenue;
    public $cashRevenue;
    public $noCashRevenue;
    public $stockId;

    public function __construct(
        CashboxRepository $cashBoxRepository,
        StockRepository $stockRepository,
        RetailShift $retailShift = null
    )
    {
        if ($retailShift) {
            $dateOpenAt = $retailShift->getOpenAt();
            $dateCloseAt = $retailShift->getCloseAt();
            $this->openAt = $dateOpenAt->format(RetailShiftService::TIME_FORMAT);
            $this->closeAt = $dateCloseAt->format(RetailShiftService::TIME_FORMAT);
            $this->retailShiftId = $retailShift->getId();
            $this->cashBoxId = $retailShift->getCashboxUuid();
            $this->revenue = $retailShift->getRevenue();
            $this->cashRevenue = $retailShift->getCashRevenue();
            $this->noCashRevenue = $retailShift->getNocashRevenue();
            $this->stockId = $retailShift->getStockUuid();
        }
        $this->cashBoxRepository = $cashBoxRepository;
        $this->stockRepository = $stockRepository;
    }

    /**
     * @return Cashbox[]
     */
    public function getCashBoxList(): array
    {
        return $this->cashBoxRepository->findAll();
    }

    /**
     * @return Stock[]
     */
    public function getStockList(): array
    {
        return $this->stockRepository->findAll();
    }

    /**
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

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    public function getOpenAt()
    {
        return $this->openAt;
    }

    public function setOpenAt($openAt)
    {
        $this->openAt = $openAt;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    public function getCloseAt()
    {
        return $this->closeAt;
    }

    public function setCloseAt($closeAt)
    {
        $this->closeAt = $closeAt;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public function getCashBoxId()
    {
        return $this->cashBoxId;
    }

    public function setCashBoxId($cashBoxId)
    {
        $this->cashBoxId = $cashBoxId;
    }

    /**
     * @Assert\NotBlank()
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;
    }

    /**
     * @Assert\NotBlank()
     */
    public function getCashRevenue()
    {
        return $this->cashRevenue;
    }

    public function setCashRevenue($cashRevenue)
    {
        $this->cashRevenue = $cashRevenue;
    }

    /**
     * @Assert\NotBlank()
     */
    public function getNoCashRevenue()
    {
        return $this->noCashRevenue;
    }

    public function setNoCashRevenue($noCashRevenue)
    {
        $this->noCashRevenue = $noCashRevenue;
    }

    /**
     * @Assert\NotBlank()
     */
    public function getStockId()
    {
        return $this->stockId;
    }

    public function setStockId($stockId)
    {
        $this->stockId = $stockId;
    }
}
