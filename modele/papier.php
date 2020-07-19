<?php
// papier
// verifier si le centre de tri peut traiter la totalité du papier

echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de papier a traiter : ".array_sum($totalPaper)." Tonnes<br>".PHP_EOL;
if ($sortingCenter->getCapacite() >= array_sum($totalPaper))
{
    $restePaper = 0;
    $paperRecycled = array_sum($totalPaper);
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du papier.<br>".PHP_EOL;
}
else
{
    $restePaper = array_sum($totalPaper) - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de papier. Le reste (".$restePaper." tonnes) va être incinéré.<br>".PHP_EOL;
}

foreach($totalPaper as $kg => $vg)
{
    echo '<b>Quartier '.$kg.' : '.$population[$kg].' habitants</b><br>'.PHP_EOL;
    $co2PaperTotal = 0;
    // le centre de tri peut tout traiter ...
    if ($sortingCenter->getCapacite() >= $totalPaper[$kg])
    {
        $restePaper = array();
        $paperRecycled = $totalPaper[$kg];
        
        // c'est bien mais est ce que le centre de recyclage du papier peut traiter la totalité du papier ?
        if ($recyclingPaper->getCapacite() > $totalPaper[$kg])
        {
            echo "la filiaire de recyclage du papier peut traiter tout le papier.<br>".PHP_EOL;
            // quantité de CO2 rejeté par le recyclage du papier
            echo "Le recyclage du papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper[$kg])." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper[$kg]));
            $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper[$kg]);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['papier'] = $totalPaper[$kg].'|'.$co2PaperTotal;
            // print_r($quartier[$kg]);
        }
        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $restePaper[$kg] = $totalPaper[$kg] - $recyclingPaper->getCapacite();
            echo "Mais la filiaire de traitement du papier d'une capacité de ".$recyclingPaper->getCapacite()." tonnes ne peut pas.<br> ".$restePaper[$kg]." tonnes de papier vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le recyclage du papier
            $paperRecycled = $totalPaper[$kg] - $restePaper[$kg];
            echo "Le recyclage de ".$paperRecycled." tonnes de papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled));
            $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled);

            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['recyclage']['papier'] = $totalPaper[$kg].'|'.$co2PaperTotal;
          
          
            // quantité de CO2 rejeter par l'incineration du papier
            // il y a plusieurs incinerateurs
            foreach ($arrayIncinerator as $i => $v)
            {
                // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
                if ($v >= $restePaper[$kg])
                {
                    echo "L'incinération de ".$restePaper[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg])." grammes de CO2.<br>".PHP_EOL;

                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]));
                    $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 1']['papier'] = $restePaper[$kg].'|'.$co2PaperTotal;
                    break;
                }
                else{
                    $restePaper1[$kg] = $restePaper[$kg] - $incinerator[$i+1]->getCapacite();
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                    if($capacite > $restePaper1[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$restePaper1[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]));
                        $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 2']['papier'] = $restePaper1[$kg].'|'.$co2PaperTotal;
                        break;
                    }
                    else
                    {
                        $restePaper2[$kg] = $restePaper[$kg] - $restePaper1[$kg];
                        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                        if($capacite > $restePaper2[$kg])
                        {
                            // 1er incinerateur
                            echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                            $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());

                            // 2eme incinerateur
                            echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite()));
                            $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite());

                            // 3eme incinerateur
                            echo "L'incinération de ".$restePaper2[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg])." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg]));
                            $co2PaperTotal += $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg]);
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['incinerateur 3']['papier'] = $restePaper2[$kg].'|'.$co2PaperTotal;
                            break;
                        }
                        else
                        {
                            $restePaper3[$kg] = $restePaper[$kg] - $restePaper2[$kg];
                            echo "La capacité des incinérateurs a été dépassée. ".$restePaper3[$kg]." tonnes de papier n'ont pas été traitées.<br>".PHP_EOL;
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['non traité']['papier'] = $restePaper3[$kg].'|0';
                        }
                    }
                }
            }
        }
    }

    // le centre de tri ne peut pas tout traiter ....
    else
    {
        $restePaper = array();
        $paperRecycled = $totalPaper[$kg];
        $restePaper[$kg] = $totalPaper[$kg] - $sortingCenter->getCapacite();

        // quantité de CO2 rejeté par le recyclage du papier
        $paperRecycled = $totalPaper[$kg] - $restePaper[$kg];
        echo "Le recyclage de ".$paperRecycled." tonnes de papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled));
        $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled);
        
        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
        $quartier[$kg]['recyclage']['papier'] = $restePaper[$kg].'|'.$co2PaperTotal;
       
        // quantité de CO2 rejeter par l'incineration du papier
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $restePaper[$kg])
            {
                echo "L'incinération de ".$restePaper[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg])." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]));
                $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]);
              
                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 1']['papier'] = $restePaper[$kg].'|'.$co2PaperTotal;
                break;
            }
            else{
                $restePaper1[$kg] = $restePaper[$kg] - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $restePaper1[$kg])
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$restePaper[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]));
                    $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]);

                    // 2eme incinerateur
                    echo "L'incinération de ".$restePaper1[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg])." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]));
                    $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 2']['papier'] = $restePaper1[$kg].'|'.$co2PaperTotal;
                    break;
                }
                else
                {
                    $restePaper2[$kg] = $restePaper[$kg] - $restePaper1[$kg];
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                    if($capacite > $restePaper3[$kg])
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$restePaper[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]));
                        $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper[$kg]);

                        // 2eme incinerateur
                        echo "L'incinération de ".$restePaper1[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]));
                        $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1[$kg]);

                        // 3eme incinerateur
                        echo "L'incinération de ".$restePaper2[$kg]." tonnes de papier dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg])." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg]));
                        $co2PaperTotal += $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2[$kg]);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 3']['papier'] = $restePaper2[$kg].'|'.$co2PaperTotal;
                      
                        break;
                    }
                    else
                    {
                        $restePaper3[$kg] = $restePaper[$kg] - $restePaper2[$kg];
                        echo "La capacité des incinérateurs a été dépassée. ".$restePaper3[$kg]." tonnes de papier n'ont pas été traitées.<br>".PHP_EOL;
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['non traité']['papier'] = $restePaper3[$kg].'|0';
                    }
                }
            }
        }
    }
    array_push($co2RejeteIncinere, $co2PaperTotal);
    echo "Le traitement de ".$totalPaper[$kg]." tonnes de papier a produit ".$co2PaperTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
    echo "_________________________________________________________________________________________________<br>".PHP_EOL;
}

