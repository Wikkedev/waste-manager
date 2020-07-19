<?php
// dechets organiques
// verifier si le centre de tri peut traiter la totalité des dechets organiques
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de dechets organiques a traiter : ".array_sum($totalOrganic)." Tonnes<br>".PHP_EOL;

if ($sortingCenter->getCapacite() >= array_sum($totalOrganic))
{
    $resteOrganic = 0;
    $organicRecycled = array_sum($totalOrganic);
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité des déches organiques.<br>".PHP_EOL;
}
else
{
    $resteOrganic = array_sum($totalOrganic) - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de déches organiques. Le reste (".$resteOrganic." tonnes) va être incinéré.<br>".PHP_EOL;
}

foreach($totalOrganic as $kg => $vg)
{
    echo '<b>Quartier '.$kg.' : '.$population[$kg].' habitants</b><br>'.PHP_EOL;
  
    $co2OrganicTotal = 0;
    // le centre de tri peut tout traiter ...
    if ($sortingCenter->getCapacite() >= $totalOrganic[$kg])
    {
        $resteOrganic = 0;
        $organicComposted = $totalOrganic[$kg];

        echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité des dechets organiques.<br>".PHP_EOL;

        // c'est bien mais est ce que le centre de compostage des dechets organiques peut traiter la totalité des dechets organiques ?
        // addition de la quantité de dechets organique
        $capaciteComposting = $composter->getCapacite();

        if ($capaciteComposting > $totalOrganic[$kg])
        {
            echo "la filiaire de compostage peut traiter tout les dechets organiques.".PHP_EOL;
            // quantité de CO2 rejeté par le compostage des dechets organiques
            echo "Le compostage des dechets organiques a produit ".$Composter->compostingTreatment($organicCompostCo2, $totalOrganic[$kg])." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteCompost, $Composter->OrganicTreatment($organicCompostCo2, $totalOrganic[$kg]));
            $co2OrganicTotal += $composter->compostingTreatment($organicCompostCo2, $totalOrganic[$kg]);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['compostage']['organique'] = $totalOrganic[$kg].'|'.$co2OrganicTotal;
        }
        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $resteOrganic = $totalOrganic[$kg] - $capaciteComposting;
            echo "Mais la filiaire de traitement des dechets organiques d'une capacité de ".$capaciteComposting." tonnes ne peut pas.<br> ".$resteOrganic." tonnes de dechets organiques vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le compostage
            $organicComposted = $totalOrganic[$kg] - $resteOrganic;
            echo "Le compostage de ".$organicComposted." tonnes de dechets organiques a produit ".$composter->compostingTreatment($organicCompostCo2, $organicComposted)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteCompost, $Composter->CompostingTreatment($organicCompostCo2, $organicComposted));
            $co2OrganicTotal += $composter->compostingTreatment($organicCompostCo2, $organicComposted);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['compostage']['organique'] = $capaciteComposting.'|'.$co2OrganicTotal;


            // quantité de CO2 rejeter par l'incineration des dechets organiques
            // il y a plusieurs composteurs
            foreach ($arrayIncinerator as $i => $v)
            {
                // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
                if ($v >= $resteOrganic)
                {
                    echo "L'incinération de ".$resteOrganic." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic));
                    $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 1']['organique'] = $resteOrganic.'|'.$co2OrganicTotal;
                  
                    break;
                }
                else
                {
                    $resteOrganic1 = $resteOrganic - $incinerator[$i+1]->getCapacite();
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                    if($capacite > $resteOrganic1)
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite()));
                        $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite());

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteOrganic1." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1));
                        $co2OrganicTotal += $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 2']['organique'] = $resteOrganic1.'|'.$co2OrganicTotal;
                        break;
                    }
                    else
                    {
                        $resteOrganic2 = $resteOrganic - $resteOrganic1;
                        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                        if($capacite > $resteOrganic2)
                        {
                            // 1er incinerateur
                            echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite()));
                            $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+1]->getCapacite());

                            // 2eme incinerateur
                            echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+2]->getCapacite()));
                            $co2OrganicTotal += $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $incinerator[$i+2]->getCapacite());

                            // 3eme incinerateur
                            echo "L'incinération de ".$resteOrganic2." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2)." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2));
                            $co2OrganicTotal += $incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2);
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['incinerateur 3']['organique'] = $resteOrganic2.'|'.$co2OrganicTotal;
                            break;
                        }
                        else
                        {
                            $resteOrganic3 = $resteOrganic - $resteOrganic2;
                            echo "La capacité des incinérateurs a été dépassée. ".$resteOrganic3." tonnes de dechets organiques n'ont pas été traitées.<br>".PHP_EOL;
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['non traité']['organique'] = $resteOrganics3[$kg].'|0';
                        }
                    }
                }
            }
        }
    }

    // le centre de tri ne peut pas tout traiter ....
    else{
      
        
        $organicComposted = $totalOrganic[$kg];
        $resteOrganic = $totalOrganic[$kg] - $sortingCenter->getCapacite();
        echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de dechets organiques. Le reste (".$resteOrganic." tonnes) va être incinéré.<br>".PHP_EOL;

        // c'est bien mais est ce que le centre de compostage des dechets organiques peut traiter la totalité des dechets organiques ?
        // quantité de dechets organique que peuvent traiter tous les composteurs
        $capaciteComposting = $composter->getCapacite();

        if ($capaciteComposting > $resteOrganic)
        {
            echo "la filiaire de compostage peut traiter tout les dechets organiques.".PHP_EOL;
            // quantité de CO2 rejeté par le compostage des dechets organiques
            echo "Le compostage des dechets organiques a produit ".$Composter->compostingTreatment($organicCompostCo2, $totalOrganic[$kg])." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteCompost, $Composter->OrganicTreatment($organicCompostCo2, $totalOrganic[$kg]));
            $co2OrganicTotal += $composter->compostingTreatment($organicCompostCo2, $totalOrganic[$kg]);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['compostage']['organique'] = $resteOrganic.'|'.$co2OrganicTotal;
        }
        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $resteOrganic = $totalOrganic[$kg] - $capaciteComposting;
            echo "Mais la filiaire de traitement des dechets organiques d'une capacité de ".$capaciteComposting." tonnes ne peut pas.<br> ".$resteOrganic." tonnes de dechets organiques vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le compostage
            $organicComposted = $totalOrganic[$kg] - $resteOrganic;
            echo "Le compostage de ".$organicComposted." tonnes de dechets organiques a produit ".$composter->compostingTreatment($organicCompostCo2, $organicComposted)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteCompost, $Composter->CompostingTreatment($organicCompostCo2, $organicComposted));
            $co2OrganicTotal += $composter->compostingTreatment($organicCompostCo2, $organicComposted);
          
            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['compostage']['organique'] = $capaciteComposting.'|'.$co2OrganicTotal;


            // quantité de CO2 rejeter par l'incineration des dechets organiques
            // il y a plusieurs incinerateurs
            foreach ($arrayIncinerator as $i => $v)
            {
                // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
                if ($v >= $resteOrganic)
                {
                    echo "L'incinération de ".$resteOrganic." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic)." grammes de CO2.<br>".PHP_EOL;
                    //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic));
                    $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic);
                  
                    // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                    //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                    $quartier[$kg]['incinerateur 1']['organique'] = $resteOrganic.'|'.$co2OrganicTotal;
                    break;
                }
                else{
                    $resteOrganic1 = $resteOrganic - $incinerator[$i+1]->getCapacite();
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
                    if($capacite > $resteOrganic1)
                    {
                        // 1er incinerateur
                        echo "L'incinération de ".$resteOrganic." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic));
                        $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic);

                        // 2eme incinerateur
                        echo "L'incinération de ".$resteOrganic1." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1)." grammes de CO2.<br>".PHP_EOL;
                        //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1));
                        $co2OrganicTotal += $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1);
                      
                        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                        $quartier[$kg]['incinerateur 2']['organique'] = $resteOrganic1.'|'.$co2OrganicTotal;
                        break;
                    }
                    else
                    {
                        $resteOrganic2 = $resteOrganic - $resteOrganic1;
                        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                        if($capacite > $resteOrganic2)
                        {
                            // 1er incinerateur
                            echo "L'incinération de ".$resteOrganic." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic)." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic));
                            $co2OrganicTotal += $incinerator[$i+1]->incineratorTreatment($organicIncinereCo2, $resteOrganic);

                            // 2eme incinerateur
                            echo "L'incinération de ".$resteOrganic1." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1)." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1));
                            $co2OrganicTotal += $incinerator[$i+2]->incineratorTreatment($organicIncinereCo2, $resteOrganic1);

                            // 3eme incinerateur
                            echo "L'incinération de ".$resteOrganic2." tonnes de dechets organiques dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2)." grammes de CO2.<br>".PHP_EOL;
                            //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2));
                            $co2OrganicTotal += $incinerator[$i+3]->incineratorTreatment($organicIncinereCo2, $resteOrganic2);
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['incinerateur 3']['organique'] = $resteOrganic2.'|'.$co2OrganicTotal;
                            break;
                        }
                        else
                        {
                            $resteOrganic3 = $resteOrganic - $resteOrganic2;
                            echo "La capacité des incinérateurs a été dépassée. ".$resteOrganic3." tonnes de dechets organiques n'ont pas été traitées.<br>".PHP_EOL;
                          
                            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                            $quartier[$kg]['non traité']['organique'] = $resteOrganics3[$kg].'|0';
                        }
                    }
                }
            }
        }
    }

    array_push($co2RejeteIncinere, $co2OrganicTotal);
    echo "Le traitement de ".$totalOrganic[$kg]." tonnes de dechets organiques a produit ".$co2OrganicTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
    echo "_________________________________________________________________________________________________<br>".PHP_EOL;
}