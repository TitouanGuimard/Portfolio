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

$signalement=$_POST['raison'];
$codeAvis = $_GET['codeavis'];
$codeproduit = $_GET['codeproduit'];

print_r($codeAvis . " " . $signalement . " " . $codeproduit);


$insert = $pdo->prepare("INSERT INTO Signalement (motif, numAvis, dateSignalement) VALUES (:signalement, :codeAvis, NOW())");
$insert->execute([
    ':signalement' => $signalement,
    ':codeAvis' => $codeAvis
]);
header("Location: dproduit.php?id=" . urlencode($codeproduit));
exit();
?>