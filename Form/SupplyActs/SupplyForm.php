<?php

namespace App\Entity\Form\SupplyActs;

use App\Entity\Act;
use App\Entity\ActInvoice;
use App\Entity\Batch;
use App\Entity\BatchBalance;
use App\Entity\InternalCode;
use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\SupplyProduct;
use App\Repository\InternalCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Collection;

class SupplyForm
{
    /**
     * @var \DateTime
     */
    private $dateAct;

    /**
     * @var Stock
     */
    private $stock;

    /**
     * @var float
     */
    private $deliveryCost;

    /**
     * @var Collection|SupplyProductForm[]
     */
    private $products;

    public function __construct(?Batch $batch = null)
    {
        if ($batch) {
            $this->dateAct = $batch->getAct()->getDateAct();
            $this->stock = $batch->getAct()->getStock();
            $this->products = new ArrayCollection();
            $this->deliveryCost = $batch->getDeliveryCost();
            foreach ($batch->getAct()->getActInvoices() as $actInvoice) {
                $product = new SupplyProductForm();
                $product->setQty($actInvoice->getQty());
                $product->setProduct($actInvoice->getProduct()->getId());
                $product->setInternalCode(
                    $actInvoice->getSupplyProduct() ?
                        $actInvoice->getSupplyProduct()->getInternalCode() :
                        ''
                );
                $this->products->add($product);
            }
        }
    }

    /**
     * @param Batch $batch
     * @param InternalCodeRepository $internalCodeRepository
     * @throws
     */
    public function deserialize(Batch $batch, ?InternalCodeRepository $internalCodeRepository = null): void
    {
        $weightError = null;
        $error = null;
        $batch->setDeliveryCost($this->deliveryCost);
        $batch->getAct()->setDateAct($this->dateAct);
        $batch->getAct()->setStock($this->stock);
        if ($internalCodeRepository) {
            foreach ($batch->getAct()->getActInvoices() as $actInvoice) {
                $actInvoice->getProduct()->setCostPrice($this->calcOldCostPrice($actInvoice, $internalCodeRepository));
                $actInvoice->setCostPrice($actInvoice->getProduct()->getCostPrice());
                $oldSupplyQty = $actInvoice->getProduct()->getSupplyQty() - $actInvoice->getQty();
                $actInvoice->getProduct()->setSupplyQty($oldSupplyQty < 0 ? 0 : $oldSupplyQty);
            }
        }
        $batch->getAct()->actInvoicesClear();
        $batch->batchBalancesClear();
        foreach ($this->products as $productForm) {
            $supplyProduct = (new SupplyProduct())
                ->setInternalCode(
                    $productForm->getInternalCode()
                )
            ;
            $actInvoice = (new ActInvoice())
                ->setQty($productForm->getQty())
                ->setProduct($productForm->getProductEntity())
                ->setSupplyProduct($supplyProduct)
            ;
            $batchBalance = BatchBalance::create(
                $productForm->getProductEntity(),
                $productForm->getQty(),
                $this->stock,
                $batch
            );

            $batch->addBatchBalance($batchBalance);
            $batch->getAct()->addActInvoice($actInvoice);
            try {
                if (!$productForm->getInternalCodeEntity()) {
                    throw new \Exception('Не найден внутренний код '.$productForm->getInternalCode(), 2);
                }
                $costPrice = $this->getCostPrice($actInvoice, $productForm->getInternalCodeEntity());
                $sumOldCostPrice = $actInvoice->getProduct()->getSupplyQty() * $actInvoice->getProduct()->getCostPrice();
                $sumNewCostPrice = $actInvoice->getQty() * $costPrice;
                $newSupplyQty = $actInvoice->getProduct()->getSupplyQty() + $actInvoice->getQty();
                $actInvoice->getProduct()->setSupplyQty($newSupplyQty);
                $newCostPrice = ($sumOldCostPrice + $sumNewCostPrice) / $newSupplyQty;
                $actInvoice->getProduct()->setCostPrice($newCostPrice);
                $actInvoice->setCostPrice($newCostPrice);
            } catch (\Exception $e) {
                if ($e->getCode() == 1) {
                    $weightError = $e->getMessage();
                } elseif ($e->getCode() != 2) {
                    throw new \Exception($e->getMessage());
                } else {
                    $error = $e->getMessage();
                }
            }
        }
        if ($weightError) {
            throw new \Exception($weightError, 1);
        }
        if ($error) {
            throw new \Exception($error, 2);
        }
    }

