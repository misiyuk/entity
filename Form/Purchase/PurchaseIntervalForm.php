<?php

namespace App\Entity\Form\Purchase;

class PurchaseIntervalForm
{
    /**
     * @var \DateTime
     */
    private $from;

    /**
     * @var \DateTime
     */
    private $to;

    /**
     * @return \DateTime
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param \DateTime $to
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }

    /**
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     */
    public function setFrom($from): void
    {
        $this->from = $from;
    }
}