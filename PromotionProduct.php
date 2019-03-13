<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromotionProduct
 *
 * @ORM\Table(name="promotion_product")
 * @ORM\Entity(repositoryClass="App\Repository\PromotionProductRepository")
 */
class PromotionProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="promotion_product_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="sync", type="boolean")
     */
    private $sync = false;

    /**
     * @var \App\Entity\Promotion
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion", inversedBy="promotionProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promotion_id", referencedColumnName="id")
     * })
     */
    private $promotion;

    /**
     * @var \App\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

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
     * Set price.
     *
     * @param float $price
     *
     * @return PromotionProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Product $product
     * @return PromotionProduct
     */
    public function setProduct(Product $product): PromotionProduct
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Promotion $promotion
     * @return PromotionProduct
     */
    public function setPromotion(Promotion $promotion): PromotionProduct
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Promotion
     */
    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    /**
     * @param bool $sync
     * @return PromotionProduct
     */
    public function setSync(bool $sync): PromotionProduct
    {
        $this->sync = $sync;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSync(): bool
    {
        return $this->sync;
    }

}
