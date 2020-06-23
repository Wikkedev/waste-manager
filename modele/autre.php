<?php
// dechets divers (autre)
// verifier si le centre de tri peut traiter la totalité des dechets divers (autre)
echo "_________________________________________________________________________________________________<br>".PHP_EOL;
echo "Quantite total de dechets divers (autre) a traiter : ".$totalOther." Tonnes.<br>".PHP_EOL;

$co2OtherTotal = 0;

// pour ces dechets, il n'y a pas de centre de tri. tout est incinéré
$resteOther = $totalOther;
echo "Le centre de tri ne traite aucun de ces dechets divers (autre). Tout (".$resteOther." tonnes) va être incinéré.<br>".PHP_EOL;

// quantité de CO2 rejeter par l'incineration du dechets divers (autre)
// il y a plusieurs incinerateurs
foreach ($arrayIncinerator as $i => $v)
{
    // je verifie si le premier incinérateur à la capacité de traiter ce qui reste
    if ($v >= $resteOther)
    {
        echo "L'incinération de ".$resteOther." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $resteOther)." grammes de CO2.<br>".PHP_EOL;
        //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $resteother));
        $co2OtherTotal += $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $resteOther);
        break;
    }
    else{
        $resteOther1 = $resteOther - $incinerator[$i+1]->getCapacite();
        $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite();
        if($capacite > $resteOther1)
        {
            // 1er incinerateur
            echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $resteother));
            $co2OtherTotal += $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+1]->getCapacite());

            // 2eme incinerateur
            echo "L'incinération de ".$resteOther1." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $resteOther1)." grammes de CO2.<br>".PHP_EOL;
            //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $resteother1));
            $co2OtherTotal += $incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $resteOther1);
            break;
        }
        else
        {
            $resteOther2 = $resteOther - $resteOther1;
            $capacite = $incinerator[$i+1]->getCapacite() + $incinerator[$i+2]->getCapacite() + $incinerator[$i+3]->getCapacite();

            if($capacite > $resteOther2)
            {
                // 1er incinerateur
                echo "L'incinération de ".$incinerator[$i+1]->getCapacite()." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+1]->getType()." a produit ".$incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+1]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $resteother));
                $co2OtherTotal += $incinerator[$i+1]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+1]->getCapacite());

                // 2eme incinerateur
                echo "L'incinération de ".$incinerator[$i+2]->getCapacite()." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+2]->getType()." a produit ".$incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+2]->getCapacite())." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $resteother1));
                $co2OtherTotal += $incinerator[$i+2]->incineratorTreatment($otherIncinereCo2, $incinerator[$i+2]->getCapacite());

                // 3eme incinerateur
                echo "L'incinération de ".$resteOther2." tonnes de dechets divers (autre) dans l'incinérateur ".$incinerator[$i+3]->getType()." a produit ".$incinerator[$i+3]->incineratorTreatment($otherIncinereCo2, $resteOther2)." grammes de CO2.<br>".PHP_EOL;
                //array_push($co2RejeteIncinere, $incinerator[$i+3]->incineratorTreatment($otherIncinereCo2, $resteother2));
                $co2OtherTotal += $incinerator[$i+3]->incineratorTreatment($otherIncinereCo2, $resteOther2);
                break;
            }
            else
            {
                $resteOther3 = $resteOther - $resteOther2;
                echo "La capacité des incinérateurs a été dépassée. ".$resteOther2." tonnes de dechets divers (autre) n'ont pas été traitées.<br>".PHP_EOL;
            }
        }
    }
}

array_push($co2RejeteIncinere, $co2OtherTotal);
echo "Le traitement de ".$totalOther." tonnes de dechets divers (autre) a produit ".$co2OtherTotal." grammes de CO2 toutes filiaires de traitement confondues.<br>".PHP_EOL;
echo "_________________________________________________________________________________________________<br>".PHP_EOL;