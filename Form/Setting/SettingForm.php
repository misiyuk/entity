<?php

namespace App\Entity\Form\Setting;

/**
 * @property string $timezone
 */
class SettingForm
{
    private $timezone;

    public function __construct(?string $timezone = null)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param $timezone
     */
    public function setTimezone($timezone): void
    {
        $this->timezone = $timezone;
    }
}
