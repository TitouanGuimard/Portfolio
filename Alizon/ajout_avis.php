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

try {
    $codeProduit = $_POST['codeProduit'] ?? null;
    $commentaire = $_POST['commentaire'] ?? null;
    $noteProd     = $_POST['noteProd'] ?? null;
    $codeCompteCli = $_SESSION['codeCompte'] ?? null;

    $sql = "INSERT INTO Avis (commentaire, noteProd, codeCompteCli, codeProduit, datePublication)
            VALUES (:commentaire, :noteProd, :cli, :prod, null)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":commentaire" => $commentaire,
        ":noteProd"    => $noteProd,
        ":cli"         => $codeCompteCli,
        ":prod"        => $codeProduit
    ]);

    $numAvis = $pdo->lastInsertId();

    $uploadDir = __DIR__ . "/uploads/avis/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); 
    }

    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['name'] as $index => $name) {
            if ($_FILES['photos']['error'][$index] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['photos']['tmp_name'][$index];

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($ext, $allowedExtensions)) {
                    continue; 
                }

                $newName = "avis_" . uniqid() . "." . $ext;
                $destPath = $uploadDir . $newName;

                if (move_uploaded_file($tmpName, $destPath)) {
                    $relativePath = "uploads/avis/" . $newName;

                    $stmtPhoto = $pdo->prepare("INSERT INTO Photo (urlPhoto) VALUES (:p) RETURNING urlPhoto");
                    $stmtPhoto->execute([":p" => $relativePath]);
                    $urlPhoto = $stmtPhoto->fetchColumn();

                    $stmtJust = $pdo->prepare("INSERT INTO JustifierAvis (numAvis, urlPhoto) VALUES (:a, :p)");
                    $stmtJust->execute([
                        ":a" => $numAvis,
                        ":p" => $urlPhoto
                    ]);
                }
            }
        }
    }

    header("Location: dproduit.php?id=" . urlencode($codeProduit));
    exit();

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>