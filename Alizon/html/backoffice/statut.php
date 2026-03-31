<?php 
    //$_GET["codeProduit"]=1;
    //include '../includes/backoffice/header.php';
    require_once('../_env.php');
    

    // Charger le fichier .env
    loadEnv('../.env');

    // Récupérer les variables
    $host = getenv('PGHOST');
    $port = getenv('PGPORT');
    $dbname = getenv('PGDATABASE');
    $user = getenv('PGUSER');
    $password = getenv('PGPASSWORD');

    // Connexion à PostgreSQL

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        $bdd = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $bdd->query('set schema \'alizon\'');

    $action = $_GET["Action"];
    $codeProduit = $_GET["Produit"];

    switch($action){
        case 'retirer':
            $stmt = $bdd->prepare("UPDATE Produit SET Disponible = false WHERE codeProduit = :codeProduit");
            
            $stmtPUC = $bdd->prepare("DELETE FROM ProdUnitPanier WHERE codeProduit = :codeProduit");
            
            $stmt->execute([
            ':codeProduit' => $codeProduit
            ]);
            $stmtPUC->execute([
            ':codeProduit' => $codeProduit
            ]);
            
            break;
        case 'ajouter':
            $stmt = $bdd->prepare("UPDATE Produit SET Disponible = true WHERE codeProduit = :codeProduit");
            $stmt->execute([
            ':codeProduit' => $codeProduit
            ]);
            break;
        case 'supprimer':
            
            $stmt = $bdd->prepare("select urlPhoto from Produit where codeProduit = :codeProduit");
            $stmt->execute([
            ':codeProduit' => $codeProduit
            ]);
            $urlPhoto = $stmt->fetch();
            $stmt = $bdd->prepare("delete from categoriser where codeProduit = :codeProduit");
            $stmt->execute([
            ':codeProduit' => $codeProduit
            ]);
            
            $stmt = $bdd->prepare("delete from Produit where codeProduit = :codeProduit");
            $stmt->execute([
            ':codeProduit' => $codeProduit
            ]);
            
            $stmt = $bdd->prepare("delete from Photo where urlPhoto = :urlPhoto");
            $stmt->execute([
            ':urlPhoto' => $urlPhoto['urlphoto']
            ]);

            break;
    }
?>

