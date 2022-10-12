<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../index.php");
}
else {

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier une information</title>
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
                top : 110px;
                bottom: 5%;
                left: 20px;
                right: 20px;
            }

            input[type="text"], input[type="submit"] {
                font-size: 20px;
                border-radius: 10px;
                border: 1px solid black;
                padding: 1px 8px;
                margin: 0 6px;
            }

            input[type="text"] {
                border: 1px solid grey;
            }

            input[type="submit"] {
                margin: 20px 80px;
                background-color: royalblue;
                color: white;
                border-radius: 8px;
                box-shadow: none;
                border: none;
                padding: 4px 8px;
                cursor: pointer;
            }

            textarea {
                font-size: 20px;
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

            ul {
                margin: 0 0;
                padding: 6px;
                list-style-position: inside;
                overflow: auto;
            }

            li {
                margin: 4px 10px;
                padding: 0 0;
                list-style-type: none;
                position: relative;
                font-size: 18px;
            }

            <?php

            if(isset($_GET['error']) or isset($_GET['overview']) or isset($_GET['supprimerPopup'])) {
                ?>
            .overlay {
                position: fixed;
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
                background-color: rgba(39, 55, 70, 0.9);
                z-index: 20;
            }
            .subOverlay {
                position: relative;
                height: 100%;
                width: 100%;
                text-align: center;
            }
            .overlay .content {
                width: 60%;
                position: fixed;
                z-index: 21;
                border-radius: 20px;
                padding: 20px 12px;
                background-color: white;
                display: inline-block;
                left: 50%;
                top: 50%;
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
                font-size: 26px;
            }

            .overlay .content a {
                display: inline-block;
                background-color: #212F3D;
                color: white;
                text-decoration: none;
                border-radius: 14px;
                padding: 5px 40px;
            }
            <?php
        }

        ?>
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../accueil.php?page=2" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Modification d'une information</p>

    <div class="interface">
        <?php

        $idInfo = $_GET['idInfo'];

        if(isset($_GET['supprimer'])) {
            $bdd->exec("DELETE FROM LuMessageInfo WHERE idMessageInfo = '$idInfo' AND idUser = '" . $_GET['idUser'] . "'");
            header("location: detailInformation.php?idInfo=$idInfo");
        }

        if(isset($_GET['supprimerPopup'])) {
            $infoUserPopup = $bdd->query("SELECT nom, prenom FROM User WHERE id = '" . $_GET['idUser'] . "'")->fetch();
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            Vous êtes sur le point de supprimer <?= strtoupper($infoUserPopup['nom']) ?> <?= $infoUserPopup['prenom'] ?> de cette liste d'information.
                        </p>
                        <div  style="display: grid; grid-template-columns: 1fr 1fr; justify-content: space-around">
                            <div>
                                <a href="#" onclick="closePopup()">annuler</a>
                            </div>
                            <div>
                                <a href="detailInformation.php?idInfo=<?= $idInfo ?>&idUser=<?= $_GET['idUser'] ?>&supprimer">approuver</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        if (isset($_GET['error'])) {
            $message = "";
            if($_GET['error'] == 'BDD')
                $message = "La base de données à refusé de créer cette information !";
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                            <?= $message ?>
                        </p>
                        <a href="#" onclick="closePopup()">Mince OK</a>
                    </div>
                </div>
            </div>
            <?php
        }

        ?>
        <div style="display: grid; grid-template-columns: 1fr 2fr; font-size: 20px">
            <div style="border: 1px solid white; border-radius: 10px; box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25)">
                <div style="text-align: center">
                    <p style="font-size: 20px; padding: 0; margin: 6px 0">Liste des utilisateurs</p>
                    <hr style="width: 90%; padding: 0; margin: 0">
                </div>
                <ul>
                    <?php

                    $requeteLu = $bdd->query("SELECT nom, prenom, dateLecture FROM LuMessageInfo, User WHERE LuMessageInfo.idUser = User.id AND idMessageInfo = '$idInfo' AND dateLecture IS NOT NULL");

                    $rien = true;
                    while($requete = $requeteLu->fetch()) {
                        if($rien) {
                            echo "<li style='margin-bottom: 10px; color: #2ECC71; text-align: center'>à déjà lu</li>";
                            $rien = false;
                        }
                        echo "<li>" . strtoupper($requete['nom']) . " " . $requete['prenom'] . " <span style='position: absolute; right: 0'>" . date('d/m/Y H:i', strtotime($requete['dateLecture'])) . "</span></li>";
                    }

                    $requetePasLu = $bdd->query("SELECT nom, prenom, dateLecture, idUser FROM LuMessageInfo, User WHERE LuMessageInfo.idUser = User.id AND idMessageInfo = '$idInfo' AND dateLecture IS NULL");

                    $rien2 = true;
                    while($requete = $requetePasLu->fetch()) {
                        if($rien2) {
                            if(!$rien)
                                echo "<li style='text-align: center'><hr style='width: 70%'></li>";
                            echo "<li style='margin-bottom: 10px; color: #EF5050; text-align: center'>pas encore lu</li>";
                            $rien2 = false;
                        }
                        echo "<li><a href='detailInformation.php?idInfo=$idInfo&idUser=" . $requete['idUser'] . "&supprimerPopup' style='text-decoration: none'><span style='color: black'>" . strtoupper($requete['nom']) . " " . $requete['prenom'] . " </span><span style='position: absolute; right: 0; color: #D35400'>en attente</span></a></li>";
                    }

                    ?>
                </ul>
            </div>
            <div style="border: 1px solid white; border-radius: 10px; box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25); margin-left: 30px; padding: 10px 20px; width: 80%; overflow: auto">
                <div>
                    <?php

                    $requesteInfo = $bdd->query("SELECT * FROM MessageInfo WHERE id = '$idInfo'")->fetch();

                    ?>
                    <p>Id : <?= $idInfo ?></p>
                    <p>Message : <a href="detailInformation.php?idInfo=<?= $idInfo ?>&overview" style="color: royalblue; background-color: white; text-decoration: none">Voir le message</a></p>
                    <p>Description : <?= $requesteInfo['description'] ?></p>
                    <p>Date de publication : <?= date('d/m/Y', strtotime($requesteInfo['dateMessage'])) ?></p>
                    <p>Date de clôture : <?php if(empty($requesteInfo['dateCloture'])) echo "Pas défini <a href='creationInformationPost.php?cloture&idInfo=$idInfo' style='background-color: royalblue; color: white; padding: 4px 8px; border-radius: 10px; text-decoration: none'>clôturer</a>"; else echo date('d/m/Y', strtotime($requesteInfo['dateMessage'])); ?></p>
                </div>
                <div>
                    <?php

                    $nombreUser = $bdd->query("SELECT COUNT(*) AS nb FROM LuMessageInfo WHERE idMessageInfo = '$idInfo'")->fetch();
                    $nombreUserLu = $bdd->query("SELECT COUNT(*) AS nb FROM LuMessageInfo WHERE idMessageInfo = '$idInfo' AND dateLecture IS NOT NULL")->fetch();
                    $nombreUserPasLu = $bdd->query("SELECT COUNT(*) AS nb FROM LuMessageInfo WHERE idMessageInfo = '$idInfo' AND dateLecture IS NULL")->fetch();

                    ?>
                    <p>Nombre d'utilisateurs concernés : <?= $nombreUser['nb'] ?></p>
                    <p>Nombre d'utilisateurs qui ont déjà <span style="color: #2ECC71">lu</span> : <?= $nombreUserLu['nb'] ?></p>
                    <p>Nombre d'utilisateurs qui sont en <span style="color: #D35400">attente</span> : <?= $nombreUserPasLu['nb'] ?></p>
                </div>
            </div>
        </div>

    </div>

    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../accueil.php?page=2">retour</a>

    <?php

    if(isset($_GET['overview'])) {
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content" style="width: 50%; text-align: left">
                    <p style="margin: 0; padding: 0">
                    <div style="text-align: center">
                        <span style="font-weight: bold; color: royalblue">-- Information --</span>
                    </div>
                    <hr>
                    <?= $requesteInfo['message'] ?>
                    </p>
                    <div style="text-align: center">
                        <a href="#" onclick="closePopup()">Quitter la visualisation</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>
<?php  } ?>