<?php

session_start();

if (!isset($_SESSION['adminAccess'])){
    header("location: index.php");
}
else {

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administrateur Planning</title>
    <style>
        @font-face {
            font-family: myFirstFont;
            src: url(../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin: 10px 20px;
        }

        .interface {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-template-rows: 48% 48%;
            grid-gap: 30px;
            position: absolute;
            bottom: 5%;
            top: 72px;
            left: 40px;
            right: 40px;
        }

        .etiquette {
            display: grid;
            grid-template-rows: 44px auto 60px;
            grid-gap: 10px;
            border: 1px solid white;
            border-radius: 10px;
            box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
            text-align: center;
            margin: 0 0;
        }

        .title {
            display: inline-block;
            margin: 10px 0;
            padding: 0 0;
        }

        .button {
            text-align: center;
        }

        .button a {
            text-align: center;
            display: inline-block;
            border: 1px dashed #212F3D;
            border-radius: 10px;
            font-size: 22px;
            color: #212F3D;
            text-decoration: none;
            padding: 10px 100px;
            margin-bottom: 20px;
        }

        .etiquette div {
            font-size: 22px;
        }

        hr {
            margin: 0 0;
        }

        .listInfos {
            margin: 0 0;
            text-align: left;
            overflow: auto;
        }

        ul {
            margin: 0 0;
            padding: 0 0;
            list-style-position: inside;
        }

        li {
            margin: 4px 20px;
            padding: 0 0;
            list-style-type: none;
            position: relative;
            font-size: 22px;
        }
        li p {
            margin: 0 0;
            padding: 0 0;
            display: inline-block;
        }

        li span {
            position: absolute;
            right: 0;
            color: rgba(0, 0, 0, 0.5);
            margin: 0 0;
            padding: 0 0;
        }

        li a {
            color: black;
            text-decoration: none;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div style="margin-bottom: 20px">
    <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
    <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
</div>
<div class="interface">
    <?php

    $bdd = null;

    include_once '../function/bdd.php';

    ?>
    <div class="etiquette" style="grid-column: 1/3; grid-row: 1/3">
        <div style="margin: 0 10px; padding: 0; font-size: 24px">
            <p class="title">Liste des Utilisateurs</p>
            <hr>
        </div>
        <div class="listInfos">
            <ul>
                <?php

                $requete = $bdd->query("SELECT * FROM User");

                while ($donnees = $requete->fetch()) {
                    echo "<li><a href='utilisateur/index.php?idUser=" . $donnees['id'] . "'>" . strtoupper($donnees['nom']) . " " . $donnees['prenom'] . "</a></li>";
                }
                ?>
                <li style="color: white">espace</li>
                <li style="color: lightgrey">
                    Nombre d'utilisateurs :
                    <?php

                    $nbUser = $bdd->query("SELECT COUNT(*) AS nbUser FROM User")->fetch();
                    echo $nbUser['nbUser'];

                    ?>
                </li>
            </ul>
        </div>
        <div class="button">
            <a href="utilisateur/ajout/ajoutUtilisateur.php">AJOUTER</a>
        </div>
    </div>
    <div class="etiquette" style="grid-column: 3/5; grid-row: 1/2">
        <div style="margin: 0 10px; padding: 0; font-size: 24px;">
            <p class="title">Liste des Entreprises</p>
            <hr>
        </div>
        <div class="listInfos">
            <ul>
                <?php

                $nomSociete = $bdd->query("SELECT nomSociete FROM Societe");

                while ($societe = $nomSociete->fetch()) {
                    $nbUser = $bdd->query("SELECT COUNT(*) AS nbUser FROM User, Societe WHERE idSociete = Societe.id AND Societe.nomSociete = '" . $societe['nomSociete'] . "'")->fetch();
                    echo "<li><p><a href='societe/infoSociete.php?nomSociete=" . $societe['nomSociete'] . "'>" . strtoupper($societe['nomSociete']) . "</a></p><span>" . $nbUser['nbUser'] . " utilisateurs</span></li>";
                }
                ?>
            </ul>
        </div>
        <div class="button">
            <a href="societe/ajoutSociete.php">AJOUTER</a>
        </div>
    </div>
    <div class="etiquette" style="grid-column: 5/7; grid-row: 1/2; grid-template-rows: 44px auto 2px">
        <div style="margin: 0 10px; padding: 0; font-size: 24px;">
            <p class="title">Liste des Requêtes</p>
            <hr>
        </div>
        <div class="listInfos">
            <ul>
                <?php

                $requete = $bdd->query("SELECT Requete.id AS id, nom, prenom, User.id AS ID FROM Requete, User WHERE Requete.idUser = User.id AND dateTraitement IS NULL");

                $rien = true;

                while ($donnees = $requete->fetch()) {
                    $rien = false;

                    echo "<li><p><a href='utilisateur/requete/infoRequete.php?idUser=" . $donnees['ID'] . "&idRequete=" . $donnees['id'] . "' style='margin: 0; padding: 0; color: black; text-decoration: none'>N°" . $donnees['id'] . " " . strtoupper($donnees['nom']) . " " . strtoupper($donnees['prenom'])[0] . ".</a></p></li>";
                }

                if ($rien) {
                    echo "<li><p>Aucunes requêtes</p></li>";
                }

                ?>
            </ul>
        </div>
        <div></div>
    </div>
    <div class="etiquette" style="grid-column: 3/6; grid-row: 2/3">
        <div style="margin: 0 10px; padding: 0; font-size: 24px">
            <p class="title">Liste des Événements</p>
            <hr>
        </div>
        <div class="listInfos">
            <ul>
                <?php

                $requete = $bdd->query("SELECT * FROM Evenement ORDER BY dateEvenement DESC");

                $rien = true;

                while ($donnees = $requete->fetch()) {
                    $rien = false;
                    if($donnees['important'])
                        echo "<a style='color: black' href='evenement/infoEvenement.php?id=" . $donnees['id'] . "&from=accueil' style='color: black'><li style='margin-bottom: 10px'><p>" . $donnees['type'] . " <span style='position: relative; color: white; background-color: red; padding: 3px 14px; border-radius: 10px; font-size: 20px'>important</span></p></li></a>";
                    else
                        echo "<a style='color: black' href='evenement/infoEvenement.php?id=" . $donnees['id'] . "&from=accueil' style='color: black'><li style='margin-bottom: 10px'><p>" . $donnees['type'] . "</p></li></a>";
                }

                if ($rien) {
                    echo "<li><p>Aucuns évènements</p></li>";
                }

                ?>
            </ul>
        </div>
        <div class="button">
            <a href="evenement/historique.php">HISTORIQUE</a>
        </div>
    </div>
</div>
</body>
</html>
<?php } ?>