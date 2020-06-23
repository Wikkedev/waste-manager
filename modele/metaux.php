<?php
// metaux
// verifier si le centre de tri peut traiter la totalité des metaux
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de metaux a traiter : ".$totalMetals." Tonnes<br>".PHP_EOL;

$co2MetalsTotal = 0;
// le centre de tri peut tout traiter ...
if ($sortingCenter->getCapacite() >= $totalMetals)
{
    $resteMetals = 0;
    $metalsRecycled = $totalMetals;
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité des metaux.<br>".PHP_EOL;

    // c'est bien mais est ce que le centre de recyclage des metaux peut traiter la totalité des metaux ?
    if ($recyclingMetals->getCapacite() > $totalMetals)
    {
        echo "la filiaire de recyclage des metaux peut traiter tout les metaux.<br>".PHP_EOL;
        // quantité de CO2 rejeté par le recyclage des metaux
        echo "Le recyclage des metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals));
        $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals);
    }
    else
    {
        //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
        $resteMetals = $totalMetals - $recyclingMetals->getCapacite();
        echo "Mais la filiaire de traitement des metaux d'une capacité de ".$recyclingMetals->getCapacite()." tonnes ne peut pas.<br> ".$resteMetals." tonnes de metaux vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;
        
        // quantité de CO2 rejeté par le recyclage des metaux
        $metalsRecycled = $totalMetals - $resteMetals;
        echo "Le recyclage de ".$metalsRecycled." tonnes de metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled));
        $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled);
      
        // quantité de CO2 rejeter par l'incineration des metaux
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $resteMetals)
            {
                echo "L'incinération de ".$resteMetals." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals));
                $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals);
                break;
            }
            else{
                $resteMetals1 = $resteMetals - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $resteMetals1)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite()));
                    $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite());
                  
                    // 2eme incinerateur
                    echo "L'incinération de ".$resteMetals1." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1));
                    $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1);
                    break;
                }
                else
                {
                    $resteMetals2 = $resteMetals - $resteMetals1;
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();
                  
                    if($capacite > $resteMetals3)
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+2]->getCapacite()));
                        $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+2]->getCapacite());
                      
                        // 3eme incinerateur
                        echo "L'incinération de ".$resteMetals2." tonnes de metaux dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2));
                        $co2MetalsTotal += $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2);
                        break;
                    }
                    else
                    {
                        $resteMetals3 = $resteMetals - $resteMetals2;
                        echo "La capacité des incinérateurs a été dépassée. ".$resteMetals3." tonnes de metaux n'ont pas été traitées.<br>".PHP_EOL;
                    }
                }
            }
        }
    }
}

// le centre de tri ne peut pas tout traiter ....
else{
    $resteMetals = $totalMetals - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de metaux. Le reste (".$resteMetals." tonnes) va être incinéré.<br>".PHP_EOL;
  
    // quantité de CO2 rejeté par le recyclage des metaux
    $metalsRecycled = $totalMetals - $resteMetals;
    echo "Le recyclage de ".$metalsRecycled." tonnes de metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled)." grammes de CO2.<br>".PHP_EOL;
    //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled));
    $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled);
  
    // quantité de CO2 rejeter par l'incineration des metaux
    // il y a plusieurs incinerateurs
    foreach ($arrayIncinerator as $i => $v)
    {
        // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
        if ($v >= $resteMetals)
        {
            echo "L'incinération de ".$resteMetals." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals));
            $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals);
            break;
        }
        else{
            $resteMetals1 = $resteMetals - $incinerator[$i+1]->getCapacite();
            $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
            if($capacite > $resteMetals1)
            {
                // 1er incinerateur
                echo "L'incinération de ".$resteMetals." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals));
                $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals);

                // 2eme incinerateur
                echo "L'incinération de ".$resteMetals1." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1));
                $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1);
                break;
            }
            else
            {
                $resteMetals2 = $resteMetals - $resteMetals1;
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                if($capacite > $resteMetals3)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$resteMetals." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals));
                    $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals);

                    // 2eme incinerateur
                    echo "L'incinération de ".$resteMetals1." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1));
                    $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1);

                    // 3eme incinerateur
                    echo "L'incinération de ".$resteMetals2." tonnes de metaux dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2));
                    $co2MetalsTotal += $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2);
                    break;
                }
                else
                {
                    $resteMetals3 = $resteMetals - $resteMetals2;
                    echo "La capacité des incinérateurs a été dépassée. ".$resteMetals3." tonnes de metaux n'ont pas été traitées.<br>".PHP_EOL;
                }
            }
        }
    }
}
array_push($co2RejeteIncinere, $co2MetalsTotal);
echo "Le traitement de ".$totalMetals." tonnes de metaux a produit ".$co2MetalsTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
echo "_________________________________________________________________________________________________<br>".PHP_EOL;