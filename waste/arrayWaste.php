<?php
/* recuperation des informations du fichier data.json pour connaitre les differents type de plastiques

*/
$file = "data.json";
$data = json_decode(file_get_contents($file), TRUE);

$arrayWaste = array();

foreach($data['quartiers'] as $key => $value)
{
    if(isset($value['plastiques']) && $value['plastiques'] != '')
    {
        foreach($value['plastiques'] as $k =>$v)
        {
            if (!in_array($k, $arrayWaste))
            {
                array_push($arrayWaste, $k);
            }
        }
    }
  
    foreach($value as $waste => $weight)
    {
        if ($waste != 'population' && $waste != 'plastiques')
        {
            if (!in_array($waste, $arrayWaste))
            {
                array_push($arrayWaste, $waste);
            }
        }
    }
}
