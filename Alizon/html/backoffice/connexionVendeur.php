<?php
//Connexion à la base de données.
require_once '../_env.php';
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
    //echo "❌ Erreur de connexion : " . $e->getMessage();
}
session_start();

if(isset($_GET["deconnexion"])){
    session_destroy();
}
$bdd->query('set schema \'alizon\'');
$error_msg = "";
if($_POST){
    $id = $_POST["pseudo"];
    $mdp = $_POST["mdp"];

    $req= $bdd->query("SELECT * FROM Vendeur WHERE pseudo = '".$id."' AND mdp = MD5('".$mdp."')");
    $rep= $req->fetch();

    if($rep!=null){
        $_SESSION["codeCompte"] = $rep["codecompte"];
        header("location: index.php");
        //print_r($_SESSION['codeCompte']);
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
    <link href="../css/style/ConnexionVendeur.css" rel="stylesheet" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
    <main>
        <div>
            <a href="index.php"><img src="../img/logo_back.svg" alt="logo-alizon" title="logo-alizon"/></a>
            <h2>Back Office</h2>
        </div>
        <form action="connexionVendeur.php" method="post">
            <h2>Connexion</h2>
            <h4>Profil Responsable</h4>
            <label for="pseudo">Identifiant</label>
            <input type="text" name="pseudo" placeholder="Identifiant..." id="pseudo" required/>
            <label for="mdp">Mot de passe</label>
            <input type="password" name="mdp" placeholder="Mot de passe..." id="mdp" required/>
             <?php
                if($error_msg != ""):
            ?>
                    <p> <?php echo($error_msg); ?><br/>
            <?php
                endif
            ?>
            <input class="bouton" type="submit" value="Se connecter" id="validerConnexion"/>
        </form>
       
        <aside>
            <figure>
                <img src="../img/line_3.svg"/>
                <p>Pas encore de compte ?</p>
                <img src="../img/line_3.svg"/>
            </figure>
            <nav>
                <a href="creerCompteVendeur.php" class="bouton">Créer un compte</a>
            <nav>
        </aside>
    
    </main>
    <?php include('../includes/backoffice/footer.php');?>



</body>
</html>