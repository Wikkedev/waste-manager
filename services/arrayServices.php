<?php
$file = "data.json";
$data = json_decode(file_get_contents($file), TRUE);

$arrayServices = array();

foreach($data['services'] as $value)
{
  array_push($arrayServices, $value['type']);
}