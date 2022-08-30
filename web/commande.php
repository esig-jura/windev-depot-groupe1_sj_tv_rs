<?php

session_start();

if($_SESSION["logged"] != true) {
//echo 'not logged in';
    header('Location : '.'./index.php');
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande | PastaDrive</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<header>
    <h1>Liste des plats</h1>
</header>
<form action="https://lmo.divtec.me/pi02gr1/getpost/">
    <div class="container">
        <img class="plat" src="src/penne-pesto-avocat.jpg" alt="">
        <div>
            <div class="titre">
                <h2>Spaghettis au pesto d'avocat</h2>
                <img class="badge" src="src/vegan.png" alt="">
                <img class="badge" src="src/bio.png" alt="">
            </div>
            <h3>Description</h3>
            <p>The behavior could be thought of as a minimum gutter, as if the gutter is bigger somehow (because of something
            like justify-content: space-between;) then the gap will only take effect if that space would end up smaller.</p>
            <h3>Aliments</h3>
            <p>Aliment : The behavior could be thought of as a minimum gutter, as if the gutter is bigger somehow (because of something</p>
        </div>
        <div class="prix-badge">
            <div class="prix">
                <h3>Prix : </h3>
                <p>10.-</p>
            </div>
            <div class="quantite">
                <label for="fqte"><h3>Quantité :</h3></label>
                <input type="number" value="1" id="fqte" name="fqte">
            </div>
            <div class="prix-plat">
                <h3>Prix total : </h3>
                <p>10.-</p>
            </div>
        </div>
    </div>
    <button type="submit">Commander</button>
</form>
<footer>
    <img src="src/pastadrive-logo.png" alt="">
    <p>Propulsé par Shannon, Reda et Théo</p>
</footer>
</body>
</html>
