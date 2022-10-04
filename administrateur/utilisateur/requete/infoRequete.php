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
        <title>Title</title>
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
                right: 340px;
                left: 300px;
                top: 0;
                bottom: 100px;
                text-align: center;
                border-radius: 10px;
                overflow: auto;
            }

            .info p {
                margin: 10px 20px;
                padding: 0;
                text-align: left;
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

            form {
                margin-top: 80px;
            }

            textarea {
                font-family: myFirstFont;
                font-size: 20px;
                padding: 10px;
                width: 80%;
                height: 100px;
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
                font-size: 22px;
            }
        </style>
    </head>
    <body>

    <?php

    $bdd = null;

    include_once '../../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <?php

    if (!empty($_GET['idUser'])) {

    $userInfo = $bdd->query("SELECT * FROM User WHERE id = '" . $_GET['idUser'] . "'")->fetch();

    ?>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Requête N° <?= $_GET['idRequete'] ?></p>

    <div class="interface">

        <div class="name">
            <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
            <p>Prénom : <?= $userInfo['prenom'] ?></p>
        </div>

        <div class="info">
            <div>
                <?php

                $infoRequete = $bdd->query("SELECT * FROM Requete WHERE id = '" . $_GET['idRequete'] . "'")->fetch();

                ?>
                <p>Type : <?= $infoRequete['type'] ?></p>
                <p>Date de réception : <?= date('d', strtotime($infoRequete['dateReception'])) ?>/<?= date('m', strtotime($infoRequete['dateReception'])) ?>/<?= date('Y', strtotime($infoRequete['dateReception'])) ?></p>
                <p>
                    État :
                    <?php

                    if($infoRequete['dateTraitement']) {
                        echo "<span style='color: #8CCD75'>traitée</span> (le " . date('d', strtotime($infoRequete['dateTraitement'])) ?>/<?= date('m', strtotime($infoRequete['dateTraitement'])) ?>/<?= date('Y', strtotime($infoRequete['dateTraitement'])) . ")";
                    }
                    else
                        echo "<span style='color: #EF5050'>à traiter</span>";

                    ?>
                </p>
                <div style="display: grid; grid-template-columns: 110px auto; margin: 10px 20px;"><p style="display: inline-block; margin: 0">Message : </p><p style="display: inline-block; text-align: left; margin: 0;"><?= $infoRequete['message'] ?></p></div>
                <?php

                if(!$infoRequete['dateTraitement']) {
                    ?>
                    <form action="requetePost.php?idRequete=<?= $_GET['idRequete'] ?>" method="POST">
                        <?php

                        $message = "Bonjour ";
                        $listeGenre = [['M', 'Monsieur'], ['Mr', 'Monsieur'], ['Mlle', 'Mademoiselle'], ['Mme', 'Madame']];

                        foreach ($listeGenre as $genre) {
                            if ($genre[0] == $userInfo['genre'])
                                $message .= $genre[1] . " ";
                        }

                        $message .= $userInfo['nom'] . ", \n\n";

                        ?>
                        <textarea name="message" required><?= $message ?></textarea><br>
                        <input type="submit" value="Envoyer">
                    </form>
                    <?php
                }

                ?>
            </div>
        </div>

        <?php } ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
    </body>
    </html>
<?php } ?>