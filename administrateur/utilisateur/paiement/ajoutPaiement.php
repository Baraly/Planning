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
            <title>Ajout d'un paiement</title>
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
                    bottom: 150px;
                    text-align: center;
                    border-radius: 10px;
                    overflow: auto;
                }

                .info > div {
                    position: relative;
                    height: 100%;
                }

                .form {
                    font-size: 24px;
                }

                .form > div {
                    margin: 10px 0;
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

                input[type="tel"] {
                    width: 40px;
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

        if (isset($_GET['error'])) {
            ?>
            <div class="overlay" id="overlay">
                <div class="subOverlay">
                    <div class="content">
                        <p>
                            <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                            <br>
                            La base de données n'a pas voulu ajouter le paiement.
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
                            Le paiement a été ajoutée avec succès !
                            <br>
                            L'abonnement commencera à partir du <?= date('d/m/Y', strtotime($_GET['dateDebutAbonnement'])) ?>.
                        </p>
                        <a href="#" onclick="closePopup()">Parfait !</a>
                    </div>
                </div>
            </div>
        <?php
        }
            ?>

            <p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?> - Ajout d'un paiement</p>

            <div class="interface">
                <div class="name">
                    <p style="margin-bottom: 8px">Nom : <?= strtoupper($userInfo['nom']) ?></p>
                    <p>Prénom : <?= $userInfo['prenom'] ?></p>
                </div>

                <form action="paiementPost.php?idUser=<?= $_GET['idUser'] ?>" method="POST">
                    <div class="form" style="margin: 0; padding: 20px 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="montant">Montant :</label>
                            </div>
                            <div style="text-align: left">
                                <input type="tel" name="montant" id="montant" required> €
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="mois">Durée de l'abonnement :</label>
                            </div>
                            <div style="text-align: left">
                                <select name="mois" id="mois">
                                    <?php

                                    $listeDuree = [1, 2, 3, 6, 9, 12, 24];

                                    foreach ($listeDuree as $duree)
                                        echo "<option value='$duree'>$duree mois</option>";

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr">
                            <div style="margin-right: 6px; display: flex; align-items: center; justify-content: flex-end">
                                <label for="etat">État :</label>
                            </div>
                            <div style="text-align: left">
                                <select name="etat" id="etat">
                                    <option value="EXECUTE MANUELLE">Exécution manuelle</option>
                                    <option value="EN ATTENTE">En attente</option>
                                    <option value="EXONERATION">Exonération</option>
                                </select>
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 20px">
                            <input type="submit" value="Ajouter le paiement">
                        </div>
                    </div>
                </form>
            </div>
            <a class="button" style="position: absolute; left: 4%; bottom: 10%" href="historiquePaiement.php?idUser=<?= $_GET['idUser'] ?>">retour</a>
            <script type="text/javascript">
                function closePopup() {
                    document.getElementById("overlay").style.display = "none";
                }
            </script>
            </body>
            </html>
            <?php
        }
    }
}
?>