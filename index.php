<?php
/*
créer un programme qui acceptera en entrée un fichier json contenant les déchets ainsi que les services de traitement des déchets disponibles, 
et afficher le résultat de la répartition des déchets 
ainsi que le CO2 rejetté (par chaque service de traitement et au global).
L'idéal est de favoriser les traitement adaptés et de ne se replier sur les autres méthodes de traitement que lorsque les capacités maximales d'un services sont atteintes.
*/

$file = "data.json";
$data = json_decode(file_get_contents($file), TRUE);
//var_dump($data);

$file = "co2.json";
$co2= json_decode(file_get_contents($file), TRUE);
//var_dump($co2);

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
use App\services\recyclingPaperClass;
use App\services\recyclingMetalsClass;
use App\services\recyclingPlasticClass;

use App\services\sortingCenterClass;

use App\treatment\recyclingInterface;

/* Logique métier :
Tous les dechets passent par le centre de tri (sortingCenter) sauf [other] qui va directement à l'incineration.
Le centre de tri à une capacité defini (73922 Tonnes(T)).
Quand la capacité du centre de tri est atteinte, les dechets vont à l'incinérateur.
Tant que la capacité du centre de tri n'est pas atteinte, les dechets vont dans leur tri respectif jusqu'a que leur capacité soit atteinte. Ils vont alors à l'incinérateur.
Il y a 3 incinétateurs. Je commence par remplir le premier puis une fois sa capacité atteinte, j'envoie au deuxieme puis au troisieme.
Il y a 9 composteurs. Même logique que pour les incinérateurs.

???????????
Les composteurs ont un attribut "foyers". Aucune information n'est fourni sur sa signification. J'aurais tendance à le considérer comme le nombre de "maison" ayant le droit de déposer leurs dechets organiques dans ce composteur. "Foyers" pourrait être en relation avec "population". il faudrait connaitre le nombre d'habitant par foyer pour pouvoir en faire quelque chose.
Je ne m'en servirai donc pas mais je l'ajoute dans les class en tant qu'attribut.
???????????

Si les dechets ne peuvent pas être recyclés ni incinérés car les capacités sont atteintes, Le programme calcule ce qui reste à traiter et cette quantité repart dans la boucle.

*/
// tableau des services : l'idée serait de verifier si une class existe pour chaque services. Si des nouveaux services sont ajoutés au fichier json, le programme renvoie un message.
include_once('services/arrayServices.php');
//var_dump($arrayServices);

// tableau des dechets : même idée
include_once('waste/arrayWaste.php');
//var_dump($arrayWaste);

// ligne destinée à compter combien on à d'incinérateur, de composteur et de recyclage plastic pour instancier un objet à chaque unité de traitement
// si une unité de compostage est créer, le programme en tiendra compte automatiquement.
$nbServices = array_count_values($arrayServices);
$nbIncinerateur = 0;
$nbComposteur = 0;
$nbRecyclingPlastic = 0;


// verifie si le service est dans le tableau avant d'instancier les services et je recupere sa capacité de data.json
foreach($data['services'] as $value)
{
    // Centre de tri
    if (in_array('centreTri', $arrayServices) && !isset($sortingCenter))
    {
        if($value['type'] === 'centreTri')
        {
            $sortingCenter = new sortingCenterClass($value['type'], $value['capacite']);
            //echo $sortingCenter->getType()." => ".$sortingCenter->getCapacite().PHP_EOL;
        }

    }

    // recyclage papier
    if (in_array('recyclagePapier', $arrayServices) && !isset($recyclingPaper))
    {
        if($value['type'] === 'recyclagePapier')
        {
            $recyclingPaper = new recyclingPaperClass($value['type'], $value['capacite']);
            //echo $recyclingPaper->getType()." => ".$recyclingPaper->getCapacite().PHP_EOL;
        }
    }

    // recyclage metaux
    if (in_array('recyclageMetaux', $arrayServices) && !isset($recyclingMetals))
    {
        if($value['type'] === 'recyclageMetaux')
        {
            $recyclingMetals = new recyclingMetalsClass($value['type'], $value['capacite']);
            //echo $recyclingMetals->getType()." => ".$recyclingMetals->getCapacite().PHP_EOL;
        }
    }

    // recyclage verre
    if (in_array('recyclageVerre', $arrayServices) && !isset($recyclingGlass))
    {
        if($value['type'] === 'recyclageVerre')
        {
            $recyclingGlass = new recyclingGlassClass($value['type'], $value['capacite']);
            //echo "Filiaire de recyclage du verre => ".$recyclingGlass->getCapacite()." Tonnes<br>".PHP_EOL; //$recyclingGlass->getType()
        }
    }

    // incinerateur
    if (in_array('incinerateur', $arrayServices) && $nbIncinerateur <= $nbServices['incinerateur'])
    {
        if($value['type'] === 'incinerateur')
        {
            $nbIncinerateur++;
            $incinerator = new incineratorClass($value['type'], $value['ligneFour'], $value['capaciteLigne']);
            //echo $incinerator->getType()."-".$nbIncinerateur." => ".$incinerator->getCapacite().PHP_EOL;
        }
    }

    // composteur
    if (in_array('composteur', $arrayServices) && $nbComposteur <= $nbServices['composteur'])
    {
        if($value['type'] === 'composteur')
        {
            $nbComposteur++;
            $composter = new composterClass($value['type'], $value['capacite'], $value['foyers']);
            //echo $composter->getType()."-".$nbComposteur."(".$composter->getFoyers().") => ".$composter->getCapacite().PHP_EOL;
        }
    }

    // recyclage plastique
    if (in_array('recyclagePlastique', $arrayServices) && $nbRecyclingPlastic <= $nbServices['recyclagePlastique'])
    {
        if($value['type'] === 'recyclagePlastique')
        {
            $nbRecyclingPlastic++;
            $recyclingPlastic = new recyclingPlasticClass($value['type'], $value['capacite'], $value['plastiques']);
            //echo $recyclingPlastic->getType()."-".$nbRecyclingPlastic."(".implode(', ',$recyclingPlastic->getPlastic()).") => ".$recyclingPlastic->getCapacite().PHP_EOL;
        }
    }
}

