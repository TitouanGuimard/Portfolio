<?php

if(isset($_GET["erreur"])){
        $erreur = $_GET["erreur"];
}
else{
        $erreur = NULL;
    }
session_start();
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
$bdd->query('set schema \'alizon\'');

$codeCompte = $_SESSION["codeCompte"];

$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);


$codeProduit = $_GET['Produit'];

$sql2 = "
SELECT r.idReduction, r.dateDebut, r.dateFin, r.remise
FROM Reduction r
JOIN FaireReduction fr ON fr.idReduction = r.idReduction
WHERE fr.codeProduit = :codeProduit
LIMIT 1
";

$stmt = $bdd->prepare($sql2);
$stmt->execute(['codeProduit' => $codeProduit]);
$remise = $stmt->fetch(PDO::FETCH_ASSOC);

$hasRemise = ($remise !== false);
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style/backoffice/ajouterPromotion.css" rel="stylesheet" type="text/css">
    <link href="../css/style/backoffice/ficheProduit.css" rel="stylesheet" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="img/favicon_alizon.png" type="image/x-icon">
    <title>Alizon Back Office - Modifier la fiche produit</title>
</head>

<body>
    <?php include("../includes/backoffice/header.php"); ?>
        <!-- <label class="label-retour btn-retour" for="retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>Retour</label>
        <INPUT id="retour" TYPE="button" VALUE="RETOUR" onclick="history.back();"> -->
        <!-- <a href="index.php" class="btn-retour">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left">
                <rect width="18" height="18" x="3" y="3" rx="2"/>
                <path d="m14 16-4-4 4-4"/>
            </svg>
            Retour
        </a> -->

<main>
    <?php include '../includes/backoffice/menuCompteVendeur.php'; ?>
    <section class="nav-btn">
        <?php $code_produit=$_GET["Produit"]; ?>
        <a href="index.php" class="btnacc">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Accueil
        </a>
        <a class="btnacc" href="#" onclick="event.preventDefault(); history.back();" >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>
            Retour au produit
        </a>
    </section>
    <div class="right-content">
        <form action="reqModifierRemise.php"
            method="post"
            enctype="multipart/form-data"
            class="form-promo">

            <h2>
                <?= $hasRemise ? "Modifier la Remise" : "Créer une Remise" ?>
            </h2>

            <!-- Toujours envoyé -->
            <input type="hidden" name="codeProduit" value="<?= $codeProduit ?>">

            <!-- Seulement si remise existante -->
            <?php if ($hasRemise): ?>
                <input type="hidden" name="idRemise" value="<?= $remise['idreduction'] ?>">
            <?php endif; ?>

            

            <!-- DATE DE DÉBUT -->
            <label for="dateD">Date de début</label>
            <input type="date"
                id="dateD"
                name="dateD"
                value="<?= $hasRemise ? $remise['datedebut'] : '' ?>"
                required>
            <span class="cache">La date de début ne peut pas être antèrieur à aujourd'hui</span>

            <!-- DATE DE FIN -->
            <label for="dateF">Date de fin</label>
            <input type="date"
                id="dateF"
                name="dateF"
                value="<?= $hasRemise ? $remise['datefin'] : '' ?>"
                required>
            <span class="cache" id="errorDate">La date de fin doit être postérieure à la date de début.</span>

            <label for="remise">Pourcentage de remise (%)</label>
            <input type="number" 
                id="remise" 
                name="remise" 
                min="1" 
                max="99" 
                value="<?= $hasRemise ? $remise['remise'] : '' ?>" 
                required>
            <span class="cache" id="errorRemise">La remise doit être comprise entre 1 et 99%.</span>

            <!-- SUBMIT -->
            <input type="submit" value="<?= $hasRemise ? "Mettre à jour la remise" : "Créer la remise" ?>" class="bouton"/>
    </form>

        <!-- <form action="reqAjouterPromotion.php" method="post" enctype="multipart/form-data">
            <h2>Ajouter une promotion</h2>
            <div class="dropreview">
                <div id="dropZone">
                    <span>Glissez une image ici ou cliquez</span>
                    <input type="file" name="photo" id="photoProd" accept="image/*" hidden>
                </div>
                <!-- <input type="file" name="photo" id="photoProd" accept="image/*"/>
                <div id="preview">

                </div>
            </div>

            <label for="dateD">Date de début de la promotion</label>
            <input type="date" name="dateD" placeholder="" id="dateD" required/> 

            <label for="dateF">Date de fin de la promotion</label>
            <input type="date" name="dateF" placeholder="" id="dateF" required/>
            <input type="hidden" name="idproduit" value="<?php echo $code_produit; ?>">

            <input class="bouton" type="submit" id="creerPromo" value="Créer la promotion"/>
        </form> -->
    </div>
</main>
    <?php include('../includes/backoffice/footer.php');?>
    <script src="../js/preview-img.js"></script>
    <script>

        let dateD = document.getElementById("dateD");
        let dateF = document.getElementById("dateF");
        let dateToday = new Date().toISOString().split('T')[0];

        console.log(dateToday);

        dateD.addEventListener("focusout", verifDateD);
        dateF.addEventListener("focusout", verifDateF);

        function verifDateD(evt){
            if(evt.type === "focusout"){
                if((dateF.value <= dateD.value) && (dateD.value < dateToday)){
                    dateD.classList.add("invalid");
                }else{
                    dateD.classList.remove("invalid");
                }
            }
        }

        function verifDateF(evt){
            if(evt.type === "focusout"){
                if(dateF.value <= dateD.value){
                    evt.target.classList.add("invalid");
                }else{
                    evt.target.classList.remove("invalid");
                }
            }
        }

        document.querySelector("form").addEventListener("submit", function(e){
            if(document.querySelectorAll(".invalid").length > 0){
                e.preventDefault();
            }
        });
</script>
<script src="../js/overlayCompteVendeur.js"></script>

</body>
</html>