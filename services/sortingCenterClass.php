<?php
namespace App\services;


class sortingCenterClass extends servicesAbstractClass
{
    protected int $capacite;
    protected string $type;

    public function __construct(string $type, int $capacite)
    {
        $this->capacite = $capacite;
        $this->type = $type;

    }

    public function getCapacite()
    {
        return $this->capacite;
    }
  
    public function setInQuartier(string $type, string $waste, int $weight, int $co2)
    {
        if ($waste == 'PET' || $waste == 'PC' || $waste == 'PVC' || $waste == 'PEHD')
        {
            // je verifie si le tableau existe
            if(isset($quartier[$type]['recyclage']['plastique'][$waste]['weight']))
            {
                $quartier[$type]['recyclage']['plastique'][$waste]['weight'] += $weight;
                $quartier[$type]['recyclage']['plastique'][$waste]['CO2'] += $co2;
            }
            else
            {
                $quartier[$type]['recyclage']['plastique'][$waste]['weight'] = $weight;
                $quartier[$type]['recyclage']['plastique'][$waste]['CO2'] = $co2;
            }
        }
        else
        {
            if(isset($quartier[$type]['recyclage'][$waste]['weight']))
            {
                $quartier[$type]['recyclage'][$waste]['weight'] += $weight;
                $quartier[$type]['recyclage'][$waste]['CO2'] += $co2;
            }
            else
            {
                $quartier[$type]['recyclage'][$waste]['weight'] = $weight;
                $quartier[$type]['recyclage'][$waste]['CO2'] = $co2;
            }
        }
    }
  
}