<?php
session_start();

if (!isset($_SESSION['id']))
    header("location: ../../index.php");
else {
    include_once '../../../function/fonctionMois.php';

    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=vice-width, initial-scale=1.0">
        <script src="../function/timeout.js"></script>
        <title>Historique</title>
        <link
                rel="stylesheet"
                href="https://unpkg.com/swiper/swiper-bundle.min.css"
        />

        <style>
            @font-face {
                font-family: myFirstFont;
                src: url(../../../font-style/ABeeZee-Regular.ttf);
            }
            html,
            body {
                position: relative;
                height: 100%;
            }

            body {
                background: white;
                font-family: myFirstFont, Helvetica Neue, Helvetica, Arial, sans-serif;
                font-size: 14px;
                color: #000;
                margin: 0;
                padding: 0;
            }

            .swiper {
                width: 100%;
                height: 80%;
            }

            .swiper-slide {
                text-align: center;
                font-size: 18px;
                background: #ffffff;
                height: 100%;

                /* Center slide text vertically */
                display: -webkit-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                -webkit-justify-content: center;
                justify-content: center;
                -webkit-box-align: center;
                -ms-flex-align: center;
                -webkit-align-items: center;
                align-items: center;
            }

            .swiper-slide img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .content {
                width: 76%;
                height: 95%;
            }

            .mois {
                display: block;
                border: 1px solid lightgray;
                border-radius: 20px;
                box-shadow: 0 0 5px 2px lightgray;
                text-align: left;
                padding: 2% 8%;
                font-size: 24px;
            }

            .mois a {
                display: inline-block;
                padding: 0;
                margin: 2px 0;
                color: #0F4F66;
                text-decoration: none;
            }

            .mois a:hover, .mois a:visited {
                color: #0F4F66;
                text-decoration: none;
            }
        </style>
    </head>
    <body>

    <h1 style="font-size: 23px; width: 100%; text-align: center; margin-bottom: 0; padding-bottom: 0">Historique des
        données</h1>

    <?php
    $bdd = null;
    include_once '../../../function/bdd.php';

    $request = $bdd->query('SELECT MONTH(datage) AS Mois, YEAR(datage) AS Annee FROM Horaire WHERE idUser = "' . $_SESSION['id'] . '" AND idHoraire NOT IN (SELECT idHoraire FROM HorairePoubelle) GROUP BY Mois, Annee ORDER BY Annee, Mois');

    $rien = true;
    $annee = "";
    while ($donnees = $request->fetch()){
    $rien = false;

    if ($annee == ""){
    $annee = $donnees['Annee'];
    ?>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="content">
                    <h2 style="width: 100%; text-align: left; margin-bottom: 5%">Année <?= $annee ?></h2>
                    <div class="mois">
                        <?php
                        }

    if ($annee != $donnees['Annee']) {
                            $annee = $donnees['Annee'];
                            echo "</div></div></div>";
                            echo "<div class='swiper-slide'>";
                            echo "<div class='content'>";
                            echo "<h2 style='width: 100%; text-align: left; margin-bottom: 5%'>Année " . $donnees['Annee'] . "</h2>";
                            echo "<div class='mois'>";
                        }
    echo "<a href='generateurPDF.php?mois=" . $donnees['Mois'] . "&annee=" . $donnees['Annee'] . "&userId=" . $_SESSION['id'] . "'>" . mois($donnees['Mois']) . "</a><br>";
                        }
                        if ($rien) {
                        ?>
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="content">
                                        <h2 style="width: 100%; text-align: left; margin-bottom: 5%">
                                            Année <?= date('Y') ?></h2>
                                        <div class="mois">
                                            <?php
                                            echo "<h1 style='font-size: 20px; margin: 20px 0; text-align: center; font-style: italic'>Vous n'avez encore aucune donnée</h1>";
                                            }

                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="position: fixed; bottom: 14%" class="swiper-pagination"></div>
                        </div>
                        <div style="position: fixed; bottom: 6%; width: 100%; text-align: center">
                            <a style="font-size: 20px; border: 1px solid gray; border-radius: 10px; background-color: #212F3D; color: white; padding: 2% 2%; text-decoration: none"
                               href="../planning.php">Retourner au planning</a>
                        </div>
                        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

                        <script>
                            var swiper = new Swiper(".mySwiper", {
                                slidesPerView: 1,
                                centeredSlides: true,
                                spaceBetween: 30,
                                grabCursor: true,
                                pagination: {
                                    el: ".swiper-pagination",
                                    clickable: true
                                }
                            });
                        </script>
    </body>
    </html>
<?php } ?>