<?php

function differenceHeures($temps1, $temps2): string {

    $secTemps1 = (int)($temps1[0] . $temps1[1]) * 3600 + (int)($temps1[3] . $temps1[4]) * 60 + (int)($temps1[6] . $temps1[7]);
    $secTemps2 = (int)($temps2[0] . $temps2[1]) * 3600 + (int)($temps2[3] . $temps2[4]) * 60 + (int)($temps2[6] . $temps2[7]);

    if ($secTemps1 < $secTemps2) {
        $difference = $secTemps2 - $secTemps1;
    }
    else {
        $difference = $secTemps2 + 24 * 60 * 60 - $secTemps1;
    }

    $resultatHeure = (int)($difference/3600);
    $difference -= $resultatHeure * 3600;
    $resultatMin = (int)($difference/60);
    $difference -= $resultatMin * 60;

    if ($resultatHeure < 10) {
        $resultat = "0" . $resultatHeure . ":";
    }
    else {
        $resultat = $resultatHeure . ":";
    }

    if ($resultatMin < 10) {
        $resultat .= "0" . $resultatMin . ":";
    }
    else {
        $resultat .= $resultatMin . ":";
    }

    if ($difference < 10) {
        $resultat .= "0" . $difference;
    }
    else {
        $resultat .= $difference;
    }

    return $resultat;
}

function differenceHeuresEnSecondes($temps1, $temps2): int {

    $secTemps1 = (int)($temps1[0] . $temps1[1]) * 3600 + (int)($temps1[3] . $temps1[4]) * 60 + (int)($temps1[6] . $temps1[7]);
    $secTemps2 = (int)($temps2[0] . $temps2[1]) * 3600 + (int)($temps2[3] . $temps2[4]) * 60 + (int)($temps2[6] . $temps2[7]);

    if ($secTemps1 < $secTemps2) {
        $difference = $secTemps2 - $secTemps1;
    }
    else {
        $difference = $secTemps2 + 24 * 60 * 60 - $secTemps1;
    }

    return $difference;
}

function getHeureEnSeconde($temps): int {
    return (int)($temps[0] . $temps[1]) * 3600 + (int)($temps[3] . $temps[4]) * 60 + (int)($temps[6] . $temps[7]);
}

function getSecondeEnHeure($temps): string {
    $resultatHeure = (int)($temps/3600);
    $temps -= $resultatHeure * 3600;
    $resultatMin = (int)($temps/60);
    $temps -= $resultatMin * 60;

    if ($resultatHeure < 10) {
        $resultat = "0" . $resultatHeure . ":";
    }
    else {
        $resultat = $resultatHeure . ":";
    }

    if ($resultatMin < 10) {
        $resultat .= "0" . $resultatMin . ":";
    }
    else {
        $resultat .= $resultatMin . ":";
    }

    if ($temps < 10) {
        $resultat .= "0" . $temps;
    }
    else {
        $resultat .= $temps;
    }

    return $resultat;
}

function tempsEntreDeuxDateEtHeure($datetime1, $datetime2): int {
    return abs(strtotime($datetime1) - strtotime($datetime2));
}