<?php

namespace App\services;


class recyclingPlasticClass extends servicesAbstractClass
{
    protected $plastic; // array
    protected int $capacite;
    protected string $type;

    public function __construct(string $type, int $capacite, $plastic)
    {
        $this->capacite = $capacite;
        $this->type = $type;
        $this->plastic = $plastic;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function getPlastic(): array
    {
        return $this->plastic;
    }
  
}