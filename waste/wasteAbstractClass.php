<?php

namespace App\waste;

abstract class wasteAbstractClass
{
    protected int $weight;
    protected int $co2;
    protected int $population;
    protected string $name;
    protected int $district;
  
    public function __construct(string $name, int $weight, int $co2, int $population, int $district)
    {
        $this->weight = $weight;
        $this->co2 = $co2;
        $this->population = $population;
        $this->name = $name;
        $this->district = $district;
    }
  
    public function getWeight()
    {
        return $this->weight;
    }
  
    public function getCo2()
    {
        return $this->co2;
    }
  
    public function getPopulation()
    {
        return $this->population;
    }
  
    public function getName()
    {
        return $this->name;
    }
    
    public function getDistrict()
    {
        return $this->district;
    }
  
    
}