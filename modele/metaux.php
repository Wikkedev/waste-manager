<?php
// metaux

// verifier si le centre de tri peut traiter la totalité des metaux
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de metaux a traiter : ".array_sum($totalMetals)." Tonnes<br>".PHP_EOL;
if ($sortingCenter->getCapacite() >= array_sum($totalMetals))
{
    $resteMetals = 0;
    $metalsRecycled = array_sum($totalMetals);
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du metal.<br>".PHP_EOL;
}
else
{
    $resteMetals = array_sum($totalMetals) - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de metaux. Le reste (".$resteMetals." tonnes) va être incinéré.<br>".PHP_EOL;
}

foreach($totalMetals as $kg => $vg)
{
    echo '<b>Quartier '.$kg.' : '.$population[$kg].' habitants</b><br>'.PHP_EOL;
    $co2MetalsTotal = 0;
    // le centre de tri peut tout traiter ...
    if ($sortingCenter->getCapacite() >= $totalMetals[$kg])
    {
        $resteMetals = array();
        $metalsRecycled = $totalMetals[$kg];
        
        // c'est bien mais est ce que le centre de recyclage du metaux peut traiter la totalité du metaux ?
        if ($recyclingMetals->getCapacite() > $totalMetals[$kg])
        {
            echo "la filiaire de recyclage des metaux peut traiter tout les metaux.<br>".PHP_EOL;
            // quantité de CO2 rejeté par le recyclage du metaux
            echo "Le recyclage des metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals[$kg])." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals[$kg]));
            $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $totalMetals[$kg]);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['metaux'] = $totalMetals[$kg].'|'.$co2MetalsTotal;
            // print_r($quartier[$kg]);
        }
        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $resteMetals[$kg] = $totalMetals[$kg] - $recyclingMetals->getCapacite();
            echo "Mais la filiaire de traitement des metaux d'une capacité de ".$recyclingMetals->getCapacite()." tonnes ne peut pas.<br> ".$resteMetals[$kg]." tonnes de metaux vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le recyclage du metaux
            $metalsRecycled = $totalMetals[$kg] - $resteMetals[$kg];
            echo "Le recyclage de ".$metalsRecycled." tonnes de metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled));
            $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled);

            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['metaux'] = $totalMetals[$kg].'|'.$co2MetalsTotal;
          
          
            // quantité de CO2 rejeter par l'incineration du metaux
            // il y a plusieurs incinerateurs
            foreach ($arrayIncinerator as $i => $v)
            {
                // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
                if ($v >= $resteMetals[$kg])
                {
                    echo "L'incinération de ".$resteMetals[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg])." grammes de CO2.<br>".PHP_EOL;

                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]));
                    $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 1']['metaux'] = $resteMetals[$kg].'|'.$co2MetalsTotal;
                    break;
                }
                else{
                    $resteMetals1[$kg] = $resteMetals[$kg] - $incinerator[$i+1]->getCapacite();
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                    if($capacite > $resteMetals1[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteMetals1[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]));
                        $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 2']['metaux'] = $resteMetals1[$kg].'|'.$co2MetalsTotal;
                        break;
                    }
                    else
                    {
                        $resteMetals2[$kg] = $resteMetals[$kg] - $resteMetals1[$kg];
                        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                        if($capacite > $resteMetals2[$kg])
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
                            echo "L'incinération de ".$resteMetals2[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg])." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg]));
                            $co2MetalsTotal += $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg]);
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['incinerateur 3']['metaux'] = $resteMetals2[$kg].'|'.$co2MetalsTotal;
                            break;
                        }
                        else
                        {
                            $resteMetals3[$kg] = $resteMetals[$kg] - $resteMetals2[$kg];
                            echo "La capacité des incinérateurs a été dépassée. ".$resteMetals3[$kg]." tonnes de metaux n'ont pas été traitées.<br>".PHP_EOL;
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['non traité']['metaux'] = $resteMetals3[$kg].'|0';
                        }
                    }
                }
            }
        }
    }

    // le centre de tri ne peut pas tout traiter ....
    else
    {
        $resteMetals = array();
        $metalsRecycled = $totalMetals[$kg];
      
        $resteMetals[$kg] = $totalMetals[$kg] - $sortingCenter->getCapacite();

        // quantité de CO2 rejeté par le recyclage des metaux
        $metalsRecycled = $totalMetals[$kg] - $resteMetals[$kg];
        echo "Le recyclage de ".$metalsRecycled." tonnes de metaux a produit ".$recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled));
        $co2MetalsTotal += $recyclingMetals->recyclingTreatment($metalsRecycleCo2, $metalsRecycled);
        
        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
        $quartier[$kg]['recyclage']['metaux'] = $resteMetals[$kg].'|'.$co2MetalsTotal;
       
        // quantité de CO2 rejeter par l'incineration du metaux
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $resteMetals[$kg])
            {
                echo "L'incinération de ".$resteMetals[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg])." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]));
                $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]);
              
                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 1']['metaux'] = $resteMetals[$kg].'|'.$co2MetalsTotal;
                break;
            }
            else{
                $resteMetals1[$kg] = $resteMetals[$kg] - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $resteMetals1[$kg])
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$resteMetals[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]));
                    $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]);

                    // 2eme incinerateur
                    echo "L'incinération de ".$resteMetals1[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]));
                    $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 2']['metaux'] = $resteMetals1[$kg].'|'.$co2MetalsTotal;
                    break;
                }
                else
                {
                    $resteMetals2[$kg] = $resteMetals[$kg] - $resteMetals1[$kg];
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                    if($capacite > $resteMetals3[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$resteMetals[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]));
                        $co2MetalsTotal += $incinerator[$i+1]->incineratorTreatment($metalsIncinereCo2, $resteMetals[$kg]);

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteMetals1[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]));
                        $co2MetalsTotal += $incinerator[$i+2]->incineratorTreatment($metalsIncinereCo2, $resteMetals1[$kg]);

                        // 3eme incinerateur
                        echo "L'incinération de ".$resteMetals2[$kg]." tonnes de metaux dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg]));
                        $co2MetalsTotal += $incinerator[$i+3]->incineratorTreatment($metalsIncinereCo2, $resteMetals2[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 3']['metaux'] = $resteMetals2[$kg].'|'.$co2MetalsTotal;
                      
                        break;
                    }
                    else
                    {
                        $resteMetals3[$kg] = $resteMetals[$kg] - $resteMetals2[$kg];
                        echo "La capacité des incinérateurs a été dépassée. ".$resteMetals3[$kg]." tonnes de metaux n'ont pas été traitées.<br>".PHP_EOL;
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['non traité']['metaux'] = $resteMetals3[$kg].'|0';
                    }
                }
            }
        }
    }
    array_push($co2RejeteIncinere, $co2MetalsTotal);
    echo "Le traitement de ".$totalMetals[$kg]." tonnes de metaux a produit ".$co2MetalsTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
    echo "_________________________________________________________________________________________________<br>".PHP_EOL;
}


