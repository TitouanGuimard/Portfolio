<?php
session_start();


//$codeCompte = $_SESSION["codecompte"];
//Connexion à la base de données.
require_once  '../_env.php';
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
    //echo "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
$estVendeur = false;
if(isset($_SESSION["codeCompte"])){

    $vendeurs = $bdd->query("SELECT ALL codeCompte FROM alizon.Vendeur")->fetchAll();
    foreach($vendeurs as $vendeur){
        if($vendeur["codecompte"] == $_SESSION["codeCompte"]){
            $estVendeur = true;
        }
    }
}
if(!$estVendeur || !isset($_SESSION["codeCompte"])){
    exit(header("location:connexionVendeur.php"));
}
else{
    $codeCompte = $_SESSION["codeCompte"];
}
$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon BackOffice</title>
    <link rel="stylesheet" href="../css/style/backoffice/accueilBack.css" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
    <link href="../css/style/backoffice/promotion.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include '../includes/backoffice/header.php';?>
    <main><?php
        include '../includes/backoffice/menuCompteVendeur.php';
    $bdd->query("SET SCHEMA 'alizon'"); ?>
    <?php include '../includes/backoffice/menu.php'; ?>
    <div class="right-content"> 
        <?php
        $stmt = $bdd->prepare('SELECT * FROM alizon.FairePromotion JOIN alizon.Promotion ON alizon.FairePromotion.idPromotion = alizon.Promotion.idPromotion JOIN alizon.Produit ON alizon.FairePromotion.codeProduit = alizon.Produit.codeProduit WHERE alizon.Produit.codeCompteVendeur = \'' . $codeCompte . '\'');
        $stmt->execute();
        $liste_reduc = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="mes-produits">
            <h1>Mes produits en promotion</h1>
                <?php 
                $rows = $liste_reduc;
                if (count($rows) != 0) {
                    for ($i = 0; $i < count($rows); $i++) {
                        $row = $rows[$i];
                    ?>
                    <div class="tous-les-produits">
                        <div class="produit">
                            <?php
                            echo '<a href="./ficheProduit.php?Produit='.htmlspecialchars($row['codeproduit']).'">';
                                echo '<img src="../'.$row['urlphoto'].'" alt="Photo de '.htmlspecialchars($row['libelleprod']).'"> </a>';
                                echo '<p class="nomArticle">'.htmlspecialchars($row['libelleprod']).'</p>';
                                echo '<div class="infoProduit">';
                                    echo '<p class="prixReduc">'.$row['prixht'].'€</p>';
                                    echo '<p class="qteStock">Stock : '.htmlspecialchars($row['qtestock']).'</p>';?>
                                </div>
                                <?php
                                echo '<a href="modifProduit.php?codeProduit='.$row['codeproduit'].'" class="btnModifReduc">'?>
                                        <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="15.0422" cy="15.0422" r="15.0422" fill="#6CA6E9"/>
                                            <path d="M14.761 21.7632H20.9583H14.761Z" fill="#6CA6E9"/>
                                            <path d="M14.761 21.7632H20.9583" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21.0498 11.471C21.432 11.0888 21.6468 10.5705 21.6469 10.0299C21.647 9.48937 21.4323 8.97093 21.0501 8.58866C20.6679 8.20638 20.1496 7.99158 19.609 7.99152C19.0685 7.99145 18.5501 8.20612 18.1678 8.58829L8.51832 18.2401C8.35044 18.4074 8.2263 18.6135 8.1568 18.8402L7.20169 21.9868C7.183 22.0494 7.18159 22.1158 7.19761 22.179C7.21362 22.2423 7.24646 22.3001 7.29264 22.3462C7.33883 22.3923 7.39663 22.425 7.45992 22.4409C7.52322 22.4569 7.58963 22.4553 7.65213 22.4366L10.7995 21.4821C11.0259 21.4133 11.2319 21.2899 11.3996 21.1228L21.0498 11.471Z" fill="#6CA6E9" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                <!-- <?php echo '<a href="delPromo.php?Produit='.$row['codeproduit'].'" class="btnSupprReduc">'?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 31 31" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><circle cx="15.0422" cy="15.0422" r="15.0422" fill="rgb(255, 0, 0)"/><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </a> -->
                                <?php echo '<p class="infoPromo">En promotion du '.$row['datedebut'].' au '.$row['datefin'].'</p>';?>
                                <?php echo '<a href="delPromo.php?Produit='.$row['codeproduit'].'&page=manage" class="btnSupprReduc">Supprimer la promo</a>';?>
                        
                                </div>
                            <?php } ?>
                            </a>
                        </div>
                    </div>
                <?php 
                }else {
                    echo "<p>Vous n'avez pas de produits en promotion pour le moment.</p>";
                } ?>
        </div>
    </main>
    <?php include '../includes/backoffice/footer.php'; ?>
    <!-- <script src="../js/preview-img.js"></script> -->
    <script src="../js/overlayCompteVendeur.js"></script>
</body>
</html>