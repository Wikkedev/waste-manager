<?php

namespace App\services;

//use App\treatment\recyclingInterface;


class recyclingGlassClass extends servicesAbstractClass  //implements recyclingInterface
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
  
    /*public function recyclingTraitement(recyclingInterface $weight)
    {
        if ($this->capacite > $weight){
            return true;
        }
        else {
            $reste = $weight - $this->capacite;
            
        }
    }*/
}