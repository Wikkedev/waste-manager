<?php
// dechets divers (autre)
// verifier si le centre de tri peut traiter la totalité des dechets divers (autre)


$waste = 'autres';
$co2 = $otherIncinereCo2;

foreach($totalOther as $kg => $vg)
{
    $co2Total = 0;

    // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
    if ($v >= $vg)
    {
        $co2Total += $incinerator[1]->incineratorTreatment($co2, $vg);
        // mise ne tableau des résultats pour pouvoir exporter les résultats en json
        //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
        $quartier[$kg]['incinerateur 1'][$waste] = $vg.'|'.$co2Total;
        //break;
    }
    else{
          // le premier incinerateur est plein, il faut le deuxieme
        $reste1 = $vg - $incinerator[1]->getCapacite();
        $capacite = $incinerator[2]->getCapacite();

        // le premier + le deuxieme incinérateur ont une capacité superieur à la quantité à traiter
        if($capacite > $reste1)
        {
            // 1er incinerateur
            //array_push($co2RejeteIncinere, $incinerator[1]->incineratorTreatment($co2, $resteother));
            $co2Total += $incinerator[1]->incineratorTreatment($co2, $incinerator[1]->getCapacite());

            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['incinerateur 1'][$waste] = $incinerator[1]->getCapacite().'|'.$co2Total;

            // 2eme incinerateur
            //array_push($co2RejeteIncinere, $incinerator[2]->incineratorTreatment($co2, $resteother1));
            $co2Total += $incinerator[2]->incineratorTreatment($co2, $reste1);

            // mise ne tableau des résultats pour pouvoir exporter les résultats en json
            //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
            $quartier[$kg]['incinerateur 2'][$waste] = $reste1.'|'.$co2Total;

            //break;
        }
        // il faut les 3 incinerateurs
        else
        {
            $reste2 = $reste1 - $incinerator[2]->getCapacite();
            $capacite =  $incinerator[3]->getCapacite();

            if($capacite > $reste2)
            {
                // 1er incinerateur
                //array_push($co2RejeteIncinere, $incinerator[1]->incineratorTreatment($co2, $resteother));
                $co2Total += $incinerator[1]->incineratorTreatment($co2, $incinerator[1]->getCapacite());

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 1'][$waste] = $incinerator[1]->getCapacite().'|'.$co2Total;

                // 2eme incinerateur
                //array_push($co2RejeteIncinere, $incinerator[2]->incineratorTreatment($co2, $resteother1));
                $co2Total += $incinerator[2]->incineratorTreatment($co2, $incinerator[2]->getCapacite());

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 2'][$waste] = $incinerator[2]->getCapacite().'|'.$co2Total;

                // 3eme incinerateur
                //array_push($co2RejeteIncinere, $incinerator[3]->incineratorTreatment($co2, $resteother2));
                $co2Total += $incinerator[3]->incineratorTreatment($co2, $reste2);

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 3'][$waste] = $reste2.'|'.$co2Total;
                //break;
            }
            else
            {
                $reste3 = $reste2 - $incinerator[3]->getCapacite();

                // 1er incinerateur
                //array_push($co2RejeteIncinere, $incinerator[1]->incineratorTreatment($co2, $resteother));
                $co2Total += $incinerator[1]->incineratorTreatment($co2, $incinerator[1]->getCapacite());

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 1'][$waste] = $incinerator[1]->getCapacite().'|'.$co2Total;

                // 2eme incinerateur
                //array_push($co2RejeteIncinere, $incinerator[2]->incineratorTreatment($co2, $resteother1));
                $co2Total += $incinerator[2]->incineratorTreatment($co2, $incinerator[2]->getCapacite());

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 2'][$waste] = $incinerator[2]->getCapacite().'|'.$co2Total;

                // 3eme incinerateur
                //array_push($co2RejeteIncinere, $incinerator[3]->incineratorTreatment($co2, $resteother2));
                $co2Total += $incinerator[3]->incineratorTreatment($co2, $incinerator[3]->getCapacite());

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['incinerateur 3'][$waste] = $incinerator[3]->getCapacite().'|'.$co2Total;

                // mise ne tableau des résultats pour pouvoir exporter les résultats en json
                //$quatier[id du quartier][filiaire de traitement][dechet][quantité traité] = CO2 regeté
                $quartier[$kg]['non traité'][$waste] = $reste3.'|0';
            }
        }
    }
    array_push($co2RejeteIncinere, $co2Total);
}