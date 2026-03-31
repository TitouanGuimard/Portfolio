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
$estClient = false;
if(isset($_SESSION["codeCompte"])){

    $clients = $bdd->query("SELECT ALL codeCompte FROM alizon.Client")->fetchAll();
    foreach($clients as $client){
        if($client["codecompte"] == $_SESSION["codeCompte"]){
            $estClient = true;
        }
    }
}

$bdd->query('set schema \'alizon\'');

    $nomRecherche = $_GET['rechercheNom'] ??  null; 
    $recherche = $_GET['recherche'] ?? null;
    $pmax = $_GET['pmax'] ?? null; 
    $pmin = $_GET['pmin'] ??  null; 
    $pmax = $_GET['pmax'] ?? null; 
    $vendeur = $_GET['vendeur'] ?? null;
    
    $nt = isset($_GET['nt']) ? (int)$_GET['nt'] : null;

    $categorie = $_GET["cat"] ?? $_POST["cat"] ?? null;
    // traitement de catégorie.
    if(isset($categorie)){ 
        $cat = strtoupper(substr($categorie, 0, 1)) . substr($categorie, 1, strlen($categorie));
        $base = "SELECT p.codeProduit, p.libelleProd, p.prixTTC, p.urlPhoto, p.noteMoy, p.descriptionProd, p.origine
                FROM Produit p
                JOIN Categoriser c ON p.codeProduit = c.codeProduit
                WHERE c.libelleCat = :cat
                AND p.Disponible = true
                AND unaccent(p.libelleProd) 
                ILIKE unaccent(:recherche)";
    }else {
        $base= 'SELECT codeProduit, libelleProd, prixTTC, urlPhoto, noteMoy, descriptionProd, origine, noteMoy FROM Produit where Disponible = true AND unaccent(libelleProd) ILIKE unaccent(:recherche)' ;
        $cat = null;
    }
    
    //Liste des recherches SQL 
    $pxCrois = ' ORDER BY prixTTC';
    $pxDecrois = ' ORDER BY prixTTC DESC';
    $ntCrois= ' ORDER BY noteMoy';
    $ntDecrois= ' ORDER BY noteMoy DESC';
    /* pas  à implementer */
    $dtCrois= ' ORDER BY dateModifProduit';
    $dtDecrois= ' ORDER BY dateModifProduit DESC';

    $cVend = ' AND codeCompteVendeur = ';
    $note = ' AND noteMoy >= ';
    $prixMin = ' AND prixTTC > '. $pmin;
    $prixMax = ' AND prixTTC < '. $pmax ;
    if($pmin !==null && $pmax !== null){
        $base = $base.$prixMin.$prixMax;
    }else if($pmin !==null && $pmax === null){
       $base = $base.$prixMin; 
    }else if($pmin ===null && $pmax !== null){
       $base = $base.$prixMax; 
    }
    
    if($nt !== null){
        $base = $base.$note.$nt;
    }
    if($vendeur !== null){
        $base = $base.$cVend.$vendeur;
    }

    //TODO faire avec le js, selon ce qui est séléctionner pour le tri, 
    
    $tri = $_GET["tri"] ?? null;
    switch ($tri){
        case 'pxCrois':
            $sql = $base.$pxCrois;
            break;
        case 'pxDecrois':
            $sql = $base.$pxDecrois;
            break;
        case 'ntCrois':
            $sql = $base.$ntCrois;
            break;
        case 'ntDecrois':
            $sql = $base.$ntDecrois;
            break;
        /*case 'dtCrois':
            $sql = $base.$dtCrois;
            break;
        case 'dtDecrois':
            $sql = $base.$dtDecrois;
            break;
        */
        default:
            $sql = $base;
            break;
    }
    $stmtMax = $bdd->query('SELECT MAX(prixTTC) from produit where disponible = true')->fetch();
    $maxPrix = round($stmtMax['max'] + 1);

    if(isset($_POST["q"])){
        $recherche = "%" . $_POST["q"] . "%" ; // formatage pour la requette sql
        $nomRecherche = $_POST["q"];
        $stmt = $bdd->prepare($sql);
        if(isset($categorie)){
            $stmt->execute([':cat' => $cat ,
        ':recherche' => $recherche]);
        }else{
            $stmt->execute([':recherche' => $recherche]);
        }
        $resRecherche =  $stmt->fetchAll();
    }
    if(isset($_GET["recherche"])){
        $recherche = "%" . $_GET["recherche"] . "%" ; // formatage pour la requette sql
        $nomRecherche = $_GET['rechercheNom'];
        $stmt = $bdd->prepare($sql);
        if(isset($categorie)){
            $stmt->execute([':cat' => $cat ,
        ':recherche' => $recherche]);
        }else{
            $stmt->execute([':recherche' => $recherche]);
        }
        $resRecherche =  $stmt->fetchAll();
    }


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style/catalogue.css" rel="stylesheet" type="text/css">

    <title><?php echo $nomRecherche?></title>
