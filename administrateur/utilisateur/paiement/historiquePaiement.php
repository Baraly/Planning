<?php

session_start();

if (!isset($_GET['idUser'])) {
    header("location: ../../accueil.php");
}
else {

    if (!isset($_SESSION['adminAccess'])){
        header("location: ../../index.php");
    }
    else {

        ?>

        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Historique des paiements</title>
            <style>
                @font-face {
                    font-family: myFirstFont;
                    src: url(../../../font-style/ABeeZee-Regular.ttf);
                }
                body {
                    font-family: myFirstFont;
                    margin: 10px 20px;
                }

                .interface {
                    position: absolute;
                    left: 20px;
                    right: 20px;
                    top: 120px;
                    bottom: 5%;
                    font-size: 22px;
                }

                .name {
                    border: 1px solid white;
                    box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
                    display: inline-block;
                    padding: 8px 20px;
                    border-radius: 10px;
                }

                .name > p {
                    margin: 0;
                    padding: 0;
                }

                .info {
                    position: absolute;
                    border: 1px solid black;
                    right: 300px;
                    left: 360px;
                    top: 0;
                    bottom: 150px;
                    text-align: center;
                    border-radius: 10px;
                    overflow: auto;
                }

                .info > div {
                    position: relative;
                    height: 100%;
                }

                .info hr {
                    margin: 10px;
                }

                ul {
                    margin: 0 0;
                    padding: 0 0;
                    position: absolute;
                    top: 4px;
                    bottom: 0;
                    right: 0;
                    left: 0;
                }

                li {
                    /*list-style-type: none;*/
                    margin: 4px 0;
                }

                li > p {
                    margin: 0;
                    padding: 0;
                }

                li > a {
                    color: black;
                    text-decoration: none;
                    margin: 0;
                    padding: 0;
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

        $bdd = null;

        include_once '../../../function/bdd.php';
        include_once '../../../function/fonctionMois.php';

        $userInfo = $bdd->query("SELECT * FROM User WHERE id = " . $_GET['idUser'])->fetch();

        ?>

        <div style="margin-bottom: 20px">
            <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
                <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
                <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
            </a>
        </div>

        <?php

        if (!empty($_GET['idUser'])) {

            ?>

            <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Historique des paiements</p>

            <div class="interface">
                <div>
                    <div style="margin: 0; padding: 0">
                        <div class="name">
                            <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
                            <p>Prénom : <?= $userInfo['prenom'] ?></p>
                        </div>
                    </div>

                    <div style="margin: 20px 0; padding: 0">
                        <div class="name">
                            <a href="ajoutPaiement.php?idUser=<?= $_GET['idUser'] ?>" style="text-decoration: none; color: royalblue">Ajouter un paiement</a>
                        </div>
                    </div>
                </div>

                <div class="info">
                    <div>
                        <ul>
                            <?php

                            $listePaiement = $bdd->query("SELECT * FROM Paiement WHERE idUser = '" . $_GET['idUser'] . "'");

                            $rien = true;

                            while($donnees = $listePaiement->fetch()){
                                $rien = false;

                                $etat = "";

                                if($donnees['etat'] == "EXECUTE AUTO" or $donnees['etat'] == "EXECUTE MANUELLE")
                                    $etat = "<span style='color: #2ECC71'>exécuté</span>";
                                elseif($donnees['etat'] == "EN ATTENTE")
                                    $etat = "<span style='color: #D35400'>en attente</span>";
                                elseif($donnees['etat'] == "EXONERATION")
                                    $etat = "<span style='color: #9B59B6'>exonéré</span>";

                                echo "<li><a href='detailPaiement.php?idPaiement=" . $donnees['id'] . "'><p style='position: absolute; left: 20px; display: inline-block'>" . $donnees['montant'] . "€ : " . date('d/m/Y', strtotime($donnees['dateDebutAbonnement'])) . " -> " . date('d/m/Y', strtotime($donnees['dateFinAbonnement'])) . " </p><p style='position: absolute; right: 20px; display: inline-block'>" . $etat . "</p></a></li>";

                            }

                            if ($rien) {
                                echo "<p>Aucune donnée de paiement</p>";
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
        </body>
        </html>
        <?php
        }
    }
}
?>