<?php
/* recuperation des informations du fichier data.json pour connaitre les services en fonctionnement
*/
$file = "data.json";
$data = json_decode(file_get_contents($file), TRUE);

$arrayServices = array();

foreach($data['services'] as $value)
{
    array_push($arrayServices, $value['type']);
}
