<?php
session_start();


//$codeCompte = $_SESSION["codecompte"];
//Connexion à la base de données.
require_once  '../_env.php';
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
    //echo "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
$bdd->query("SET SCHEMA 'alizon'");
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
$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);

$signalements = $bdd->query("SELECT motif, commentaire, libelleprod, dateSignalement FROM alizon.Signalement
INNER JOIN alizon.Avis ON Signalement.numAvis = Avis.numAvis 
INNER JOIN alizon.Produit ON Avis.codeProduit = Produit.codeProduit WHERE Produit.codeCompteVendeur = ".$_SESSION["codeCompte"])->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon BackOffice</title>
    <link rel="stylesheet" href="../css/style/backoffice/accueilBack.css" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include '../includes/backoffice/header.php';?>
    <main>
        <?php
            include '../includes/backoffice/menuCompteVendeur.php';
            include '../includes/backoffice/menu.php'; 
        ?>
        
        <section>
            <h1 class="bvn-vendeur">Signalement d'avis</h1>
            <?php 
            foreach ($signalements as $signalement) { 
                // Récupérer le nom du produit associé au signalement
                
                $nomProduit = $produit ? $produit['libelleprod'] : 'Produit inconnu';
                ?>
                <div class="signalement-item" style="background-color: white; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc;">
                    <p>Produit: <?= $signalement["libelleprod"] ?></p>
                    <p>Motif : <?= $signalement["motif"]?></p>
                    <p>Message: <?= $signalement['commentaire'] ?></p>
                    <p>Date: <?= $signalement['datesignalement'] ?></p>
                </div>
            <?php } ?>
        </section>
    </main>
</body>
</html>