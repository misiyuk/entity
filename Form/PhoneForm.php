<?php

namespace App\Entity\Form;

use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

class PhoneForm
{
    /**
     * @AssertPhoneNumber
     */
    public $tel;

    public function __construct(int $tel)
    {
        $this->tel = "+$tel";
    }
}
