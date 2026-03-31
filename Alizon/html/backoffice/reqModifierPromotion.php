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
$idPromotion = $_POST['idPromotion'] ?? null;
$dateD = $_POST['dateD'];
$dateF = $_POST['dateF'];
$ancienneImage = $_POST['ancienneImage'] ?? null;


if ($idPromotion) {
    // UPDATE
    $sql = "UPDATE Promotion
            SET dateDebut = :d, dateFin = :f
            WHERE idPromotion = :id";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'd' => $dateD,
        'f' => $dateF,
        'id' => $idPromotion
    ]);
} else {
    // INSERT
    $sql = "INSERT INTO Promotion (dateDebut, dateFin)
            VALUES (:d, :f)
            RETURNING idPromotion";

    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        'd' => $dateD,
        'f' => $dateF
    ]);

    $idPromotion = $stmt->fetchColumn();

    $bdd->prepare(
        "INSERT INTO FairePromotion (codeProduit, idPromotion)
         VALUES (:p, :i)"
    )->execute([
        'p' => $codeProduit,
        'i' => $idPromotion
    ]);
}


$uploadDir = __DIR__ . "/../img/promo/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['photo']['name'])) {

    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif', 'webp'];

    if (in_array($ext, $allowed)) {

        $newName = "pr_" . uniqid() . "." . $ext;
        $dest = $uploadDir . $newName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
            header('Location: ajouterPromotion.php?Produit='.$codeProduit.'&erreur=image');
            exit;
        }

        $url = "img/promo/" . $newName;

        $bdd->prepare("INSERT INTO Photo (urlPhoto) VALUES (:u)")
            ->execute(['u' => $url]);

        $bdd->prepare("
            UPDATE FairePromotion
            SET urlPhoto = :u
            WHERE codeProduit = :p AND idPromotion = :i
        ")->execute([
            'u' => $url,
            'p' => $codeProduit,
            'i' => $idPromotion
        ]);
    }
}


header('Location: index.php?success=promo');
exit;
?>

