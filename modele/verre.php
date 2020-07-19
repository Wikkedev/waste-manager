<?php
// verre
// verifier si le centre de tri peut traiter la totalité du verre
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de verre a traiter : ".array_sum($totalGlass)." Tonnes<br>".PHP_EOL;
if ($sortingCenter->getCapacite() >= array_sum($totalGlass))
{
    $resteGlass = 0;
    $glassRecycled = array_sum($totalGlass);
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du verre.<br>".PHP_EOL;
}
else
{
    $resteGlass = array_sum($totalGlass) - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de verre. Le reste (".$resteGlass." tonnes) va être incinéré.<br>".PHP_EOL;
}

foreach($totalGlass as $kg => $vg)
{
    echo '<b>Quartier '.$kg.' : '.$population[$kg].' habitants</b><br>'.PHP_EOL;
    $co2GlassTotal = 0;
    // le centre de tri peut tout traiter ...
    if ($sortingCenter->getCapacite() >= $totalGlass[$kg])
    {
        $resteGlass = array();
        $glassRecycled = $totalGlass[$kg];
        
        // c'est bien mais est ce que le centre de recyclage du verre peut traiter la totalité du verre ?
        if ($recyclingGlass->getCapacite() > $totalGlass[$kg])
        {
            echo "la filiaire de recyclage du verre peut traiter tout le verre.<br>".PHP_EOL;
            // quantité de CO2 rejeté par le recyclage du verre
            echo "Le recyclage du verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass[$kg])." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass[$kg]));
            $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $totalGlass[$kg]);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['verre'] = $totalGlass[$kg].'|'.$co2GlassTotal;
            // print_r($quartier[$kg]);
        }
        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $resteGlass[$kg] = $totalGlass[$kg] - $recyclingGlass->getCapacite();
            echo "Mais la filiaire de traitement du verre d'une capacité de ".$recyclingGlass->getCapacite()." tonnes ne peut pas.<br> ".$resteGlass[$kg]." tonnes de verre vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le recyclage du verre
            $glassRecycled = $totalGlass[$kg] - $resteGlass[$kg];
            echo "Le recyclage de ".$glassRecycled." tonnes de verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled));
            $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled);

            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['verre'] = $totalGlass[$kg].'|'.$co2GlassTotal;
          
          
            // quantité de CO2 rejeter par l'incineration du verre
            // il y a plusieurs incinerateurs
            foreach ($arrayIncinerator as $i => $v)
            {
                // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
                if ($v >= $resteGlass[$kg])
                {
                    echo "L'incinération de ".$resteGlass[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]));
                    $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 1']['verre'] = $resteGlass[$kg].'|'.$co2GlassTotal;
                    break;
                }
                else{
                    $resteGlass1[$kg] = $resteGlass[$kg] - $incinerator[$i+1]->getCapacite();
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                    if($capacite > $resteGlass1[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteGlass1[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]));
                        $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 2']['verre'] = $resteGlass1[$kg].'|'.$co2GlassTotal;
                        break;
                    }
                    else
                    {
                        $resteGlass2[$kg] = $resteGlass[$kg] - $resteGlass1[$kg];
                        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                        if($capacite > $resteGlass2[$kg])
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
                            echo "L'incinération de ".$resteGlass2[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg])." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg]));
                            $co2GlassTotal += $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg]);
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['incinerateur 3']['verre'] = $resteGlass2[$kg].'|'.$co2GlassTotal;
                            break;
                        }
                        else
                        {
                            $resteGlass3[$kg] = $resteGlass[$kg] - $resteGlass2[$kg];
                            echo "La capacité des incinérateurs a été dépassée. ".$resteGlass3[$kg]." tonnes de verre n'ont pas été traitées.<br>".PHP_EOL;
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['non traité']['verre'] = $resteGlass3[$kg].'|0';
                        }
                    }
                }
            }
        }
    }

    // le centre de tri ne peut pas tout traiter ....
    else
    {
        $resteGlass = array();
        $glassRecycled = $totalGlass[$kg];
        $resteGlass[$kg] = $totalGlass[$kg] - $sortingCenter->getCapacite();

        // quantité de CO2 rejeté par le recyclage du verre
        $glassRecycled = $totalGlass[$kg] - $resteGlass[$kg];
        echo "Le recyclage de ".$glassRecycled." tonnes de verre a produit ".$recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled));
        $co2GlassTotal += $recyclingGlass->recyclingTreatment($glassRecycleCo2, $glassRecycled);
        
        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
        $quartier[$kg]['recyclage']['verre'][$resteGlass[$kg]] = $co2GlassTotal;
       
        // quantité de CO2 rejeter par l'incineration du verre
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $resteGlass[$kg])
            {
                echo "L'incinération de ".$resteGlass[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg])." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]));
                $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]);
              
                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 1']['verre'] = $resteGlass[$kg].'|'.$co2GlassTotal;
                break;
            }
            else{
                $resteGlass1[$kg] = $resteGlass[$kg] - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $resteGlass1[$kg])
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$resteGlass[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]));
                    $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]);

                    // 2eme incinerateur
                    echo "L'incinération de ".$resteGlass1[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]));
                    $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 2']['verre'] = $resteGlass1[$kg].'|'.$co2GlassTotal;
                    break;
                }
                else
                {
                    $resteGlass2[$kg] = $resteGlass[$kg] - $resteGlass1[$kg];
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                    if($capacite > $resteGlass3[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$resteGlass[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]));
                        $co2GlassTotal += $incinerator[$i+1]->incineratorTreatment($glassIncinereCo2, $resteGlass[$kg]);

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteGlass1[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]));
                        $co2GlassTotal += $incinerator[$i+2]->incineratorTreatment($glassIncinereCo2, $resteGlass1[$kg]);

                        // 3eme incinerateur
                        echo "L'incinération de ".$resteGlass2[$kg]." tonnes de verre dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg]));
                        $co2GlassTotal += $incinerator[$i+3]->incineratorTreatment($glassIncinereCo2, $resteGlass2[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 3']['verre'] = $resteGlass2[$kg].'|'.$co2GlassTotal;
                      
                        break;
                    }
                    else
                    {
                        $resteGlass3[$kg] = $resteGlass[$kg] - $resteGlass2[$kg];
                        echo "La capacité des incinérateurs a été dépassée. ".$resteGlass3[$kg]." tonnes de verre n'ont pas été traitées.<br>".PHP_EOL;
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['non traité']['verre'] = $resteGlass3[$kg].'|0';
                    }
                }
            }
        }
    }
    array_push($co2RejeteIncinere, $co2GlassTotal);
    echo "Le traitement de ".$totalGlass[$kg]." tonnes de verre a produit ".$co2GlassTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
    echo "_________________________________________________________________________________________________<br>".PHP_EOL;
}
