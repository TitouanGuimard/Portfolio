<?php
session_start();

// if(!array_key_exists("codeCompte", $_SESSION) || !isset($_SESSION["codeCompte"]) || $_GET['numCom'] == null){
//             header("location:index.php");
// }
$socket = fsockopen("10.253.5.102", 8080);

$codeCompte = $_SESSION["codeCompte"];
$numCom = $_GET['numCom'];

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
    // "✅ Connecté à PostgreSQL ($dbname)";
} catch (PDOException $e) {
    //"❌ Erreur de connexion : " . $e->getMessage();
    ?>
    <script>
        alert("Erreur lors du chargement");
    </script>
    <?php
        header('Location: http://localhost:8888/index.php');
        exit();
}

$bdd->query('set schema \'alizon\'');
$data = '';
$res = $bdd->query("SELECT bordereau FROM alizon.Commande WHERE numCom = ".$numCom)->fetch();
$bordereau = $res["bordereau"];
if($bordereau != -1){

    fwrite($socket, "CONN test0 mdp0\n");
    while (($data = fread($socket, 24 )) == '\n' ) {
        $data .= fread($socket, 24 );
    }
    
$data = '';
fwrite($socket, "SETBDR ".$bordereau);


$data = fread($socket, 1024);

$final = fread($socket, 1024);
//var_dump($etape);
$etape = substr($final, strlen("Étape "), 1);
}
else{
    $etape = 1;
}

//fwrite($socket, "GETETAPE\n");
//$etape = fread($socket, 1024);

//if($data == "CONNECTÉ"){

//echo $bordereau;
    //fwrite($socket, "SETBDR ".$bordereau. "\n");
    // while (($data = fread($socket, 1024 )) == '\n' ) {
    //     $data .= fread($socket, 1024 );
    // }

    //fwrite($socket, "BYE BYE\n");
//fclose($socket);
    // fwrite($socket, "GETETAPE\n");
    // $etape = fread($socket, 64);
    // $etape = substr($etape, strlen("Étape "), 1);
 //   var_dump($etape);
