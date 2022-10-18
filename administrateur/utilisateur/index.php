<?php

session_start();

if (!isset($_GET['idUser'])) {
    header("location: ../accueil.php");
}
else {

    if (!isset($_SESSION['adminAccess'])){
        header("location: ../index.php?url=utilisateur/index.php?idUser=" . $_GET['idUser']);
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
            src: url(../../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin: 10px 20px;
        }

        h2 {
            margin: 4px 0;
            padding: 0 0;
        }
        
        .interface {
             display: grid;
             grid-template-columns: 2fr 1fr 1fr;
             grid-template-rows: 56% 36%;
             grid-gap: 40px;
             position: absolute;
             top : 110px;
             bottom: 5%;
             left: 20px;
             right: 20px;
        }
        
        .interface > div {
            border: 1px solid white;
            box-shadow: 0 0 5px 5px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
        }
        
        .interface p {
             font-size: 22px;
             padding: 0;
        }

        .info p {
            margin: 4px 0;
        }

        .title {
            display: inline-block;
            margin: 6px 0;
            padding: 0 0;
        }

        hr {
            margin: 0;
            padding: 0;
        }

        .button {
            display: inline-block;
            color: #4169E1;
            font-size: 22px;
            text-decoration: none
        }

        ul {
            margin: 0;
            padding: 0 10px;
            overflow: auto;
        }

        li {
            margin: 0;
            padding: 0;
            list-style-type: none;
            position: relative;
            font-size: 22px;
        }

        li p, li span {
            display: inline-block;
            padding: 0;
            margin: 0;
        }

        <?php

        if(isset($_GET['popup']) or isset($_GET['action'])) {
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
include_once '../../function/fonctionMois.php';

$userInfo = $bdd->query("SELECT * FROM User WHERE id = " . $_GET['idUser'])->fetch();

?>

<div style="margin-bottom: 20px"> 
    <a href="../accueil.php" style="color: black; text-decoration: none; display: inline-block">
        <h1 style="display: inline-block; padding: 0 0; margin: 0 20px 0 0; font-size: 30px">Planning</h1>
        <p style="display: inline-block; color: rgba(0, 0, 0, 0.5); font-size: 24px; margin: 0 0; padding: 0 0">Espace administrateur</p>
    </a>
</div>

<?php

if (!empty($_GET['idUser'])) {

    ?>

<p style="font-size: 24px; display: inline-block; margin: 0;">Utilisateur N° <?= $_GET['idUser'] ?></p>

<div class="interface">
    <div style="display: grid; grid-template-rows: auto 40px; margin: 0; padding: 0">
        <div style="padding: 10px 10px; overflow: auto" class="info">
            <p>Nom : <?= strtoupper($userInfo['nom']) ?></p>
            <p>Prénom : <?= $userInfo['prenom'] ?></p>
            <p>
                E-mail : <?= $userInfo['email'] ?>
                <?php
                if ($userInfo['verifie'] == 0)
                    echo "<br><span style='color: red; font-size: 20px; margin-bottom: 4px'>attention : adresse non vérifiée</span>";
                ?>
            </p>
            <p>Tel : <?= $userInfo['telephone'] ?></p>

            <br>

            <p>Inscription : <?= (int)date('d', strtotime($userInfo['inscription'])) ?> <?= mois(date('m', strtotime($userInfo['inscription']))) ?> <?= date('Y', strtotime($userInfo['inscription'])) ?></p>
            <p>
                Entreprise :
                <?php

                $societe = $bdd->query("SELECT nomSociete FROM User, Societe WHERE User.idSociete = Societe.id AND User.id = '" . $userInfo['id'] . "'")->fetch();

                if ($societe['nomSociete'])
                    echo strtoupper($societe['nomSociete']);
                else
                    echo "aucune";

                ?>
            </p>
            <p>
                Préférences :
                <?php

                if ($userInfo['preferenceEmail'])
                    echo "accepte les mails";
                else
                    echo "refuse les mails";

                ?>
            </p>
            <p>
                Nouveau design :
                <?php

                if ($userInfo['ancienPlanning'] == 0)
                    echo "accepte les changements";
                else
                    echo "refuse les changements";

                ?>
            </p>
        </div>
        <div style="text-align: center; margin: 0; padding: 6px 0;">
            <a href="modifie/modifieUtilisateur.php?idUser=<?= $_GET['idUser'] ?>" class="button">modifier les informations</a>
        </div>
    </div>
    <div style="display: grid; grid-template-rows: 50px auto 40px; margin: 0; padding: 0; text-align: center">
        <div style="margin: 0; font-size: 24px; padding: 0 10px;">
            <p class="title">Liste des Connexions</p>
            <hr>
        </div>
        <ul>
            <?php

            $requete = $bdd->query("SELECT dateConnexion FROM Connexion WHERE idUser = '" . $_GET['idUser'] . "' ORDER BY dateConnexion DESC LIMIT 10");

            $rien = true;

            while($donnees = $requete->fetch()) {
                $rien = false;
                echo "<li><p>" . date('d', strtotime($donnees['dateConnexion'])) . "/" . date('m', strtotime($donnees['dateConnexion'])) . "/" . date('Y', strtotime($donnees['dateConnexion'])) . " " . date('H', strtotime($donnees['dateConnexion'])) . ":" . date('i', strtotime($donnees['dateConnexion'])) . ":" . date('s', strtotime($donnees['dateConnexion'])) . "</p></li>";
            }

            if ($rien)
                echo "<li><p style='text-align: center'>Aucune connexion</p></li>";

            ?>
        </ul>
        <div style="margin: 0; padding: 6px 0;">
            <a href="connexion/historiqueConnexion.php?idUser=<?= $_GET['idUser'] ?>" class="button">historique</a>
        </div>
    </div>
    <div style="display: grid; grid-template-rows: 50px auto 40px; margin: 0; padding: 0; text-align: center">
        <div style="margin: 0; font-size: 24px; padding: 0 10px;">
            <p class="title">Liste des Requêtes</p>
            <hr>
        </div>
        <ul>
            <?php

            //$requete = $bdd->query("SELECT dateReception, dateTraitement, id FROM Requete WHERE idUser = '" . $_GET['idUser'] . "' ORDER BY dateReception DESC LIMIT 20");
            $requete = $bdd->query("SELECT dateOuverture, dateCloture, id FROM Requete WHERE idUser = '" . $_GET['idUser'] . "' ORDER BY dateOuverture DESC LIMIT 20");

            $rien = true;

            while($donnees = $requete->fetch()) {
                $rien = false;

                $etat = "";

                if ($donnees['dateCloture'])
                    $etat .= "<span style='position: absolute; right: 10px; color: #8CCD75'>traitée</span>";
                else
                    $etat .= "<span style='position: absolute; right: 10px; color: #EF5050'>à traiter</span>";

                echo "<li style='position: relative; text-align: left'><p><a href='requete/infoRequete.php?idUser=" . $_GET['idUser'] . "&idRequete=" . $donnees['id'] . "' style='margin: 0; padding: 0; color: black; text-decoration: none'>" . date('d/m/Y', strtotime($donnees['dateOuverture'])) . "</a></p>" . $etat . "</li>";
            }

            if ($rien)
                echo "<li><p style='text-align: center'>Aucune requête</p></li>";

            ?>
        </ul>
        <div style="margin: 0; padding: 6px 0;">
            <a href="requete/historiqueRequete.php?idUser=<?= $_GET['idUser'] ?>" class="button">historique</a>
        </div>
    </div>

    <div style="display: grid; grid-template-rows: 50px auto 40px; margin: 0; padding: 0; text-align: center">
        <div style="margin: 0; font-size: 24px; padding: 0 10px;">
            <p class="title">Journées</p>
            <hr>
        </div>
        <ul style="margin: 0 10px">
            <?php

            $requete = $bdd->query("SELECT YEAR(datage) AS annee, COUNT(*) AS nbJournee FROM Horaire WHERE idUser = '" . $_GET['idUser'] . "' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY annee ORDER BY annee DESC");

            $rien = true;

            while($donnees = $requete->fetch()) {
                $rien = false;

                echo "<li style='text-align: left'><p>" . $donnees['annee'] . " : " . $donnees['nbJournee'] . " journées</p></li>";
            }

            if ($rien)
                echo "<li><p style='text-align: center'>Aucune journée</p></li>";

            ?>
        </ul>
        <div style="margin: 0; padding: 6px 0; display: grid; grid-template-columns: 1fr 1fr 1fr">
            <div style="text-align: center">
                <a href="journee/ajouter/ajouter.php?idUser=<?= $_GET['idUser'] ?>" class="button">ajouter</a>
            </div>
            <div style="text-align: center">
                <a href="journee/modifier/modifier.php?idUser=<?= $_GET['idUser'] ?>" class="button">modifier</a>
            </div>
            <div style="text-align: center">
                <a href="journee/historique/historique.php?idUser=<?= $_GET['idUser'] ?>" class="button">historique</a>
            </div>
        </div>
    </div>
    <div style="display: grid; grid-template-rows: 50px auto 6px; margin: 0; padding: 0; text-align: center">
        <div style="margin: 0; font-size: 24px; padding: 0 10px;">
            <p class="title">Actions</p>
            <hr>
        </div>
        <div style="margin: 0; display: flex; flex-direction: column; justify-content: space-between">
            <div style="text-align: center; margin: 0; padding: 0">
                <?php
                if($userInfo['verifie'])
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=modifierCode' class='button'>modifier le code</a>";
                else
                    echo "<a href='#' style='color: rgba(65, 105, 225, 0.4)' class='button'>modifier le code</a>";
                ?>
            </div>
            <div style="text-align: center; margin: 0; padding: 0">
                <?php
                if($userInfo['bloquer'])
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=debloquer' class='button'>débloquer</a>";
                else
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=bloquer' class='button'>bloquer</a>";
                ?>
            </div>
            <div style="text-align: center; margin: 0; padding: 0">
                <?php
                if($userInfo['desactiver'])
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=reactiver' class='button'>réactiver</a>";
                else
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=desactiver' class='button'>désactiver</a>";
                ?>
            </div>
        </div>
        <div></div>
    </div>
    <div style="display: grid; grid-template-rows: 50px auto 6px; margin: 0; padding: 0; text-align: center">
        <div style="margin: 0; font-size: 24px; padding: 0 10px;">
            <p class="title">Mails</p>
            <hr>
        </div>
        <div style="margin: 0; display: flex; flex-direction: column; justify-content: space-between">
            <div style="text-align: center; margin: 0; padding: 0">
                <a href="envoieMail/envoyerMail.php?idUser=<?= $_GET['idUser'] ?>" class="button">envoyer un mail</a>
            </div>
            <div style="text-align: center; margin: 0; padding: 0">
                <?php

                if($userInfo['verifie'])
                    echo "<a href='#' class='button' style='color: rgba(65, 105, 225, 0.4)'>vérifier l'e-mail</a>";
                else
                    echo "<a href='index.php?idUser=" . $_GET['idUser'] . "&popup=verifier' class='button'>vérifier l'e-mail</a>";

                ?>
            </div>
            <div style="text-align: center; margin: 0; padding: 0">
                <a class="button" style="color: white">désactiver</a>
            </div>
        </div>
        <div></div>
    </div>
</div>
    <?php

    if(isset($_GET['popup'])) {
        $message = "";

        if($_GET['popup'] == 'modifierCode')
            $message = "Voulez-vous envoyer une demande de modification de code ?";
        elseif($_GET['popup'] == 'debloquer')
            $message = "Voulez-vous débloquer le compte de cet utilisateur ?";
        elseif($_GET['popup'] == 'bloquer')
            $message = "Voulez-vous bloquer le compte de cet utilisateur ?";
        elseif($_GET['popup'] == 'reactiver')
            $message = "Voulez-vous réactiver le compte de cet utilisateur ?";
        elseif($_GET['popup'] == 'desactiver')
            $message = "Voulez-vous désactiver le compte de cet utilisateur ?";
        elseif($_GET['popup'] == 'verifier')
            $message = "Voulez-vous envoyer une demande de vérification de compte ?";

        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        Vous êtes sur le point d'envoyer un e-mail.
                        <br><br>
                        <?= $message ?>
                    </p>
                    <div  style="display: grid; grid-template-columns: 1fr 1fr; justify-content: space-around">
                        <div>
                            <a href="#" onclick="closePopup()">annuler</a>
                        </div>
                        <div>
                            <a href="action.php?idUser=<?= $_GET['idUser'] ?>&action=<?= $_GET['popup'] ?>">approuver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if(isset($_GET['action'])) {
        $message = "";

        if($_GET['action'] == 'modifierCode' and isset($_GET['succes']))
            $message = "Un e-mail a été envoyé à cet utilisateur pour réinitialiser sa clé personnelle !";
        elseif($_GET['action'] == 'modifierCode' and isset($_GET['error']))
            $message = "Un e-mail n'a pas pu être envoyé à cet utilisateur pour réinitialiser sa clé personnelle !";

        if($_GET['action'] == 'debloquer' and isset($_GET['succes']))
            $message = "Cet utilisateur a bien été débloqué !";
        elseif($_GET['action'] == 'debloquer' and isset($_GET['error']))
            $message = "Cet utilisateur n'a pas pu être débloqué !";

        if($_GET['action'] == 'bloquer' and isset($_GET['succes']))
            $message = "Cet utilisateur a bien été bloqué !";
        elseif($_GET['action'] == 'bloquer' and isset($_GET['error']))
            $message = "Cet utilisateur n'a pas pu être bloqué !";

        if($_GET['action'] == 'reactiver' and isset($_GET['succes']))
            $message = "Cet utilisateur a bien été réactivé !";
        elseif($_GET['action'] == 'reactiver' and isset($_GET['error']))
            $message = "Cet utilisateur n'a pas pu être réactivé !";

        if($_GET['action'] == 'desactiver' and isset($_GET['succes']))
            $message = "Cet utilisateur a bien été désactivé !";
        elseif($_GET['action'] == 'desactiver' and isset($_GET['error']))
            $message = "Cet utilisateur n'a pas pu être désactivé !";

        if($_GET['action'] == 'verifier' and isset($_GET['succes']))
            $message = "Un e-mail a été envoyé à cet utilisateur pour vérifier som compte !";
        elseif($_GET['action'] == 'verifier' and isset($_GET['error']))
            $message = "Un e-mail n'a pas pu être envoyé à cet utilisateur pour vérifier som compte !";
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        <?php

                        if(isset($_GET['error'])) {
                            ?>
                            <span style="font-weight: bold; color: red">Un problème est survenu</span><br>
                            <?php
                        }

                        echo $message;
                        ?>
                    </p>
                    <div  style="text-align: center">
                    <?php
                    if(isset($_GET['error']))
                        echo "<a href='#' onclick='closePopup()'>Mince OK</a>";
                    else
                        echo "<a href='#' onclick='closePopup()'>OK parfait !</a>";
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
?>

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
?>