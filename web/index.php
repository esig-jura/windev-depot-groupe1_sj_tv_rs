<?php

// Page de connexion et d'inscription
// But : Permet à l'utilisateur créé un compte ou de se connecter
// Créé par : Shannon, Reda et Théo
// Date : 05.09.2022

// Démarrage de la session
session_start();
$_SESSION['logged'] = false;

require 'getpost/php-ref-master/ref.php';
require 'conn.inc.php';

// Ouverture de la connexion à la BD
$dbh = conn_db();

// Récupération de la liste des localités
$sql = "SELECT * FROM ih1o2_pi02db1.tb_localite ORDER BY ih1o2_pi02db1.tb_localite.npa_loc;";

// Compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
$stmt = $dbh->prepare($sql);

$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_NUM);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires</title>
    <link rel="stylesheet" href="css/login-inscription.css">
</head>
<body>
<div class="background">
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="https://lmo.divtec.me/pi02gr1/getpost/" method="post">
                <h1>Inscription</h1>
                <input type="email" name="f-ins-email" placeholder="Email"/>
                <input type="text" name="f-ins-nom" placeholder="Nom"/>
                <input type="text" name="f-ins-pre" placeholder="Prénom"/>
                <input type="text" name="f-ins-adre" placeholder="Adresse"/>
                <select id="f-ins-npa" name="f-ins-loc">
                    <libellé>NPA</libellé>
                    <?php

                    // Affichage du NPA et du nom de la localité
                    try {
                        foreach ($stmt as $row) {
                            echo'<option value="'.$row[0].'">'.$row[1].' '.$row[2].'</option>';
                        }
                    } catch (PDOException $e) {
                        die($e->getMessage());
                    }
                    ?>
                </select>
                <input type="password" name="f-ins-mdp" placeholder="Mot de passe"/>
                <button type="submit" name="inscription" >Inscription</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="https://lmo.divtec.me/pi02gr1/getpost/" method="post">
                <h1>Connexion</h1>
                <input type="email" name="f-con-email" id="f-con-email" placeholder="Email"/>
                <input type="password" name="f-con-mdp" placeholder="Mot de passe" />
                <a href="#">Mot de passe oublié ?</a>
                <button type="submit" name="connexion">Connexion</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Bentornati!</h1>
                    <p>Tagliatelle à la truffe ? N'attends pas, connecte toi </p>
                    <button class="ghost" id="signIn">Connexion</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Un petit creux ?</h1>
                    <p>Inscris toi pour déguster nos superbes pâtes</p>
                    <button class="ghost" id="signUp">Inscription</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Formulaires pour petits écrans uniquement -->
<div class="form-responsive">
    <form action="https://lmo.divtec.me/pi02gr1/getpost/" method="post">
        <h1>Connexion</h1>
        <input type="email" name="f-con-email" id="f-con-email" placeholder="Email"/>
        <input type="password" name="f-con-mdp" placeholder="Mot de passe" />
        <a href="#">Mot de passe oublié ?</a>
        <button type="submit" name="connexion">Connexion</button>
    </form>
    <form action="https://lmo.divtec.me/pi02gr1/getpost/" method="post">
        <h1>Inscription</h1>
        <input type="email" name="f-ins-email" placeholder="Email"/>
        <input type="text" name="f-ins-nom" placeholder="Nom"/>
        <input type="text" name="f-ins-pre" placeholder="Prénom"/>
        <input type="text" name="f-ins-adre" placeholder="Adresse"/>
        <select id="f-ins-npa" name="f-ins-loc">
            <libellé>NPA</libellé>
            <?php

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_NUM);

            try {
                foreach ($stmt as $row) {
                    echo'<option value="'.$row[0].'">'.$row[1].' '.$row[2].'</option>';
                }
            } catch (PDOException $e) {
                die($e->getMessage());
            }

            ?>
        </select>
        <input type="password" name="f-ins-mdp" placeholder="Mot de passe"/>
        <button type="submit" name="inscription" >Inscription</button>
    </form>
</div>
</body>
</html>
<script>

    // Récupération pour les transitions entre le formulaire de connexion et d inscription
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>