//}
?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/style/Commande.css" rel="stylesheet" type="text/css">
    <title>Alizon</title>
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
        <?php
            
            include 'includes/menuCompte.php';
        ?>
        <div class="titre-cat">
            <h1>Suivi commande</h1>
            <div class="separateur"></div>
        </div>
        <article>
            <?php
            //Récupération des informations de la commande 
            $commande = $bdd->query("SELECT * FROM Commande where numCom = ".$numCom)->fetch();
            $client = $commande["codecompte"];
            $prixTTC = $commande["prixttctotal"];
            $prixHT = $commande["prixhttotal"];
            $date = date( 'd/m/Y', strtotime($commande["datecom"]));
            
            //Récupération des informations de l'acheteur
            $infoCpt = $bdd->query("SELECT nom,prenom,numtel from Client where codeCompte = ".$client)->fetch();
            $nomCli = $infoCpt['nom'] ;
            $prenomCli = $infoCpt['prenom'];
            $numTel = wordwrap($infoCpt['numtel'], 2, ".", 1);


            //Récupération des informations de l'adresse de facturation
            $adrLiv = $bdd->query("SELECT idAdresse FROM AdrLiv where numCom = ".$numCom)->fetch();
            $infoAdr = $bdd->query("SELECT * FROM Adresse WHERE idAdresse = ".$adrLiv['idadresse'])->fetch();
            $num = $infoAdr['num'];
            $nomRue = $infoAdr['nomrue'];
            $ville = $infoAdr['nomville'];
            $codePostal = $infoAdr['codepostal'];            
                
            ?>
            <div>
                <p><strong>Numero de commande :</strong> <?php echo $numCom ?></p>
                <p><strong>Date de commande :</strong> <?php echo $date ?></p>
            </div>
            <div>
                <p><strong>Informations Client :</strong> <?php echo $nomCli .' '.$prenomCli; ?></p>
                <p><strong>Numéro téléphone :</strong> <?php echo $numTel ?></p>
            </div>
            <div>
                <p><strong>Adresse Livraison :</strong></p>
                <p><?php echo $num.' '.$nomRue.' '.$ville.' '.$codePostal ;?></p>
            </div>

        </article>
        <div class="titre-cat">
            <h1>Avancement</h1>
            <div class="separateur"></div>
        </div>
        <section>
            <?php
            
            switch ($etape) { #en fonction de l'étape recue par delivraptor, afficher l'avancement
                case 1:
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    
                    <?php 
                    break;
                case 2:
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>

                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <?php
                    break;
                case 3:
                case 4:
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>

                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <?php
                    break;
                case 5:  
                case 6:
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>


                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <?php
                    break;
                case 7:
                case 8:
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#FCB66B" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>

                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#FCB66B"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#FCB66B"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <?php
                    break;
                case 9: 
                    ?>
                    <div class="suiviOrdi">
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 22C1.11929 22 0 23.1193 0 24.5C0 25.8807 1.11929 27 2.5 27L2.5 24.5L2.5 22ZM212.5 27L215 27V22L212.5 22V24.5V27ZM2.5 24.5L2.5 27L212.5 27V24.5V22L2.5 22L2.5 24.5Z" fill="#064082"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="215" height="50" viewBox="0 0 215 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 24.5L212.5 24.5" stroke="#064082" stroke-width="5" stroke-linecap="square"/>
                                <circle cx="106" cy="25" r="25" fill="#064082"/>
                            </svg>
                            <p>Livrée</p>
                        </div>

                    </div>
                    <div class = "suiviTel">
                        <div>
                            <svg width="24" height="70" viewBox="0 0 24 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.8984 2.5C14.8984 1.11929 13.7791 0 12.3984 0C11.0177 0 9.89844 1.11929 9.89844 2.5L12.3984 2.5L14.8984 2.5ZM9.89844 67.5V70H14.8984V67.5H12.3984H9.89844ZM12.3984 2.5L9.89844 2.5L9.89844 67.5H12.3984H14.8984L14.8984 2.5L12.3984 2.5Z" fill="#064082"/>
                                <circle cx="12" cy="36.5259" r="12" transform="rotate(90 12 36.5259)" fill="#064082"/>
                            </svg>
                            <p>Création bordereau  <br>de livraison</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Prise en charge <br> Alizon</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge  <br> transporteur</p>
                        </div>
                        <div>
                           <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge <br> plateforme régionale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65V67.5H14.8984V65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>

                            <p>Prise en charge <br> plateforme locale</p>
                        </div>
                        <div>
                            <svg width="24" height="68" viewBox="0 0 24 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.89844 65C9.89844 66.3807 11.0177 67.5 12.3984 67.5C13.7791 67.5 14.8984 66.3807 14.8984 65H12.3984H9.89844ZM12.3984 0L9.89844 0L9.89844 65H12.3984H14.8984L14.8984 0L12.3984 0Z" fill="#064082"/>
                                <circle cx="12" cy="34.0259" r="12" transform="rotate(90 12 34.0259)" fill="#064082"/>
                            </svg>
                            <p>Livrée</p>
                        </div>
                    </div>
                    <?php 
                    break;
                }
            ?>
        </section>
        <?php if($etape == 9){
            $statutColis = trim(substr($final, strlen("Étape 9 STATUT COLIS : " ),strlen($final) - strlen("Étape 9 STATUT COLIS : ")));
            
            ?>
            <div class="statut">
                <?php if($statutColis == "COLOK" || $statutColis == "COLOKABS"){?>
                <h2>Votre commande a été livrée</h2> 
                <?php
                }
                else{?>
                <h2 style="color:red">Votre commande n'a pas été livrée</h2>

                <?php
                }
            
            

            //echo $statutColis;
            switch($statutColis){
            
            case "COLOK":
                    ?><p>Colis remis en mains propres.</p>
                    <?php
                    break;
            case "COLOKABS":
                ?><p>Colis remis en votre absence, votre livreur a pris une photo.</p><?php
                fwrite($socket, "GETPHOTO\r\n");
                //RÉCUP IMAGE   
                exec("rm ./img/boitelettre2.png");  
                $fin = false;
                while($fin == false){
                    $data = fread($socket, 8192);
                    if(strlen($data) == 8192){
                        //$tailleTot += strlen($data);
                        //echo strlen($data);
                        //$_SESSION["boucle"] = $_SESSION["boucle"] + 1;
                        //var_dump($data);
                        file_put_contents("./img/boitelettre2.png", $data, FILE_APPEND);
                        $data = '';
                    }
                    else{
                        $fin = true;
                        file_put_contents("./img/boitelettre2.png", $data, FILE_APPEND);
                        //fclose($socket);
                    }
                    

                }
                echo '<img src="data:image/png;base64,'.base64_encode(file_get_contents("./img/boitelettre2.png")).'" />';
                break;
            case "COLDMG":
                ?><p>Votre colis a été endommagé, vous avez décidé de le refuser. Contactez votre livreur pour faire un recours.</p>
                <?php
                break;
            case "COLJSP":
                ?><p>Vous avez refusé votre colis, contactez votre livreur pour faire un recours.</p><?php
                break;
            case "COLADR":
                ?><p>L'adresse de livraison indiquée était erronnée. Contactez votre livreur pour choisir un point de dépôt.</p><?php
                break;
            case "COLCMD":
                ?><p>Vous avez refusé votre colis car il ne correspondait pas à la commande, veuillez contacter le support pour faire un recours.</p><?php
                break;

                
        }
            
            ?>
        </div>
        <?php }?>
        <div class="titre-cat">
            <h1>Produits</h1>
            <div class="separateur"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nom produit</th>
                    <th>Code Produit</th>
                    <th>Prix TTC</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $lesProduits = $bdd->query("SELECT ALL * FROM ProdUnitCommande where numCom = ".$numCom)->fetchAll();
                //print_r($lesProduits);
                foreach($lesProduits as $prod){
                    $idProd = $prod['codeproduit'];
                    $prixTTC = $prod['prixttctotal'];
                    $qteprod =$prod['qteprod'];
                    $nomProd = $bdd->query('SELECT libelleProd FROM Produit where codeProduit = '. $idProd)->fetch();
                    
                    ?>
                    
                     <tr>
                        <td>
                            <a href="dproduit.php?id=<?= $idProd ?>" class="btn-prod">
                            <?php echo $nomProd['libelleprod'];?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right-icon lucide-arrow-up-right">
                                    <path d="M7 7h10v10"/>
                                    <path d="M7 17 17 7"/>
                                </svg>
                            </a>
                        </td>
                        <td><?php echo $idProd ; ?></td>
                        <td><?php echo $prixTTC ;?></td>
                        <td><?php echo $qteprod ;?></td>
                     </tr>
                   
                     <?php
                }
                ?>
            </tbody>
        </table>
        <?php fwrite($socket, "BYE BYE\n"); fclose($socket);?>
    </main>
    <?php include "includes/footer.php"?>
</body>
</html>
