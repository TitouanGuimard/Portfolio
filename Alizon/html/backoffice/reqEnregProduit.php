<?php
header('location:ajouterProduit.php?erreur=succes');
session_start();
$codeCompte = $_SESSION["codeCompte"];
//Connexion à la base de données.
require_once('../_env.php');
loadEnv('../.env');

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
    
}

$nomProd = $_POST["nom"];

$descProd = $_POST["description"];
$catProd = $_POST["categorie"];
$qteProd = $_POST["qteStock"] ? $_POST["qteStock"] : NULL ;
$tvaProd = $_POST["TVA"];
$seuilProd = $_POST["seuil"];
$prixProd = $_POST["prix"];
$origine = $_POST["origine"];
$tarif = $_POST["tarif"];

// TAILLE 
$spe1 = $_POST["spe1"] ? $_POST["spe1"] : NULL;
$spe2 = $_POST["spe2"] ? $_POST["spe2"] : NULL;
$spe3 = $_POST["spe3"] ? $_POST["spe3"] : NULL;

$res = $bdd->query("SELECT * FROM alizon.Produit WHERE libelleProd = '".$nomProd."'")->fetch();
if($res){
        header('location:ajouterProduit.php?erreur=produit');
}
else{
    
    if($_FILES["photo"]){
            if($_FILES["photo"]["name"] != ""){
                $nomPhoto = $_FILES["photo"]["name"];
                $extension = $_FILES["photo"]["type"];
                $extension = substr($extension, strlen("image/"), (strlen($extension) - strlen("image/")));
                $chemin = "../img/photosProduit/".time().".".$extension;

                move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin);
                $stmt = $bdd->prepare("INSERT INTO alizon.Photo (urlPhoto) VALUES (:urlPhoto)");
                $stmt->execute(array(
                    ":urlPhoto" => $chemin
                ));
            }
            else{
                $chemin = "../img/photosProduit/imgErr.jpg";

            }
    }
    

    $stmtP = $bdd->prepare("INSERT INTO alizon.Produit(libelleProd, descriptionProd, prixHT, spe1, spe2, spe3, qteStock, seuilAlerte, nomTarif, nomTVA, urlPhoto,Origine, codeCompteVendeur) VALUES (:libelleProd, :descriptionProd, :prixHT, :spe1, :spe2, :spe3, :qteStock, :seuilAlerte,:nomTarif, :nomTVA, :photo, :origine, :codeCompteVendeur)");
    
    $stmtC = $bdd->prepare("INSERT INTO alizon.Categoriser(libelleCat,codeProduit) VALUES (:libelleCat,:codeProduit)");
    
    

    $stmtP->execute(array(
            ":libelleProd" => $nomProd,
            ":descriptionProd" => $descProd,
            ":prixHT" => $prixProd,
            ":spe1" => $spe1,
            ":spe2" => $spe2,
            ":spe3" => $spe3,
            ":qteStock" => $qteProd,
            ":seuilAlerte" => $seuilProd,
            ":nomTarif" => $tarif,
            ":nomTVA" => $tvaProd,
            ":photo" => $chemin,
            ":origine" => $origine,
            ":codeCompteVendeur" => $codeCompte

        ));
    
    $prod = ($bdd->query("SELECT codeProduit FROM alizon.Produit WHERE libelleProd = '".$nomProd."'")->fetch());
    $codeProduit = $prod["codeproduit"];

    $stmtC->execute(array(
        ":libelleCat" => $catProd,
        ":codeProduit" => $codeProduit,
        ));   
}
?>