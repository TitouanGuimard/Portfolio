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
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        $bdd = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
    $codeCompte = $_SESSION["codeCompte"];

    $mdpBase = ($bdd->query("SELECT mdp FROM alizon.Client WHERE codeCompte = '".$codeCompte."'")->fetch())["mdp"];

    if($mdpBase == md5($_POST["mdpPourValider"])){
        $_SESSION["mdpValide"] = 1;
        if(isset($_GET["modifMdp"])){
            if($_GET["modifMdp"] == 1){
                $_SESSION["nouveauMdp"] = $_POST["mdpModifCli"];
                $_SESSION["mdpValide"] = 0;
                exit(header('location:modifCompteCli.php?modif=mdp'));
                ;
            }
        }

        else{
            exit(header("location:infosCompte.php"));
        }
    }
    else{
        $_SESSION["mdpValide"] = -1;

        exit(header('location:infosCompte.php'));
             
        
    }
?>