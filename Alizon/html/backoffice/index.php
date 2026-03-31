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
</head>
<body>
    <?php include '../includes/backoffice/header.php';?>
    <main><?php
        include '../includes/backoffice/menuCompteVendeur.php';
    $bdd->query("SET SCHEMA 'alizon'"); ?>
    <?php include '../includes/backoffice/menu.php'; ?>
    <div class="right-content"> 
        <?php
        $sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
        $stmt = $bdd->query($sql);
        $vendeur = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<h1 class="bvn-vendeur">Bienvenue '.$vendeur['prenom'].' '.$vendeur['nom'].'</h1>';
        ?>
        <div class="mes-produits">
            <h1>Mes produits au catalogue</h1>
            <?php 
            $stmt = $bdd->prepare('SELECT * FROM Produit where codeCompteVendeur =\'' . $codeCompte . '\' and Disponible = true');
            $stmt->execute();
            $liste_reduc = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
            <div class="tous-les-produits">
                <?php 
                $rows = $liste_reduc;
                for ($i = 0; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    $code_produit = $row['codeproduit'];
                ?>
                <div class="produit">
                <?php
                echo '<a href="./ficheProduit.php?Produit='.htmlspecialchars($row['codeproduit']).'">';
                    echo '<img src="../'.htmlspecialchars($row['urlphoto']).'" alt="Photo de '.htmlspecialchars($row['libelleprod']).'"> </a>';
                    echo '<p class="nomArticle">'.htmlspecialchars($row['libelleprod']).'</p>';
                    echo '<div class="infoProduit">';

                        $stmt = $bdd->prepare("SELECT * FROM alizon.FaireReduction JOIN alizon.Reduction ON alizon.FaireReduction.idReduction = alizon.Reduction.idReduction WHERE alizon.FaireReduction.codeProduit = :id");
                        $stmt->execute(['id' => $code_produit]);
                        $infoRemise = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $hasRemise = $stmt->rowCount() > 0;

                        if ($hasRemise != false){
                            echo '<p class="prixNormalbarre">'.$row['prixht'].'€</p>';
                            echo '<p class="prixReducRed">'.round($row['prixht'] * (1 - $infoRemise[0]['remise']/100), 2).'€ <span class="remise"> - '.$infoRemise[0]['remise'].'%</span></p>';
                        } else {
                            echo '<p class="prixReduc">'.$row['prixht'].'€</p>';
                        }

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
                </div>
                <?php } ?>
                </a>
            </div>
        </div>
        <div class="mes-produits">
            <h1>Mes produits au catalogue</h1>
            <?php 
            $stmt = $bdd->prepare('SELECT * FROM Produit where codeCompteVendeur =\'' . $codeCompte . '\' and Disponible = false');
            $stmt->execute();
            $liste_reduc = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
            <div class="tous-les-produits">
                <?php 
                $rows = $liste_reduc;
                for ($i = 0; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    $code_produit = $row['codeproduit'];
                ?>
                <div class="produit">
                <?php
                echo '<a href="./ficheProduit.php?Produit='.htmlspecialchars($row['codeproduit']).'">';
                    echo '<img src="../'.htmlspecialchars($row['urlphoto']).'" alt="Photo de '.htmlspecialchars($row['libelleprod']).'"> </a>';
                    echo '<p class="nomArticle">'.htmlspecialchars($row['libelleprod']).'</p>';
                    echo '<div class="infoProduit">';

                        $stmt = $bdd->prepare("SELECT * FROM alizon.FaireReduction JOIN alizon.Reduction ON alizon.FaireReduction.idReduction = alizon.Reduction.idReduction WHERE alizon.FaireReduction.codeProduit = :id");
                        $stmt->execute(['id' => $code_produit]);
                        $infoRemise = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $hasRemise = $stmt->rowCount() > 0;

                        if ($hasRemise != false){
                            echo '<p class="prixNormalbarre">'.$row['prixht'].'€</p>';
                            echo '<p class="prixReducRed">'.round($row['prixht'] * (1 - $infoRemise[0]['remise']/100), 2).'€ <span class="remise"> - '.$infoRemise[0]['remise'].'%</span></p>';
                        } else {
                            echo '<p class="prixReduc">'.$row['prixht'].'€</p>';
                        }

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
                </div>
                <?php } ?>
                </a>
            </div>
        </div>
        <div>
            <!--<section class="lesAvis">
                <h1>Les avis</h1>
                <div>
                <?php
                    $liste_avis = $bdd->query("SELECT avis.numAvis, profil.urlphoto, avis.codeproduit, produit.libelleprod, client.pseudo, avis.noteprod, avis.commentaire FROM avis INNER JOIN produit ON (avis.codeproduit = produit.codeproduit) INNER JOIN client ON (avis.codecomptecli = client.codecompte) INNER JOIN profil ON (profil.codeclient = client.codecompte) WHERE produit.codecomptevendeur = " . $codeCompte . " ORDER BY avis.codeproduit DESC;");  
                    $rows = $liste_avis->fetchAll(PDO::FETCH_ASSOC);
                    $limit = 3;
                    $count = count($rows);
                    if ($count === 0) {
                        echo '<p>Aucun avis pour le moment.</p>';
                    } else {
                        for ($i = 0; $i < min($limit, $count); $i++) {
                            $row = $rows[$i];
                            // Ne pas générer le paramètre Avis si la valeur n'existe pas
                            echo '<a href="ficheProduit.php?Produit=' . htmlspecialchars($row['codeproduit']) .'&Avis=1" class="avis">';
                            echo '<img src="../' . htmlspecialchars($row['urlphoto']) . '" alt="Photo de profil de ' . htmlspecialchars($row['pseudo']) . '" class="pdp">';
                ?>
                        <div class="infoAvis">
                            <div class="nomNote">
                                <?php
                                echo "<p>" . htmlspecialchars($row['libelleprod']) . "</p>";
                                ?>
                                <div class="note">
                                    <?php
                                    $note = htmlspecialchars($row['noteprod']);
                                    // Assurer que $note est un nombre et afficher les étoiles pleines puis les vides jusqu'à 5
                                    $rempli = is_numeric($note) ? (int)floor($note) : 0;
                                    $rempli = max(0, min(5, $rempli));

                                    // étoiles pleines
                                    for ($j = 0; $j < $rempli; $j++) {
                                        echo '<svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">';
                                        echo '<path d="M12.7205 0.886552C12.7767 0.770493 12.8635 0.6728 12.9712 0.604497C13.0789 0.536194 13.2031 0.5 13.3298 0.5C13.4565 0.5 13.5807 0.536194 13.6884 0.604497C13.796 0.6728 13.8829 0.770493 13.9391 0.886552L16.9022 7.01987C17.0974 7.42356 17.3856 7.77281 17.742 8.03765C18.0983 8.3025 18.5122 8.47502 18.9482 8.54041L25.5749 9.53139C25.7004 9.54998 25.8184 9.60411 25.9154 9.68764C26.0125 9.77118 26.0847 9.88079 26.1239 10.0041C26.1632 10.1274 26.1679 10.2594 26.1375 10.3853C26.1071 10.5112 26.0429 10.6259 25.952 10.7164L21.1597 15.4851C20.8436 15.7999 20.6072 16.1884 20.4706 16.6172C20.3341 17.046 20.3016 17.5023 20.3759 17.9468L21.5073 24.6844C21.5295 24.8127 21.5159 24.9447 21.4682 25.0655C21.4204 25.1862 21.3404 25.2908 21.2373 25.3674C21.1342 25.4439 21.0121 25.4893 20.885 25.4983C20.7579 25.5074 20.6308 25.4797 20.5183 25.4185L14.5946 22.2358C14.2043 22.0264 13.77 21.917 13.3291 21.917C12.8883 21.917 12.454 22.0264 12.0637 22.2358L6.14127 25.4185C6.02881 25.4793 5.9019 25.5067 5.77498 25.4975C5.64806 25.4883 5.52621 25.4428 5.42331 25.3664C5.3204 25.2899 5.24056 25.1854 5.19287 25.0649C5.14519 24.9443 5.13156 24.8125 5.15355 24.6844L6.28365 17.9482C6.3583 17.5034 6.32596 17.0468 6.18942 16.6177C6.05287 16.1886 5.81623 15.7999 5.49989 15.4851L0.707548 10.7177C0.615952 10.6273 0.551044 10.5124 0.520219 10.3861C0.489393 10.2599 0.49389 10.1273 0.533196 10.0035C0.572501 9.87976 0.645036 9.76975 0.742537 9.68605C0.840039 9.60234 0.958586 9.5483 1.08468 9.53008L7.71007 8.54041C8.14653 8.47553 8.56103 8.30323 8.91788 8.03835C9.27473 7.77348 9.56325 7.42395 9.75861 7.01987L12.7205 0.886552Z" fill="#FFD500" stroke="black" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                    }

                                    // étoiles vides pour compléter jusqu'à 5
                                    for ($j = $rempli; $j < 5; $j++) {
                                        echo '<svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">';
                                        echo '<path d="M12.7205 0.886552C12.7767 0.770493 12.8635 0.6728 12.9712 0.604497C13.0789 0.536194 13.2031 0.5 13.3298 0.5C13.4565 0.5 13.5807 0.536194 13.6884 0.604497C13.796 0.6728 13.8829 0.770493 13.9391 0.886552L16.9022 7.01987C17.0974 7.42356 17.3856 7.77281 17.742 8.03765C18.0983 8.3025 18.5122 8.47502 18.9482 8.54041L25.5749 9.53139C25.7004 9.54998 25.8184 9.60411 25.9154 9.68764C26.0125 9.77118 26.0847 9.88079 26.1239 10.0041C26.1632 10.1274 26.1679 10.2594 26.1375 10.3853C26.1071 10.5112 26.0429 10.6259 25.952 10.7164L21.1597 15.4851C20.8436 15.7999 20.6072 16.1884 20.4706 16.6172C20.3341 17.046 20.3016 17.5023 20.3759 17.9468L21.5073 24.6844C21.5295 24.8127 21.5159 24.9447 21.4682 25.0655C21.4204 25.1862 21.3404 25.2908 21.2373 25.3674C21.1342 25.4439 21.0121 25.4893 20.885 25.4983C20.7579 25.5074 20.6308 25.4797 20.5183 25.4185L14.5946 22.2358C14.2043 22.0264 13.77 21.917 13.3291 21.917C12.8883 21.917 12.454 22.0264 12.0637 22.2358L6.14127 25.4185C6.02881 25.4793 5.9019 25.5067 5.77498 25.4975C5.64806 25.4883 5.52621 25.4428 5.42331 25.3664C5.3204 25.2899 5.24056 25.1854 5.19287 25.0649C5.14519 24.9443 5.13156 24.8125 5.15355 24.6844L6.28365 17.9482C6.3583 17.5034 6.32596 17.0468 6.18942 16.6177C6.05287 16.1886 5.81623 15.7999 5.49989 15.4851L0.707548 10.7177C0.615952 10.6273 0.551044 10.5124 0.520219 10.3861C0.489393 10.2599 0.49389 10.1273 0.533196 10.0035C0.572501 9.87976 0.645036 9.76975 0.742537 9.68605C0.840039 9.60234 0.958586 9.5483 1.08468 9.53008L7.71007 8.54041C8.14653 8.47553 8.56103 8.30323 8.91788 8.03835C9.27473 7.77348 9.56325 7.42395 9.75861 7.01987L12.7205 0.886552Z" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                    } ?>
                                </div>
                            </div>
                            <?php echo'<p class="nomUtilisateur">'.htmlspecialchars($row['pseudo']).'</p>';
                                $commentaire = htmlspecialchars($row['commentaire']);
                                $maxLength = 90;
                                if (strlen($commentaire) > $maxLength) {
                                    $affichage = substr($commentaire, 0, $maxLength - 3) . '...';
                                } else {
                                    $affichage = $commentaire;
                                }
                                echo '<p class="contenuAvis">'.$affichage.'</p>';
                            ?>
                        </div>
                    </a>
                    <?php
                        } // end for
                    } // end else
                    ?>
                </div>
            </section>
            <section class="btnAccueil">
                    <a href="ajouterProduit.php">Ajouter un produit</a>
                    <a href="stock.php">Consulter la liste des produits</a>
            </section>
            <section class="btnAccueil">
                <a href="commandes.php">Consulter la liste des commandes</a>
            </section>-->
        </div>
    </div>
    </main>
    <?php include '../includes/backoffice/footer.php'; ?>
    <!-- <script src="../js/preview-img.js"></script> -->
    <script src="../js/overlayCompteVendeur.js"></script>
</body>
</html>
