<?php
    session_start(); 
    header("location:index.php");
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


    $mail = $_POST['mail'];
    $mdp = $_POST['mdp'];
    $dateNaissance = $_POST['dateNaiss'];



    $pseudo = $_POST["pseudo"];
    $nom = strtoupper($_POST["nom"]);
    $prenom = $_POST["prenom"];
    $prenom = strtoupper(substr($prenom, 0, 1)) . substr($prenom, 1, strlen($prenom));
    $numTel = $_POST["numTel"];
    $numRue = $_POST["numRue"] ? $_POST["numRue"] : NULL;

    $nomRue = $_POST["nomRue"] ? $_POST["nomRue"] : NULL;

    $codePostal = $_POST["codePostal"] ? $_POST["codePostal"] : NULL;

    $ville = $_POST["ville"] ? strtoupper(substr($_POST["ville"], 0,1)) . substr($_POST["ville"],1,strlen($_POST["ville"]))  : NULL;

    $numApt = $_POST["numApt"] ? $_POST["numApt"] : NULL;

    $complement = $_POST["comp"] ? $_POST["comp"] : NULL;
    
    $res = $bdd->prepare("SELECT * FROM alizon.Client WHERE pseudo = '".$pseudo."'");
    $res->execute();
    $res = $res->fetch();
    $resMail = $bdd->prepare("SELECT * FROM alizon.Client WHERE email = '".$mail."'");
    $resMail->execute();
    $resMail = $resMail->fetch();
    if($res){
        exit(header('location:CreerCompte.php?erreur=pseudo'));
        die();
    }
    else if($resMail){
        exit(header('location:CreerCompte.php?erreur=mail'));
        die();
    }
    else{
        $stmt = $bdd->prepare("INSERT INTO alizon.Client(pseudo, dateCreation, dateNaissance, nom, prenom, email, mdp, numTel) VALUES (:pseudo, :dateCreation, :dateNaissance, :nom, :prenom, :email, MD5(:mdp), :numTel)");
        $stmt->execute(array(
            ":pseudo" => $pseudo,
            ":dateCreation" => date("Y-m-d H:i:s"),
            ":dateNaissance" => $dateNaissance,
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $mail,
            ":mdp" => $mdp,
            ":numTel" => $numTel
        ));
        
        if($numRue != NULL && $codePostal != NULL && $ville != NULL){
            $stmt = $bdd->prepare("INSERT INTO alizon.Adresse(num,codePostal, nomVille, nomRue, complementAdresse, numAppart) VALUES(:num, :codePostal, :nomVille, :nomRue, :complement, :numAppart)");
            $stmt->execute(array(
                ":num" => $numRue,
                ":codePostal" => $codePostal,
                ":nomVille" => $ville,
                ":nomRue" => $nomRue,
                ":complement" => $complement,
                ":numAppart" => $numApt
            ));
            $idAdresse = $bdd->lastInsertId();
        }
        else{
            $idAdresse = -1;
        }

            
    
        $res = $bdd->prepare("SELECT codeCompte FROM alizon.Client WHERE pseudo = '".$pseudo."'");
        $res->execute();
        $res = $res->fetch();
        $codeCompte = $res["codecompte"];
        if($idAdresse > 0){

            $stmt = $bdd->prepare("INSERT INTO alizon.AdrFactCli(codeCompte, idAdresse) VALUES (:codeCompte, :idAdresse)");
            $stmt->execute(array(
                ":codeCompte" => $codeCompte,
                ":idAdresse" => $idAdresse,
            ));
        }


        if($_FILES["photo"]["size"]!= 0){
            
            $nomPhoto = $_FILES["photo"]["name"];
            $extension = $_FILES["photo"]["type"];
            $extension = substr($extension, strlen("image/"), (strlen($extension) - strlen("image/")));
            $chemin = "./img/photosProfil/".time().".".$extension;

            move_uploaded_file($_FILES["photo"]["tmp_name"], $chemin);
            $stmt = $bdd->prepare("INSERT INTO alizon.Photo VALUES (:urlPhoto)");
            $stmt->execute(array(
                ":urlPhoto" => $chemin
            ));

        }
        else{
            $chemin = "./img/photosProfil/Default_pfp.svg";
        }

        $stmt = $bdd->prepare("INSERT INTO alizon.Profil(urlPhoto, codeClient) VALUES(:photo, :client)");
        $stmt->execute(array(
            ":photo" => $chemin,
            ":client" => $codeCompte
        ));

        $_SESSION["codeCompte"] = $codeCompte;
    }



