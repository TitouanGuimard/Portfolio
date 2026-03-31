<?php
//Connexion à la base de données.
require_once __DIR__ . '/_env.php';
loadEnv(__DIR__ . '/.env');

// Récupération des variables
$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');
session_start();
global $bdd;
// Connexion à PostgreSQL

try {
    $ip = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';';
    $bdd = new PDO($ip, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    "❌ Erreur de connexion : " . $e->getMessage();
    
}
$bdd->query('set schema \'alizon\'');

$idPanier = $_GET["Panier"];
$codeProduit = $_GET["Produit"];
$action = $_GET["Action"];

switch($action){
    case 'supprimerProduit':
        $stmt = $bdd->prepare("DELETE FROM ProdUnitPanier WHERE idPanier = :idPanier AND codeProduit = :codeProduit");
        $stmt->execute([
            ':idPanier' => $idPanier,
            ':codeProduit' => $codeProduit
        ]);
        break;
    case 'augmenterProduit':
        $stmt = $bdd->prepare("UPDATE ProdUnitPanier SET qteProd = qteProd + 1 WHERE idPanier = :idPanier AND codeProduit = :codeProduit");
        $stmt->execute([
            ':idPanier' => $idPanier,
            ':codeProduit' => $codeProduit
        ]);
        break;
    case 'reduireProduit':
        $stmt = $bdd->prepare("UPDATE ProdUnitPanier SET qteProd = qteProd - 1 WHERE idPanier = :idPanier AND codeProduit = :codeProduit");
        $stmt->execute([
            ':idPanier' => $idPanier,
            ':codeProduit' => $codeProduit
        ]);
        break;
    case 'supprimerPanier':
        $stmtProd = $bdd->prepare("DELETE FROM ProdUnitPanier WHERE idPanier = :idPanier");
        $stmtPanier = $bdd->prepare("DELETE FROM Panier WHERE idPanier = :idPanier");
        $stmtProd->execute([
            ':idPanier' => $idPanier
        ]);

        $stmtPanier->execute([
            ':idPanier' => $idPanier
        ]);
        unset($_SESSION["idPanier"]);
        break;
    
}
?>