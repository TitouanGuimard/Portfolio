<?php
session_start();
if(!array_key_exists("codeCompte", $_SESSION) || !isset($_SESSION["codeCompte"])){
    header('location: connexionVendeur.php');
    
}else{

    $codeCompte = $_SESSION["codeCompte"];
    
}

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


$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        



        
?>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/style/backoffice/ficheProduit.css" >
        <link rel="stylesheet" type="text/css" href="../css/style/backoffice/popupAvis.css" >
        <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
        <script src="../js/FicheProd.js"></script>

        <title>alizon</title>
        <style>
            .Rea-open {
                display:flex !important;
            }
            </style>
    </head>
    <body>
        <script>
 
        document.cookie = "qteAjout = " + 0;
        
        const btnReappro = document.getElementById("btnReappro");

        function ouvrirReappro() {
            document.getElementById("divReappro").classList.add("Rea-open");
        };
        function annulerReappro(){
            document.getElementById("divReappro").classList.remove("Rea-open");
        }
        function validerReappro(){
            let qte = document.getElementById("qteAjout").value;
            if (isNaN(qte) || qte < 0) {
                alert("Veuillez entrer une quantité positive");
            return;
            }
            document.getElementById("divReappro").classList.remove("Rea-open");
            document.cookie = "qteAjout = " + qte;
            window.location.reload();
        }
    
        </script>
        <?php include '../includes/backoffice/header.php';
        $bdd->query("SET SCHEMA 'alizon'");?>
        <main>
            <?php include '../includes/backoffice/menuCompteVendeur.php';; ?>
            <section class="nav-btn">
                <?php $code_produit=$_GET["Produit"]; ?>
                <a href="index.php" class="btnacc">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    Accueil
                </a>
                <a class="btnacc" href="modifProduit.php?codeProduit=<?php echo $code_produit?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen-line-icon lucide-pen-line"><path d="M13 21h8"/><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                    Modifier le produit
                </a>
                <a href="#avis-produits" class="btnacc">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-icon lucide-message-circle"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/></svg>
                    Voir les avis
                </a>
                <?php
                    $stmt = $bdd->prepare("SELECT Disponible FROM alizon.Produit WHERE codeProduit = :id");
                    $stmt->execute(['id' => $code_produit]);
                    $state = $stmt->fetch(PDO::FETCH_ASSOC);

                    
                    if($state['disponible'] == 1){                            
                ?>
                <a class="btnacc rouge" href="#" onclick="event.preventDefault(); retirerCatalogue(<?php echo $code_produit?>)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-minus-icon lucide-file-minus"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M9 15h6"/></svg>
                    Retirer du catalogue
                </a>
                <?php
                    }
                    elseif ($state['disponible'] != 1){?>
                    <a class="btnacc vert" href="#" onclick="event.preventDefault(); ajouterCatalogue(<?php echo $code_produit?>)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-plus-icon lucide-file-plus"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M9 15h6"/><path d="M12 18v-6"/></svg>
                    Ajouter au catalogue
                </a>
                <?php } ?>
                </a>
                <?php 
                    $stmt = $bdd->prepare("SELECT * FROM alizon.FairePromotion WHERE codeProduit = :id");
                    $stmt->execute(['id' => $code_produit]);
                    $hasPromo = $stmt->rowCount() > 0;

                    if ($hasPromo){
                ?>
                <a class="btnacc" href="ajouterPromotion.php?Produit=<?php echo $code_produit?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone-icon lucide-megaphone"><path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14"/><path d="M8 6v8"/></svg>
                    Modifier la promotion
                </a>
                <?php } else { ?>
                <a class="btnacc" href="ajouterPromotion.php?Produit=<?php echo $code_produit?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone-icon lucide-megaphone"><path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/><path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14"/><path d="M8 6v8"/></svg>
                    Ajouter une promotion
                </a>
                <?php } ?>

                <?php 
                    $stmt = $bdd->prepare("SELECT * FROM alizon.FaireReduction WHERE codeProduit = :id");
                    $stmt->execute(['id' => $code_produit]);
                    $hasRemise = $stmt->rowCount() > 0;

                    if ($hasRemise){
                ?>
                <a class="btnacc" href="ajouterRemise.php?Produit=<?php echo $code_produit?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/></svg>
                    Modifier la remise
                </a>
                <?php } else { ?>
                <a class="btnacc" href="ajouterRemise.php?Produit=<?php echo $code_produit?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/></svg>
                    Ajouter une remise
                </a>
                <?php } ?>
                <a class="btnacc rouge" href="#" onclick="event.preventDefault(); supprimerCatalogue(<?php echo $code_produit?>)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Supprimer le produit
                </a>
            </section>
            <div class="right-content">
                <!-- <a href="index.php" class="btn-retour">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="m14 16-4-4 4-4"/>
                    </svg>
                    Retour
                </a> -->
                <div class="alignemnt_droite_gauche">
                    <section class="produit-content">
                        <div class="info-produit">
                            <?php
                            $code_produit=$_GET["Produit"];
                            $info = $bdd->query("SELECT urlPhoto FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();
                            $res=$info["urlphoto"];
                            
                            ?>
                            <div class="img-box">
                            <?php echo '<img src="../'.htmlspecialchars($res).'" alt="image du produit" width="340px" height="auto" class="image_prod">'; ?> 
                            </div>
                        
                            <div class="description-prod">
                                <h1>
                                    <?php
                                    
                                    $info = $bdd->query("SELECT libelleProd FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();
                                    
                                    $res=$info["libelleprod"];
                                    echo "$res";
                                    
                                    ?>
                                </h1>
                                <div class = stock>
                                    <?php $qtestock = $bdd->query("SELECT qtestock FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch(); 
                                        echo "<p class=\"stock\">Stock disponible : " . $qtestock["qtestock"] . "</p>";
                                    ?>
                                    <button onclick="ouvrirReappro()" id="ouvrirReappro"> 
                                       <svg width="25" height="25" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="22.5" cy="22.5" r="22.5" fill="#FCB66B"/>
                                        <path d="M22.272 7.94113V38.4851" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7.56873 23.2131H38.1127" stroke="black" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Ajouter
                                    </button>
                                 </div>

                                <div id="divReappro">
                                    <article>
                                        <h3>Réapprovisionnement</h3>
                                        <label for="qteAjout" class="pObl">Quelle quantité voulez-vous ajouter?</label>
                                        <input type="number" name="qteAjout" min="0" placeholder="Quantité à ajouter" id="qteAjout"/> 
                                        <button onclick="annulerReappro()" id="annulerReappro"> Annuler </button>
                                        <!-- il faut recharger manuellement une fois la page pour que l'ajout s'effectue -->
                                        <button onclick="validerReappro()" id="validerReappro"> Valider </button>
                                    </article>
                                </div>

                                <div class="alignemnt_droite_gauche">

                                    <div class="etoiles">
                                        <?php
                                            $note = $bdd->query("SELECT AVG(noteProd) AS moyenne FROM alizon.avis WHERE codeProduit=$code_produit")->fetchColumn();

                                            
                                            // Assurer que $note est un nombre et afficher les étoiles pleines puis les vides jusqu'à 5
                                            $rempli = is_numeric($note) ? (int)ceil($note) : 0;
                                            $rempli = max(0, min(5, $rempli));

                                            // étoiles pleines
                                            for ($j = 0; $j < ceil($rempli); $j++) {
                                                // echo '<svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">';
                                                // echo '<path d="M12.7205 0.886552C12.7767 0.770493 12.8635 0.6728 12.9712 0.604497C13.0789 0.536194 13.2031 0.5 13.3298 0.5C13.4565 0.5 13.5807 0.536194 13.6884 0.604497C13.796 0.6728 13.8829 0.770493 13.9391 0.886552L16.9022 7.01987C17.0974 7.42356 17.3856 7.77281 17.742 8.03765C18.0983 8.3025 18.5122 8.47502 18.9482 8.54041L25.5749 9.53139C25.7004 9.54998 25.8184 9.60411 25.9154 9.68764C26.0125 9.77118 26.0847 9.88079 26.1239 10.0041C26.1632 10.1274 26.1679 10.2594 26.1375 10.3853C26.1071 10.5112 26.0429 10.6259 25.952 10.7164L21.1597 15.4851C20.8436 15.7999 20.6072 16.1884 20.4706 16.6172C20.3341 17.046 20.3016 17.5023 20.3759 17.9468L21.5073 24.6844C21.5295 24.8127 21.5159 24.9447 21.4682 25.0655C21.4204 25.1862 21.3404 25.2908 21.2373 25.3674C21.1342 25.4439 21.0121 25.4893 20.885 25.4983C20.7579 25.5074 20.6308 25.4797 20.5183 25.4185L14.5946 22.2358C14.2043 22.0264 13.77 21.917 13.3291 21.917C12.8883 21.917 12.454 22.0264 12.0637 22.2358L6.14127 25.4185C6.02881 25.4793 5.9019 25.5067 5.77498 25.4975C5.64806 25.4883 5.52621 25.4428 5.42331 25.3664C5.3204 25.2899 5.24056 25.1854 5.19287 25.0649C5.14519 24.9443 5.13156 24.8125 5.15355 24.6844L6.28365 17.9482C6.3583 17.5034 6.32596 17.0468 6.18942 16.6177C6.05287 16.1886 5.81623 15.7999 5.49989 15.4851L0.707548 10.7177C0.615952 10.6273 0.551044 10.5124 0.520219 10.3861C0.489393 10.2599 0.49389 10.1273 0.533196 10.0035C0.572501 9.87976 0.645036 9.76975 0.742537 9.68605C0.840039 9.60234 0.958586 9.5483 1.08468 9.53008L7.71007 8.54041C8.14653 8.47553 8.56103 8.30323 8.91788 8.03835C9.27473 7.77348 9.56325 7.42395 9.75861 7.01987L12.7205 0.886552Z" fill="#FFD500" stroke="black" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                                ?><span class="etoile pleine grosse">★</span><?php
                                            }

                                            // étoiles vides pour compléter jusqu'à 5
                                            for ($j = $rempli; $j < 5; $j++) {
                                                // echo '<svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">';
                                                // echo '<path d="M12.7205 0.886552C12.7767 0.770493 12.8635 0.6728 12.9712 0.604497C13.0789 0.536194 13.2031 0.5 13.3298 0.5C13.4565 0.5 13.5807 0.536194 13.6884 0.604497C13.796 0.6728 13.8829 0.770493 13.9391 0.886552L16.9022 7.01987C17.0974 7.42356 17.3856 7.77281 17.742 8.03765C18.0983 8.3025 18.5122 8.47502 18.9482 8.54041L25.5749 9.53139C25.7004 9.54998 25.8184 9.60411 25.9154 9.68764C26.0125 9.77118 26.0847 9.88079 26.1239 10.0041C26.1632 10.1274 26.1679 10.2594 26.1375 10.3853C26.1071 10.5112 26.0429 10.6259 25.952 10.7164L21.1597 15.4851C20.8436 15.7999 20.6072 16.1884 20.4706 16.6172C20.3341 17.046 20.3016 17.5023 20.3759 17.9468L21.5073 24.6844C21.5295 24.8127 21.5159 24.9447 21.4682 25.0655C21.4204 25.1862 21.3404 25.2908 21.2373 25.3674C21.1342 25.4439 21.0121 25.4893 20.885 25.4983C20.7579 25.5074 20.6308 25.4797 20.5183 25.4185L14.5946 22.2358C14.2043 22.0264 13.77 21.917 13.3291 21.917C12.8883 21.917 12.454 22.0264 12.0637 22.2358L6.14127 25.4185C6.02881 25.4793 5.9019 25.5067 5.77498 25.4975C5.64806 25.4883 5.52621 25.4428 5.42331 25.3664C5.3204 25.2899 5.24056 25.1854 5.19287 25.0649C5.14519 24.9443 5.13156 24.8125 5.15355 24.6844L6.28365 17.9482C6.3583 17.5034 6.32596 17.0468 6.18942 16.6177C6.05287 16.1886 5.81623 15.7999 5.49989 15.4851L0.707548 10.7177C0.615952 10.6273 0.551044 10.5124 0.520219 10.3861C0.489393 10.2599 0.49389 10.1273 0.533196 10.0035C0.572501 9.87976 0.645036 9.76975 0.742537 9.68605C0.840039 9.60234 0.958586 9.5483 1.08468 9.53008L7.71007 8.54041C8.14653 8.47553 8.56103 8.30323 8.91788 8.03835C9.27473 7.77348 9.56325 7.42395 9.75861 7.01987L12.7205 0.886552Z" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                                ?><span class="etoile grosse">★</span><?php
                                            } ?>
                                    </div>
                                    <!--<button onclick="togglePopup()" class="buton_commentaire">Afficher les commentaires</button>-->
                                    <!-- <?php include '../includes/backoffice/avis.php' ?> -->
                                </div>
                                <?php
                                    $info = $bdd->query("SELECT descriptionProd FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();
                                    
                                    $res=$info["descriptionprod"];
                                    echo "<p class=\"description\">$res</p>";
                                ?>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="alignement_space_betwen">
                            <h2>
                                <?php
                                    $sql = "SELECT * FROM alizon.Produit JOIN alizon.FaireReduction ON alizon.Produit.codeProduit = alizon.FaireReduction.codeProduit JOIN alizon.Reduction ON alizon.FaireReduction.idReduction = alizon.Reduction.idReduction WHERE alizon.Produit.codeProduit=$code_produit";
                                    $stmt = $bdd->prepare($sql);
                                    $info = $stmt->execute();
                                    $info = $stmt->fetch(PDO::FETCH_ASSOC);

                                    $sql2 = "SELECT * FROM alizon.Produit where alizon.Produit.codeProduit=$code_produit";
                                    $stmt2 = $bdd->prepare($sql2);
                                    $info2 = $stmt2->execute();
                                    $info2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                                    $res=$info2["prixht"];
                                    if($hasRemise){
                                        echo '<p class="prixNormalbarre">'.$info['prixht'].'€</p>';
                                        echo '<p class="prixReduc">'.round($info['prixht'] * (1 - $info['remise']/100), 2).'€ <span class="remise"> - '.$info['remise'].'%</span></p>';
                                    } else {
                                        echo '<p class="prixNormal">'.$res.'€</p>';
                                    }
                                ?>
                            </h2>
                            <!--<div>
                                <a href="modifProduit.php?codeProduit=<?php echo $code_produit?>" class="buton_modif"><div>Modifier le produit</div></a>
                                <?php
                                $state = $bdd->query("SELECT Disponible FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();
                                
                                if($state['disponible'] == 1){                            
                                ?>
                                <button onclick="retirerCatalogue(<?php echo $code_produit?>)" class="buton_ret_cat">Retirer du catalogue</button>
                                <?php
                                }
                                elseif ($state['disponible'] != 1){?>

                                <button onclick="ajouterCatalogue(<?php echo $code_produit?>)" class="buton_ajt_cat">Ajouter au catalogue</button>

                                <?php } ?>
                            </div> -->
                        </div>
                    </section>
                </div>
                <div class="catego">
                    <?php
                    
                    $info = $bdd->query("SELECT spe1, spe2, spe3 FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();
                    $Org = $bdd->query("SELECT Origine FROM alizon.Produit WHERE codeProduit=$code_produit")->fetch();

                    if ($info['spe1']!= NULL || $info['spe2']!= NULL || $info['spe3']!= NULL){
                        if($info['spe1'] != NULL){
                            $sp1 = explode(":", $info['spe1']);
                        }else{
                            $sp1 = NULL;
                        }
                        if($info['spe2'] != NULL){
                            $sp2 = explode(":", $info['spe2']);
                        }else{
                            $sp2 = NULL;
                        }
                        if($info['spe3'] != NULL){
                            $sp3 = explode(":", $info['spe3']);
                        }else{
                            $sp3 = NULL;
                        }
                        ?>
                        <section class="specificitees">
                            <h2> Spécificitées : </h2>
                            <!-- <table class="table-spe">
                                <tr>
                                    <td><?php echo strtoupper($sp1[0]) ?></td>
                                    <td><?php echo $sp1[1] ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo strtoupper($sp2[0]) ?></td>
                                    <td><?php echo $sp2[1] ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo strtoupper($sp3[0]) ?></td>
                                    <td><?php echo $sp3[1] ?></td>
                                </tr>
                            </table> -->
                            <div class="table-spe">
                                <?php if ($sp1 != NULL) { ?>
                                <p><?php echo $sp1[0] ?></p>
                                <p class="spe-droite"><?php echo $sp1[1] ?></p>
                                <hr>
                                <?php } ?>
                                <?php if ($sp2 != NULL) { ?>
                                <p><?php echo $sp2[0] ?></p>
                                <p class="spe-droite"><?php echo $sp2[1] ?></p>
                                <hr>
                                <?php } ?>
                                <?php if ($sp3 != NULL) { ?>
                                <p><?php echo $sp3[0] ?></p>
                                <p class="spe-droite"><?php echo $sp3[1] ?></p>
                                <hr>
                                <?php } ?>
                            </div>
                            <!-- <ul>
                                <li><?php echo $info['spe1']?></li>
                                <li><?php echo $info['spe2']?></li>
                                <li><?php echo $info['spe3']?></li>
                            </ul> -->
                            <?php
                            }
                            if ($Org['origine'] != NULL){
                                
                            ?>
                            <h2> Origine :</h2>
                            <ul>
                                <p>Made in <?php echo $Org['origine']?></p>

                            </ul>
                        </section>
                    <?php
                    }
                    ?>
                </div>
                <section class="evaluation-produit">
                    <h1>Évaluation du produit</h1>
                    <div class="evaluation">
                        <?php
                        $id = $_GET['Produit'];
                        $sqlAvis = "SELECT A.*, C.prenom, C.nom,
                                    ARRAY(
                                        SELECT J.urlPhoto
                                        FROM JustifierAvis J
                                        WHERE J.numAvis = A.numAvis
                                    ) AS photos
                                FROM Avis A
                                LEFT JOIN Client C ON C.codeCompte = A.codeCompteCli
                                WHERE A.codeProduit = :id
                                ORDER BY A.datePublication DESC
                            ";

                        $sqlAvis2 = "SELECT profil.urlphoto, produit.libelleprod, client.pseudo, client.nom, client.prenom, avis.noteprod, avis.commentaire, avis.datepublication FROM avis INNER JOIN produit ON (avis.codeproduit = produit.codeproduit) INNER JOIN client ON (avis.codecomptecli = client.codecompte) INNER JOIN profil ON (profil.codeclient = client.codecompte) WHERE produit.codeproduit = " . $code_produit . " ORDER BY avis.codeproduit;";
                        $stmtAvis = $bdd->prepare($sqlAvis2);
                        $stmtAvis->execute();
                        //['id' => $id]
                        $avisList = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);

                        $totalAvis = count($avisList);
                        $sommeNotes = 0;
                        $noteCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]; 

                        foreach ($avisList as $avis) {
                            $note = (int)$avis['noteprod'];
                            $sommeNotes += $note;
                            $noteCounts[$note]++;
                        }

                        $moyenneNote = $totalAvis > 0 ? round($sommeNotes / $totalAvis, 2) : 0;
                        ?>

                        <div class="eval-moy">
                            <div class="score-moyen">
                                <span class="score"><?= $moyenneNote ?></span>/5
                            </div>
                            <div class="etoiles">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="etoile <?= $i <= round($moyenneNote) ? 'pleine' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="total">
                                (<?= $totalAvis ?> avis)
                            </div>
                        </div>

                        <div class="repartition-notes">
                            <?php foreach ($noteCounts as $note => $count): ?>
                                <div class="note-bar">
                                    <span class="note-label"><?= $note ?>★</span>
                                    <div class="progression-note">
                                        <div class="barre-progression" style="width: <?= $totalAvis > 0 ? ($count / $totalAvis) * 100 : 0 ?>%"></div>
                                    </div>
                                    <span class="nbr-note"><?= $count ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </section>
                <section id="avis-produits" class="avis-produits">
                    <?php
                        

                        // foreach ($avisList as &$avis) {

                        //     if (is_string($avis["photos"]) && $avis["photos"] !== "{}") {

                        //         $str = trim($avis["photos"], "{}");

                        //         $parts = array_map('trim', explode(',', $str));

                        //         $photos = [];

                        //         foreach ($parts as $p) {
                        //             $p = trim($p, '"');

                        //             if (strtoupper($p) !== "NULL" && $p !== "") {
                        //                 $photos[] = $p;
                        //             }
                        //         }

                        //         $avis["photos"] = $photos;

                        //     } else {
                        //         $avis["photos"] = [];
                        //     }
                        // }
                        // unset($avis);
                    ?>
                    <h1>Les avis</h1>

                    <div class="liste-avis">
                        <?php if (empty($avisList)): ?>
                            <p>Aucun avis pour ce produit.</p>
                        <?php else: ?>
                            <?php foreach ($avisList as $avis): ?>
                                <div class="avis">
                                    <div class="avis-header">
                                        <?php echo "<img src='../".htmlspecialchars($avis['urlphoto']) . "' alt='Photo de l'utilisateur' class='pdp-avis'>"; ?>
                                        <strong>
                                            <?php
                                            $pseudo = strtoupper(htmlspecialchars($avis['pseudo']));
                                            //$nom = strtoupper(htmlspecialchars($avis['nom']));
                                            
                                            $stm = $bdd->prepare("SELECT cmtBlq, codeCompte FROM alizon.Client WHERE pseudo = :pseudo");
                                            $stm->execute(['pseudo' => $avis['pseudo']]);
                                            $bloque = $stm->fetchall(PDO::FETCH_ASSOC)[0];
                                            if ($bloque['cmtblq'] == 1){
                                                echo "Utilisateur Supprimé";
                                            }
                                            else {
                                                echo "$pseudo";
                                            }
                                            ?>

                                        </strong>
                                        <span class="date">
                                            <?= date("m/d/Y", strtotime($avis['datepublication'])) ?>
                                        </span>
                                    </div>
                                    <span class="note">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?= $i <= (int)$avis['noteprod'] ? 'full' : '' ?>">★</span>
                                        <?php endfor; ?>
                                    </span>
                                    

                                    <p class="commentaire">
                                        <?= htmlspecialchars($avis['commentaire']) ?>
                                    </p>
                                    <?php if (!empty($avis['photos'])): ?>
                                        <div id="overlay-photos-avis" class="photos-avis">
                                            <?php foreach ($avis['photos'] as $photo): ?>
                                                <img src="<?= htmlspecialchars($photo) ?>" 
                                                    alt="Photo de l'avis" 
                                                    class="photo-avis">
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>

            </div>
        </main>
        
        <?php include '../includes/backoffice/footer.php';?>
        <script src="/js/popupAvis.js"></script>
        <script src="../js/overlayCompteVendeur.js"></script>
        <script>src="../js/scripts.js"</script>
<?php
if(isset($_COOKIE["qteAjout"])){

    $qteAjout = $_COOKIE["qteAjout"];
    
    setcookie("qteAjout", '', time() - 4200, '/');
    
    print_r($_COOKIE);
    $req = $bdd->prepare("Update alizon.produit SET qteStock= :qteAjout + qteStock WHERE codeProduit=:code_produit");
    $res = $req->execute(array(
    ":qteAjout" => $qteAjout,
    ":code_produit" => $code_produit
    ));
    
}
?>
        
    </body>
</html>
