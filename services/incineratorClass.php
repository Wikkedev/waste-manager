<?php

namespace App\services;


class incineratorClass extends servicesAbstractClass
{
    protected int $ligneFour;
    protected int $capaciteligne;
    protected string $type;

    public function __construct(string $type, int $ligneFour, int $capaciteLigne)
    {
        $this->capaciteLigne = $capaciteLigne;
        $this->type = $type;
        $this->ligneFour = $ligneFour;
    }

    public function getCapacite()
    {
        return $this->capaciteLigne * $this->ligneFour;
    }
  
    public function incineratorTreatment()
    {
      
    }
}