// verifie si le dechets est dans le tableau avant d'instancier le dechets et je recupere les infos de data.json
$totalGlass = 0;

foreach($data['quartiers'] as $key => $value)
{
    foreach($value as $waste => $weight)
    {
        // glass
        if (in_array('verre', $arrayWaste) && !isset($glassRecyclage[$key]))
        {
            if($waste === 'verre')
            {
                if(array_key_exists('recyclage', $co2['verre']))
                {
                    $co2ClassRecyclage = $co2['verre']['recyclage'];
                    $glassRecyclage[$key] = new glassRecyclingClass('Verre', $weight, $co2ClassRecyclage, $value['population'], $key);

                    //echo PHP_EOL.$glassRecyclage[$key]->getName()." => ".$glassRecyclage[$key]->getWeight()." tonnes, ".$glassRecyclage[$key]->getCo2()." g/tonne de dechets recyclés (Quartier ".$glassRecyclage[$key]->getDistrict()."->".$glassRecyclage[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le verre recyclé produit X gramme de CO2 par tonnes
                    $glassRecycleCo2 = $glassRecyclage[$key]->getCo2();
                }
                if(array_key_exists('incineration', $co2['verre']))
                {
                    $co2ClassIncineration = $co2['verre']['incineration'];
                    $glassIncineration[$key] = new glassIncinerationClass('Verre', $weight, $co2ClassIncineration, $value['population'], $key);

                    //echo PHP_EOL.$glassIncineration[$key]->getName()." => ".$glassIncineration[$key]->getWeight()." tonnes, ".$glassIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$glassIncineration[$key]->getDistrict()."->".$glassIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le verre incinéré produit X gramme de CO2 par tonnes
                    $glassIncinereCo2 = $glassIncineration[$key]->getCo2();
                }
                
                // poids total de Verre à traiter
                $totalGlass += $weight;
            }
        }
    }
}
// verifier si le centre de tri peut traiter la totalité du verre
echo "Quantite total de verre a traiter : ".$totalGlass." Tonnes<br>".PHP_EOL;

if ($sortingCenter->getCapacite() > $totalGlass)
{
    
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du Verre.".PHP_EOL;

    // c'est bien mais est ce que le centre de recyclage du verre peut traiter la totalité du verre
    if ($recyclingGlass->getCapacite() > $totalGlass)
    {
        echo "la filiaire de recyclage du Verre peut traiter tout le verre.".PHP_EOL;
        // quantité de CO2 rejeté par le recyclage du verre
        echo "Le recyclage du verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass)." grammes de CO2.<br>".PHP_EOL;
    }
    else
    {
        //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
        $resteVerre = $totalGlass - $recyclingGlass->getCapacite();
        echo "Mais la filiaire de traitement du verre d'une capacité de ".$recyclingGlass->getCapacite()." tonnes ne peut pas.<br> ".$resteVerre." tonnes de verre vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;
        
        // quantité de CO2 rejeté par le recyclage du verre
        $glassRecycled = $totalGlass - $resteVerre;
        echo "Le recyclage de ".$glassRecycled." tonnes de verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled)." grammes de CO2.<br>".PHP_EOL;
      
        echo "L'incinération de ".$resteVerre." de verre a produit ".$incinerator->incineratorTreatment($glassIncinereCo2, $resteVerre)." grammes de CO2.<br>".PHP_EOL;
        
    }
}
else{
    $resteVerre = $totalGalss - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de verre. Le reste (".$resteVerre." tonnes) va être incinéré".PHP_EOL;
}

