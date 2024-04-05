<?php
session_start();

if (isset($_POST['id_personnage'])) {
    $idPersonnage = $_POST['id_personnage'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=gaulois;charset=utf8', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $req = $pdo->prepare("SELECT personnage.nom_personnage, lieu.nom_lieu, specialite.nom_specialite 
                               FROM personnage
                               JOIN lieu ON personnage.id_lieu = lieu.id_lieu
                               JOIN specialite ON personnage.id_specialite = specialite.id_specialite
                               WHERE personnage.id_personnage = ?");/* ? = placeholder */

        $req->execute([$idPersonnage]);/* ? = [$idPersonnage] */

        $detailsPerso = $req->fetch();

        $req = $pdo->prepare("SELECT bataille.nom_bataille, bataille.date_bataille 
                              FROM bataille
                              JOIN prendre_casque ON bataille.id_bataille = prendre_casque.id_bataille
                              WHERE prendre_casque.id_personnage = ?
                              ORDER BY bataille.date_bataille DESC");

        $req->execute([$idPersonnage]);
        $batailles = $req->fetchAll();


        $_SESSION['detailsPerso'] = $detailsPerso;
        $_SESSION['batailles'] = $batailles;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // redirection vers listGaulois.php
    header('Location: listGaulois.php');
    exit();
}
