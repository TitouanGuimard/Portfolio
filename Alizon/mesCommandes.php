<?php
session_start();


//Connexion à la base de données.
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
    //"❌ Erreur de connexion : " . $e->getMessage();
    ?>
    <script>
        alert("Erreur lors du chargement");
    </script>
    <?php
        header('Location: http://localhost:8888/index.php');
        exit();
}
$bdd->query('set schema \'alizon\'');
    $estClient = false;
    if(isset($_SESSION["codeCompte"])){

        $clients = $bdd->query("SELECT ALL codeCompte FROM alizon.Client")->fetchAll();
        foreach($clients as $client){
            if($client["codecompte"] == $_SESSION["codeCompte"]){
                $estClient = true;
            }
        }
    }
    if(!$estClient || !isset($_SESSION["codeCompte"])){
        exit(header("location:index.php"));
    }
    else{
        $codeCompte = $_SESSION["codeCompte"];
    }

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style/mesCommandes.css" rel="stylesheet" type="text/css">
    <title>Alizon</title>
</head>
<body>
    <?php include "includes/headerCon.php"?>
    <?php include 'includes/menuCompte.php'?>   

    <main>
    <h1>Vos commandes</h1>
    
    
    <?php 
     
    $lesCommandes = $bdd->prepare('SELECT * FROM Commande WHERE codeCompte =\''. $codeCompte .'\'');
    $lesCommandes->execute();
    $lesCommandes = $lesCommandes->fetchAll();
    //print_r($lesCommandes);
    // Si ne possède pas des commandes -> Pas de commandes
    // Sinon afficher son nb de commandes
    if($lesCommandes == null){
        ?>
        <div class="separateur"></div>
        <div class="vide">
                <h1> Vous n'avez pas passé de commande</h1>
                <a href="index.php">Revenir à l'accueil<a>
            </div>
        <?php
    }
    else {
        ?>
        <div class="cmd">
        <?php
        foreach($lesCommandes as $commande){
            $prixTTC = $commande["prixttctotal"];
            $prixHT = $commande["prixhttotal"];
            $date = date( 'd/m/Y', strtotime($commande["datecom"]));
            $idCom = $commande["numcom"];
            $lesProduits = $bdd->prepare('SELECT codeProduit FROM ProdUnitCommande WHERE numCom =\''.$idCom.'\' ORDER BY codeProduit LIMIT 3 ');
            $lesProduits->execute();
            
            ?>
            <div class="separateur"></div>
            <article>
                <div >
                <?php foreach($lesProduits as $prod){
                    $imgProd = $bdd->prepare("SELECT urlPhoto FROM Produit WHERE codeProduit =" .$prod['codeproduit']);
                    $imgProd->execute();
                    $imgProd = $imgProd->fetch();
                    
                    ?>
                
                    <img src="<?php echo $imgProd['urlphoto']?>" alt="Image produit"/>
                
                <?php }?>
                </div>
                <div class='info'>
                    <p><strong> Numéro de commande :</strong> <?php echo $idCom ?></p>
                    <p><strong> Date commande :</strong> <?php echo $date ?></p>
                </div>
                <a class='button' href="commande.php?numCom=<?php echo $idCom ?>">Voir la commande</a>
            </article>
            <?php
        }
    }
    ?>
    </div>
    

    </main>
    <?php include "includes/footer.php"?>
</body>
</html>