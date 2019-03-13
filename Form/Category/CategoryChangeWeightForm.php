<?php

namespace App\Entity\Form\Category;

class CategoryChangeWeightForm
{
    private $weight;

    /**
     * @param float $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }
}