<?php
if(!isset($_GET["instant"])){
    header("location:".$_GET["page"]."?ajout=1");

}
else{
    header("location:paiementAdr.php");
}
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
    // "❌ Erreur de connexion : " . $e->getMessage();
}
$bdd->query('set schema \'alizon\'');
?>
<?php
session_start();
print_r($_SESSION);
#Vérifier s'il y a déjà un panier pour la session en cours
if(!isset($_SESSION["idPanier"])){
    if(isset($_SESSION["codeCompte"])){
        $stmt = $bdd->prepare("INSERT INTO alizon.Panier (codeCompte) VALUES ('".$_SESSION["codeCompte"]."')");
    }
    else{
        $stmt = $bdd->prepare("INSERT INTO alizon.Panier (prixTTCtotal) VALUES (0)");
    }

    $stmt->execute();
    $idPanier = $bdd->lastInsertId();

    $_SESSION["idPanier"] = $idPanier;
}
else{
    echo "ya un panier";
}

echo "Salut : " . $_GET["codeProd"];
#Vérifier si il y a déjà un exemplaire dans le panier
$prodPanier = $bdd->query("SELECT codeProduit, qteProd FROM alizon.ProdUnitPanier WHERE idPanier = '".$_SESSION["idPanier"]."' AND codeProduit = '".$_GET["codeProd"]."'")->fetch();
if($prodPanier != false){
    #On augmente juste la qteProd de cet article
    if(isset($_GET['qteProd'])){
        $qteProd = $prodPanier["qteprod"] + $_GET['qteProd'];
    }else    
        $qteProd = $prodPanier["qteprod"] + 1;

    $stmt = $bdd->prepare("UPDATE alizon.ProdUnitPanier set qteProd = '".$qteProd."' WHERE codeProduit = '".$_GET["codeProd"]."' AND idPanier = '".$_SESSION["idPanier"]."'");

}

else{
    if(isset($_GET['qteProd'])){
        $qteProd = $_GET['qteProd'];
    }else 
        $qteProd = 1;

    $stmt = $bdd->prepare("INSERT INTO alizon.ProdUnitPanier (idPanier, codeProduit, qteProd) VALUES ('".$_SESSION["idPanier"]."', '".$_GET["codeProd"]."', '".$qteProd."')");

}
    $stmt->execute();


    #alert("Le produit a bien été ajouté au panier.");
   