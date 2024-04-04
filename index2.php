<?php

phpinfo();

/*
Le premier paramètre (qui commence par mysql  ) s'appelle le DSN : Data Source Name. C'est généralement le seul qui change en fonction du type de base de données auquel on se connecte.

host : le nom d'hôte
dbname : la base de données
l'identifiant (login) : root
'' : le mot de passe 
*/

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gaulois;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo 'Connexion réussie <br>';
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

/*
La méthode "prepare" de l'objet PDO sert à préparer une requête SQL pour être exécutée sur la base de données. L'utilisation de prepare est une bonne pratique pour plusieurs raisons :
    - Sécurité : Elle permet d'éviter les injections SQL en séparant clairement la requête SQL de ses données. Les données envoyées à la base de données ne sont pas directement intégrées dans la requête, mais liées à celle-ci comme des paramètres, ce qui empêche l'exécution de code SQL malveillant.

    - Performance : Si vous devez exécuter la même requête plusieurs fois avec seulement de légères modifications dans les données, préparer la requête une seule fois et l'exécuter plusieurs fois avec différents paramètres peut améliorer les performances. Le serveur de base de données peut optimiser le plan d'exécution de la requête préparée.

    - Flexibilité : Elle permet de lier des variables à des paramètres spécifiques dans la requête SQL de manière dynamique, ce qui rend le code plus lisible et flexible.
*/
$recipesStatement = $pdo->prepare('SELECT * FROM personnage');

$recipesStatement->execute();

/*
$recipesStatement contient quelque chose d'inexploitable directement : un objet PDOStatement. Cet objet va contenir la requête SQL que nous devons exécuter, et par la suite, les informations récupérées en base de données.
Pour récupérer les données, demandez à cet objet d'exécuter la requête SQL et de récupérer toutes les données dans un format "exploitable", c'est-à-dire sous forme d'un tableau PHP.
Fetch" en anglais signifie « va chercher ».
Voici les principaux modes de récupération et ce que fetchAll retourne dans chaque cas :
PDO::FETCH_ASSOC : Retourne un tableau associatif où les clés sont les noms des colonnes de votre requête,
                   chaque élément du tableau $rows représente une ligne de l'ensemble de résultats, avec les 
                   noms de colonnes comme clés.
PDO::FETCH_NUM : Retourne un tableau indexé numériquement, où chaque colonne est accessible par 
                 un index numérique (commençant à 0).
PDO::FETCH_OBJ : Retourne un tableau d'objets, où chaque ligne est un objet avec des propriétés 
                 correspondant aux colonnes de la requête.
PDO::FETCH_BOTH : Retourne un tableau qui combine les caractéristiques de FETCH_ASSOC et FETCH_NUM. Chaque ligne 
                  sera un tableau avec à la fois des indices numériques et des clés associatives.
PDO::FETCH_KEY_PAIR : Utile pour récupérer un tableau où chaque élément est un couple clé-valeur (deux colonnes sont 
                      nécessaires dans votre requête pour ce mode).
PDO::FETCH_CLASS : Instancie et retourne un tableau d'instances d'une classe spécifiée, en mappant les colonnes de
                   chaque ligne aux propriétés de la classe.
*/
$recipes = $recipesStatement->fetchAll(); //PDO::FETCH_BOTH par défaut (2 tableaux, 1 associatif + 1 numériquement) 

// On affiche chaque recette une à une
// foreach ($recipes as $recipe) {
//     var_dump($recipe);
// }

//on peut aussi utiliser une requête avec des paramètres qui sont associés à la requête
$sqlQuery = 'SELECT nom_personnage FROM personnage WHERE nom_personnage = :perso OR adresse_personnage = :adress';
$Statement = $pdo->prepare($sqlQuery);
$Statement->execute([
    'perso' => 'Astérix',
    'adress' => 'Port',
]);
$Statement->setFetchMode(PDO::FETCH_ASSOC);
$persos = $Statement->fetchAll();
echo 'Nom du personnage : <br>';
foreach ($persos as $perso) {
    echo  $perso['nom_personnage'] . '<br>';
}
