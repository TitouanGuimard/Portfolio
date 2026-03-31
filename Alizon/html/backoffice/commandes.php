<?php

session_start();
//$_SESSION["codeCompte"] = 5 ; 


//Connexion à la base de données.
require_once __DIR__ . '/_env.php';
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
    //"❌ Erreur de connexion : " . $e->getMessage();
    ?>
    <script>
        alert("Erreur lors du chargement");
    </script>
    <?php
        //header('Location: http://localhost:8888/index.php');
        exit();
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
    $codeCompte = $_SESSION["codeCompte"];
    $sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style/backoffice/CommandesVendeur.css" rel="stylesheet" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
    <title>Alizon</title>
</head>
<body>
    <?php include("../includes/backoffice/header.php"); ?>
    <main>
    <?php include '../includes/backoffice/menu.php'; ?>
    <?php include '../includes/backoffice/menuCompteVendeur.php'; ?>
    <div class="right-content">
        <h1>Vos commandes</h1>
        
        
        <?php  
        $sql = "SELECT DISTINCT puc.numCom FROM Produit p 
        INNER JOIN ProdUnitCommande puc ON p.codeProduit = puc.codeProduit 
        where  CodeCompteVendeur = :codeCompte ORDER BY numCom
        ";
        $stmt = $bdd->prepare($sql);
        $stmt->execute(array("codeCompte"=>$codeCompte));
        $lesCommandes = $stmt->fetchAll();
        //print_r($lesCommandes);
        // Si ne possède pas des commandes -> Pas de commandes
        // Sinon afficher son nb de commandes
        if($lesCommandes == null){
            ?>
            <div class="separateur"></div>
            <div class="vide">
                    <h1> Vous n'avez reçu aucune commande</h1>
                    <a href="index.php">Revenir à l'accueil<a>
                </div>
            <?php
        }
        else {
            ?>
            <div class="cmd">
            <?php
            foreach($lesCommandes as $commande){
                $idCom = $commande["numcom"];
                $stmt = $bdd->prepare('SELECT DISTINCT p.codeProduit,puc.qteProd FROM Produit p INNER JOIN ProdUnitCommande puc ON p.codeProduit = puc.codeProduit where numCom =\''.$idCom.'\' AND CodeCompteVendeur =\''. $codeCompte.'\' ');
                $stmt->execute();
                $lesProduits = $stmt->fetchAll();
                //print_r($lesProduits);
                ?>
                <div class="separateur"></div>
                <article>
                    <h2>Commande n°<?php echo $idCom ?></h2>
                    <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom produit</th>
                        <th>Code Produit</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    //print_r($lesProduits);
                    foreach($lesProduits as $prod){
                        $idProd = $prod['codeproduit'];
                        $qteprod =$prod['qteprod'];
                        $stmt = $bdd->prepare('SELECT libelleProd FROM Produit where codeProduit =:idProd');
                        $stmt->execute(array("idProd"=>$idProd));
                        $nomProd = $stmt->fetch();
                        //nomProd = $bdd->query('SELECT libelleProd FROM Produit where codeProduit = '. $idProd)->fetch();
                        $stmt = $bdd->prepare('SELECT urlPhoto FROM Produit where codeProduit =:idProd');
                        $stmt->execute(array("idProd"=>$idProd));
                        $imgProd = $stmt->fetch();
                        //$imgProd = $bdd->query("SELECT urlPhoto FROM Produit WHERE codeProduit =" .$prod['codeproduit'])->fetch();
                        
                        ?>
                        
                        <tr>
                            <td><?php echo "<img src='../".$imgProd['urlphoto']."' alt='Image du produit' class='imgCmd'>" ; ?></td>
                            <td><?php echo $nomProd['libelleprod'];?></td>
                            <td><?php echo $idProd ; ?></td>
                            <td><?php echo $qteprod ;?></td>
                        </tr>
                    
                        <?php
                    }
                    ?>
                </tbody>
            </table>
                    
                </article>
                <?php
            }
        }
        ?>
        </div>
        
    </div>
    </main>
    <?php include "../includes/backoffice/footer.php"?>
    <script src="../js/overlayCompteVendeur.js"></script>
</body>
</html>
