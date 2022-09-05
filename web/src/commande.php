<?php
// Page de commande
// But : Permet à l'utilisateur de passer une commande sur PastaDrive
// Créé par : Shannon, Reda et Théo
// Date : 05.09.2022

// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if($_SESSION["logged"] != true) {

    // Redirection sur la page de connexion
    header('Location : '.'./index.php');
}

require 'getpost/php-ref-master/ref.php';
require 'conn.inc.php';

// Ouverture de la connexion à la BD
$dbh = conn_db();

// Requete pour récuperer tous les plats
$sql = "SELECT * FROM ih1o2_pi02db1.tb_plat ORDER BY ih1o2_pi02db1.tb_plat.nom_plat;";

// Compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
$stmt = $dbh->prepare($sql);

// Exécution de la requête
$stmt->execute();

$stmt->setFetchMode(PDO::FETCH_NUM);

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
<form action="https://lmo.divtec.me/pi02gr1/getpost/" method="get">
    <?php
    try {

        // Affichage de tous les plats
        foreach ($stmt as $row) {

            echo '<div class="container">';
            echo '<img class="plat" src="'.$row[7].'" alt="">';
            echo ' <div><div class="titre">';
            echo "<h2>$row[1]</h2>";

            // Logo Bio
            if ($row[4]){
                echo '<img class="badge" src="src/bio.png" alt="">';
            }

            // Logo Vegan
            if ($row[5]){
                echo '<img class="badge" src="src/vegan.png" alt="">';
            }

            echo '</div><h3>Description</h3>';

            // Description du plat
            echo '<p>'.$row[2].'</p>';

            echo ' <h3>Aliments</h3>';

            // Requete qui récupère les aliments du plat
            $sql = "SELECT tb_aliment.nom_alim FROM tb_aliment
                     INNER JOIN tb_composer ON tb_aliment.id_alim = tb_composer.fk_comp_alim
                     INNER JOIN tb_plat ON tb_composer.fk_comp_plat = tb_plat.id_plat 
                     WHERE tb_plat.id_plat = $row[0];";

            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_NUM);

            // Création de la liste des aliments
            $liste_aliments = "";

            // Remplissage de la liste des aliments
            foreach ($stmt as $aliment) {

                if ($liste_aliments != ""){
                    $liste_aliments = $liste_aliments.", ".$aliment[0];
                } else {
                    $liste_aliments = $liste_aliments.$aliment[0];
                }
            }

            // Affichage de la liste des aliments
            echo '<p>'.$liste_aliments.'</p></div>';

            // Affichage du prix de l'aliment
            echo '<div class="prix-badge"><div class="prix"><h3>Prix : </h3>';
            echo '<p>'.$row[3].'</p></div>';

            // Affichage de l'id du plat et de la quantité
            echo '<input id="idplat'.$row[0].'" name="idplat'.$row[0].'" type="hidden" value="idplat'.$row[0].'">
                <div class="quantite"><label for="fqte'.$row[0].'"><h3>Quantité :</h3></label>
                <input onblur="findTotal()" min="0" type="number" value="0" id="fqte'.$row[0].'" name="fqte'.$row[0].'" class="input-qte"></div>';

            // Affichage du prix total du plat
            echo '<div class="prix-plat">
            <h3>Prix total : </h3>
            <p><span class="totalPlat'.$row[0].'"></span></p>
            <input id="prix-total-plat" class="prix-total-plat'.$row[0].'" name="input-prix-total-plat" type="hidden" value="0">
            </div></div></div>
                  
               <script>
                    // Récupération de la quantité du plat
                    const quantitePlat'.$row[0].' = document.getElementById("fqte'.$row[0].'");

                    // Récupération du prix total du plat
                    const totalPrixPlat'.$row[0].' = document.querySelector("input.prix-total-plat'.$row[0].'");
                    
                    // Span qui affichera le montant total du plat
                    const totalPlat'.$row[0].' = document.querySelector("span.totalPlat'.$row[0].'");
                    
                    // Sur changement de la valeur de linput de quantité
                    quantitePlat'.$row[0].'.addEventListener("change", function() {
                       
                       // Récupère la quantité
                       let total = quantitePlat'.$row[0].'.value;

                       // Calcul du prix total du plat
                       let totalPrix = total*'.$row[3].';
                       
                       if (totalPrix < 1){
                            totalPrix = 0;
                       }; 
                       
                       // Attribution de la valeur
                       totalPlat' . $row[0] . '.innerText = totalPrix;
                       totalPrixPlat' . $row[0] . '.value = totalPrix;
                    });
            </script>
                  ';}

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    ?>
    <div class="prix-total">
    <h3>Prix total de la commande : </h3>
    <p><span class="totalCommande"></span></p>
    </div>
    <script type="text/javascript">

        // Calcul du prix total de la commande
        function findTotal(){

            const totalCommande= document.querySelector("span.totalCommande");
            const quantitePlat = document.getElementsByClassName('input-qte');
            const prixPlat = document.getElementsByName('input-prix-total-plat');


                var arr = quantitePlat;
                var tot=0;

                // Parcours tous les plats en additionant le montant total
                for(var i=0;i<arr.length;i++){

                    console.info(prixPlat[i].value);

                    if(parseInt(arr[i].value))
                        tot += parseInt(prixPlat[i].value);
                }

                // Attribution de la valeur 
                totalCommande.innerText = tot;
        }
    </script>
    <button type="submit">Commander</button>
</form>
<footer>
    <img src="src/pastadrive-logo.png" alt="">
    <p>Propulsé par Shannon, Reda et Théo</p>
</footer>
</body>
</html>