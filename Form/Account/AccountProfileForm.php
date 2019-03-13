<?php

namespace App\Entity\Form\Account;

use App\Entity\Client;
use libphonenumber\PhoneNumber;

class AccountProfileForm
{
    public $firstName;
    public $fatherName;
    public $lastName;
    public $tel;

    /** @var AccountProfileCodeForm[] */
    public $codeForms = [];

    public function __construct(Client $client)
    {
        $this->firstName = $client->getFirstName();
        $this->fatherName = $client->getFatherName();
        $this->lastName = $client->getLastName();
        $this->tel = (new PhoneNumber())
            ->setRawInput("+{$client->getMobilePhone()}")
        ;
    }

    public function deserialize(Client $client)
    {
        $client->setFirstName($this->firstName);
        $client->setFatherName($this->fatherName);
        $client->setLastName($this->lastName);
        $client->setMobilePhone($this->getTelInt());
    }

    public function getTelInt(): int
    {
        return (int) ($this->tel->getCountryCode().$this->tel->getNationalNumber());
    }
}
