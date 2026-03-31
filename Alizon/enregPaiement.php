<?php 
    session_start();
    require_once __DIR__ . '/_env.php';
    header("location:paiementFini.php");
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
    $bdd->query("SET SCHEMA 'alizon'");

    #Modification de l'adresse

    $codeCompte = $_SESSION["codeCompte"];


    $nom = $_POST["nomTitulaireCB"];
    $numCarte = $_POST["numCB"];
    $expDate = $_POST["expDate"];
    $cvc = $_POST["cvc"];

    $stmt = $bdd->prepare("INSERT INTO alizon.Carte (numCarte, nomTit, cvc, dateExp) VALUES (:numCarte, :nomTit, :cvc, :dateExp)");
    $stmt->execute(array(
        ":numCarte" => $numCarte,
        ":nomTit" => $nom,
        ":cvc" => $cvc,
        ":dateExp" => $expDate
    ));
    $idCarte = $bdd->lastInsertId();
    $idPanier = ($bdd->query("SELECT idPanier FROM alizon.Panier WHERE codeCompte = '".$codeCompte."'")->fetch())["idpanier"];

    $stmt = $bdd->prepare("INSERT INTO alizon.Commande (dateCom, codeCompte, idCarte) VALUES (:dateCom, :codeCompte, :idCarte)");
    $stmt->execute(array( 
        ":dateCom" => date("Y-m-d H:i:s"),
        ":codeCompte" => $codeCompte,
        ":idCarte" => $idCarte
    ));

    $numCom = $bdd->lastInsertId();
    $_SESSION["numCom"] = $numCom;
    $prodUnitPan = $bdd->query("SELECT ALL * FROM alizon.ProdUnitPanier WHERE idPanier = '".$idPanier."'")->fetchAll();
    foreach($prodUnitPan as $prodUnit){
        $prixTTCProd = $bdd->prepare("SELECT prixTTC FROM alizon.Produit WHERE codeProduit = '".$prodUnit["codeproduit"]."'");
        $prixTTCProd->execute();
        $prixTTCProd = $prixTTCProd->fetch();
        $stmt = $bdd->prepare("UPDATE alizon.Produit SET qteStock = qteStock - 1 WHERE codeProduit = :codeProd");
        $stmt->execute(array(
            "codeProd" => $prodUnit["codeproduit"]
        ));
        $stmt = $bdd->prepare("INSERT INTO alizon.ProdUnitCommande (codeProduit, numCom, qteProd) VALUES (:codeProduit, :numCom, :qteProd)");
        $stmt->execute(array(
            ":codeProduit" => $prodUnit["codeproduit"],
            ":numCom" => $numCom,
            ":qteProd" => $prodUnit["qteprod"]
        ));
    }
    $stmt = $bdd->prepare("INSERT INTO alizon.AdrLiv (idAdresse, numCom) VALUES (:idAdresse, :numCom)");
    $stmt->execute(array(
        ":idAdresse" => $_SESSION["idAdresse"],
        ":numCom" => $numCom
    ));
    $_SESSION["numCom"] = $numCom;
    $stmt = $bdd->prepare("DELETE FROM alizon.Panier WHERE codeCompte = '".$codeCompte."'");
    $stmt->execute();
    
    unset($_SESSION["idPanier"]);
    
    //LIEN DELIVRAPTOR
    
    $socket = fsockopen("10.253.5.102", 8080);
    fwrite($socket, "CONN test0 mdp0");
    $data = fread($socket, 1024);
    var_dump($data);
    fwrite($socket, "INIT ".$_SESSION["numCom"]);
    $bordereau = fread($socket, 100);
    echo $bordereau;
    $stmt = $bdd->prepare("UPDATE alizon.Commande SET bordereau = ".$bordereau." WHERE numCom = ".$numCom);
    $stmt->execute();
    fclose($socket);
    exit(header("location:paiementFini.php"));





?>
