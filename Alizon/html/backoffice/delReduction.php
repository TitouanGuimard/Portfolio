<?php
if(isset($_GET["erreur"])){
        $erreur = $_GET["erreur"];
}
else{
        $erreur = NULL;
    }
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
    //"❌ Erreur de connexion : " . $e->getMessage();

        
}

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
$bdd->query('set schema \'alizon\'');

$codeCompte = $_SESSION["codeCompte"];

$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);


$codeProduit = $_GET['Produit'];
$page = $_GET['page'] ?? null;

$sql2 = "
SELECT r.idReduction, r.dateDebut, r.dateFin
FROM Reduction r
JOIN FaireReduction fr ON fr.idReduction = r.idReduction
WHERE fr.codeProduit = :codeProduit
LIMIT 1
";

$stmt = $bdd->prepare($sql2);
$stmt->execute(['codeProduit' => $codeProduit]);
$remise = $stmt->fetch(PDO::FETCH_ASSOC);

$hasReduction = ($remise !== false);

$stmtSuppr = $bdd->prepare("DELETE FROM alizon.FaireReduction WHERE codeProduit = :id");
$stmtSuppr->execute(['id' => $codeProduit]);

if($page == "create"){
    header('Location: ajouterRemise.php?Produit=' . $codeProduit);
    exit();
}if($page == "manage"){
    header('Location: remise.php');
    exit();
}else{
    header('Location: index.php');
    exit();
}
