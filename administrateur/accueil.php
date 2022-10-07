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
    <a href="accueil.php" style="color: black; text-decoration: none; display: inline-block">
        <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
        <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
    </a>
</div>
<div class="interface">
    <?php

    $bdd = null;

    include_once '../function/bdd.php';

    if(isset($_GET['page']) and $_GET['page'] == 2) {
        ?>
        <div class="etiquette" style="grid-column: 1/3; grid-row: 1/2">
            <div style="margin: 0 10px; padding: 0; font-size: 24px">
                <p class="title">Liste des Informations</p>
                <hr>
            </div>
            <div class="listInfos">
                <ul>
                    <?php

                    $requete = $bdd->query("SELECT * FROM MessageInfo ORDER BY dateMessage DESC");

                    while ($donnees = $requete->fetch()) {
                        if(!empty($donnees['dateCloture']) and floor((strtotime($donnees['dateCloture']) - strtotime(date('Y-m-d')))/(60*60*24)) <= 1)
                            echo "<li><a href='information/detailInformation.php?idInfo=" . $donnees['id'] . "'>" . date('d/m/Y', strtotime($donnees['dateMessage'])) . " <span style='color: #D35400; position: absolute; right: 0'>cloturé</span></a></li>";
                        else
                            echo "<li><a href='information/detailInformation.php?idInfo=" . $donnees['id'] . "'>" . date('d/m/Y', strtotime($donnees['dateMessage'])) . " <span style='color: #28B463; position: absolute; right: 0'>en cours</span></a></li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="button">
                <a href="information/creationInformation.php">AJOUTER</a>
            </div>
        </div>
        <div class="etiquette" style="grid-column: 3/5; grid-row: 1/2">
            <div style="margin: 0 10px; padding: 0; font-size: 24px;">
                <p class="title">Liste de ...</p>
                <hr>
            </div>
            <div class="listInfos">

            </div>
            <div class="button">
                <a href="#">AJOUTER</a>
            </div>
        </div>
        <div class="etiquette" style="grid-column: 5/7; grid-row: 1/2; grid-template-rows: 44px auto 2px">
            <div style="margin: 0 10px; padding: 0; font-size: 24px;">
                <p class="title">Liste de ...</p>
                <hr>
            </div>
            <div class="listInfos">

            </div>
            <div></div>
        </div>
        <div style="grid-column: 1/2; grid-row: 2/3; position: relative">
            <a href="accueil.php" style="height: 140px; border-radius: 10px; text-align: center; border: 1px solid white; box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25); text-decoration: none; color: royalblue; position: absolute; bottom: 0; left: 0; right: 0; font-size: 26px">
                <div style="display: inline-block; left: 50%; top: 50%; -ms-transform: translateY(50%); transform: translateY(50%);">
                    Page précédente
                </div>
            </a>
        </div>
        <div class="etiquette" style="grid-column: 2/5; grid-row: 2/3">
            <div style="margin: 0 10px; padding: 0; font-size: 24px">
                <p class="title">Liste des Blocages</p>
                <hr>
            </div>
            <div class="listInfos">
                <ul>
                    <?php

                    $requete = $bdd->query("SELECT * FROM BlockUser ORDER BY datage DESC");

                    $rien = true;

                    while ($donnees = $requete->fetch()) {
                        $rien = false;
                        if($donnees['nbTentative'] >= 5 and $donnees['estBloque'] == 1 and strtotime(date('Y-m-d', strtotime($donnees['datage'])) . " + ". $donnees['dureeBloquage'] . " days") >= strtotime(date('Y-m-d')))
                            echo "<a style='color: black' href='blocage/detailBlocage.php?id=" . $donnees['id'] . "' style='color: black'><li style='margin-bottom: 10px'><p>" . date('d/m/Y', strtotime($donnees['datage'])) . " - " . $donnees['ipAdresse'] . "<span style='position: absolute; color: #E74C3C; padding: 3px 14px; border-radius: 10px; font-size: 20px; right: 10px'>bloqué</span></p></li></a>";
                        elseif ($donnees['nbTentative'] >= 5 and $donnees['estBloque'] == 1 and strtotime(date('Y-m-d', strtotime($donnees['datage'])) . " + ". $donnees['dureeBloquage'] . " days") < strtotime(date('Y-m-d')))
                            echo "<a style='color: black' href='blocage/detailBlocage.php?id=" . $donnees['id'] . "' style='color: black'><li style='margin-bottom: 10px'><p>" . date('d/m/Y', strtotime($donnees['datage'])) . " - " . $donnees['ipAdresse'] . "<span style='position: absolute; color: #2ECC71; padding: 3px 14px; border-radius: 10px; font-size: 20px; right: 10px'>débloqué</span></p></li></a>";
                        elseif ($donnees['nbTentative'] >= 5 and $donnees['estBloque'] == 0)
                            echo "<a style='color: black' href='blocage/detailBlocage.php?id=" . $donnees['id'] . "' style='color: black'><li style='margin-bottom: 10px'><p>" . date('d/m/Y', strtotime($donnees['datage'])) . " - " . $donnees['ipAdresse'] . "<span style='position: absolute; color: #2ECC71; padding: 3px 14px; border-radius: 10px; font-size: 20px; right: 10px'>débloqué</span></p></li></a>";
                        else
                            echo "<a style='color: black' href='blocage/detailBlocage.php?id=" . $donnees['id'] . "' style='color: black'><li style='margin-bottom: 10px'><p>" . date('d/m/Y', strtotime($donnees['datage'])) . " - " . $donnees['ipAdresse'] . "</p></li></a>";
                    }

                    if ($rien) {
                        echo "<li><p>Aucun blocage</p></li>";
                    }

                    ?>
                </ul>
            </div>
        </div>
        <div class="etiquette" style="grid-column: 5/7; grid-row: 2/3">
            <div style="margin: 0 10px; padding: 0; font-size: 24px;">
                <p class="title">Liste de ...</p>
                <hr>
            </div>
            <div class="listInfos">

            </div>
            <div class="button">
                <a href="#">AJOUTER</a>
            </div>
        </div>
        <?php
    }
    else {
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
                        echo "<li><p>Aucune requête</p></li>";
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
                        echo "<li><p>Aucun évènement</p></li>";
                    }

                    ?>
                </ul>
            </div>
            <div class="button">
                <a href="evenement/historique.php">HISTORIQUE</a>
            </div>
        </div>
        <div style="grid-column: 6/7; grid-row: 2/3; position: relative">
            <a href="accueil.php?page=2" style="height: 140px; border-radius: 10px; text-align: center; border: 1px solid white; box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25); text-decoration: none; color: royalblue; position: absolute; bottom: 0; left: 0; right: 0; font-size: 26px">
                <div style="display: inline-block; left: 50%; top: 50%; -ms-transform: translate(0, 50%); transform: translate(0, 50%);">
                    Page suivante
                </div>
            </a>
        </div>
    <?php } ?>
</div>
</body>
</html>
<?php } ?>