<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../index.php");
}
else {
$bdd = null;

include_once '../../function/bdd.php';
include_once '../../function/fonctionJours.php';

    $idBlocage = $_GET['id'];

    if(isset($_POST['jourBlocage'])) {
        $bdd->exec("UPDATE BlockUser SET dureeBloquage = '" . $_POST['jourBlocage'] . "' WHERE id = '$idBlocage'");
    }

    if(isset($_GET['deblocage']))
        $bdd->exec("UPDATE BlockUser SET estBloque = 0 WHERE id = '$idBlocage'");

    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Détail d'un blocage</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../font-style/ABeeZee-Regular.ttf);
            }
            body {
                font-family: myFirstFont;
                margin: 10px 20px;
            }

            .interface {
                position: absolute;
                left: 4%;
                right: 30%;
                top: 120px;
                bottom: 20%;
                font-size: 22px;
                box-shadow: 0 0 5px 5px lightgrey;
                padding: 20px;
                border-radius: 20px;
            }

            .interface > p {
                padding: 0;
                margin: 0 0 8px;
            }

            .interface a {
                text-decoration: none;
                color: white;
                background-color: royalblue;
                border-radius: 10px;
                padding: 4px 12px;
                box-shadow: 0 16px 16px -12px royalblue;
                /* box-shadow: #3B326C 0 60px 60px -40px; */
            }

            .button {
                text-align: center;
                display: inline-block;
                background-color: #212F3D;
                border-radius: 10px;
                font-size: 22px;
                color: white;
                text-decoration: none;
                padding: 4px 20px;
            }
        </style>
    </head>
    <body>

    <?php

    $infoBlocage = $bdd->query("SELECT * FROM BlockUser WHERE id = '$idBlocage'")->fetch();

        ?>
        <div style="margin-bottom: 20px">
            <a href="../accueil.php?page=2" style="color: black; text-decoration: none; display: inline-block">
                <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
                <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
            </a>
        </div>

        <p style="font-size: 24px; display: inline-block; margin: 0;">Blocage N° <?= $idBlocage ?></p>

        <div class="interface">
            <div>
                <p>Dernière date enregistrée : <?= date('d/m/Y', strtotime($infoBlocage['datage'])) ?></p>
                <p>Adresse IP : <?= $infoBlocage['ipAdresse'] ?></p>
                <p>Nombre de tentative : <?= $infoBlocage['nbTentative'] ?></p>
                <form method="post" action="detailBlocage.php?id=<?= $idBlocage ?>">
                    <p>Durée du blocage prévu : <input type="tel" name="jourBlocage" value="<?= $infoBlocage['dureeBloquage'] ?>" style="font-size: 20px" oninput="loadForm()"> jours</p>
                </form>
                <p>Statut :
                    <?php

                    if($infoBlocage['nbTentative'] >= 5 and $infoBlocage['estBloque'] == 0)
                        echo "<span style='color: #2ECC71'>débloqué (autorisation administrateur)</span>";
                    elseif ($infoBlocage['nbTentative'] >= 5 and strtotime(date('Y-m-d', strtotime($infoBlocage['datage'])) . " + ". $infoBlocage['dureeBloquage'] . " days") < strtotime(date('Y-m-d')))
                        echo "<span style='color: #2ECC71'>débloqué (autorisation automatique)</span>";
                    elseif ($infoBlocage['nbTentative'] >= 5 and strtotime(date('Y-m-d', strtotime($infoBlocage['datage'])) . " + ". $infoBlocage['dureeBloquage'] . " days") >= strtotime(date('Y-m-d')))
                        echo "<span style='color: #E74C3C'>bloqué</span>";
                    else
                        echo "OK";
                    ?>
                </p>

                <?php

                if($infoBlocage['nbTentative'] >= 5 and $infoBlocage['estBloque'] == 1 and strtotime(date('Y-m-d', strtotime($infoBlocage['datage'])) . " + ". $infoBlocage['dureeBloquage'] . " days") >= strtotime(date('Y-m-d'))) {
                    $dateDeblocage = date('d/m/Y', strtotime(date('Y-m-d', strtotime($infoBlocage['datage'])) . " + ". $infoBlocage['dureeBloquage'] . " days"));
                    ?>
                    <p>Déblocage prévu le <?= $dateDeblocage ?> (dans <?= differenceJours(date('Y-m-d', strtotime($dateDeblocage)), date('Y-m-d')) ?> jours)</p>
                    <p><a href="detailBlocage.php?id=<?= $idBlocage ?>&deblocage" style="color: white; background-color: royalblue; padding: 4px 10px; border-radius: 10px">Débloquer cet utilisateur</a></p>
                    <?php
                }

                ?>

            </div>
        </div>

        <?php
    }

    echo "<a class='button' style='position: absolute; left: 4%; bottom: 10%' href='../accueil.php?page=2'>retour</a>";
    ?>
    </body>
    </html>