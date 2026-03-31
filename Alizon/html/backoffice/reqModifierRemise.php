<?php
session_start();
require_once('../_env.php');
loadEnv('../.env');

$bdd = new PDO(
    "pgsql:host=".getenv('PGHOST').";port=".getenv('PGPORT').";dbname=".getenv('PGDATABASE'),
    getenv('PGUSER'),
    getenv('PGPASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$bdd->query("SET SCHEMA 'alizon'");

$codeProduit = $_POST['codeProduit'];
$idReduction = $_POST['idRemise'] ?? null;
$remise = $_POST['remise'];
$dateD = $_POST['dateD'];
$dateF = $_POST['dateF'];


if ($idReduction) {
    // UPDATE
    $sql = "UPDATE Reduction
            SET dateDebut = :d, dateFin = :f, remise = :r
            WHERE idReduction = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'd' => $dateD,
        'f' => $dateF,
        'r' => $remise,
        'id' => $idReduction
    ]);
} else {
    // INSERT
    $sql = "INSERT INTO Reduction (dateDebut, dateFin, remise)
            VALUES (:d, :f, :r)
            RETURNING idReduction";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'd' => $dateD,
        'f' => $dateF,
        'r' => $remise
    ]);

    $idReduction = $stmt->fetchColumn();

    $bdd->prepare(
        "INSERT INTO FaireReduction (codeProduit, idReduction)
         VALUES (:p, :i)"
    )->execute([
        'p' => $codeProduit,
        'i' => $idReduction
    ]);
}

header('Location: index.php?success=promo');
exit;
?>

