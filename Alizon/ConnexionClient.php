<?php
//Connexion à la base de données.
require_once __DIR__ . '/_env.php';
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
    //echo "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    //echo "❌ Erreur de connexion : " . $e->getMessage();
}
$bdd->query('set schema \'alizon\'');
$error_msg = "";
$debloq_msg = "";
session_start();

if($_POST){
    $id = $_POST["pseudo"];
    $mdp = $_POST["mdp"];
    $_SESSION["pseudo"] = $id;
    $_SESSION["mdp"] = $mdp;
    $stmt = $bdd->prepare("SELECT * FROM Client WHERE pseudo = :pseudo AND mdp = MD5(:mdp)");
    $stmt->execute([':pseudo' => $id, ':mdp' => $mdp]);
    $rep = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rep!=null){
        $blq = $rep["cmtblq"];
        //bon id mais verif si bloqué
        if($blq==true){
            $error_msg="Vous avez décidé de bloquer votre compte. Vous ne pouvez pas vous connecter.";
            //$debloq_msg="Pour le débloquer, cliquez ici.";
        }else{
            $blqMod=$rep["cmtblqmod"];
            //si compte pas bloqué, vérif si compte bloqué par modo
            if($blqMod==true){
                $error_msg="Votre compte a été bloqué car vous n'avez pas respecté les règles d'utilisation de ce site.";
            }else{
                $_SESSION["id"] = "";
                $_SESSION["mdp"] = "";
                $_SESSION["codeCompte"] = $rep["codecompte"];
                $panierExiste = $bdd->prepare("SELECT idPanier FROM alizon.Panier WHERE codeCompte = :codecompte");
                $panierExiste->execute([':codecompte' => $rep["codecompte"]]);
                $panierExiste = $panierExiste->fetch(PDO::FETCH_ASSOC);
                if($panierExiste){
                    $_SESSION["idPanier"] = $panierExiste["idpanier"];
                }
                else if(isset($_SESSION["idPanier"])){
                    $codeCompte = $_SESSION["codeCompte"];
                    $idPanier = $_SESSION["idPanier"];
                    $stmt = $bdd->prepare("UPDATE alizon.Panier SET codeCompte = :codeCompte WHERE idPanier = :idPanier");
                    $stmt->execute(array(
                        ":codeCompte" => $codeCompte,
                        ":idPanier" => $idPanier,
                    ));
                }
                exit(header("location: index.php"));
                    
                
                die();

            }
        }
        
    }else{
        $error_msg="Identifiant ou mot de passe incorrect.";
        
    }
    
}
?> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="./css/style/ConnexionClient.css" rel="stylesheet" type="text/css">
    <link href="./css/components/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
    <main>
        <a href="index.php"><img src="./img/logo_alizon_front.svg" alt="logo-alizon" title="logo-alizon"/></a>
        <form action="ConnexionClient.php" method="post">
            <h2>Connexion</h2>
            <label for="pseudo">Identifiant</label>
            <input type="text" name="pseudo" placeholder="Identifiant..." id="identifiant" required/>
            <label for="mdp">Mot de passe</label>
            <input type="password" name="mdp" placeholder="Mot de passe..." id="mdpCli" required/>
            <?php
                if($error_msg != ""):
            ?>
                    <p> <?php echo($error_msg); ?><br/>
                    <a><?php echo ($debloq_msg);?></a>
                    
            <?php
                endif
            ?>
            <input class="bouton" type="submit" value="Se connecter" id="validerConnexion"/>
        </form>
        
        <aside>
            <figure>
                <img src="./img/line_1.svg"/>
                <p>Pas encore de compte ?</p>
                <img src="./img/line_1.svg"/>
            </figure>
            <div class="bouton-connexion">
                <a href="CreerCompte.php" class="bouton">Créer un compte</a>
                <a href="Catalogue.php" class="btnJaune">Retour</a>
            <div>
        </aside>
    
    </main>
    <?php include('./includes/footer.php');?>
<script>

    function debloquerCompte(){

        window.location.href = "debloquer.php";
    }  
</script>

</body>
</html>
