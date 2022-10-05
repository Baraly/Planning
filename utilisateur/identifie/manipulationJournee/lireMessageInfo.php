<?php

session_start();

$bdd = null;
include_once '../../../function/bdd.php';

$id = $_SESSION['id'];

if(isset($_GET['updateInformation'])) {
    $bdd->exec("UPDATE LuMessageInfo SET dateLecture = NOW() WHERE idUser = '$id' AND idMessageInfo = '" . $_GET['updateInformation'] . "'");
    header('location: commencer/commencerJournee.php');
}