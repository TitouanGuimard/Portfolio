<?php
    header("location:infosCompteVendeur.php");
    session_start();
    require_once __DIR__ . '/_env.php';

    loadEnv(__DIR__ . '/../.env');

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

    $codeCompte = $_SESSION["codeCompte"];
    $infosCompte = $bdd->query("SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'")->fetch();

    $infosAdresse = $bdd->query("SELECT * FROM alizon.Adresse adresse INNER JOIN alizon.AdrSiegeSocial fact ON adresse.idAdresse = fact.idAdresse WHERE codeCompte = '".$codeCompte."'")->fetch();
    
    if(isset($_GET["traitement"])){

        session_destroy();
        
    }    

    if(isset($_GET["modif"])){
        if( $_GET["modif"] == "mdp"){
            $mdp = $_SESSION["mdp"];
            $codeCompte = $_SESSION["codeCompte"];
            $stmt = $bdd->prepare("UPDATE alizon.Vendeur SET mdp = MD5(:mdp) WHERE codeCompte = :codeCompte");
            $stmt->execute(array(
                ":mdp" => $mdp,
                ":codeCompte" => $codeCompte,
            ));
            $_SESSION["nouveauMdp"] = "";
        }

    }



    //Faire les modifs sur tous les attributs    
    foreach($_POST as $attribut => $valeur){
        $item = strtolower($attribut);
        //Vérifier si on est sur un attribut de compte
        if(array_key_exists($item, $infosCompte)){

            if($valeur != $infosCompte[$item]){
                //Vérifier qu'un pseudo n'est pas déjà pris
                if($item == "pseudo"){
                    $verifPseudo = $bdd->query("SELECT * FROM alizon.Vendeur WHERE pseudo = '".$valeur."'")->fetch();
                    if($verifPseudo == NULL || $verifPseudo["pseudo"] == $value){
                        $stmt = $bdd->prepare("UPDATE alizon.Vendeur SET ".$item." = '".$valeur."' WHERE codeCompte = '".$codeCompte."'");
                        $stmt->execute();                        
                    }
                    else{
                        header('location:infosCompteVendeur.php?erreur=pseudo');
                    }
                }  
                //Vérifier qu'un mail n'est pas déjà pris
                if($item == "email"){
                    $verifMail = $bdd->query("SELECT * FROM alizon.Vendeur WHERE email = '".$valeur."'")->fetch();
                    if($verifMail == NULL || $verifMail["email"] == $value){
                        $stmt = $bdd->prepare("UPDATE alizon.Vendeur SET ".$item." = '".$valeur."' WHERE codeCompte = '".$codeCompte."'");
                        $stmt->execute();                        
                    }
                    else{
                        header('location:infosCompteVendeur.php?erreur=email');
                    }
                }                 
                if($item == "raisonsoc"){
                    $verifRaison = $bdd->query("SELECT * FROM alizon.Vendeur WHERE raisonSociale = '".$valeur."'")->fetch();
                    if($verifRaison == NULL || $verifRaison["raisonsociale"] == $value){
                        $stmt = $bdd->prepare("UPDATE alizon.Vendeur SET ".$item." = '".$valeur."' WHERE codeCompte = '".$codeCompte."'");
                        $stmt->execute();   
                    }
                    else{
                        header('location:infosCompteVendeur.php?erreur=raisonSoc');

                    }

                }
                else{
                    $stmt = $bdd->prepare("UPDATE alizon.Vendeur SET ".$item." = '".$valeur."' WHERE codeCompte = '".$codeCompte."'");
                    $stmt->execute();
                }

            }

        }        //Vérifier si on est sur un attribut d'adresse

        else if(array_key_exists($item, $infosAdresse)){
            if($valeur != $infosAdresse[$item]){
                $stmt = $bdd->prepare("UPDATE alizon.Adresse SET ".$item." = '".$valeur."' WHERE idAdresse = '".$infosAdresse["idadresse"]."'");
                $stmt->execute();
            }

        }
    }
