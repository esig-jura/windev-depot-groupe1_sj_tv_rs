<?php

session_start();
/*
if($_SESSION["logged"] != true) {
//echo 'not logged in';
    header('Location : '.'./index.php');
}*/

require 'getpost/php-ref-master/ref.php';
require 'conn.inc.php';

//ouverture de la connexion
$dbh = conn_db();

//rédaction de la requête

$sql = "SELECT * FROM ih1o2_pi02db1.tb_plat ORDER BY ih1o2_pi02db1.tb_plat.nom_plat;";

// la compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
$stmt = $dbh->prepare($sql);

//exécution de la requête
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
        foreach ($stmt as $row) {

            echo '<div class="container">';
            echo '<img class="plat" src="'.$row[7].'" alt="">';
            echo ' <div><div class="titre">';
            echo "<h2>$row[1]</h2>";

            if ($row[4]){
                echo '<img class="badge" src="src/bio.png" alt="">';
            }

            if ($row[5]){
                echo '<img class="badge" src="src/vegan.png" alt="">';
            }

            echo '</div><h3>Description</h3>';
            echo '<p>'.$row[2].'</p>';
            echo ' <h3>Aliments</h3>';
            echo '<p>liste des aliments</p></div>';
            echo '<div class="prix-badge"><div class="prix"><h3>Prix : </h3>';
            echo '<p>'.$row[3].'</p></div>';
            echo ' <input id="idplat'.$row[0].'" name="idplat'.$row[0].'" type="hidden" value="idplat'.$row[0].'">
 <div class="quantite"><label for="fqte'.$row[0].'"><h3>Quantité :</h3></label>
                  <input onblur="findTotal()" min="0" type="number" value="0" id="fqte'.$row[0].'" name="fqte'.$row[0].'" class="input-qte"></div>';
                   echo '<div class="prix-plat">
            <h3>Prix total : </h3>
            <p><span class="totalPlat'.$row[0].'"></span></p>
            </div></div>
    </div>
                  
                                      <script>
                    // Récupère la liste déroulante #pays et le span .code
                    const quantitePlat'.$row[0].' = document.getElementById("fqte'.$row[0].'");
                    const totalPlat'.$row[0].' = document.querySelector("span.totalPlat'.$row[0].'");
                    
                    // Sur changement de la valeur de la liste déroulante
                    quantitePlat'.$row[0].'.addEventListener("change", function() {
                       // Récupère la valeur de loption sélectionnée
                       let total = quantitePlat'.$row[0].'.value;
                       // Modifie le contenu texte du span .code
                       let totalPrix = total*'.$row[3].';
                       
                       if (totalPrix < 1){
                       
                       totalPrix = 0;
                       } 
                       
                       totalPlat' . $row[0] . '.innerText = totalPrix;
                    });
                            </script>


                  ';
        


        }

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    ?>
    <h3>Prix total de la commande : </h3>
    <p><span class="totalCommande"></span></p>
    <script type="text/javascript">

        function findTotal(){

            const totalCommande= document.querySelector("span.totalCommande");
            const quantitePlat = document.getElementsByClassName('input-qte');
            const prixPlat = document.getElementsByClassName('totalPlat*');

                var arr = quantitePlat;
                var tot=0;

                for(var i=0;i<arr.length;i++){

                    if(parseInt(arr[i].value))
                        tot += parseInt(arr[i].value);
                }
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

