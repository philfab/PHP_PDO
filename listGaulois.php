<?php
session_start();
//---------------------------------------------------------------------------------------

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

$sql =
    "SELECT id_personnage, nom_personnage, specialite.nom_specialite, lieu.nom_lieu FROM personnage
    INNER JOIN lieu ON personnage.id_lieu = lieu.id_lieu
    INNER JOIN specialite ON personnage.id_specialite = specialite.id_specialite";

$perso = $pdo->prepare($sql);
$perso->execute();
$persos = $perso->fetchAll();
?>

<!-------------------------------------------------------------------------------------->

<link rel="stylesheet" type="text/css" href="style.css">

<div class="container">
    <table>
        <tr>
            <th>Nom</th>
            <th>Lieu</th>
            <th>Spécialité</th>
        </tr>
        <?php foreach ($persos as $index => $perso) : ?>
            <tr onclick="submitForm(<?= $index ?>)"> <!-- click sur row -->
                <td><?= $perso['nom_personnage']; ?></td><!-- '=' :  remplace php echo, plus concis et clair -->
                <td><?= $perso['nom_lieu']; ?></td>
                <td><?= $perso['nom_specialite']; ?></td>
                <td class="td-form">
                    <form id="form_<?= $index ?>" action="detailGaulois.php" method="post">
                        <input type="hidden" name="id_personnage" value="<?= $perso['id_personnage']; ?>">
                        <button type="submit">Voir détails</button>
                    </form>
                </td>
            </tr>
            <script>
                function submitForm(index) {
                    document.getElementById('form_' + index).submit();
                };
            </script>
        <?php endforeach; ?><!-- endforeach pour meilleure lisibilité php -->
    </table>

    <!-- affiche les détails du perso -->
    <?php if (isset($_SESSION['detailsPerso'])) : ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Village</th>
                <th>Spécialité</th>
            </tr>
            <tr>
                <td><?= $_SESSION['detailsPerso']['nom_personnage'] ?></td>
                <td><?= $_SESSION['detailsPerso']['nom_lieu'] ?></td>
                <td><?= $_SESSION['detailsPerso']['nom_specialite'] ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <div class="container-bataille">
        <?php if (isset($_SESSION['batailles'])) : ?>
            <h2>Batailles</h2>
            <ul>
                <?php foreach ($_SESSION['batailles'] as $bataille) : ?>
                    <li><?= $bataille['date_bataille'] ?> - <?= $bataille['nom_bataille'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php
            unset($_SESSION['detailsPerso'], $_SESSION['batailles']);
        endif;
        ?>
    </div>
</div>