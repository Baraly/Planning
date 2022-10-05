<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../../index.php");
}
else {

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Historique des journées</title>
        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../../font-style/ABeeZee-Regular.ttf);
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
                font-size: 22px;
            }

            .name {
                border: 1px solid white;
                box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
                display: inline-block;
                padding: 8px 20px;
                border-radius: 10px;
                margin-top: 6px;
            }

            .name > p {
                margin: 0;
                padding: 0;
            }

            select {
                border: none;
                border-radius: 6px;
            }

            input, select {
                margin: 0;
                padding: 1px 3px;
                font-size: 22px;
            }

            input[type="submit"] {
                position: absolute;
                top: 14px;
                right: 14px;
                background-color: royalblue;
                color: white;
                border-radius: 8px;
                box-shadow: none;
                border: none;
                padding: 4px 12px;
                cursor: pointer;
                font-size: 22px;
            }


            ::-webkit-scrollbar {
                display: none;
            }
            .info {
                position: absolute;
                top: 20%;
                left: 20%;
                right: 20%;
                bottom: 15%;
                padding: 10px 20px;
                overflow: auto;
                border: 1px solid gray;
                border-radius: 20px;
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

            <?php

        if(isset($_GET['error']) or isset($_GET['succes']) or isset($_GET['suppressionOK']) or isset($_GET['supprimerJournee'])) {
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

            li {
                list-style-type: none;
                padding: 0;
                margin: 0 0 6px;
                display: flex;
            }

            li a, li p, ul {
                margin: 0;
                padding: 0;
            }

            li a {
                margin-left: 10px;
                text-decoration: none;
                color: royalblue;
            }

            ul {
                margin: 10px 20px;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../../function/bdd.php';
    include_once '../../../../function/fonctionMois.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 4px 0 0;">Historique des journées</p>

    <?php

    $idUser = $_GET['idUser'];
    $userInfo = $bdd->query("SELECT * FROM User WHERE id = '$idUser'")->fetch();

    ?>

    <div class="interface">
        <div class="name">
            <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
            <p>Prénom : <?= $userInfo['prenom'] ?></p>
        </div>

        <?php

            $annee = (int)date('Y');

            if(isset($_POST['annee']))
                $annee = $_POST['annee'];

            ?>
        <div class="info">
            <form action="historique.php?idUser=<?= $idUser ?>" method="post" id="myform">
                <label for="annee">Année :</label>
                <select name="annee" id="annee" oninput="loadForm()">
                    <option value="all">Toutes</option>
                    <?php

                    $rien = true;

                    $listeAnnee = $bdd->query("SELECT YEAR(datage) AS annee FROM Horaire WHERE idUser ='$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY YEAR(datage) ORDER BY YEAR(datage)");
                    while($anneeUser = $listeAnnee->fetch()) {
                        $rien = false;

                        if(isset($_POST['annee']) and $_POST['annee'] == $anneeUser['annee'])
                            echo "<option value='" . $anneeUser['annee'] . "' selected>" . $anneeUser['annee'] . "</option>";
                        else
                            echo "<option value='" . $anneeUser['annee'] . "'>" . $anneeUser['annee'] . "</option>";
                    }

                    if($rien)
                        echo "<option value='$annee'>$annee</option>";

                    ?>
                </select>
            </form>
            <div>
                <form action="historiquePost.php?idUser=<?= $idUser ?>" method="post">
                    <input type="submit" value="Envoyer par e-mail">
                    <ul>
                        <?php

                        if(isset($_POST['annee']) AND $_POST['annee'] != 'all')
                            $listeMoisAnnee = $bdd->query("SELECT MONTH(datage) AS mois, YEAR(datage) AS annee FROM Horaire WHERE idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) AND YEAR(datage) = '" . $_POST['annee'] . "' GROUP BY MONTH(datage), YEAR(datage) ORDER BY YEAR(datage), MONTH(datage)");
                        else
                            $listeMoisAnnee = $bdd->query("SELECT MONTH(datage) AS mois, YEAR(datage) AS annee FROM Horaire WHERE idUser = '$idUser' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY MONTH(datage), YEAR(datage) ORDER BY YEAR(datage), MONTH(datage)");

                        $anneeUserVariable = "";
                        $rien = true;

                        while($ligneUser = $listeMoisAnnee->fetch()) {
                            $rien = false;

                            $moisUser = $ligneUser['mois'];
                            $anneeUser = $ligneUser['annee'];

                            if($anneeUserVariable == "") {
                                $anneeUserVariable = $anneeUser;
                                echo "<li><p class='annee'>$anneeUser</p></li>";
                                echo "<li><input type='checkbox' name='mois" . (int)$moisUser . "_annee" . $anneeUser . "'><a href='../../../../utilisateur/identifie/historique/generateurPDF.php?userId=$idUser&mois=$moisUser&annee=$anneeUser'><p class='mois'>" . mois($moisUser) . " $anneeUser</p></a></li>";

                            }
                            elseif ($anneeUserVariable == $anneeUser) {
                                echo "<li><input type='checkbox' name='mois" . (int)$moisUser . "_annee" . $anneeUser . "'><a href='../../../../utilisateur/identifie/historique/generateurPDF.php?userId=$idUser&mois=$moisUser&annee=$anneeUser'><p class='mois'>" . mois($moisUser) . " $anneeUser</p></a></li>";
                            }
                            else {
                                $anneeUserVariable = $anneeUser;
                                echo "<hr>";
                                echo "<li><p class='annee'>$anneeUser</p></li>";
                                echo "<li><input type='checkbox' name='mois" . (int)$moisUser . "_annee" . $anneeUser . "'><a href='../../../../utilisateur/identifie/historique/generateurPDF.php?userId=$idUser&mois=$moisUser&annee=$anneeUser'><p class='mois'>" . mois($moisUser) . " $anneeUser</p></a></li>";
                            }
                        }
                        ?>
                    </ul>
                </form>
            </div>
        </div>

            <?php

        if (isset($_GET['error'])) {
            $message = "";
            if($_GET['error'] == 'mail')
                $message = "Le fichier PDF a bien été généré mais il ne s'est pas envoyé.";
            elseif($_GET['error'] == 'fichier')
                $message = "Le fichier PDF n'a malheureusement pas été généré correctement.";
            elseif($_GET['error'] == 'supprimerFichier')
                $message = "Le fichier PDF a bien été envoyé mais il n'a pas été supprimé correctement.";
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                            <br>
                            <?= $message ?>
                        </p>
                        <a href="#" onclick="closePopup()">J'ai compris</a>
                    </div>
                </div>
            </div>
            <?php
        }

        if (isset($_GET['succes'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            Un fichier PDF a bien été envoyé !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }

        function loadForm() {
            document.forms["myform"].submit();
        }
    </script>
    </body>
    </html>
<?php } ?>