/*
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de papier a traiter : ".$totalPaper." Tonnes<br>".PHP_EOL;

$co2PaperTotal = 0;
// le centre de tri peut tout traiter ...
if ($sortingCenter->getCapacite() >= $totalPaper)
{
    $restePaper = 0;
    $paperRecycled = $totalPaper;
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité du papier.<br>".PHP_EOL;

    // c'est bien mais est ce que le centre de recyclage du papier peut traiter la totalité du papier ?
    if ($recyclingPaper->getCapacite() > $totalPaper)
    {
        echo "la filiaire de recyclage du papier peut traiter tout le papier.".PHP_EOL;
        // quantité de CO2 rejeté par le recyclage du papier
        echo "Le recyclage du papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper));
        $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $totalPaper);
    }
    else
    {
        //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
        $restePaper = $totalPaper - $recyclingPaper->getCapacite();
        echo "Mais la filiaire de traitement du papier d'une capacité de ".$recyclingPaper->getCapacite()." tonnes ne peut pas.<br> ".$restePaper." tonnes de papier vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;
        
        // quantité de CO2 rejeté par le recyclage du papier
        $paperRecycled = $totalPaper - $restePaper;
        echo "Le recyclage de ".$paperRecycled." tonnes de papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled));
        $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled);
      
        // quantité de CO2 rejeter par l'incineration du papier
        // il y a plusieurs incinerateurs
        foreach ($arrayIncinerator as $i => $v)
        {
            // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
            if ($v >= $restePaper)
            {
                echo "L'incinération de ".$restePaper." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper));
                $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper);
                break;
            }
            else{
                $restePaper1 = $restePaper - $incinerator[$i+1]->getCapacite();
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                if($capacite > $restePaper1)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                    $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());
                  
                    // 2eme incinerateur
                    echo "L'incinération de ".$restePaper1." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1));
                    $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1);
                    break;
                }
                else
                {
                    $restePaper2 = $restePaper - $restePaper1;
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();
                  
                    if($capacite > $restePaper2)
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite()));
                        $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite());
                      
                        // 3eme incinerateur
                        echo "L'incinération de ".$restePaper2." tonnes de papier dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2));
                        $co2PaperTotal += $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2);
                        break;
                    }
                    else
                    {
                        $restePaper3 = $restePaper - $restePaper2;
                        echo "La capacité des incinérateurs a été dépassée. ".$restePaper2." tonnes de papier n'ont pas été traitées.<br>".PHP_EOL;
                    }
                }
            }
        }
    }
}

// le centre de tri ne peut pas tout traiter ....
else{
    $restePaper = $totalPaper - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de papier. Le reste (".$restePaper." tonnes) va être incinéré.<br>".PHP_EOL;
  
    // quantité de CO2 rejeté par le recyclage du papier
    $paperRecycled = $totalPaper - $restePaper;
    echo "Le recyclage de ".$paperRecycled." tonnes de papier a produit ".$recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled)." grammes de CO2.<br>".PHP_EOL;
    //array_push($co2RejeteRecycle, $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled));
    $co2PaperTotal += $recyclingPaper->recyclingTreatment($paperRecycleCo2, $paperRecycled);

    // quantité de CO2 rejeter par l'incineration du papier
    // il y a plusieurs incinerateurs
    foreach ($arrayIncinerator as $i => $v)
    {
        // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
        if ($v >= $restePaper)
        {
            echo "L'incinération de ".$restePaper." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper));
            $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $restePaper);
            break;
        }
        else{
            $restePaper1 = $restePaper - $incinerator[$i+1]->getCapacite();
            $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
            if($capacite > $restePaper1)
            {
                // 1er incinerateur
                echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());

                // 2eme incinerateur
                echo "L'incinération de ".$restePaper1." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1));
                $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $restePaper1);
                break;
            }
            else
            {
                $restePaper2 = $restePaper - $restePaper1;
                $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                if($capacite > $restePaper2)
                {
                    // 1er incinerateur
                    echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite()));
                    $co2PaperTotal += $incinerator[$i+1]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+1]->getCapacite());

                    // 2eme incinerateur
                    echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de papier dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite()));
                    $co2PaperTotal += $incinerator[$i+2]->incineratorTreatment($paperIncinereCo2, $incinerator[$i+2]->getCapacite());

                    // 3eme incinerateur
                    echo "L'incinération de ".$restePaper2." tonnes de papier dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2));
                    $co2PaperTotal += $incinerator[$i+3]->incineratorTreatment($paperIncinereCo2, $restePaper2);
                    break;
                }
                else
                {
                    $restePaper3 = $restePaper - $restePaper2;
                    echo "La capacité des incinérateurs a été dépassée. ".$restePaper2." tonnes de papier n'ont pas été traitées.<br>".PHP_EOL;
                }
            }
        }
    }
}
array_push($co2RejeteIncinere, $co2PaperTotal);
echo "Le traitement de ".$totalPaper." tonnes de papier a produit ".$co2PaperTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
*/