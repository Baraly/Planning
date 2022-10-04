<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF8"/>
    <title>Clé oubliée</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <style>
        @font-face {
            font-family: myFirstFont;
            src: url(../../font-style/ABeeZee-Regular.ttf);
        }
        body {
            font-family: myFirstFont;
            margin-top: 10%;
            text-align: center;
        }

        form {
            margin-top: 10%;
        }

        input {
            border: none;
            border-bottom: 1px solid black;
            outline-width: 0;
            box-shadow: none;
            padding: 0 0;
            border-radius: 0;
        }

        input[type="email"] {
            padding: 2% 1%;
            border: none;
            border-bottom: 1px gray solid;
            border-radius: 0;
            margin: 2% 0;
            width: 80%;
            font-size: 350%;
            text-align: left;
        }

        input[type="submit"] {
            -webkit-appearance: none;
            width: 60%;
            margin-top: 10%;
            font-size: 325%;
            padding: 3% 0;
            border: none;
            border-radius: 20px;
            color: white;
            background-color: #3B326C;
            box-shadow: #3B326C 0 60px 60px -40px;
        }

        <?php

        if(isset($_GET['status'])) {
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
        .content {
            width: 90%;
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
            font-size: 300%;
        }

        .content > a {
            display: inline-block;
            background-color: #212F3D;
            color: white;
            text-decoration: none;
            padding: 2% 4%;
            border-radius: 20px;
        }
            <?php
        }
        ?>
    </style>
</head>
<body>

<h1 style="font-size: 375%">Clé personnelle oubliée</h1>

<p style="font-size: 325%; margin-top: 20%">Pas de panique ! Il vous suffit juste de renseigner l'adresse e-mail que vous avez utilisé pour créer votre compte ^^</p>
<form action="cleOublieePost.php" method="post">
    <?php

    if(isset($_GET['email'])) {
        $email = $_GET['email'];
        echo "<input type='email' name='email' placeholder='E-mail' required value='$email'>";
    }
    else {
        echo "<input type='email' name='email' placeholder='E-mail' required>";
    }

    ?>
    <br>
    <input type="submit" value="Suivant">
</form>

<?php

    if(isset($_GET['status']) and $_GET['status'] == 'succes') {
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        Un e-mail contenant un lien de réinitialisation de clé personnelle vous a été envoyé !
                    </p>
                    <a href="#" onclick="closePopup()">Ok merci</a>
                </div>
            </div>
        </div>
        <?php
    }
    elseif (isset($_GET['status']) and $_GET['status'] == 'nonVerifie') {
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        Vous n'avez malheureusement pas vérifié votre compte. De ce fait nous ne pouvons pas vous envoyer
                        d'e-mail pour réinitialiser votre clé personnelle.<br>
                        Cependant nous venons de vous envoyer un lien (par e-mail) pour le faire vérifier, saisissez vite cette chance ! <br>
                        (Astuce : Pensez à regarder dans vos e-mails indésirables)
                    </p>
                    <a href="#" onclick="closePopup()">J'ai compris</a>
                </div>
            </div>
        </div>
        <?php
    }
    elseif (isset($_GET['status']) and $_GET['status'] == 'error') {
        ?>
        <div class="overlay" id="overlay">
            <div class="subOverlay">
                <div class="content">
                    <p>
                        Nous ne trouvons pas de compte Planning avec cette adresse e-mail.<br>
                        Nous sommes désolé.
                    </p>
                    <a href="#" onclick="closePopup()">J'ai compris</a>
                </div>
            </div>
        </div>
        <?php
    }

?>

<script>
    function closePopup() {
        document.getElementById("overlay").style.display = "none";
    }
</script>

</body>
</html>