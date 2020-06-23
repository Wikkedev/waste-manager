<?php
// dechets organiques
// verifier si le centre de tri peut traiter la totalité des dechets organiques
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de dechets organiques a traiter : ".$totalOrganic." Tonnes<br>".PHP_EOL;

$co2OrganicTotal = 0;
// le centre de tri peut tout traiter ...
if ($sortingCenter->getCapacite() >= $totalOrganic)
{
    $resteOrganic = 0;
    $organicComposted = $totalOrganic;
  
    echo "Le centre de tri d'une capacité de ".$sortingCenter->getCapacite()." tonnes peut traiter la totalité des dechets organiques.<br>".PHP_EOL;

    // c'est bien mais est ce que le centre de compostage des dechets organiques peut traiter la totalité des dechets organiques ?
  
    // il y a plusieurs composteurs
    foreach ($arrayComposter as $i => $v)
    {
        if ($composter[$i+1]->getCapacite() > $totalOrganic)
        {
            echo "la filiaire de compostage des dechets organiques peut traiter tout les dechets organiques.<br>".PHP_EOL;
            // quantité de CO2 rejeté par le compostage des dechets organiques
            echo "Le compostage des dechets organiques a produit ".$organicComposting->compostingTreatment($organicCompostCo2, $totalOrganic)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteCompost, $organicComposting->compostingTreatment($organicCompostCo2, $totalOrganic));
            $co2OrganicTotal += $organicComposting->compostingTreatment($organicCompostCo2, $totalOrganic);
        } 

        else
        {
            //je recupere ce qu'il reste à traiter que j'envoie à l'incinerateur
            $resteOrganic = $totalOrganic - $organicComposting->getCapacite();
            echo "Mais la filiaire de traitement des dechets organiques d'une capacité de ".$organicComposting->getCapacite()." tonnes ne peut pas.<br> ".$resteOrganic." tonnes de dechets organiques vont directement être envoyés à l'incinérateur.<br>".PHP_EOL;

            // quantité de CO2 rejeté par le compostage des dechets organiques
            $organicComposted = $totalOrganic - $resteOrganic;
            echo "Le compostage de ".$organicComposted." tonnes de dechets organiques a produit ".$organicComposting->compostingTreatment($organicRecycleCo2, $organicComposted)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteRecycle, $organicComposting->compostingTreatment($organicRecycleCo2, $organicComposted));
            $co2OrganicTotal += $organicComposting->compostingTreatment($organicRecycleCo2, $organicComposted);

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
                    break;
                }
                else{
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
                            break;
                        }
                        else
                        {
                            $resteOrganic3 = $resteOrganic - $resteOrganic2;
                            echo "La capacité des incinérateurs a été dépassée. ".$resteOrganic3." tonnes de dechets organiques n'ont pas été traitées.<br>".PHP_EOL;
                        }
                    }
                }
            }
        }
    }
}

// le centre de tri ne peut pas tout traiter ....
else{
    $resteOrganic = $totalOrganic - $sortingCenter->getCapacite();
    echo "Le centre de tri ne peut traiter que ".$sortingCenter->getCapacite()." tonnes de dechets organiques. Le reste (".$resteOrganic." tonnes) va être incinéré.<br>".PHP_EOL;
  
  
    
        // quantité de CO2 rejeté par le compostage des dechets organiques
        $organicComposted = $totalOrganic - $resteOrganic;
        echo "Le compostage de ".$organicComposted." tonnes de dechets organiques a produit ".$composter[$i+1]->compostingTreatment($organicCompostCo2, $organicComposted)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteCompost, $composter[$i+1]->compostingTreatment($organicCompostCo2, $organicComposted));
        $co2OrganicTotal += $composter[$i+1]->compostingTreatment($organicCompostCo2, $organicComposted);

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
                    break;
                }
                else
                {
                    $resteOrganic2 = $resteOrganic - $resteOrganic1;
                    $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

                    if($capacite > $resteOrganic3)
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
                        break;
                    }
                    else
                    {
                        $resteOrganic3 = $resteOrganic - $resteOrganic2;
                        echo "La capacité des incinérateurs a été dépassée. ".$resteOrganic3." tonnes de dechets organiques n'ont pas été traitées.<br>".PHP_EOL;
                    }
                }
            }
        }
    
}
array_push($co2RejeteIncinere, $co2OrganicTotal);
echo "Le traitement de ".$totalOrganic." tonnes de dechets organiques a produit ".$co2OrganicTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
echo "_________________________________________________________________________________________________<br>".PHP_EOL;