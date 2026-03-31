<?php
    header("location:infosCompte.php");
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

    $codeCompte = $_SESSION["codeCompte"];
    $infosCompte = $bdd->prepare("SELECT * FROM alizon.Client WHERE codeCompte = :codeCompte");
    $infosCompte->execute([":codeCompte" => $codeCompte]);
    $infosCompte = $infosCompte->fetch();
    $infosAdresse = $bdd->prepare("SELECT * FROM alizon.Adresse adresse INNER JOIN alizon.AdrFactCli fact ON adresse.idAdresse = fact.idAdresse WHERE codeCompte = :codeCompte");
    $infosAdresse->execute([":codeCompte" => $codeCompte]);
    $infosAdresse = $infosAdresse->fetch();

    if(isset($_GET["traitement"])){
        switch($_GET["traitement"]){
            case "bloquer":
                    $stmt = $bdd->prepare("UPDATE alizon.Client SET cmtBlq = TRUE WHERE codeCompte = :codeCompte");
                    $stmt->execute([":codeCompte" => $_SESSION["codeCompte"]]);
                    session_destroy();
            case "deconnecter":
                session_destroy();
                exit(header("location:index.php?deconnexion"));
        }
    }    

    if(isset($_GET["modif"])){
        if( $_GET["modif"] == "mdp"){
            $stmt = $bdd->prepare("UPDATE alizon.Client SET mdp = MD5(:newmdp) WHERE codeCompte = :codeCompte");
            $stmt->execute([":newmdp" => $_SESSION["nouveauMdp"]
            , ":codeCompte" => $_SESSION["codeCompte"]]);
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
                    $verifPseudo = $bdd->prepare("SELECT * FROM alizon.Client WHERE pseudo = :pseudo");
                    $verifPseudo->execute([":pseudo" => $valeur]);
                    $verifPseudo = $verifPseudo->fetch();
                    if($verifPseudo == NULL || $verifPseudo["pseudo"] == $value){
                        $stmt = $bdd->prepare("UPDATE alizon.Client SET :item = :valeur WHERE codeCompte = :codeCompte");
                        $stmt->execute([":codeCompte" => $codeCompte,
                        ":item" => $item,
                        ":valeur" => $valeur
                        ]);                        
                    }
                    else{
                        header('location:infosCompte.php?erreur=pseudo');
                    }
                }  
                //Vérifier qu'un mail n'est pas déjà pris
                if($item == "email"){
                    $verifMail = $bdd->prepare("SELECT * FROM alizon.Client WHERE email = :valeur");
                    $verifMail->execute([":valeur" => $valeur]);
                    $verifMail = $verifMail->fetch();
                    if($verifMail == NULL || $verifMail["email"] == $value){
                        $stmt = $bdd->prepare("UPDATE alizon.Client SET :item = :valeur WHERE codeCompte = :codeCompte");
                        $stmt->execute([":codeCompte" => $codeCompte,
                        ":item" => $item,
                        ":valeur" => $valeur
                        ]);                       
                    }
                    else{
                        header('location:infosCompte.php?erreur=email');
                    }
                }                 
                else{
                    $stmt = $bdd->prepare("UPDATE alizon.Client SET :item = :valeur WHERE codeCompte = :codeCompte");
                        $stmt->execute([":codeCompte" => $codeCompte,
                        ":item" => $item,
                        ":valeur" => $valeur
                        ]); 
                }

            }

        }        //Vérifier si on est sur un attribut d'adresse

        else if(array_key_exists($item, $infosAdresse)){
            if($valeur != $infosAdresse[$item]){
                $stmt = $bdd->prepare("UPDATE alizon.Adresse SET :item = :valeur WHERE idAdresse = :infoadr");
                $stmt->execute([
                    ":item" => $item,
                    ":valeur" => $valeur,
                    ":infoadr" => $infosAdresse["idadresse"]
                ]);
            }

        }
    }

if($_FILES["photo"]["name"] != ""){
    print_r($_FILES["photo"]);
    $extension = $_FILES["photo"]["type"];
    $extension = substr($extension, strlen("image/"), (strlen($extension) - strlen("image/")));
    $chemin = "./img/photosProfil/".time().".".$extension;


    move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin);


    $stmt = $bdd->prepare("INSERT INTO alizon.Photo (urlPhoto) VALUES (:urlphoto)");
    $stmt->execute(array(
        ":urlphoto" => $chemin
    ));
    $stmt = $bdd->prepare("UPDATE alizon.Profil SET urlPhoto = :urlPhoto WHERE codeClient = :codeCompte");
    $stmt->execute(array(
        ":urlPhoto" => $chemin,
        ":codeCompte" => $codeCompte
    ));

    $_SESSION["mdpValide"] = 0;
}
 