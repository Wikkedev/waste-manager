<?php
/*
créer un programme qui acceptera en entrée un fichier json contenant les déchets ainsi que les services de traitement des déchets disponibles, 
et afficher le résultat de la répartition des déchets 
ainsi que le CO2 rejetté (par chaque service de traitement et au global).
L'idéal est de favoriser les traitement adaptés et de ne se replier sur les autres méthodes de traitement que lorsque les capacités maximales d'un services sont atteintes.
*/

$file = "data.json";
$data = json_decode(file_get_contents($file), TRUE);

$file = "co2.json";
$co2= json_decode(file_get_contents($file), TRUE);


require 'autoload.php';

use App\waste\glassIncinerationClass;
use App\waste\glassRecyclingClass;

use App\waste\metalsIncinerationClass;
use App\waste\metalsRecyclingClass;

use App\waste\organicIncinerationClass;
use App\waste\organicCompostinggClass;

use App\waste\otherIncinerationClass;

use App\waste\paperIncinerationClass;
use App\waste\paperRecyclingClass;

use App\waste\pcIncinerationClass;
use App\waste\pcRecyclingClass;

use App\waste\petIncinerationClass;
use App\waste\petRecyclingClass;

use App\waste\phedIncinerationClass;
use App\waste\phedRecyclingClass;

use App\waste\pvcIncinerationClass;
use App\waste\pvcRecyclingClass;

use App\services\composterClass;
use App\services\incineratorClass;
use App\services\recyclingGlassClass;
use App\services\recyclingMetalsClass;
use App\services\recyclingPlaticClass;

use App\services\sortingCenterClass;

/* Logique métier :
Tous les dechets passent par le centre de tri (sortingCenter) sauf [other] qui va directement à l'incineration.
Le centre de tri à une capacité defini (73922 Tonnes(T)).
Quand la capacité du centre de tri est atteinte, les dechets vont à l'incinérateur.
Tant que la capacité du centre de tri n'est pas atteinte, les dechets vont dans leur tri respectif jusqu'a que leur capacité soit atteinte. Ils vont alors à l'incinérateur.
Il y a 3 incinétateurs. Je commence par remplir le premier puis une fois sa capacité atteinte, j'envoie au deuxieme puis au troisieme.
Il y a 9 composteurs. Même logique que pour les incinérateurs.

???????????
Les composteurs ont un attribut "foyers". Aucune information n'est fourni sur sa signification. J'aurai tendance à le considérer comme le nombre de "maison" ayant le droit de déposer leurs dechets organiques dans ce composteur. "Foyers" pourrait être en relation avec "population". il faudrait connaitre le nombre d'habitant par foyer pour pouvoir en faire quelque chose.
Je ne m'en servirai donc pas mais je l'ajoute dans les class en tant qu'attribut.
???????????

Si les dechets ne peuvent pas être recyclés ni incinérés car les capacités sont atteintes, Le programme calcule ce qui reste à traiter et cette quantité repart dans la boucle.

*/

include_once('services/arrayServices.php');

var_dump($arrayServices);

if (in_array('centreTri', $arrayServices) && !isset($sortingCenter))
{
  $sortingCenter = new sortingCenter('centreTri', '73922');
}


