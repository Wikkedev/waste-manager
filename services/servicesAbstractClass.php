<?php
namespace App\services;


abstract class servicesAbstractClass
{
    protected string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
  
    
}