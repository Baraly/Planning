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
        <title>Ajouter une information</title>
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
                padding: 0 0;
                list-style-position: inside;
            }

            li {
                margin: 4px 10px;
                padding: 0 0;
                list-style-type: none;
                position: relative;
                font-size: 18px;
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

    include_once '../../function/bdd.php';

    ?>


    <div style="margin-bottom: 20px">
        <a href="../accueil.php?page=2" style="color: black; text-decoration: none; display: inline-block">
            <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
            <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
        </a>
    </div>

    <p style="font-size: 24px; display: inline-block; margin: 0;">Création d'une information</p>

    <div class="interface">
        <?php

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

        if (isset($_GET['succes'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            L'information a bien été ajoutée.
                        </p>
                        <a href="#" onclick="closePopup()">OK parfait !</a>
                    </div>
                </div>
            </div>
            <?php
        }

        ?>
            <div style="display: grid; grid-template-rows: 1fr 3fr">
                <div>
                    <form action="creationInformation.php" method="GET">
                        <div style="border: 1px solid white; border-radius: 10px; box-shadow: 0 0 5px 5px lightgrey; padding: 20px; display: flex; flex-direction: row; justify-content: start; align-items: center">
                            <div style="display: flex; justify-content: start; align-items: center; margin-right: 20px">
                                <label for="nomUser">Nom :</label>
                                <div>
                                    <input type="text" name="nomUser" id="nomUser">
                                </div>
                            </div>
                            <div style="display: flex; justify-content: start; align-items: center; margin-right: 20px">
                                <label for="prenomUser">Prénom :</label>
                                <div>
                                    <input type="text" name="prenomUser" id="prenomUser">
                                </div>
                            </div>
                            <div>
                                <input type="submit" value="rechercher" style="margin: 0">
                            </div>
                        </div>
                    </form>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 3fr; margin-top: 1px">
                    <div style="border: 1px solid white; border-radius: 10px; box-shadow: 0 0 5px 5px lightgrey; padding: 20px; display: inline-block; overflow: auto; height: 80%">
                        <form action="creationInformationPost.php" method="POST">
                            <ul>
                                <li><input type="checkbox" id="all" name="tout"><label for="all">Tout sélectionner</label></li>
                                <hr>

                                <?php

                                $listUser = $listUser = $bdd->query("SELECT id, nom, prenom FROM User");

                                if(!empty($_GET['nomUser']) AND !empty($_GET['prenomUser']))
                                    $listUser = $bdd->query("SELECT id, nom, prenom FROM User WHERE nom LIKE '%" . $_GET['nomUser'] . "%' AND prenom LIKE '%" . $_GET['prenomUser'] . "%'");
                                elseif(!empty($_GET['nomUser']))
                                    $listUser = $bdd->query("SELECT id, nom, prenom FROM User WHERE nom LIKE '%" . $_GET['nomUser'] . "%'");
                                elseif(!empty($_GET['prenomUser']))
                                    $listUser = $bdd->query("SELECT id, nom, prenom FROM User WHERE prenom LIKE '%" . $_GET['prenomUser'] . "%'");

                                while($user = $listUser->fetch()) {
                                    ?>
                                    <li>
                                        <input id="userId<?= $user['id'] ?>" type="checkbox" name="userId<?= $user['id'] ?>">
                                        <label for="userId<?= $user['id'] ?>"><?= strtoupper($user['nom']) ?> <?= $user['prenom'] ?></label>
                                    </li>
                                    <?php
                                }

                                ?>

                            </ul>
                    </div>
                    <div style="margin: 0; padding: 20px 20px; font-size: 20px; text-align: center">
                        <div style="display: inline-block; text-align: left; width: 30%">
                            <div>
                                <div>
                                    <label for="message">Message :</label>
                                </div>
                                <textarea name="message" id="message" required></textarea>
                            </div>
                            <div>
                                <div>
                                    <label for="description">Description :</label>
                                </div>
                                <textarea name="description" id="description" required></textarea>
                            </div>
                            <div>
                                <label for="fin">Date de fin :</label>
                                <input type="date" name="fin" id="fin" style="font-size: 20px">
                            </div>
                            <div>
                                <input type="submit" value="Créer l'information">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="../accueil.php?page=2">retour</a>

    <script type="text/javascript">
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    </body>
    </html>
<?php  } ?>