<?php
// Page de récupération POST GET
// But : Réception des formulaires
// Créé par : Shannon, Reda et Théo
// Date : 05.09.2022

// Démarrage de la session
session_start();

// Utilisateur déconnecté par défaut
$_SESSION['logged'] = false;

require 'php-ref-master/ref.php';
require '../conn.inc.php';

// Vérification de la réception d'un formulaire
if (isset($_POST['inscription'])) {

    // Réception du formulaire d'inscription

    // Filtres d'entrée
    $nom = filter_input(INPUT_POST, 'f-ins-nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'f-ins-pre', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'f-ins-adre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'f-ins-email', FILTER_SANITIZE_EMAIL);
    $mdp = filter_input(INPUT_POST, 'f-ins-mdp', FILTER_SANITIZE_STRING);
    $loc = filter_input(INPUT_POST, 'f-ins-loc', FILTER_SANITIZE_NUMBER_INT);

    try {

        // Ouverture de la connexion à la BD
        $dbh = conn_db();

        // Création de la requete d'insertion d'un nouvel utilisateur
        $sql = "insert into tb_utilisateur values(null,:nom,:pre,:adr,:email,:mdp,0,:loc)";

        $stmt = $dbh->prepare($sql);

        // Association du marqueur à une variable
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pre', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':adr', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $stmt->bindParam(':loc', $loc, PDO::PARAM_INT);

        // Exécution de la requete
        $stmt->execute();

        // Choix du mode de récuperation
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Récupère le nombre d'enregistrement affectés par la requête
        $res = $stmt->rowCount();

    } catch (PDOException $e) {
        die($e->getMessage());
    }
    
    // Redirection sur la page de connexion
    header('Location : '.'../commande.php');


} elseif (isset($_POST['connexion'])){

    // Réception du formulaire de connexion

    // Filtres d'entrée
    $email = filter_input(INPUT_POST, 'f-con-email', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'f-con-mdp', FILTER_SANITIZE_STRING);

    $dbh = conn_db();

    // Récupération de l'email, du mot de passe et de l'id qui essaie de se connecter
    $sql = "SELECT email_util, mdp_util, id_util FROM ih1o2_pi02db1.tb_utilisateur where email_util LIKE :email";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_NUM);

    $mdp_util = "";

    // Récupération du mot de passe de l'utilisateur depuis la BD
    foreach ($stmt as $row) {
        $mdp_util = $row[1];
    }

    // Vérification que le mot de passe est correct
    if ($mdp_util == $mdp){

        // Utilisateur connecté
        $_SESSION['logged'] = true;

        // Id de l'utilisateur
        $_SESSION['id'] = $row[2];

        // Redirection sur la page commande
        header('Location : '.'../commande.php');

    } else {

        // Redirection sur la page d'index si l'utilisateur n'est pas connecté
        header('Location : '.'../index.php');
    }

}elseif (isset($_GET)){

    // Réception du formulaire de commande

    try {
        
        $dbh = conn_db();

        // Date et heure de maintenant
        $dateHeure = date('y-m-d h:i:s');

        // Id de l'utilisateur
        $iduser = $_SESSION["id"];

        // Requete d'insertion d'une commande
        $sql = "insert into tb_commande values(null,0,'$dateHeure','$iduser')";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_NUM);

        // Valeurs par défaut
        $id_plat = -1;
        $qte_plat = -1;

        // Récupération de l'id de la dernière insertion dans la BD
        $id_com = $dbh->lastInsertId();

        // Suppression du prix total du plat du tableau
        unset($_GET['input-prix-total-plat']);

        // Création de la requete d'insertion dans la tabke associative contenir
        // Parcours de GET
        foreach ($_GET as $plat) {

            // Vérifie si la valeur est un id ou la quantité
            if (strpos($plat, "id") !== false){
                $id_plat = substr($plat, -2);
            } else {
                $qte_plat = $plat;
            }

            // Vérifie si la quantité est valide
            if ($qte_plat ==-1){
                continue;
            }

            // Vérifie si la quautité est égal à 0
            if ($qte_plat ==0){
                $id_plat = -1;
                $qte_plat = -1;
                continue;
            }

            // Création de la requete d'insertion dans la table associative
            $sql = "insert into tb_contenir values(null,:fk_cont_plat,:fk_cont_com,:qte_cont)";

            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':fk_cont_plat', $id_plat, PDO::PARAM_INT);
            $stmt->bindParam(':fk_cont_com', $id_com, PDO::PARAM_INT);
            $stmt->bindParam(':qte_cont', $qte_plat, PDO::PARAM_INT);

            $stmt->execute();

            // Réinitialisation des valeurs
            $id_plat = -1;
            $qte_plat = -1;

        }

    } catch (PDOException $e) {
        die($e->getMessage());
    }

    // Redirection sur la page de commande
    $_SESSION['logged'] = true;
    header('Location : '.'../commande.php');
}