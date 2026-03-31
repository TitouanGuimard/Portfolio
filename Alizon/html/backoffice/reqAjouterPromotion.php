<?php
$code_produit = $_POST["idproduit"];
session_start();
$codeCompte = $_SESSION["codeCompte"];
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

$bdd->query("SET SCHEMA 'alizon'");


$dateD = $_POST["dateD"];
$dateF = $_POST["dateF"];


$sql = "INSERT INTO Promotion (dateDebut, dateFin) Values (:dateDebut, :dateFin) RETURNING idPromotion";
$stmt = $bdd->prepare($sql);
$stmt->execute([
    "dateDebut" => $dateD,
    "dateFin"   => $dateF
]);

$numProm = $stmt->fetchColumn();



$uploadDir = __DIR__ . "/img/";

if (!empty($_FILES['photo']['name'][0])) {
        foreach ($_FILES['photo']['name'] as $index => $name) {
            if ($_FILES['photo']['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['photo']['tmp_name'][$index];

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowedExtensions)) {
                    continue; 
                }

                $newName = "promo_" . uniqid() . "." . $ext;
                $destPath = $uploadDir . $newName;

                if (move_uploaded_file($tmpName, $destPath)) {
                    $relativePath = "/img/" . $newName;

                    $stmtPhoto = $bdd->prepare("INSERT INTO Photo (urlPhoto) VALUES (:p) RETURNING urlPhoto");
                    $stmtPhoto->execute([":p" => $relativePath]);
                    $urlPhoto = $stmtPhoto->fetchColumn();

                    $stmtJust = $bdd->prepare("INSERT INTO FairePromotion (codeProduit, idPromotion, urlPhoto) VALUES (:codeProduit, :idPromotion, :p)");
                    $stmtJust->execute([
                        ":codeProduit" => $code_produit,
                        "idPromotion"  => $numProm,
                        ":p" => $urlPhoto
                    ]);
                }
            }
        }
    }

header('location:/backoffice/index.php');
exit;

