<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: ../../index.php");
}
else {
    function getGenre($mot): string {
        if($mot == 'M' or $mot == 'Mr')
            return 'Monsieur ';
        elseif($mot == 'Mlle')
            return 'Mademoiselle ';
        elseif($mot == 'Mme')
            return 'Madame ';
        else
            return '';
    }

    ?>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Envoyer un mail</title>
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

            input {
                margin: 0;
                padding: 1px 3px;
                font-size: 22px;
            }

            input[type="submit"] {
                margin: 20px 0;
                background-color: royalblue;
                color: white;
                border-radius: 0;
                box-shadow: none;
                border: none;
                padding: 4px 12px;
                cursor: pointer;
                font-size: 22px;
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

            <?php

        if(isset($_GET['error']) or isset($_GET['succes'])) {
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

    include_once '../../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../../accueil.php" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 4px 0 0;">Envoyer un mail</p>

    <?php

    $idUser = $_GET['idUser'];
    $userInfo = $bdd->query("SELECT * FROM User WHERE id = '$idUser'")->fetch();

    ?>

    <div class="interface">
        <div class="name">
            <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
            <p>Prénom : <?= $userInfo['prenom'] ?></p>
        </div>

        <div class="info">
            <form action="envoyerMailPost.php?idUser=<?= $idUser ?>" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 4fr; grid-gap: 10px; margin-bottom: 10px">
                    <div>
                        <label for="sujet">Sujet :</label>
                    </div>
                    <div>
                        <input type="text" name="sujet" id="sujet">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 3fr; grid-gap: 10px">
                    <div>
                        <label for="message">Message :</label>
                    </div>
                    <textarea name="message" id="message" style="border: 1px solid gray; border-radius: 10px; padding: 6px; font-size: 22px; position: absolute; left: 140px; right: 10px; top: 50px; bottom: 25%; text-align: left"><?= "Bonjour " . getGenre($userInfo['genre']) . strtoupper($userInfo['nom']) .", \n\n" ?></textarea>
                </div>
                <div style="position: absolute; bottom: 5%; left: 0; right: 0; text-align: center">
                    <input type="submit" value="Envoyer">
                </div>
            </form>
        </div>
        <?php

        if (isset($_GET['error'])) {
            ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                        <br>
                        Le mail ne s'est pas envoyé.
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
                            Le mail a bien été envoyé !
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }

        ?>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../index.php?idUser=<?= $_GET['idUser'] ?>">retour</a>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>
<?php } ?>