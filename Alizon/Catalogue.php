<?php
include('connDb.php');
$bdd->query('set schema \'alizon\'');

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
                AND p.Disponible = true";
    }else {
        $base= 'SELECT codeProduit, libelleProd, prixTTC, urlPhoto, noteMoy, descriptionProd, origine FROM Produit where Disponible = true' ;
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
    //echo $sql;
    

?>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon</title>
    <link href="./css/style/catalogue.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    $estClient = false;
    if(isset($_SESSION["codeCompte"])){

        $clients = $bdd->query("SELECT ALL codeCompte FROM alizon.Client")->fetchAll();
        foreach($clients as $client){
            if($client["codecompte"] == $_SESSION["codeCompte"]){
                $estClient = true;
            }
        }
    }
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
                
                <div id="ajoutPanierFait">
                    <div class="partieGauche" onclick="fermerPopUpPanier()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div class="partieDroite">
                        <p>Produit ajouté au panier</p>
                        <a href="Panier.php" class="bouton">Aller au panier</a>
                    </div>
                </div>
                
                <div>
                    <div class="filtres">
                        <label class="label-retour btn-retour" for="retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m14 16-4-4 4-4"/></svg>Retour</label>
                        <input id="retour" TYPE="button" VALUE="RETOUR" onclick="history.back();">
                        <button onclick="ouvrirFiltres()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal-icon lucide-sliders-horizontal"><path d="M10 5H3"/><path d="M12 19H3"/><path d="M14 3v4"/><path d="M16 17v4"/><path d="M21 12h-9"/><path d="M21 19h-5"/><path d="M21 5h-7"/><path d="M8 10v4"/><path d="M8 12H3"/></svg>
                            Filtres
                        </button>
                    </div>
                </div>
                <?php if(!$categorie) {  
                    //Vérification si on ne recherche pas seulement une catégorie
                    ?>
                <h1>Catalogue</h1>
                <div class="separateur"></div>
                <?php
                    $stmt = $bdd->prepare($sql);
                    $stmt->execute();
                    $articles = $stmt->fetchAll();
                ?>
                
                <article class="catalogue">
                <?php    
                    foreach ($articles as $article) {
                        //print_r($article);
                        $codeProduit = $article['codeproduit'];
                        $img = $article['urlphoto'];
                        $libArt = $article['libelleprod'];
                        $prix = $article['prixttc'];
                        $prix = round($prix, 2); // Arrondir à 2 chiffre après la virgule 
                        $id = $article['codeproduit'];
                        $desc = $article['descriptionprod'];
                        $moyennenote = $article['notemoy'];
                        $madein = $article['origine'];
                        include 'includes/card.php' ;
                ?>
                
                <?php
                    } // Fin foreach
                ?>
                </article>
                <?php }else { 
                    
                    
                    $stmt = $bdd->prepare($sql);
                        $stmt->execute(array(
                            ":cat"=> $cat
                        ));
                    $prodUnit = $stmt->fetchAll();
                            //print_r($prodUnit) ;
                    
                    ?>
                    <div class="titre-cat">
                        <h2>
                            <?php echo $cat?>
                        </h2>
                        <div class="separateur2"></div>
                    </div>
                    <div class="separateur"></div>
                    <?php if($prodUnit == null){ ?>
                    <article class="catalogue" style="justify-content: center"> 
                    
                            <div class="vide">
                                <h1> Aucun article trouvé </h1>
                                <a href="Catalogue.php">Revenir au catalogue</a>
                            </div>
                   <?php } else{
                    echo "<article class='catalogue'>"; // pour éviter de fermer & réouvrir php
                    $stmt = $bdd->prepare($sql);
                        $stmt->execute(array(
                            ":cat"=> $cat
                        ));
                        $prodUnit = $stmt->fetchAll();
                         //print_r($prodUnit);
                    foreach($prodUnit as $produit){
                        $codeProduit = $produit['codeproduit'];
                        $img = $produit['urlphoto'];
                        $libArt = $produit['libelleprod'];
                        $prix = $produit['prixttc'];
                        $prix = round($prix, 2); // Arrondir à 2 chiffre après la virgule 
                        $id = $produit['codeproduit'];
                        $desc = $produit['descriptionprod'];
                        $moyennenote = $produit['notemoy'];
                        $madein = $produit['origine'];
                        include 'includes/card.php' ;
                        
                        } } ?>
                    </article>
                </div>
        <?php
                }
            include 'includes/menuCompte.php';
        ?>
    
        
        </div>
    </main>
    <?php include 'includes/footer.php';?>
    <script src="./js/Catalogue.js"></script>
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