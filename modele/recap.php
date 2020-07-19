<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Résultat du traitement des dechets par Quartier</title>
<!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

</head>

<body>
<div class="container-fluid ml-auto mr-auto">
    <h1>Résultat du traitement des dechets par Quartier</h1>
    <?php
    $resultat = json_encode($quartier);
    file_put_contents('resultat.json', $resultat);
    ?>
    <a href="resultat.json" target="_blank">Télécharger le fichier JSON des traitements</a>
    

    <?php
      //debug($quartier);
    ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr class="text-center">
                <td></td>
                <?php
                $services = array();
                foreach($quartier as $kq => $vq)
                {
                    foreach($vq as $kt => $vt)
                    { 
                        if (!in_array($kt, $services))
                        {
                            array_push($services, $kt);
                        }
                    }
                }
                foreach($services as $ks => $vs)
                {
                    ?>
                    <td class="">
                        <h5><?=$vs;?></h5>
                    </td> 
                    <?php
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($quartier as $kq => $vq)
            { ?>
                <tr>
                    <th style="vertical-align: middle;">Quartier <?=$kq;?></th> 
                    <?php
                    foreach($services as $ks => $vs)
                    {
                        foreach($vq as $kt => $vt)
                        { 
                            if ($kt == $vs)
                            {  ?>
                                <td style="font-size: 0.8em;">
                                    <div class="row bg-info">
                                        <div class="col-sm-4">Déchets</div>
                                        <div class="col-sm-4">Poids (t)</div>
                                        <div class="col-sm-4">CO2 (kg)</div>
                                    </div>
                                    <?php
                                    foreach($vt as $waste => $value)
                                    { 
                                        $v = explode('|', $value);?>
                                        <div class="row" style="border-bottom: 1px dashed #DDD;">
                                            <div class="col-sm-4"><?=$waste;?></div>
                                            <div class="col-sm-4"><?=number_format($v[0], 2, ',',' ');?></div>
                                            <div class="col-sm-4"><?=number_format($v[1]/1000, 2, ',',' ');?></div>
                                        </div>
                                    <?php
                                    } ?>
                                </td>
                                <?php
                            }
                        } 
                    }?>
                </tr>    
                <?php
            } ?>
                    
            
                


        </tbody>

    </table>  

</div>  
</body>
</html>


