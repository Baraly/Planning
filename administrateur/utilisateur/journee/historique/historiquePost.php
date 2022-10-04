<?php

$idUser = $_GET['idUser'];

$bdd = null;

include_once '../../../../function/bdd.php';
include_once '../../../../function/fonctionMois.php';

$infoUser = $bdd->query("SELECT nom, prenom, email, genre FROM User WHERE id = '$idUser'")->fetch();

use Dompdf\Dompdf;

ob_start();

$url = 'modele.php';

require_once $url;

$html = ob_get_contents();

ob_end_clean();

include_once '../../../../dompdf/autoload.inc.php';

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portait');

$dompdf->render();

$nomFichier = "Relevé_Heures_" . $infoUser['nom'] . "_" . $infoUser['prenom'] . ".pdf";

$output = $dompdf->output();

file_put_contents($nomFichier, $output);

$fichier = file_get_contents($nomFichier);

if($fichier) {

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

    require '../../../../PHPMailer/src/Exception.php';

    require '../../../../PHPMailer/src/PHPMailer.php';
    require '../../../../PHPMailer/src/SMTP.php';
    include_once '../../../../function/mail.php';

    $nom = getGenre($infoUser['genre']) . $infoUser['nom'];

    $message = "
        <html>
        <head>
            <style>
                body {
                    margin: 10px;
                }
                span {
                    font-style: italic;
                }
            </style>
        </head>
        <body>
            <h2>Bonjour $nom,</h2>
            <p>
            Veuillez trouver en pièce jointe un fichier PDF reprennant des informations sur votre historique <span>Planning</span>.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        </html>
        ";

    if (EnvoyerMailAvecFichier($infoUser['email'], $infoUser['nom'], $infoUser['prenom'], "Envoie de l'historique Planning", $message, $nomFichier)) {
        If (unlink($nomFichier)) {
            header("location: historique.php?idUser=" . $idUser . "&succes");
        } else {
            header("location: historique.php?idUser=" . $idUser . "&error=supprimerFichier");
        }
    }
    else
        header("location: historique.php?idUser=" . $idUser . "&error=mail");
}
else
    header("location: historique.php?idUser=" . $idUser . "&error=fichier");
?>