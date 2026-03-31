<?php session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande confirmée</title>
    <link href="./css/style/paiementFini.css" rel="stylesheet" type="text/css">
    <link href="./css/components/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 

require_once __DIR__ . '/_env.php';

loadEnv(__DIR__ . '/.env');

$host = getenv('PGHOST');
$port = getenv('PGPORT');
$dbname = getenv('PGDATABASE');
$user = getenv('PGUSER');
$password = getenv('PGPASSWORD');

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $bdd = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
    $estClient = false;
    if(isset($_SESSION["codeCompte"])){

        $clients = $bdd->query("SELECT ALL codeCompte FROM alizon.Client")->fetchAll();
        foreach($clients as $client){
            if($client["codecompte"] == $_SESSION["codeCompte"]){
                $estClient = true;
            }
        }
    }
    if(!isset($_SESSION["codeCompte"]) || !$estClient){
        exit(header("location:index.php"));
    }
?>

<?php include "includes/headerCon.php"?>
<main>
    <h1>Votre commande a été enregistrée !</h1>
    <a href="index.php" class="bouton">Retour à l'accueil</a>
    <a href="facture.php" class="bouton">Générer une facture</a>
    <a href="mesCommandes.php" class="bouton">Voir mes commandes</a>
</main>
<?php include "includes/footer.php"?>
</body>
</html>