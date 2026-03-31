<?php
session_start();
require_once __DIR__ . '/_env.php';
loadEnv(__DIR__ . '/.env');

$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur BDD : " . $e->getMessage());
}

$pdo->query("SET SCHEMA 'alizon'");


$codeAvis = $_GET['codeavis'] ?? null;

if ($codeAvis) {
    $sql = "SELECT * FROM JustifierAvis WHERE numAvis = $codeAvis";
    $photo = $pdo->query($sql)->fetch();
    if ($photo!=NULL){
        $sql = "DELETE FROM JustifierAvis WHERE numAvis = :codeAvis";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':codeAvis' => $codeAvis]);
    }
    

    $sql = "DELETE FROM Avis WHERE numAvis = :codeAvis";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':codeAvis' => $codeAvis]);
}
   
header("Location: dproduit.php?id=" . urlencode($_GET["codeproduit"]));
exit();
?>