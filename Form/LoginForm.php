<?php

namespace App\Entity\Form;

class LoginForm
{
    private $tel;
    public $code;
    public $captcha;

    public function getTel()
    {
        return $this->tel;
    }

    public function setTel($tel): void
    {
        $this->tel = $tel;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): void
    {
        $this->code = $code;
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }

    public function setCaptcha($captcha): void
    {
        $this->captcha = $captcha;
    }
}
