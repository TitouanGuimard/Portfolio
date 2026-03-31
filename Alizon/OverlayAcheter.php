<?php
session_start();

//Connexion à la base de données.
require_once( __DIR__ . '/_env.php');
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
    // "❌ Erreur de connexion : " . $e->getMessage();
}
$bdd->query('set schema \'alizon\'');
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon</title>
    <link href="./css/style/overlayAchat.css" rel="stylesheet" type="text/css">
    <script src="js/achat.js"></script>
</head>

<body>
    <?php 


    if(isset($_SESSION['codeCompte'])){
        include 'includes/headerCon.php' ;
        $codeCompte = $_SESSION['codeCompte'];
    }else{
        include 'includes/header.php';
    }

    $codeProduit = isset($_GET["codeProd"]) ? $_GET["codeProd"] : null;

    $req = $bdd->prepare("SELECT * FROM Produit WHERE codeProduit = :codeproduit");
    $req->execute([':codeproduit' => $codeProduit]);
    $rep = $req->fetch(PDO::FETCH_ASSOC);

    if (!$rep) {
        // produit introuvable — définir des valeurs par défaut pour éviter les erreurs
        $img = '';
        $libelProduit = 'Produit introuvable';
    } else {
        $img = $rep["urlphoto"];
        $libelProduit = $rep["libelleprod"];
    }
    
    ?>

    <main>
        <section>
            <h4>Ajouter au panier</h4>
            <label class="label-retour btn-retour" for="retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>Retour</label>
                <INPUT id="retour" TYPE="button" VALUE="RETOUR" onclick="history.back();">
            <div class="content">
                <figure>
                    <a href="dproduit.php?id=<?= $codeProduit ?>"><img src="<?php echo $img ?>"width=300px /></a>
                    <figcaption><?php echo $libelProduit ?></figcaption>
                </figure>
                <?php if(!isset($_SESSION["codeCompte"])):?>
                    <p> Souhaitez vous ajouter ce produit au panier? <br>
                    En vous connectant, vous pouvez aussi l'acheter instantanément.</p>
                    <?php endif?>
                <?php if(isset($_SESSION["codeCompte"])):?>
                    <p> Souhaitez vous ajouter ce produit au panier ou l'acheter instantanément?</p>
                <?php endif?> 
                <div class="ligneCompteurBoutons">
                    <div class="compteur">
                        
                        <button onclick='modifier(this)' class="btn-moins"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus-icon lucide-minus"><path d="M5 12h14"/></svg></button>
                        
                        <p id="nbArt">1</p>
                        
                        <button onclick='modifier(this)' class="btn-plus"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg></button>
                    </div>
                    
                    <div class="boutons">
                        
                        <?php if(isset($_SESSION["codeCompte"])):?>
                        <button class="btnJaune" onclick="window.location.href ='AjouterAuPanier.php?codeProd=<?php echo $codeProduit?>&qteProd=' + encodeURIComponent(getQuantite()) + '&instant=1'">Acheter</button>
                        <?php endif?>
                        <button class="btnJaune" onclick="window.location.href = 'AjouterAuPanier.php?codeProd=<?php echo $codeProduit ?>&qteProd=' + encodeURIComponent(getQuantite()) + '&page=Catalogue.php';">Ajouter au panier</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php';?>
    <script>
        function fermerPopUpPanier(){
            window.location.href = "index.php";
        }   
    </script>

</body>

</html>