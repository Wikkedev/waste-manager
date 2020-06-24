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
use App\waste\organicCompostingClass;

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
Tant que la capacité du centre de tri n'est pas atteinte, les dechets vont dans leur tri respectif jusqu'à ce que leur capacité soit atteinte. Ils vont alors à l'incinérateur.
Il y a 3 incinétateurs. Je commence par remplir le premier puis une fois sa capacité atteinte, j'envoie au deuxieme puis au troisieme.
Il y a 9 composteurs. Même logique que pour les incinérateurs.

???????????
Les composteurs ont un attribut "foyers". Aucune information n'est fourni sur sa signification. J'aurais tendance à le considérer comme le nombre de "maison" ayant le droit de déposer leurs dechets organiques dans ce composteur. "Foyers" pourrait être en relation avec "population". il faudrait connaitre le nombre d'habitant par foyer pour pouvoir en faire quelque chose.
Je ne m'en servirai donc pas mais je l'ajoute dans les class en tant qu'attribut.
???????????

Si les dechets ne peuvent pas être recyclés ni incinérés car les capacités sont atteintes, Le programme calcule ce qui reste à traiter et cette quantité repart dans la boucle.

*/
// tableau des services : l'idée serait de verifier si une class existe pour chaque service. Si des nouveaux services sont ajoutés au fichier json, le programme renvoie un message.
include_once('services/arrayServices.php');
//var_dump($arrayServices);

// tableau des dechets : même idée
include_once('waste/arrayWaste.php');
//var_dump($arrayWaste);

// ligne destinée à compter combien on a d'incinérateur, de composteur et de recyclage plastic pour instancier un objet à chaque unité de traitement
// si une unité de compostage est créer, le programme en tiendra compte automatiquement.
$nbServices = array_count_values($arrayServices);

// tableau pour enregistrer le poids des dechets recyclés, compostés ou incinérés ainsi que le CO2 rejeté par quartier
$quartier = array();
/*
$i(array) => recyclage(array)
                plastiques(array)
                    PET(array)    => Weight(int)
                                  => Co2(int)
                    PVC(array)    => Weight(int)
                                  => Co2(int)
                    ......
                                  
                verre(array)  => Weight(int)
                              => Co2(int)
                
                ........
                                        
          => Compostage(array)
                  organic(array)  => Weight(int)
                                  => Co2(int)
                                  
          => Incineration (array)
                plastiques(array)
                    PET(array)    => Weight(int)
                                  => Co2(int)
                    PVC(array)    => Weight(int)
                                  => Co2(int)
                    ......
                                  
                verre(array)  => Weight(int)
                              => Co2(int)
                
                ........
               
*/

if (in_array('incinerateur', $arrayServices))
{
    $nbIncinerateur = 0;
    // tableau pour recuperer les capacité ds 3 incinerateurs
    $arrayIncinerator = array();
  
    // tableau pour recuperer le CO2 rejeté par incineration
    $co2RejeteIncinere = array();
}
if (in_array('recyclagePlastique', $arrayServices))
{
    $nbRecyclingPlastic = 0;
}
if (in_array('composteur', $arrayServices))
{
    // tableau pour recuperer les capacité ds 9 composteurs
    $arrayComposter = array();
  
    // tableau pour recuperer le CO2 rejeté par compostage
    $co2RejeteCompost = array();
}

// tableau pour recuperer le CO2 rejeté par recyclage
$co2RejeteRecycle = array();




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
    if (in_array('incinerateur', $arrayServices))
    {
        if ($nbIncinerateur <= $nbServices['incinerateur'])
        {
            if($value['type'] === 'incinerateur')
            {
                $nbIncinerateur++;
                $incinerator[$nbIncinerateur] = new incineratorClass($nbIncinerateur, $value['ligneFour'], $value['capaciteLigne']);
                array_push($arrayIncinerator, $incinerator[$nbIncinerateur]->getCapacite());
                //echo $incinerator[$nbIncinerateur]->getType()."-".$nbIncinerateur." => ".$incinerator[$nbIncinerateur]->getCapacite().PHP_EOL;
            }
        }
    }

    // composteur
    if (in_array('composteur', $arrayServices))
    {
        $capaciteComposter = 0;
        if($value['type'] === 'composteur')
        {
            $capaciteComposter += $value['capacite'] * $value['foyers'];
            $composter = new composterClass($value['type'], $capaciteComposter, $value['foyers']);
        }
    }

    // recyclage plastique
    if (in_array('recyclagePlastique', $arrayServices) && $nbRecyclingPlastic <= $nbServices['recyclagePlastique'])
    {
        if($value['type'] === 'recyclagePlastique')
        {
            $nbRecyclingPlastic++;
            $recyclingPlastic[$nbRecyclingPlastic] = new recyclingPlasticClass($value['type'], $value['capacite'], $value['plastiques']);
            //echo $recyclingPlastic[$nbRecyclingPlastic]->getType()."-".$nbRecyclingPlastic."(".implode(', ',$recyclingPlastic[$nbRecyclingPlastic]->getPlastic()).") => ".$recyclingPlastic[$nbRecyclingPlastic]->getCapacite().PHP_EOL;
        }
    }
}

