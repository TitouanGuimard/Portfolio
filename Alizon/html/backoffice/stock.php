<?php
session_start();




//Connexion à la base de données.
require_once('../_env.php');
loadEnv('../.env');

// Récupération des variables
$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');


// Connexion à PostgreSQL

try {
    $ip = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';';
    $bdd = new PDO($ip, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    // "❌ Erreur de connexion : " . $e->getMessage();
}
$bdd->query('set schema \'alizon\'');
    $estVendeur = false;
    if(isset($_SESSION["codeCompte"])){

        $vendeurs = $bdd->query("SELECT ALL codeCompte FROM alizon.Vendeur")->fetchAll();
        foreach($vendeurs as $vendeur){
            if($vendeur["codecompte"] == $_SESSION["codeCompte"]){
                $estVendeur = true;
            }
        }
    }
    if(!$estVendeur || !isset($_SESSION["codeCompte"])){
        exit(header("location:connexionVendeur.php"));
    }
    else{
        $codeCompte = $_SESSION["codeCompte"];
    }
?>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon BACK</title>
    <link href="../css/style/backoffice/Stock.css" rel="stylesheet" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
</head>

<body>


    <main>
        <?php include('../includes/backoffice/header.php'); ?>
        <a href="index.php" class="btn-retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>Retour</a>
        <h1>Votre Stock</h1>
        
        <div class="separateur"></div>
        <?php
        $listArtTmp = $bdd->query('SELECT * FROM Produit where codeCompteVendeur = \'' . $codeCompte.'\'');
        $listArt = $listArtTmp->fetchAll();

        if ($listArt == NULL) { ?>
            <!-- L'utilisateur ne possède pas d'article dans son stock -->
            <div class="vide">
                <h1> Vous ne possèdez aucun article en stock </h1>
                <a href="index.php">Revenir à l'accueil<a>
            </div>

        <?php
        } else {
            $articlesC = $bdd->query('SELECT DISTINCT codeProduit, libelleProd, prixTTC, urlPhoto FROM Produit where codeCompteVendeur =\'' . $codeCompte . '\' and Disponible = true')->fetchAll();
            $articlesHC = $bdd->query('SELECT DISTINCT codeProduit, libelleProd, prixTTC, urlPhoto FROM Produit where codeCompteVendeur =\'' . $codeCompte . '\' and Disponible = false')->fetchAll();
        ?>



            <!--
        <div class="separateur"></div>
            <div class="titre-cat">
                <h2>
                    Mes promotions 
                </h2>
        </div>
        <div class="separateur"></div>
            <div class="titre-cat">
                <h2>
                    Mes Réductions 
                </h2>
        </div>-->

            <div class="titre-cat">
                <h2>
                    Mes produits - Catalogue
                </h2>

            </div>
            <article><?php
                        if ($articlesC == null) { ?>
                    <div class="vide">
                        <h1> Vous ne possédez aucun article en Catalogue </h1>

                    </div>
                    <?php } else {
                            foreach ($articlesC as $article) {
                                //print_r($article);
                                $codeProduit = $article['codeproduit'];
                                $img = $article['urlphoto'];
                                $libArt = $article['libelleprod'];
                                $prix = $article['prixttc'];
                                $prix = round($prix, 2); // Arrondir à 2 chiffre après la virgule 
                    ?>

                        <div class="card">
                            <figure>
                                 <a href="./ficheProduit.php?Produit=<?php echo $codeProduit; ?>"><img src="<?php echo $img ?>" /></a>
                                <figcaption><?php echo $libArt ?></figcaption>
                            </figure>
                            <p class="prix"><?php echo $prix ?> €</p>
                            <div>
                                <a class="button" href="modifProduit.php?codeProduit=<?php echo $codeProduit?>">Modifier</a>
                                <a class="button" href="./ficheProduit.php?Produit=<?php echo $codeProduit ?>">Détails</a>
                            </div>

                        </div>

                <?php

                            }
                        } ?>

            </article>
            <div class="separateur"></div>
            <div class="titre-cat">
                <h2>
                    Mes produits - Hors-Catalogue
                </h2>
            </div>
            <?php
            if ($articlesHC == null) { ?>
                <div class="vide">
                    <h1> Vous ne possédez aucun article hors du Catalogue </h1>

                </div>
            <?php } else { ?>
                <article><?php
                            foreach ($articlesHC as $article) {


                                //print_r($article);
                                $codeProduit = $article['codeproduit'];
                                $img = $article['urlphoto'];
                                $libArt = $article['libelleprod'];
                                $prix = $article['prixttc'];
                                $prix = round($prix, 2); // Arrondir à 2 chiffre après la virgule 
                            ?>

                        <div class="card">
                            <figure>
                                <a href="./ficheProduit.php?Produit=<?php echo $codeProduit; ?>"><img src="<?php echo $img ?>" /></a>
                                <figcaption><?php echo $libArt ?></figcaption>
                            </figure>
                            <p class="prix"><?php echo $prix ?> €</p>
                            <div>
                                <a class="button" href="modifProduit.php?codeProduit=<?php echo $codeProduit?>">Modifier</a>
                                <a class="button" href="./ficheProduit.php?Produit=<?php echo $codeProduit; ?>">Détails</a>
                            </div>

                        </div>

                <?php
                            }
                        } ?>
                </article>
            <?php } ?>

            
            
            
            
        </main>
        <?php include('../includes/backoffice/footer.php'); ?>


</body>

</html>