    /**
     * @param ActInvoice $actInvoice
     * @param InternalCodeRepository $internalCodeRepository
     * @return float
     * @throws
     */
    private function calcOldCostPrice(ActInvoice $actInvoice, InternalCodeRepository $internalCodeRepository): ?float
    {
        $internalCode = $internalCodeRepository->findBy([
            'value' => $actInvoice->getSupplyProduct()->getInternalCode(),
            'product' => $actInvoice->getProduct(),
        ]);
        if (!$internalCode) {
            throw new \Exception('Внутренний код ('.$actInvoice->getSupplyProduct()->getInternalCode().') был удален у товара ('.$actInvoice->getProduct()->getId().'). Ошибка во время пересчета себестоимости.');
        } elseif (count($internalCode) > 1){
            throw new \Exception('Ошибка данных, несколько внутренних кодов. Ошибка во время пересчета себестоимости.');
        }
        $costPrice = (new SupplyForm())->getCostPrice($actInvoice, $internalCode[0]);
        $nss = $actInvoice->getProduct()->getCostPrice();
        $x = $actInvoice->getQty() * $costPrice;
        $y = $actInvoice->getProduct()->getSupplyQty();
        $oktp = $actInvoice->getProduct()->getSupplyQty() - $actInvoice->getQty();
        $oldCostPrice = ($oktp && $y) ? ($nss - ($x/$y))/($oktp/$y) : 0;

        return $oldCostPrice;
    }

    /**
     * @param ActInvoice $actInvoice
     * @param InternalCode $internalCode
     * @return float
     * @throws \Exception
     */
    public function getCostPrice(ActInvoice $actInvoice, InternalCode $internalCode): float
    {
        if (
            !$internalCode->getActInvoice() ||
            $internalCode->getActInvoice()->getAct()->getType() !== Act::PURCHASE_TYPE
        ) {
            throw new \Exception('Не существует закупки для внутреннего кода!('.$internalCode->getValue().')', 2);
        }
        $purchase = $internalCode->getActInvoice()->getAct()->getPurchase();
        $deliveryCost = $purchase->getDeliveryCost();
        $productsQty = 0;
        foreach ($purchase->getAct()->getActInvoices() as $ai) {
            if ($ai->getProduct()->getId() == $actInvoice->getProduct()->getId()) {
                $productCostPrice = $ai->getCostPrice();
            }
            $productsQty += $ai->getQty();
        }
        if ($productsQty < 1) {
            throw new \Exception('Нет товаров в закупке ('.$purchase->getId().')!');
        }
        if (!isset($productCostPrice)) {
            throw new \Exception('Нет товара "'.$actInvoice->getProduct()->getName().'" в закупке!');
        }
        $productDeliveryCost = $deliveryCost / $productsQty;
        $noneWeightProducts = [];
        if ($this->products) {
            $productsWeightSum = array_sum(
                array_map(
                    function(SupplyProductForm $productForm) use (&$noneWeightProducts) {
                        if (!$productForm->getProductEntity()->getWeight()) {
                            $noneWeightProducts[] = $productForm->getProductEntity();
                        }
                        return $productForm->getQty() * $productForm->getProductEntity()->getWeight();
                    },
                    is_array($this->products) ? $this->products : $this->products->toArray()
                )
            );
        } else {
            $productsWeightSum = array_sum(
                array_map(
                    function (ActInvoice $actInvoice) use (&$noneWeightProducts) {
                        if (!$actInvoice->getProduct()->getWeight()) {
                            $noneWeightProducts[] = $actInvoice->getProduct();
                        }
                        return $actInvoice->getQty() * $actInvoice->getProduct()->getWeight();
                    },
                    $actInvoice->getAct()->getActInvoices()->toArray()
                )
            );
        }

        if (count($noneWeightProducts)) {
            $productIds = implode('; ', array_map(function (Product $product) {
                return $product->getId();
            }, $noneWeightProducts));
            throw new \Exception($productIds, 1);
        }
        if ($productsWeightSum == 0) {
            throw new \Exception('Вес товаров в приемке равен 0!');
        }
        $deliveryCost = $this->deliveryCost ?: $actInvoice->getAct()->getBatches()->first()->getDeliveryCost();

        return $productCostPrice +
            $productDeliveryCost +
            (($this->deliveryCost ?: $deliveryCost) *
            ($actInvoice->getProduct()->getWeight() / $productsWeightSum));
    }

    /**
     * @param SupplyProductForm[]|Collection $products
     */
    public function setProducts($products): void
    {
        $this->products = $products;
    }

    /**
     * @return SupplyProductForm[]|Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateAct()
    {
        return $this->dateAct;
    }

    /**
     * @return Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param \DateTimeInterface|null $dateAct
     * @throws \Exception
     */
    public function setDateAct(?\DateTimeInterface $dateAct): void
    {
        $this->dateAct = $dateAct ?: new \DateTime();
    }

    /**
     * @param Stock $stock
     */
    public function setStock(Stock $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @param float $deliveryCost
     */
    public function setDeliveryCost($deliveryCost): void
    {
        $this->deliveryCost = $deliveryCost;
    }

    /**
     * @return float
     */
    public function getDeliveryCost(): ?float
    {
        return $this->deliveryCost;
    }
}
