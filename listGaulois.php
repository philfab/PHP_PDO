<link rel="stylesheet" type="text/css" href="style.css">
<?php

include 'detailGaulois.php';

function rowClicked($nom_personnage) {
    echo "click sur : " . $nom_personnage;
}

echo "<script>
    function rowClicked(nom_personnage) {
        alert('click sur : ' + nom_personnage);
    }
</script>";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=gaulois;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

//---------------------------------------------------------------------------------------
    
$sqlQ1 = 
    "SELECT nom_personnage, specialite.nom_specialite, lieu.nom_lieu FROM personnage
    INNER JOIN lieu ON personnage.id_lieu = lieu.id_lieu
    INNER JOIN specialite ON personnage.id_specialite = specialite.id_specialite";

$perso = $pdo->prepare($sqlQ1);
$perso->execute();
$persos = $perso->fetchAll();

echo "<table>
        <tr>
            <th>Nom</th>
            <th>Lieu</th>
            <th>Spécialité</th>
        </tr>";

foreach ($persos as $perso) {
    echo "<tr onclick='rowClicked(\"" . $perso['nom_personnage'] . "\")'>
            <td>" . $perso['nom_personnage'] . "</td>
            <td>" . $perso['nom_lieu'] . "</td>
            <td>" . $perso['nom_specialite'] . "</td>
          </tr>";
}
echo "</table><br>";

?>