// verifie si le dechets est dans le tableau avant d'instancier le dechets et je recupere les infos de data.json
if (in_array('verre', $arrayWaste)) {$totalGlass = 0;}
if (in_array('metaux', $arrayWaste)) {$totalMetals = 0;}
if (in_array('papier', $arrayWaste)) {$totalPaper = 0;}
if (in_array('autre', $arrayWaste)) {$totalOther = 0;}
if (in_array('organique', $arrayWaste)) {$totalOrganic = 0;}

foreach($data['quartiers'] as $key => $value)
{
    
    foreach($value as $waste => $weight)
    {
        // glass
        if (in_array('verre', $arrayWaste) && !isset($glassIncineration[$key]))
        {
            if($waste === 'verre')
            {
                if(array_key_exists('recyclage', $co2['verre']))
                {
                    $co2Recycling = $co2['verre']['recyclage'];
                    $glassRecycling[$key] = new glassRecyclingClass('Verre', $weight, $co2Recycling, $value['population'], $key);

                    //echo PHP_EOL.$glassRecycling[$key]->getName()." => ".$glassRecycling[$key]->getWeight()." tonnes, ".$glassRecycling[$key]->getCo2()." g/tonne de dechets recyclés (Quartier ".$glassRecycling[$key]->getDistrict()."->".$glassRecycling[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le verre recyclé produit X gramme de CO2 par tonnes
                    $glassRecycleCo2 = $glassRecycling[$key]->getCo2();
                  
                    
                }
                if(array_key_exists('incineration', $co2['verre']))
                {
                    $co2Incineration = $co2['verre']['incineration'];
                    $glassIncineration[$key] = new glassIncinerationClass('Verre', $weight, $co2Incineration, $value['population'], $key);

                    //echo PHP_EOL.$glassIncineration[$key]->getName()." => ".$glassIncineration[$key]->getWeight()." tonnes, ".$glassIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$glassIncineration[$key]->getDistrict()."->".$glassIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le verre incinéré produit X gramme de CO2 par tonnes
                    $glassIncinereCo2 = $glassIncineration[$key]->getCo2();
                }
                
                // poids total de Verre à traiter
                $totalGlass += $weight;
            }
        }
      
        //Metals
        if (in_array('metaux', $arrayWaste) && !isset($metalsIncineration[$key]))
        {
            if($waste === 'metaux')
            {
                if(array_key_exists('recyclage', $co2['metaux']))
                {
                    $co2Recycling = $co2['metaux']['recyclage'];
                    $metalsRecycling[$key] = new metalsRecyclingClass('Métaux', $weight, $co2Recycling, $value['population'], $key);

                    //echo PHP_EOL.$metalsRecycling[$key]->getName()." => ".$metalsRecycling[$key]->getWeight()." tonnes, ".$metalsRecycling[$key]->getCo2()." g/tonne de dechets recyclés (Quartier ".$metalsRecycling[$key]->getDistrict()."->".$metalsRecycling[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le metaux recyclé produit X gramme de CO2 par tonnes
                    $metalsRecycleCo2 = $metalsRecycling[$key]->getCo2();
                }
                if(array_key_exists('incineration', $co2['metaux']))
                {
                    $co2Incineration = $co2['metaux']['incineration'];
                    $metalsIncineration[$key] = new metalsIncinerationClass('Métaux', $weight, $co2Incineration, $value['population'], $key);

                    //echo PHP_EOL.$metalsIncineration[$key]->getName()." => ".$metalsIncineration[$key]->getWeight()." tonnes, ".$metalsIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$metalsIncineration[$key]->getDistrict()."->".$metalsIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le metaux incinéré produit X gramme de CO2 par tonnes
                    $metalsIncinereCo2 = $metalsIncineration[$key]->getCo2();
                }
                
                // poids total de metaux à traiter
                $totalMetals += $weight;
            }
        }
      
        //Paper
        if (in_array('papier', $arrayWaste) && !isset($paperIncineration[$key]))
        {
            if($waste === 'papier')
            {
                if(array_key_exists('recyclage', $co2['papier']))
                {
                    $co2Recycling = $co2['papier']['recyclage'];
                    $paperRecycling[$key] = new paperRecyclingClass('Papier', $weight, $co2Recycling, $value['population'], $key);

                    //echo PHP_EOL.$paperRecycling[$key]->getName()." => ".$paperRecycling[$key]->getWeight()." tonnes, ".$paperRecycling[$key]->getCo2()." g/tonne de dechets recyclés (Quartier ".$paperRecycling[$key]->getDistrict()."->".$paperRecycling[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le papier recyclé produit X gramme de CO2 par tonnes
                    $paperRecycleCo2 = $paperRecycling[$key]->getCo2();
                }
                if(array_key_exists('incineration', $co2['papier']))
                {
                    $co2Incineration = $co2['papier']['incineration'];
                    $paperIncineration[$key] = new paperIncinerationClass('Papier', $weight, $co2Incineration, $value['population'], $key);

                    //echo PHP_EOL.$paperIncineration[$key]->getName()." => ".$paperIncineration[$key]->getWeight()." tonnes, ".$paperIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$paperIncineration[$key]->getDistrict()."->".$paperIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le papier incinéré produit X gramme de CO2 par tonnes
                    $paperIncinereCo2 = $paperIncineration[$key]->getCo2();
                }
                
                // poids total de papier à traiter
                $totalPaper += $weight;
            }
        }
      
        //autre
        if (in_array('autre', $arrayWaste) && !isset($otherIncineration[$key]))
        {
            if($waste === 'autre')
            {
                if(array_key_exists('incineration', $co2['autre']))
                {
                    $co2Incineration = $co2['autre']['incineration'];
                    $otherIncineration[$key] = new otherIncinerationClass('Déchets divers (autre)', $weight, $co2Incineration, $value['population'], $key);

                    //echo PHP_EOL.$otherIncineration[$key]->getName()." => ".$otherIncineration[$key]->getWeight()." tonnes, ".$otherIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$otherIncineration[$key]->getDistrict()."->".$otherIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le papier incinéré produit X gramme de CO2 par tonnes
                    $otherIncinereCo2 = $otherIncineration[$key]->getCo2();
                }
                
                // poids total de papier à traiter
                $totalOther += $weight;
            }
        }
      
        // organic
        if (in_array('organique', $arrayWaste) && !isset($organicComposting[$key]))
        {
            if($waste === 'organique')
            {
                if(array_key_exists('compostage', $co2['organique']))
                {
                    // dechet organique compostés
                    $co2Composting = $co2['organique']['compostage'];
                    $organicComposting[$key] = new organicCompostingClass('Dechets organiques', $weight, $co2Composting, $value['population'], $key);

                    //echo PHP_EOL.$organicComposting[$key]->getName()." => ".$organicComposting[$key]->getWeight()." tonnes, ".$organicComposting[$key]->getCo2()." g/tonne de dechets recyclés (Quartier ".$organicComposting[$key]->getDistrict()."->".$organicComposting[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le dechet organique recyclé produit X gramme de CO2 par tonnes
                    $organicCompostCo2 = $organicComposting[$key]->getCo2();
                    
                }
              
                if(array_key_exists('incineration', $co2['organique']))
                {
                    $co2Incineration = $co2['organique']['incineration'];
                    $organicIncineration[$key] = new organicIncinerationClass('Dechets organiques', $weight, $co2Incineration, $value['population'], $key);

                    //echo PHP_EOL.$organicIncineration[$key]->getName()." => ".$organicIncineration[$key]->getWeight()." tonnes, ".$organicIncineration[$key]->getCo2()." g/tonne de dechets incinérés (Quartier ".$organicIncineration[$key]->getDistrict()."->".$organicIncineration[$key]->getpopulation()." habitants)".PHP_EOL;
                  
                    // le verre incinéré produit X gramme de CO2 par tonnes
                    $organicIncinereCo2 = $organicIncineration[$key]->getCo2();
                }
                
                // poids total de Verre à traiter
                $totalOrganic += $weight;
            }
        }
    }
}

//Ajout des fichier necessaire et alerte en cas de nouveaux dechets
foreach($arrayWaste as $value)
{
    if (!file_exists('modele/'.$value.'.php'))
    {
        echo $value." -- est un nouveau type de déchet. Il faut l'ajouter au programme de traitement. Contactez votre CDA préféré.<br>".PHP_EOL;
    }
    else
    {
        include('modele/'.$value.'.php');
    }
}


