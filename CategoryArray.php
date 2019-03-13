<?php

namespace App\Entity;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryArray
 * @package App\Entity
 *
 * @property Category $activeEntity
 */
class CategoryArray implements CategoryInterface
{
    private $activeEntity;
    /** @var Category[] */
    private static $categories;

    public function __construct($activeEntityId, ?ObjectManager $em = null)
    {
        if ($em) {
            $categories = $em->getRepository(Category::class)->findAll();
            $this->setCategories($categories);
        }
        $this->activeEntity = self::$categories[$activeEntityId];
    }

    /**
     * @return CategoryInterface[]
     */
    public function getChildren(): array
    {
        $children = [];
        foreach (self::$categories as $category) {
            if ($category->getParentId() === $this->activeEntity->getId()) {
                $children[] = new self($category->getId());
            }
        }

        return $children;
    }

    public function getParent(): ?CategoryInterface
    {
        $parentId = $this->activeEntity->getParentId();

        return new self($parentId);
    }

    public function getName(): string
    {
        return $this->activeEntity->getName();
    }

    public function getId()
    {
        return $this->activeEntity->getId();
    }

    /**
     * @param Category[] $categories
     */
    private function setCategories(array $categories): void
    {
        foreach ($categories as $category) {
            self::$categories[$category->getId()] = $category;
        }
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function setName(string $name)
    {
        throw new \Exception('Not support set operation');
    }

    /**
     * @param CategoryInterface|null $category
     * @throws \Exception
     */
    public function setParent(?CategoryInterface $category)
    {
        throw new \Exception('Not support set operation');
    }
}