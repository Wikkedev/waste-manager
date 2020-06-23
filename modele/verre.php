<?php
// verre
// verifier si le centre de tri peut traiter la totalité du verre
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de verre a traiter : ".$totalGlass." Tonnes<br>".PHP_EOL;

$co2GlassTotal = 0;
// le centre de tri peut tout traiter ...
if ($sortingCenter->getCapacite() >= $totalGlass)
{
    $resteGlass = 0;
    $glassRecycled = $totalGlass;
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du verre.<br>".PHP_EOL;

    // c'est bien mais est ce que le centre de recyclage du verre peut traiter la totalité du verre ?
    if ($recyclingGlass->getCapacite() > $totalGlass)
    {
        echo "la filiaire de recyclage du verre peut traiter tout le verre.".PHP_EOL;
        // quantité de CO2 rejeté par le recyclage du verre
        echo "Le recyclage du verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass));
        $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass);
    }
    else
    {
        //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
        $resteGlass = $totalGlass - $recyclingGlass->getCapacite();
        echo "Mais la filiaire de traitement du verre d'une capacité de ".$recyclingGlass->getCapacite()." tonnes ne peut pas.<br> ".$resteGlass." tonnes de verre vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;
        
        // quantité de CO2 rejeté par le recyclage du verre
        $glassRecycled = $totalGlass - $resteGlass;
        echo "Le recyclage de ".$glassRecycled." tonnes de verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled));
        $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled);
      
        // quantité de CO2 rejeter par l'incineration du verre
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $resteGlass)
            {
                echo "L'incinération de ".$resteGlass." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass));
                $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass);
                break;
            }
            else{
                $resteGlass1 = $resteGlass - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $resteGlass1)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite()));
                    $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite());
                  
                    // 2eme incinerateur
                    echo "L'incinération de ".$resteGlass1." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1));
                    $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1);
                    break;
                }
                else
                {
                    $resteGlass2 = $resteGlass - $resteGlass1;
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();
                  
                    if($capacite > $resteGlass2)
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+2]->getCapacite()));
                        $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+2]->getCapacite());
                      
                        // 3eme incinerateur
                        echo "L'incinération de ".$resteGlass2." tonnes de verre dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2));
                        $co2GlassTotal += $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2);
                        break;
                    }
                    else
                    {
                        $resteGlass3 = $resteGlass - $resteGlass2;
                        echo "La capacité des incinérateurs a été dépassée. ".$resteGlass3." tonnes de verre n'ont pas été traitées.<br>".PHP_EOL;
                    }
                }
            }
        }
    }
}

// le centre de tri ne peut pas tout traiter ....
else{
    $resteGlass = $totalGlass - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de verre. Le reste (".$resteGlass." tonnes) va être incinéré.<br>".PHP_EOL;
  
    // quantité de CO2 rejeté par le recyclage du verre
    $glassRecycled = $totalGlass - $resteGlass;
    echo "Le recyclage de ".$glassRecycled." tonnes de verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled)." grammes de CO2.<br>".PHP_EOL;
    //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled));
    $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled);
  
    // quantité de CO2 rejeter par l'incineration du verre
    // il y a plusieurs incinerateurs
    foreach ($arrayIncinerator as $i => $v)
    {
        // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
        if ($v >= $resteGlass)
        {
            echo "L'incinération de ".$resteGlass." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass));
            $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass);
            break;
        }
        else{
            $resteGlass1 = $resteGlass - $incinerator[$i+1]->getCapacite();
            $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
            if($capacite > $resteGlass1)
            {
                // 1er incinerateur
                echo "L'incinération de ".$resteGlass." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass));
                $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass);

                // 2eme incinerateur
                echo "L'incinération de ".$resteGlass1." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1));
                $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1);
                break;
            }
            else
            {
                $resteGlass2 = $resteGlass - $resteGlass1;
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                if($capacite > $resteGlass3)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$resteGlass." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass));
                    $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass);

                    // 2eme incinerateur
                    echo "L'incinération de ".$resteGlass1." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1));
                    $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1);

                    // 3eme incinerateur
                    echo "L'incinération de ".$resteGlass2." tonnes de verre dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2));
                    $co2GlassTotal += $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2);
                    break;
                }
                else
                {
                    $resteGlass3 = $resteGlass - $resteGlass2;
                    echo "La capacité des incinérateurs a été dépassée. ".$resteGlass3." tonnes de verre n'ont pas été traitées.<br>".PHP_EOL;
                }
            }
        }
    }
}
array_push($co2RejeteIncinere, $co2GlassTotal);
echo "Le traitement de ".$totalGlass." tonnes de verre a produit ".$co2GlassTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
echo "_________________________________________________________________________________________________<br>".PHP_EOL;