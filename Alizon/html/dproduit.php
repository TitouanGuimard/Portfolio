<?php
//Connexion à la base de données.
require_once __DIR__ . '/_env.php';
loadEnv(__DIR__ . '/.env');
session_start();
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




$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("Produit introuvable.");

$produit = $bdd->query("SELECT * FROM Produit WHERE codeProduit = $id")->fetch(PDO::FETCH_ASSOC);
if (!$produit) die("Produit introuvable !");


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

$stmtAvis = $bdd->prepare($sqlAvis);
$stmtAvis->execute(['id' => $id]);
$avisList = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);

foreach ($avisList as &$avis) {

    if (is_string($avis["photos"]) && $avis["photos"] !== "{}") {

        $str = trim($avis["photos"], "{}");

        $parts = array_map('trim', explode(',', $str));

        $photos = [];

        foreach ($parts as $p) {
            $p = trim($p, '"');

            if (strtoupper($p) !== "NULL" && $p !== "") {
                $photos[] = $p;
            }
        }

        $avis["photos"] = $photos;
    } else {
        $avis["photos"] = [];
    }
}
unset($avis);


$cat = ($bdd->query("SELECT libelleCat FROM alizon.Categoriser WHERE codeProduit = '" . $id . "'")->fetch())["libellecat"];


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alizon</title>
    <link rel="stylesheet" href="css/style/dproduit.css">
</head>

