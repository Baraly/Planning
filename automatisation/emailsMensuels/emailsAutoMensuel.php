<?php

$bdd = new PDO('mysql:host=91.234.194.113;dbname=cp1699398p07_planning;charset=utf8','cp1699398p07_admin','sh)q.sz1j7Ry');

include_once '../../function/fonctionMois.php';
include_once '../../function/fonctionHeures.php';

use Dompdf\Dompdf;

$listeUser = $bdd->query("SELECT id, nom, prenom, genre, email FROM User WHERE preferenceEmail = 1");

$mois = (int)date('m',strtotime("-1 days"));
$annee = (int)date('Y',strtotime("-1 days"));

$nbMail = 0;

while ($user = $listeUser->fetch()) {

    ob_start();

    $url = 'contenuPDF.php';

    $request = $bdd->query("SELECT * FROM Horaire WHERE idUser = '" . $user['id'] . "' AND MONTH(datage) = '$mois' AND YEAR(datage) = '$annee' AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle)");

    require_once $url;

    $html = ob_get_contents();

    ob_end_clean();

    include_once '../../dompdf/autoload.inc.php';

    $dompdf = new Dompdf();

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portait');

    $dompdf->render();

    $nomFichier = "Relevé_Heures_" . $user['nom'] . "_" . $user['prenom'] . ".pdf";

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

        require '../../PHPMailer/src/Exception.php';

        require '../../PHPMailer/src/PHPMailer.php';
        require '../../PHPMailer/src/SMTP.php';
        include_once '../../function/mail.php';

        $nom = getGenre($user['genre']) . $user['nom'];

        if($mois == 4 or $mois == 8 or $mois == 10)
            $moisString = "d'". strtolower(mois($mois)) . " " . $annee;
        else
            $moisString = "de ". strtolower(mois($mois)) . " " . $annee;

        $lien = "https://baraly.fr/Test/Planning/verification.php?email=" . $user['email'] . "&preferenceEmail";

        $message = "
        <html>
        <head>
            <style>
                body {
                    margin: 10px;
                }
            </style>
        </head>
        <body>
            <h2>Bonjour $nom,</h2>
            <p>
            Veuillez trouver en pièce jointe votre fichier PDF mensuel reprennant vos journées du mois $moisString.
            </p>
            <p>
            Si vous souhaitez vous désabonner de ce service, veuillez cliquer sur le lien suivant : <a href='$lien'>Me désabonner</a>.
            </p>
            <p>Bien cordialement,<br>Le service informatique Planning</p>
        </body>
        </html>
        ";

        if(EnvoyerMailAvecFichier($user['email'], $user['nom'], $user['prenom'], "Envoie mensuel du Planning", $message, $nomFichier)) {
            $nbMail ++;
        }
    }
}

if($nbMail > 1)
    $bdd->exec("INSERT INTO Evenement (type, description) VALUES ('Envoie auto mails mensuel', '" . $nbMail . " mails ont été envoyés !')");
else
    $bdd->exec("INSERT INTO Evenement (type, description) VALUES ('Envoie auto mails mensuel', '" . $nbMail . " mail a été envoyé !')");