</head>
<body>
    <?php

    if(isset( $_SESSION["codeCompte"]) && $estClient){
        $idUser =  $_SESSION["codeCompte"];
        include 'includes/headerCon.php' ;
    }else{
        include 'includes/header.php';
    }
    ?>

<main>
    <?php include 'includes/menuCompte.php' ?>
    <aside id="filtresAside">
                <label class="label-retour btn-retour" for="retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>Retour</label>
                <input id="retour" TYPE="button" VALUE="RETOUR" onclick="history.back();">
                <div>
                <button class="btn-fermer-filtres" onclick="fermerFiltres()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
                    <?php include('filtretri.php') ;?> 
                </div>
    </aside>
    <div>
    <?php

    if(isset($resRecherche) && $resRecherche){
            //print_r($resRecherche);
            ?>
            
            <div class="titre-cat">
                <h2>Résultat pour "<?php echo $nomRecherche ?>":</h2>
                <div class="separateur2"></div>
            </div>
            <div class="separateur" style="margin-bottom:1em"></div>
            <article>
                <?php
                    foreach($resRecherche as $article){

                        $codeProduit = $article["codeproduit"];    
                        //print_r($article);
                        $img = $article['urlphoto'];
                        $libArt = $article['libelleprod'];
                        $prix = $article['prixttc'];
                        $prix = round($prix, 2); // Arrondir à 2 chiffre après la virgule 
                        $id = $article['codeproduit'];
                        $p = $article;
                        $desc = $article['descriptionprod'];
                        $madein = $article['origine'];
                        $moyennenote = $article['notemoy'];

                        include 'includes/card.php';
                            
                        } ?>
            </article>
        <?php } 
        else if(isset($resRecherche) && !$resRecherche) { 
            ?>
            <div class="titre-cat">
                <h2>Résultat pour "<?php echo $nomRecherche ?>":</h2>
                <div class="separateur2"></div>
            </div>
            <div class="separateur" style="margin-bottom:1em"></div>
            <div class="vide">
                <h1> Aucun article trouvé </h1>
                <a href="Catalogue.php">Revenir au catalogue</a>
            </div>
       <?php } ?>
      </div>
</main>
<?php include 'includes/footer.php';?>
<script>
        <?php if(isset($_GET["ajout"])):?>
            document.getElementById("ajoutPanierFait").classList.toggle("open");
            console.log("salut");
            setTimeout(function() { fermerPopUpPanier(); }, 5000);
        <?php endif?>

        const form = document.getElementById('filtreForm');
        const etoiles = document.querySelectorAll('#stars span');
        const ntInput = document.getElementById('nt-input');
        const categorie = document.querySelectorAll('.cats');
        const vendeur = document.querySelectorAll('.vend');
        const tris = document.querySelectorAll('.tris');
        
        function fermerPopUpPanier(){
            document.getElementById("ajoutPanierFait").classList.remove("open");
        }
        
        function ouvrirFiltres(){
            document.getElementById("filtresAside").classList.add("mobile-open");
        }
        
        function fermerFiltres(){
            document.getElementById("filtresAside").classList.remove("mobile-open");
        }
        
        categorie.forEach(cat => {
                cat.addEventListener('change', function () {
                    form.submit();
                    fermerFiltres();
                });
            }
        )
        tris.forEach(tri => {
                tri.addEventListener('change', function () {
                    form.submit();
                    fermerFiltres();
                });
            }
        )
        vendeur.forEach(vd => {
                vd.addEventListener('change', function () {
                    form.submit();
                    fermerFiltres();
                });
            }
        )

        const minRange = document.querySelector('.min-range');
        const maxRange = document.querySelector('.max-range');
        minRange.addEventListener("change",function (){
            form.submit();
            fermerFiltres();
        })
        maxRange.addEventListener("change",function (){
            form.submit();
            fermerFiltres();
        });

        etoiles.forEach(star => {
            star.addEventListener("click", function(){
                const valeur = star.dataset.value;
                if (ntInput.value === valeur) {
                    ntInput.value = 0;
                } else {
                    ntInput.value = valeur;
                }
                form.submit();
                fermerFiltres();
            });
        });
                
        
        updateStars(<?php echo $nt ?>);
        
    </script>
</body>
</html>