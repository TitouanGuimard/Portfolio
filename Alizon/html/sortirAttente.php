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

// Connexion à PostgreSQL

try {
    $ip = 'pgsql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';';
    $bdd = new PDO($ip, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    //"❌ Erreur de connexion : " . $e->getMessage();
    ?>
    <script>
        alert("Erreur lors du chargement");
    </script>
    <?php
        header('Location: http://localhost:8888/index.php');
        exit();
}
$socket = fsockopen("10.253.5.102", 8080);
fwrite($socket, "CONN test0 mdp0\n");
$data = fread($socket, 1024);
fwrite($socket, "VERIFATT\n");

$numComAtt = fread($socket, 1024);
echo $numComAtt;
if($numComAtt != "NULL"){
    
    fwrite($socket, "INIT ".$numComAtt . "\n");
    $bordereau = fread($socket, 100);
    $stmt = $bdd->prepare("UPDATE alizon.Commande SET bordereau = :bordereau WHERE numCom = :numCom ");
    $stmt->execute(array(
        ":bordereau" => $bordereau,
        ":numCom" => $numComAtt
    ));
    
//    $data = fread($socket, 1024);
}

fclose($socket);
