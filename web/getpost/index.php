<?php
/**
 * Created by PhpStorm.
 * User: Théo Vuillaume
 * Date: 30.08.2022
 * Time: 09:00
 */

session_start();
$_SESSION['logged'] = false;



require 'php-ref-master/ref.php';
require '../conn.inc.php';

// Vérifier si le formulaire est soumis
if (isset($_POST['inscription'])) {

    $nom = filter_input(INPUT_POST, 'f-ins-nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'f-ins-pre', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'f-ins-adre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'f-ins-email', FILTER_SANITIZE_EMAIL);
    $mdp = filter_input(INPUT_POST, 'f-ins-mdp', FILTER_SANITIZE_STRING);
    $loc = filter_input(INPUT_POST, 'f-ins-loc', FILTER_SANITIZE_NUMBER_INT);

    try {

        var_dump($loc);

        //ouverture de la connexion
        $dbh = conn_db();

        //rédaction de la requête

        $sql = "insert into tb_utilisateur values(null,:nom,:pre,:adr,:email,:mdp,0,:loc)";

        // la compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
        $stmt = $dbh->prepare($sql);

        //association du marqueur à une variable (E/S)
        //lier les données
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pre', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adr', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $stmt->bindParam(':loc', $loc, PDO::PARAM_INT);

        //exécution de la requête
        $stmt->execute();

        //choix du mode de récuperation
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // récupère le nombre d'enregistrement affectés par la requête
        $res = $stmt->rowCount();

        //redirection vers la page d'accueil
        // header('location:index.html');

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    
    header('Location : '.'../commande.php');


} elseif (isset($_POST['connexion'])){

    $email = filter_input(INPUT_POST, 'f-con-email', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'f-con-mdp', FILTER_SANITIZE_STRING);

    //ouverture de la connexion
    $dbh = conn_db();

    //rédaction de la requête

    $sql = "SELECT email_util, mdp_util FROM ih1o2_pi02db1.tb_utilisateur where email_util LIKE :email";

    // la compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
    $stmt = $dbh->prepare($sql);

    //association du marqueur à une variable (E/S)
    //lier les données
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    //exécution de la requête
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_NUM);

    $mdp_util = "";
    foreach ($stmt as $row) {
        $mdp_util = $row[1];
    }

    var_dump($mdp);
    var_dump($mdp_util);

    if ($mdp_util == $mdp){

        $_SESSION['logged'] = true;
        echo "Logged in";
        header('Location : '.'../commande.php');
    } else {
        header('Location : '.'../index.php');
    }
}elseif (isset($_GET)){

    try {


        //ouverture de la connexion
        $dbh = conn_db();

        //rédaction de la requête

        $dateHeure = date('y-m-d h:i:s');

        $sql = "insert into tb_commande values(null,0,'$dateHeure',48)";

        // la compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
        $stmt = $dbh->prepare($sql);

        //exécution de la requête
        $stmt->execute();

        //choix du mode de récuperation
        $stmt->setFetchMode(PDO::FETCH_NUM);


        $id_plat = -1;
        $qte_plat = -1;
        $id_com = $dbh->lastInsertId();

        foreach ($_GET as $plat) {

            if (strpos($plat, "id") !== false){
                $id_plat = substr($plat, -2);
            } else {
                $qte_plat = $plat;
            }

            var_dump('id plat : '.$id_plat);
            echo '<br>';
            var_dump('qte plat : '.$qte_plat);
            echo '<br>';

            if ($qte_plat ==-1){
                continue;
            }

            if ($qte_plat ==0){
                $id_plat = -1;
                $qte_plat = -1;
                continue;
            }

            var_dump('insert');

            $sql = "insert into tb_contenir values(null,:fk_cont_plat,:fk_cont_com,:qte_cont)";

            // la compilation de la requête sur le serveur retour un objet PDOStatment qui représente la requête sur le serveur
            $stmt = $dbh->prepare($sql);

            //association du marqueur à une variable (E/S)
            //lier les données



            $stmt->bindParam(':fk_cont_plat', $id_plat, PDO::PARAM_INT);
            $stmt->bindParam(':fk_cont_com', $id_com, PDO::PARAM_INT);
            $stmt->bindParam(':qte_cont', $qte_plat, PDO::PARAM_INT);

            //exécution de la requête
            $stmt->execute();

            $id_plat = -1;
            $qte_plat = -1;

        }

    } catch (PDOException $e) {
        die($e->getMessage());
    }

   // header('Location : '.'../commande.php');

}
