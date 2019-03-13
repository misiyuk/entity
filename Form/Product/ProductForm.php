<?php

namespace App\Entity\Form\Product;

use App\Entity\Barcode;
use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\ECommerceCategory;
use App\Entity\ECommerceTag;
use App\Entity\InternalCode;
use App\Entity\Product;
use App\Entity\ProductPhoto;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductForm
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $oldPrice;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var ECommerceCategory
     */
    private $eCommerceCategory;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @var ECommerceTag[]
     */
    private $eCommerceTags;

    /**
     * @var Brand
     */
    private $brandSelect;

    /**
     * @var string
     */
    private $brandText;

    /**
     * @var ProductInternalCodeForm[]
     */
    private $internalCodes;

    /**
     * @var ProductBarcodeForm[]
     */
    private $barcodes;

    /**
     * @var bool
     */
    private $purchaseHelper;

    /**
     * @var bool
     */
    private $isECommerce;

    /**
     * @var float
     */
    private $weight;

    /**
     * @var UploadedFile[]
     */
    private $photos;

    private $newProductPhotos = [];

    public function __construct(?Product $product = null)
    {
        if ($product) {
            $this->name = $product->getName();
            $this->price = $product->getPrice();
            $this->oldPrice = $product->getOldPrice();
            $this->description = $product->getDescription();
            $this->category = $product->getCategory();
            $this->eCommerceCategory = $product->getECommerceCategory();
            $this->tags = $product->getTags()->toArray();
            $this->eCommerceTags = $product->getECommerceTags()->toArray();
            $this->brandSelect = $product->getBrand();
            $this->purchaseHelper = $product->getPurchaseHelper();
            $this->isECommerce = $product->getIsECommerce();
            $this->weight = $product->getWeight();
            foreach ($product->getInternalCodes() as $internalCode) {
                $internalCodeForm = new ProductInternalCodeForm();
                $internalCodeForm->setValue($internalCode->getValue());
                $internalCodeForm->setActInvoice(
                    $internalCode->getActInvoice() ?
                        $internalCode->getActInvoice()->getId() :
                        null
                );
                $this->internalCodes[] = $internalCodeForm;
            }
            foreach ($product->getBarcodes() as $barcode) {
                $barcodeForm = new ProductBarcodeForm();
                $barcodeForm->setValue($barcode->getValue());
                $this->barcodes[] = $barcodeForm;
            }
            $this->photos = [];
        }
    }

    /**
     * @param Product $product
     * @throws \Exception
     */
    public function deserialize(Product $product)
    {
        foreach ($this->tags as $tag) {
            $product->addTag($tag);
        }
        foreach ($this->eCommerceTags as $ECTag) {
            $product->addECommerceTag($ECTag);
        }

        foreach ($this->internalCodes as $internalCodeForm) {
            if (!$internalCodeForm->getValue()) {
                continue;
            }
            $internalCode = InternalCode::create(
                $internalCodeForm->getValue(),
                $product,
                $internalCodeForm->getActInvoiceEntity()
            );
            $product->addInternalCode($internalCode);
        }
        foreach ($this->barcodes as $barcode) {
            if (!$barcode->getValue()) {
                continue;
            }
            $newBarcode = Barcode::create($barcode->getValue());
            $product->addBarcode($newBarcode);
        }
        $brand = empty($this->brandText) ?
            $this->brandSelect :
            (new Brand())->setName($this->brandText)
        ;

        $product->setCategory($this->category);
        $product->setECommerceCategory($this->eCommerceCategory);
        $product->setBrand($brand);

        $product->setName($this->name);
        $product->setPrice($this->price);
        $product->setOldPrice($this->oldPrice);
        $product->setDescription($this->description);
        $product->setPurchaseHelper($this->purchaseHelper);
        $product->setIsECommerce($this->isECommerce);
        $product->setWeight($this->weight);
        foreach ($this->photos as $photo) {
            if (!preg_match('#(image/.*)#', $photo->getMimeType())) {
                throw new \Exception('Неверный тип файла '.$photo->getMimeType());
            }
            $photo = (new ProductPhoto())
                ->setPath($photo->getRealPath())
                ->setMimeType($photo->getMimeType())
                ->setOriginalName($photo->getClientOriginalName())
                ->setSize($photo->getSize())
                ->setSort($product->getProductPhotos()->count())
            ;
            $product->addProductPhoto($photo);
            $this->newProductPhotos[] = $photo;
        }
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param mixed $oldPrice
     */
    public function setOldPrice($oldPrice): void
    {
        $this->oldPrice = $oldPrice;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return ProductBarcodeForm[]
     */
    public function getBarcodes()
    {
        return $this->barcodes;
    }

    /**
     * @return mixed
     */
    public function getBrandSelect()
    {
        return $this->brandSelect;
    }

    /**
     * @return mixed
     */
    public function getBrandText()
    {
        return $this->brandText;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return ProductInternalCodeForm[]
     */
    public function getInternalCodes()
    {
        return $this->internalCodes;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return ECommerceTag[]
     */
    public function getECommerceTags()
    {
        return $this->eCommerceTags;
    }

    /**
     * @param ECommerceTag[] $eCommerceTags
     */
    public function setECommerceTags($eCommerceTags): void
    {
        $this->eCommerceTags = $eCommerceTags;
    }

    /**
     * @param mixed $barcodes
     */
    public function setBarcodes($barcodes): void
    {
        $this->barcodes = $barcodes;
    }

    /**
     * @param mixed $brandSelect
     */
    public function setBrandSelect($brandSelect): void
    {
        $this->brandSelect = $brandSelect;
    }

    /**
     * @param mixed $brandText
     */
    public function setBrandText($brandText): void
    {
        $this->brandText = $brandText;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return ECommerceCategory
     */
    public function getECommerceCategory()
    {
        return $this->eCommerceCategory;
    }

    /**
     * @param ECommerceCategory $eCommerceCategory
     */
    public function setECommerceCategory($eCommerceCategory): void
    {
        $this->eCommerceCategory = $eCommerceCategory;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @param mixed $internalCodes
     */
    public function setInternalCodes($internalCodes): void
    {
        $this->internalCodes = $internalCodes;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return bool
     */
    public function getPurchaseHelper()
    {
        return $this->purchaseHelper;
    }

    /**
     * @param bool $purchaseHelper
     */
    public function setPurchaseHelper($purchaseHelper): void
    {
        $this->purchaseHelper = $purchaseHelper;
    }

    /**
     * @return bool
     */
    public function getIsECommerce()
    {
        return $this->isECommerce;
    }

    /**
     * @param bool $isECommerce
     */
    public function setIsECommerce($isECommerce): void
    {
        $this->isECommerce = $isECommerce;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getPhotos()
    {
        return $this->photos;
    }

    public function setPhotos($photos): void
    {
        $this->photos = $photos;
    }

    /**
     * @return array
     */
    public function getNewProductPhotos(): array
    {
        return $this->newProductPhotos;
    }
}
