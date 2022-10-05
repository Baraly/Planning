<?php

$bdd = null;

include_once '../../function/bdd.php';

if(isset($_GET['cloture'])) {
    $bdd->exec("UPDATE MessageInfo SET dateCloture = CURDATE() WHERE id = '" . $_GET['idInfo'] . "'");
    header("location: detailInformation.php?idInfo=" . $_GET['idInfo']);
}
else {
    $messageForm = str_replace("\r\n", "<br />", $_POST['message']);
    $messageForm = str_replace("\n\r", "<br />", $_POST['message']);
    $messageForm = str_replace("\n", "<br />", $_POST['message']);
    $messageForm = str_replace("\r", "<br />", $_POST['message']);

    if(!empty($_POST['fin']))
        $bddOK = $bdd->exec("INSERT INTO MessageInfo (message, description, dateMessage, dateCloture) VALUES ('$messageForm', '" . $_POST['description'] . "', CURDATE(), '" . $_POST['fin'] . "')");
    else
        $bddOK = $bdd->exec("INSERT INTO MessageInfo (message, description, dateMessage) VALUES ('$messageForm', '" . $_POST['description'] . "', CURDATE())");

    if($bddOK) {
        $idMessage = $bdd->query("SELECT id FROM MessageInfo WHERE message = '$messageForm' AND dateMessage = CURDATE()")->fetch();
        $listUser = $listUser = $bdd->query("SELECT id FROM User");

        while($user = $listUser->fetch()) {
            if(isset($_POST['userId' . $user['id']]) or isset($_POST['tout']))
                $bdd->exec("INSERT INTO LuMessageInfo (idMessageInfo, idUser) VALUES ('" . $idMessage['id'] . "', '" . $user['id'] . "')");
        }

        header('location: creationInformation.php?succes');
    }
    else
        header('location: creationInformation.php?error=BDD');
}