<body>

    <?php

    if (isset($_SESSION['codeCompte'])) {
        include 'includes/headerCon.php';
        $codeCompte = $_SESSION['codeCompte'];
    } else {
        include 'includes/header.php';
    }
    ?>







    <!-- ajouter verif bernard -->





    <main>
        <?php
        include 'includes/menuCompte.php';
        ?>
        <div class="ariane">
            <a class="arianeItem" href="index.php">Accueil > </a><a class="arianeItem" href="Catalogue.php">Catalogue > </a><a class="arianeItem" href="Categorie.php?cat=<?php echo $cat ?>"><?php echo $cat ?></a>
        </div>
        <label class="label-retour btn-retour" for="retour"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="m14 16-4-4 4-4" />
            </svg>Retour</label>
        <INPUT id="retour" TYPE="button" VALUE="RETOUR" onclick="history.back();">

        <section class="prod">
            <div class="detail-produit">
                <div class="detail-produit-content">
                    <?php if (!empty($produit['urlphoto'])): ?>
                        <img src="<?= $produit['urlphoto'] ?>" alt="Image du produit">
                    <?php endif; ?>
                    <div class="info-produit">
                        <h1><?= $produit['libelleprod'] ?></h1>
                        <p><strong>Description :</strong> <?= $produit['descriptionprod'];?><br>
                        <?php if(isset($produit['spe1']) && $produit['spe1'] != ''){
                            $sp1 = explode(":", $produit['spe1']);
                            echo "<strong>".$sp1[0]." : </strong>".$sp1[1]."<br>";
                        }
                        if(isset($produit['spe2']) && $produit['spe2'] != ''){
                            $sp2 = explode(":", $produit['spe2']);
                            echo "<strong>".$sp2[0]." : </strong>".$sp2[1]."<br>";
                        }

                        if(isset($produit['spe3']) && $produit['spe3'] != ''){
                            $sp3 = explode(":", $produit['spe3']);
                            echo "<strong>".$sp3[0]." : </strong>".$sp3[1]."</p>";
                        }
                        ?>
                        <div class="prix">
                            <?php
                            $stmt = $bdd->prepare("SELECT * FROM alizon.FaireReduction JOIN alizon.Reduction ON alizon.FaireReduction.idReduction = alizon.Reduction.idReduction WHERE alizon.FaireReduction.codeProduit = :id");
                            $stmt->execute(['id' => $produit['codeproduit']]);

                            $infoRemise = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $hasRemise = $stmt->rowCount() > 0;
                            if ($hasRemise != false) {
                                //print_r($infoRemise);
                                echo '<div style="display:flex;flex-direction:row;gap:10px;">';
                                echo '<p>PrixHT :</p> <p class="prixReducRed">' . round($produit['prixht'] * (1 - $infoRemise[0]['remise'] / 100), 2) . '€ <span class="remise"> - ' . $infoRemise[0]['remise'] . '%</span></p>';
                                echo '<p class="prixNormalbarre">' . round($produit['prixht'], 2) . '€</p>';
                                echo '</div>';
                                echo '<div style="display:flex;flex-direction:row;gap:10px;">';
                                echo '<p>PrixTTC : </p><p class="prixReducRed">' . round($produit['prixttc'] * (1 - $infoRemise[0]['remise'] / 100), 2) . '€ <span class="remise"> - ' . $infoRemise[0]['remise'] . '%</span></p>';
                                echo '<p class="prixNormalbarre">' . round($produit['prixttc'], 2) . '€</p>';
                                echo '</div>';
                            } else {
                                echo '<p class="prixReduc"> PrixHT : ' . round($produit['prixht'], 2) . '€</p>';
                                echo '<p>Prix TTC :'. round($produit['prixttc'], 2).'€</p>';
                            }
                            ?>
                        </div>
                        
                    </div>

                </div>
            </div>
            <div class="partie-droite">

                <div class="panier-section">

                    <p class="price" id="price" data-price="<?= round($produit['prixttc'], 2) ?>"><?php if($hasRemise) { echo round($produit['prixttc'] * (1 - $infoRemise[0]['remise'] / 100), 2);} else{ echo round($produit['prixttc'], 2); } ?> €</p>
                    <div class="quantity">
                        <label for="qte">Quantité :</label>
                        <select id="qte" name="qte">
                            <?php for ($i = 1; $i <= 100; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                            <option value="1000000">1000000</option>
                        </select>
                    </div>
                    <button class="btnJaune" onclick="window.location.href = 'AjouterAuPanier.php?codeProd=<?php echo $produit['codeproduit'] ?>&qteProd=' + encodeURIComponent(getQuantite()) + '&page=Catalogue.php'">Ajouter au panier</button>
                    <button class="btnJaune" onclick="window.location.href ='AjouterAuPanier.php?codeProd=<?php echo $produit['codeproduit'] ?>&qteProd=' + encodeURIComponent(getQuantite()) + '&instant=1'">Acheter</button>
                    <!--<button class="add-to-cart">Ajouter au panier</button>-->
                </div>



                <?php
                if (isset($_SESSION["codeCompte"])) {
                    $commande = 'SELECT * FROM alizon.ProdUnitCommande NATURAL JOIN alizon.Commande WHERE alizon.Commande.codeCompte = ' . $_SESSION["codeCompte"] . ' AND alizon.ProdUnitCommande.codeProduit = ' . $produit['codeproduit'] . '';
                    $commander = $bdd->query($commande)->fetch();
                }
                ?>
                <?php if (isset($_SESSION["codeCompte"]) and $commander != NULL):   // && $commander!=NULL Si l'utilisateur a comandé le produit, afficher le formulaire d'avis
                ?>
                    <form class="avis-section" method="POST" action="ajout_avis.php" enctype="multipart/form-data">

                        <h2>Votre avis</h2>

                        <div class="noter" id="stars">
                            <span data-value="1">★</span>
                            <span data-value="2">★</span>
                            <span data-value="3">★</span>
                            <span data-value="4">★</span>
                            <span data-value="5">★</span>
                        </div>

                        <span id="note-value" style="display:none;">0</span>

                        <textarea name="commentaire" maxlength="255" placeholder="Rédiger un commentaire..." required></textarea>



                        <div class="plein-buttons">
                            <label class="photo" for="photos">Ajouter des photos</label>
                            <input id="photos" type="file" name="photos[]" multiple accept="image/*">
                            <button type="reset" class="cancel">Annuler</button>
                            <button type="submit" class="submit">Publier</button>
                        </div>

                        <input type="hidden" name="codeProduit" value="<?php echo $produit['codeproduit'] ?>">
                        <input type="hidden" name="noteProd" id="noteProd" value="1">

                    </form>
                <?php endif ?>

            </div>
        </section>
        <section class="evaluation-produit">
            <h1>Évaluation du produit</h1>
            <div class="evaluation">
                <?php
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
        <section class="avis-produits">
            <h1>Les avis</h1>
            <div class="liste-avis">
                <?php if (empty($avisList)): ?>
                    <p>Aucun avis pour ce produit.</p>
                <?php else: ?>
                    <?php foreach ($avisList as $avis): ?>
                        <div class="avis">
                            <div class="avis-header">
                                <strong>
                                    <?php
                                    $prenom = htmlspecialchars($avis['prenom']);
                                    $nom = strtoupper(htmlspecialchars($avis['nom']));

                                    $stm = $bdd->prepare("SELECT cmtBlq FROM alizon.Client WHERE codeCompte = :codeCompte");
                                    $stm->execute(['codeCompte' => $avis['codecomptecli']]);
                                    $bloque = $stm->fetch(PDO::FETCH_ASSOC)['cmtblq'];
                                    if ($bloque==1){
                                        echo "Utilisateur Supprimé";
                                    }
                                    else {
                                        echo "$prenom $nom";
                                    }
                                    
                                    ?>
                                </strong>

                                <span class="date">
                                    <?php echo htmlspecialchars($avis['datepublication']) ?>
                                </span>
                            </div>
                            <span class="note">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= (int)$avis['noteprod'] ? 'full' : '' ?>">★</span>
                                <?php endfor; ?>
                            </span>

                            <p class="commentaire">
                                <?php echo htmlspecialchars($avis['commentaire']) ?>
                            </p>
                            <?php if (!empty($avis['photos'])): ?>
                                <div id="overlay-photos-avis" class="photos-avis">
                                    <?php foreach ($avis['photos'] as $photo): ?>
                                        <img src="<?= htmlspecialchars($photo) ?>"
                                            alt="Photo de l'avis"
                                            class="photo-avis"
                                            onclick="openOverlay(this.src)">
                                        <img src="<?= htmlspecialchars($photo) ?>"
                                            alt="Photo de l'avis"
                                            class="photo-avis"
                                            id="overlay"
                                            onclick="fermerOverlay()">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php
                            if ($_SESSION && $_SESSION["codeCompte"] == $avis["codecomptecli"]):
                            ?>
                                <div class="actions-avis">
                                    <a id="btnModifierAvis" onclick="openOverlayModif(<?php echo $avis['noteprod'] ?>, <?php echo $avis['numavis'] ?>, <?php echo $avis['codeproduit'] ?>, '<?php echo $avis['commentaire'] ?>')">Modifier</a>
                                    <div class="overlaymodifier" id="<?php echo $avis['numavis'] ?>">

                                        <form class="avis-section" method="POST" action="modifier_avis.php?noteprod=<?php echo $avis['noteprod'] ?>&codeAvis=<?php echo $avis['numavis'] ?>&codeProduit=<?php echo $avis['codeproduit'] ?>" enctype="multipart/form-data">
                                            <h2>Modifier votre avis</h2>

                                            <div class="noter" id="stars">
                                                <span data-value="1" onclick="selectStar(1, <?php echo $avis['numavis'] ?>)">★</span>
                                                <span data-value="2" onclick="selectStar(2, <?php echo $avis['numavis'] ?>)">★</span>
                                                <span data-value="3" onclick="selectStar(3, <?php echo $avis['numavis'] ?>)">★</span>
                                                <span data-value="4" onclick="selectStar(4, <?php echo $avis['numavis'] ?>)">★</span>
                                                <span data-value="5" onclick="selectStar(5, <?php echo $avis['numavis'] ?>)">★</span>
                                            </div>

                                            <span id="note-value" style="display:none;">0</span>

                                            <textarea name="commentaire" maxlength="255" placeholder="Rédiger un commentaire..." required><?php echo $avis['commentaire'] ?></textarea>

                                            <div class="plein-buttons">
                                                <?php if (!empty($avis['photos'])): ?>
                                                    <p>La photo est prise en compte</p>
                                                <?php endif; ?>
                                                <label class="photo" for="contact_upload">Ajouter des photos</label>
                                                <input type="file" name="contact_upload" id="contact_upload" /><?php if (isset($contact_upload)) echo $contact_upload; ?></textarea>

                                                <button type="reset" class="cancel" onclick="closeOverlayModif(<?php echo $avis['numavis'] ?>)">Annuler</button>
                                                <button type="submit" class="submit">Modifier</button>


                                            </div>
                                            <!--<input type="hidden" name="commentaire" value="<?php echo $avis['commentaire'] ?>">-->
                                            <!--<input type="hidden" name="codeProduit" value="<?php echo $produit['codeproduit'] ?>">-->
                                            <input type="hidden" name="noteprod" id="noteprod<?php echo $avis['numavis'] ?>" value=1>
                                        </form>
                                    </div>
                                    <a href="supprimer_avis.php?codeavis=<?= $avis['numavis'] ?>&codeproduit=<?= $avis['codeproduit'] ?>">Supprimer</a>
                                </div>
                            <?php elseif ($_SESSION): ?>
                                <div class="signalement-avis">
                                    <a id="btnSignalerAvis" onclick="openOverlaySignaler()">Signaler</a>
                                    <div class="overlaysignaler" id="overlaysignaler">
                                        <form class="avis-section" method="POST" action="signaler_avis.php?codeavis=<?php echo $avis['numavis'] ?>&codeproduit=<?php echo $avis['codeproduit'] ?>" enctype="multipart/form-data">
                                            <h2>Signaler cet avis</h2>
                                            <textarea name="raison" maxlength="255" placeholder="Rédiger la raison du signalement..." required></textarea>
                                            <div class="bouttonSignalement" id="bouttonSignalement">
                                                <button type="reset" class="cancel" onclick="closeOverlaySignaler()">Annuler</button>
                                                <button type="submit" class="submit">Signaler</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="js/achat.js"></script>

    <script>
        function openOverlaySignaler() {
            document.getElementById("overlaysignaler").style.display = "flex";
        }

        function closeOverlaySignaler() {
            document.getElementById("overlaysignaler").style.display = "none";
        }

        function openOverlayModif(src, numAvis, codeProduit, avisCommentaire) {
            document.getElementById(numAvis).style.display = "flex";
            document.querySelector("form.avis-section").action = "modifier_avis.php?codeAvis=" + numAvis + "&codeProduit=" + codeProduit; //'&noteprod=' + document.getElementById('noteprod').value + 
            document.querySelector("form.avis-section textarea").value = document.querySelector(".avis .commentaire").innerText;
            updateStars(src);
        }

        function closeOverlayModif(numAvis) {
            document.getElementById(numAvis).style.display = "none";
        }

        function openOverlay(src) {
            document.querySelector("#overlay").src = src;
            document.getElementById("overlay").style.display = "block";
        }

        function fermerOverlay() {
            document.getElementById("overlay").style.display = "none";
        }

        function selectStar(valeur, numAvis) {
            console.log(document.getElementById('noteprod' + numAvis).value);
            document.getElementById('noteprod' + numAvis).setAttribute('value', valeur);
            console.log(document.getElementById('noteprod' + numAvis).value);
        }
    </script>
</body>

</html>