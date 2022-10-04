<?php

$bdd = null;

include_once '../../function/bdd.php';

$nom = $_GET['nom'];
$nombreCoupure = $_GET['nombre'];

if ($bdd->query("SELECT nomSociete FROM Societe WHERE nomSociete LIKE '" . $nom . "'")->fetch()) {
    header("location: ajoutSociete.php?error=nom");
}
else {
    $error = false;

    for ($i = 1; $i < (int)$nombreCoupure; $i++) {
        for ($j = 2; $j > $i and $j <= (int)$nombreCoupure; $j++) {
            if (getMin($_POST['debut' . $i]) >= getMin($_POST['debut' . $j]) or getMin($_POST['fin' . $i]) >= getMin($_POST['fin' . $j]) or getMin($_POST['fin' . $i]) >= getMin($_POST['debut' . $j])) {
                $error = true;
            }
        }

        if (getMin($_POST['debut' . $i]) >= getMin($_POST['fin' . $i])) {
            $error = true;
        }
    }

    if ($error) {
        header("location: ajoutSociete.php?error=heure&nom=" . $_GET['nom'] . "&nombre=" . $_GET['nombre']);
    }
    else {
        $bdd->exec("INSERT INTO Societe VALUES (0, '" . ucfirst($nom) . "')");
        $idSociete = $bdd->query("SELECT id FROM Societe WHERE nomSociete = '" . ucfirst($nom) . "'")->fetch();

        $prb = false;
        for ($i = 0; $i < (int)$nombreCoupure; $i++) {
            if(!$bdd->exec("INSERT INTO Coupure VALUES ('" . $idSociete['id'] . "', '" . $_POST['debut' . ($i+1)] . ":00', '" . $_POST['fin' . ($i+1)] . ":00', '" . $_POST['coupure' . ($i+1)] . ":00')")) {
                $prb = true;
                header("location: ajoutSociete.php?error=bdd&nom=" . $_GET['nom'] . "&nombre=" . $_GET['nombre']);
            }
        }

        if (!$prb) {
            header("location: ajoutSociete.php?succes&nom=" . $_GET['nom'] . "&nombre=" . $_GET['nombre']);
        }
    }
}

function getMin($time) {
    return (int)date('H', strtotime($time)) * 60 + (int)date('i', strtotime($time));
}


?>