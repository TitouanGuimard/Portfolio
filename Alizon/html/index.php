<?php
//Connexion à la base de données.
session_start();
require_once __DIR__ . '/_env.php';
loadEnv(__DIR__ . '/.env');

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
    $estClient = false;
    if(isset($_SESSION["codeCompte"])){

        $clients = $bdd->query("SELECT ALL codeCompte FROM alizon.Client")->fetchAll();
        foreach($clients as $client){
            if($client["codecompte"] == $_SESSION["codeCompte"]){
                $estClient = true;
            }
        }
    }

$bdd->query('set schema \'alizon\'');
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon</title>
    <link rel="stylesheet" href="css/style/accueil.css">
</head>
<body>
    <?php

    if(isset( $_SESSION["codeCompte"]) && $estClient){
        $idUser =  $_SESSION["codeCompte"];
        include 'includes/headerCon.php' ;
    }else{
        include 'includes/header.php';
    }
    ?>
    
    <main>
        <?php
            include 'includes/menuCompte.php';
        ?>

        <?php if(isset($_GET["deconnexion"])):?>
            <script>alert("Vous avez été déconnecté.");</script>
        <?php endif?>
        <!-- <section class="bienvenue">
            <img src="img/bvn4.png" alt="">
        </section> -->
        <?php
        if(isset($_GET["ajout"])):?>
            <div class="ajoutPanierFait">
                <div class="partieGauche" onclick="fermerPopUpPanier()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <div class="partieDroite">
                    <p>Produit ajouté au panier</p>
                    <a href="Panier.php" class="bouton">Aller au panier</a>
                </div>
            </div>
        <?php endif?>
        <section class="bienvenue">
            <div class="carousel">
                <img src="img/play.png" alt="quitter" class="fermer">
                <img src="img/pause.png" alt="play" style="display:none;" class="play">
                <div class="carousel-track">
                    <img src="img/bvn4.png" alt="Image de contextualisation 1">
                    <img src="img/bvn.png" alt="Image de contextualisation 2">
                </div>
            </div>
        </section>
        <!--
    
         <section id="Promotion" class="aff_prod">
            <div class="separateur"></div>
            <h1>Promotions</h1>
            <div class="separateur"></div>
        </section> -->
        <section id="promotion" class="aff_prod">
        <div class="separateur"></div>    
        <h1>Promotions</h1>
            
                <?php
                $produits = $bdd->query("
                    SELECT produit.codeProduit, libelleProd, prixTTC, produit.urlPhoto, descriptionProd, origine, noteMoy
                    FROM Produit
                    INNER JOIN FairePromotion ON Produit.codeProduit = FairePromotion.codeProduit 
                    ORDER BY codeProduit DESC;
                ");
                ?>

            <article class="grid-produits">
                <?php
                foreach ($produits as $article) {
                    $img = $article['urlphoto'];
                    $libArt = $article['libelleprod'];
                    $prix = number_format($article['prixttc'], 2, ',', '');
                    $desc = $article['descriptionprod'];
                    $id = $article['codeproduit'];
                    $madein = $article['origine'];
                    $moyennenote = $article['notemoy'];
                    include 'includes/card.php';
                } ?>
            </article>
        </section>
        <section id="nouveautes" class="aff_prod">
            <div class="separateur"></div>
            <h1>Nouveautés</h1>
                <?php
                $produits = $bdd->query("
                    SELECT codeProduit, libelleProd, prixTTC, urlPhoto, descriptionProd, noteMoy, origine, noteMoy
                    FROM Produit
                    ORDER BY codeProduit DESC
                    LIMIT 7
                ");
                ?>

            <article class="grid-produits">
                <?php
                foreach ($produits as $article) {
                    $img = $article['urlphoto'];
                    $libArt = $article['libelleprod'];
                    $prix = number_format($article['prixttc'], 2, ',', '');
                    $desc = $article['descriptionprod'];
                    $id = $article['codeproduit'];
                    $madein = $article['origine'];
                    $moyennenote = $article['notemoy'];
                    include 'includes/card.php';

                } ?>
            </article>
        </section>

        
        <div class="separateur"></div>
        <h1 class="aff_prod">Les produits</h1>
        <article class="catalogue">
        <?php
        $base= 'SELECT codeProduit, libelleProd, prixTTC, urlPhoto, noteMoy, descriptionProd, origine, noteMoy FROM Produit where Disponible = true LIMIT 30' ;
        $req= $bdd->prepare($base);
        $req->execute();
        $produits= $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produits as $article) {
            $img = $article['urlphoto'];
            $libArt = $article['libelleprod'];
            $prix = number_format($article['prixttc'], 2, ',', '');
            $desc = $article['descriptionprod'];
            $id = $article['codeproduit'];
            $madein = $article['origine'];
            $moyennenote = $article['notemoy'];
            include 'includes/card.php';
        }        
        ?>
        </article>
        <div class="a-cat">
        <a href="Catalogue.php" class="bouton">Accéder au catalogue complet</a>
        </div>
    </main>
    <?php
        include 'includes/footer.php';
    ?>
    <script>
        
        function fermerPopUpPanier(){
            window.location.href = "index.php";
        }
        
    </script>
</body>
</html>