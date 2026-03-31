<?php
    header("location:ConnexionClient.php");

    session_start();
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
        //echo "✅ Connecté à PostgreSQL ($dbname)";
    } catch (PDOException $e) {
        //echo "❌ Erreur de connexion : " . $e->getMessage();
    }

    $stmt = $bdd->prepare("UPDATE alizon.Client SET cmtBlq = NULL WHERE pseudo = '".$_SESSION["pseudo"]."' AND mdp = '".$_SESSION["mdp"]."'");
    $stmt->execute();
    #$req2= $bdd->query ("UPDATE alizon.Client SET cmtBlq=NULL WHERE pseudo = '".$_SESSION["pseudo"]."' AND mdp = '".$_SESSION["mdp"]."'");
?>