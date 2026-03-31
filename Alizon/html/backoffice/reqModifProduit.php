<?php
header('location:modifProduit.php?codeProduit='.$_GET["codeproduit"].'&erreur=succes');
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
$codeProduit = $_GET["codeproduit"];
$nomProd = $_POST["nom"];
$descProd = $_POST["description"];
if(isset($_POST["categorie"])){
    $catProd = $_POST["categorie"];
}
else{
    $catProd = $bdd->query("SELECT libelleCat FROM alizon.Categoriser WHERE codeProduit = '".$codeProduit."'")->fetch();
    $catProd = $catProd["libellecat"];
}
$qteProd = $_POST["qteStock"] ? $_POST["qteStock"] : NULL ;
if(isset($_POST["TVA"])){
    $tvaProd = $_POST["TVA"];
}
else{
    $tvaProd = $bdd->query("SELECT nomTVA FROM alizon.Produit WHERE codeProduit = '".$codeProduit."'")->fetch();
    $tvaProd = $tvaProd["nomtva"];
}
$seuilProd = $_POST["seuil"];
$prixProd = $_POST["prix"];

if(isset($_POST["origine"])){
    $origine = $_POST["origine"];
}
else{
    $origine = $bdd->query("SELECT Origine FROM alizon.Produit WHERE codeProduit = '".$codeProduit."'")->fetch();
    $origine = $origine["origine"];
}
if(isset($_POST["tarif"])){
    $tarif = $_POST["tarif"];
}
else{
    $tarif = $bdd->query("SELECT nomTarif FROM alizon.Produit WHERE codeProduit = '".$codeProduit."'")->fetch();
    $tarif = $tarif["nomtarif"];
}
//$tarif = $_POST["tarif"];

// TAILLE 
$spe1 = $_POST["spe1"] ? $_POST["spe1"] : NULL;
$spe2 = $_POST["spe2"] ? $_POST["spe2"] : NULL;
$spe3 = $_POST["spe3"] ? $_POST["spe3"] : NULL;

$res = $bdd->query("SELECT * FROM alizon.Produit WHERE libelleProd = '".$nomProd."'")->fetch();
$nomOldProd = $bdd->query("SELECT libelleProd FROM alizon.Produit WHERE codeProduit = '".$codeProduit."'")->fetch();
if($res=!$nomOldProd && $nomProd == $res["libelleprod"]){
        header('location:modifProduit.php?erreur=produit');
}
else{
    if($_FILES["photo"] && $_FILES["photo"]["tmp_name"] !=''){
            if($_FILES["photo"]["name"] != ""){
                $nomPhoto = $_FILES["photo"]["name"];
                $extension = $_FILES["photo"]["type"];
                $extension = substr($extension, strlen("image/"), (strlen($extension) - strlen("image/")));
                $chemin = "../img/photosProduit/".time().".".$extension;

                move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin);
                $stmt = $bdd->prepare("INSERT INTO alizon.Photo (urlPhoto) VALUES ('".$chemin."')");
                $stmt->execute();
                $stmt = $bdd->prepare("UPDATE alizon.Produit SET urlPhoto = '".$chemin."'");
            }
            else{
                $chemin = "../img/photosProduit/imgErr.jpg";
            }
    }
    else{
        $photoProd = $bdd->query("SELECT urlPhoto FROM alizon.Produit WHERE codeProduit = '".$codeProduit."'")->fetch();
        $chemin = $photoProd["urlphoto"];
    }

    

    $stmtP = $bdd->prepare("UPDATE alizon.Produit SET libelleProd = :libelleProd, descriptionProd = :descriptionProd, prixHT = :prixHT , spe1 = :spe1, spe2 = :spe2, spe3 = :spe3, qteStock = :qteStock, seuilAlerte = :seuilAlerte , nomTarif= :nomTarif, nomTVA = :nomTVA, urlPhoto = :photo, Origine = :origine, codeCompteVendeur = :codeCompteVendeur WHERE codeProduit=:codeProduit");
    
    $stmtC = $bdd->prepare("UPDATE alizon.Categoriser SET libelleCat = :libelleCat, codeProduit =:codeProduit)");
    

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
            ":codeCompteVendeur" => $codeCompte,
            ":codeProduit" => $codeProduit

        ));
    
    $prod = ($bdd->query("SELECT codeProduit FROM alizon.Produit WHERE libelleProd = '".$nomProd."'")->fetch());
    $codeProduit = $prod["codeproduit"];

    $stmtC->execute(array(
        ":libelleCat" => $catProd,
        ":codeProduit" => $codeProduit,
        ));   
}
?>