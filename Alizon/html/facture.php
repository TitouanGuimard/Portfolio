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
    if(!$estClient || !isset($_SESSION["codeCompte"])){
        exit(header("location:index.php"));
    }
    $codeCompte = $_SESSION["codeCompte"];
    $bdd->query("SET SCHEMA 'alizon'");
    $numCom = $_SESSION["numCom"];
    $adrLiv = $bdd->query("SELECT idAdresse FROM alizon.AdrLiv WHERE numCom = ".$numCom)->fetch();
    $adresse = $bdd->query("SELECT * FROM alizon.Adresse WHERE idAdresse = ". $adrLiv["idadresse"])->fetch();
    $articles = $bdd->query("SELECT * FROM alizon.ProdUnitCommande INNER JOIN alizon.Produit ON alizon.ProdUnitCommande.codeProduit = alizon.Produit.codeProduit WHERE numCom = ".$numCom)->fetchAll();

    $prixTotaux = $bdd->query("SELECT SUM(prixhttotal * qteprod) prixtotalht, SUM(prixttctotal * qteprod) prixtotalttc FROM alizon.ProdUnitCommande WHERE numCom = ".$numCom)->fetch();
    $infosCompte = $bdd->query("SELECT nom, prenom, email FROM alizon.Client WHERE codeCompte = ". $codeCompte)->fetch();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALIZON Facture n°<?php echo $numCom?>"</title>
    <link href="./css/style/facture.css" rel="stylesheet" type="text/css">

</head>
<body>
<main>
    <a class="bouton" href="paiementFini.php">Retour</a>
    <div class="ligneFacture">
        <img src="./img/logo_alizon_front.svg" alt="logo_alizon" title="logo_alizon"/>
        <h1>FACTURE</h1>
    </div>
    <div class="ligneFacture">
        <p>DATE : <?php echo date("d-m-Y")?></p>
        <p>Facture n° <?php echo $numCom?></p>
    </div>
    <hr>
    <div class="ligneFacture">
        <div class="groupeFact">
            <p>ÉMETTEUR : Alizon</p><br/>
            <p>alizon.support@gmail.com</p>
            <p>02.98.30.85.19</p>
            <p>2 Rue Jules Simon</p>
            <p>35000 RENNES</p>
        </div>
        <div class="groupeFact">
            <p>DESTINATAIRE : <?php echo $infosCompte["nom"] . " " . $infosCompte["prenom"]?></p><br/>
            <p><?php echo $infosCompte["email"]?></p>
            <p><?php echo $adresse["num"] . " " . $adresse["nomrue"]?></p>
            <p><?php echo $adresse["nomville"] . " " . $adresse["codepostal"]?></p> 

        </div>
    </div><br/>
    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>    
        </tr>
            <?php foreach($articles as $article):?>
                <tr>
                
                <td><?php echo $article["libelleprod"]?></td>
                <td><?php echo $article["prixttc"]?> €</td>
                <td><?php echo $article["qteprod"]?></td>
                <td><?php echo $article["prixttc"] * $article["qteprod"]?> €</td>

            </tr>
            <?php endforeach?>
        
        </tbody>
    </table>
    <div class="ligneFacture">
        <div class="groupeFact">
            <h2>RÈGLEMENT</h2>
            <p><strong>Par carte bancaire</strong></p><br/>
            <p>Conditions Générales de Vente disponibles sur le site www.alizon.fr</p>
        </div>
        <div class="groupeFact">
            <h3>TOTAL HT : <?php echo round($prixTotaux["prixtotalht"],2)?> €</h3>
            <h4>TVA : <?php echo $prixTotaux["prixtotalttc"] - $prixTotaux["prixtotalht"]?> €</h4>
            <h3>TOTAL TTC : <?php echo round($prixTotaux["prixtotalttc"],2)?> €</h3>
        </div>
    </div>

    
</main>
<script>
    window.print();

</script>
</body>
    
</html>