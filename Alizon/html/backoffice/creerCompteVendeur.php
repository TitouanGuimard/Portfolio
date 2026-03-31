<?php 
    if(isset($_GET["erreur"])){
        $erreur = $_GET["erreur"];
    }
    else{
        $erreur = NULL;
    }
    
    if($_POST){
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
        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
            $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
            //echo "✅ Connecté à PostgreSQL ($dbname)";
        } catch (PDOException $e) {
            //echo "❌ Erreur de connexion : " . $e->getMessage();
        }
        
        //initialisation des variables
        $pseudo = $_POST["pseudo"];
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $email = $_POST["email"];
        $email_conf = $_POST["email_conf"];
        $num_tel = $_POST["num_tel"];
        $mdp = $_POST["mdp"];
        $mdp_conf = $_POST["mdp_conf"];
        $num_siren = $_POST["num_siren"];
        $raison_soc = $_POST["raison_soc"];
        $num_adresse1 = $_POST["num_adresse1"];
        $rue_adresse1 = $_POST["rue_adresse1"];
        $adresse2 = $_POST["adresse2"];
        $code_post = $_POST["code_post"];
        $ville = $_POST["ville"];
        
        
            
        //insertion d'une adresse dans la base de données
        $stmt = $pdo->prepare("INSERT INTO alizon.Adresse(num, nomRue, codePostal, nomVille, complementAdresse) VALUES (:num, :nomRue, :codePostal, :nomVille, :complementAdresse)");
        $stmt->execute(array(
            ":num" => $num_adresse1,
            ":nomRue" => $rue_adresse1,
            ":codePostal" => $code_post,
            ":nomVille" => $ville,
            ":complementAdresse" => $adresse2, 
            
        ));
        
        //Prise de l'id de l'adresse créée
        $res = $pdo->query("SELECT idAdresse FROM alizon.Adresse ORDER BY idAdresse DESC LIMIT 1")->fetch();
        $idAdresse = $res["idadresse"];


        //check si identifiant / raison sociale / mail n'est pas déjà utilisé
        $requete = $pdo->query("SELECT * FROM alizon.Vendeur WHERE pseudo = '".$_POST["pseudo"]."'")->fetch();
        $requeteRaiSoc = $pdo->query("SELECT * FROM alizon.Vendeur WHERE raisonSociale ='".$_POST["raison_soc"]."'")->fetch();
        $requeteMail = $pdo->query("SELECT * FROM alizon.Vendeur WHERE email = '".$_POST["email"]."'")->fetch();
        if($requete != NULL){
            $erreur = "pseudo";
        }
        else if($requeteRaiSoc != NULL){
            $erreur = "raisonSoc";
        }
        else if($requeteMail != NULL){
            $erreur = "Mail";
        }
        else{
            $erreur = "";
        }
        

        if($erreur == ""){
            //insertion d'un vendeur dans la base de données
            $stmt = $pdo->prepare('INSERT INTO alizon.Vendeur(nom, prenom, numTel, SIREN, email, pseudo, raisonSociale, idAdresseSiege, mdp) VALUES (:nom, :prenom, :numtel, :siren, :mail, :id, :raisonsoc, :idAdresseSiege, MD5(:mdp))');
            $stmt->execute(array(
                ":nom" => $nom,
                ":prenom" => $prenom,
                ":numtel" => $num_tel,
                ":siren" => $num_siren,
                ":id" => $pseudo,
                ":raisonsoc" => $raison_soc,
                ":idAdresseSiege" => $idAdresse, //insertion de l'id associé au vendeur
                ":mail" => $email,
                ":mdp" => $mdp
            ));
        }


    }
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style/backoffice/creer_compte_vendeur.css" >
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
    <title>Creer Compte Vendeur</title>
</head>
<body>
    <header>
        <?php 
        if($erreur != ""){
            
            switch($erreur){
                case "pseudo":
                    echo "<h2 style=\"color:red\">  Identifiant déjà utilisé</h2>";
                    break;
                case "raisonSoc":
                    echo "<h2 style=\"color:red\">  Raison sociale déjà enregistrée</h2>";
                    break;
                case "mail":
                    echo "<h2 style=\"color:red\">  Adresse e-mail déjà utilisée</h2>";
            }
        }
        ?>
</header>
    <main>

        <div class="header">
            <img src="../img/logoAlizonBack.svg" alt="Alizon">
            <h1>Back Office</h1>

        </div>
        <form action="creerCompteVendeur.php" method="post">
            <div class="main">
                <section>
                    <h2>
                        Création de compte
                    </h2>

                    <h3>
                        Profil Responsable
                    </h3>
                    <div class="label">
                        <label>
                            Identifiant* :
                        </label>
                        <?php 
                            if($erreur == "pseudo"){
                                echo "<p style=\"color:red\">Pseudonyme déjà utilisé</p>";
                            }
                        ?>
                        <input type="text" name="pseudo" id="pseudo" pattern="[A-Za-z._0-9]{2,20}" class="moyeninput" required>
                        <span>L'identifiant doit faire entre 2 et 20 caractères (lettres, ".", "_" acceptés)</span>
                    </div>
                    <div class="alignementdesentrer">
                        <div class="label">
                            <label>
                                Nom* :
                            </label>
                            <input type="text" name="nom" id="nom" class="moyeninput" required>
                            <span>Le nom doit faire entre 2 et 20 caractères (lettres, ".", "_" acceptés)</span>
                        </div>
                        <div class="labelaligner">
                            <label>
                                Prenom* :
                            </label>
                            <input type="text" name="prenom" id="prenom" class="moyeninput"  class="dimension_petit" required>
                            <span>Le prénom doit faire entre 2 et 20 caractères (lettres, ".", "_" acceptés)</span>
                        </div>
                    </div>
                    <div class="label">
                        <label>
                            Adresse e-mail* :
                        </label>
                        <?php 
                            if($erreur == "mail"){
                                echo "<p>Adresse e-mail déjà utilisée</p>";   
                            }
                        ?>
                        <input type="text" name="email" id="email"  class="grandinput" pattern="^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$" placeholder="ex: nomadresse@gmail.fr" required>
                        <span>Le format est incorrect ("exemple : a@gmail.fr")</span>
                    </div>
                    <div class="label">
                        <label>
                            Confirmer adresse e-mail* :
                        </label>
                        <input type="text" name="email_conf" id="email_conf" class="grandinput" required>
                        <span>Les deux adresses e-mail doivent être identiques</span>
                    </div>
                    <div class="label">
                        <label>
                            Numéro de téléphone* :
                        </label>
                        <input type="text" name="num_tel" id="num_tel" pattern="[0-9]{10}" class="petitinput" placeholder="ex: 0298525236" required>
                        <span>Le numero de téléphone doit faire 10 numéros</span>
                    </div>
                    <div class="label">
                        <label>
                            Mot de passe* :
                        </label>
                        <input type="password" name="mdp" id="mdp" class="moyeninput" pattern="[A-Za-z0-9?,.;:§!$£*µù%]{2,20}" required>
                        <span>Le mot de passe doit contenir au minimum 2 caractères et au maximum 20.</span>
                    </div>
                    <div class="label">
                        <label>
                            Confirmer mot de passe* :
                        </label>
                        <input type="password" name="mdp_conf" class="moyeninput" id="mdp_conf" required>
                        <span>Les deux mots de passe doivent être identiques</span>
                    </div>
                </section>
                <hr>
                <section>
                    <h3>
                        Information entreprise
                    </h3>
                    <div class="label">
                        <label>
                            Numéro SIREN* :
                        </label>
                        <input type="text" name="num_siren" class="moyeninput" id="num_siren" pattern="[0-9]{9}" placeholder="ex: 021021021" required>
                        <span>Le numéro de SIREN doit faire 9 chiffres de suites</span>
                    </div>
                    <div class="label">
                        <label>
                            Raison sociale* :
                        </label>
                        <input type="text" name="raison_soc" class="moyeninput" id="raison_soc" required>
                    </div>
                    <div class="label">
                        <label>
                            Numéro d'adresse* :
                        </label>
                        <input type="text" name="num_adresse1" class="moyeninput" id="num_adresse1" pattern="[0-9]{1,9}" required>
                    </div>
                    <div class="label">
                        <label>
                            Rue* :
                        </label>
                        <input type="text" name="rue_adresse1" class="grandinput" id="rue_adresse1" required>
                    </div>
                    <div class="label">
                        <label>
                            Complément :
                        </label>
                        <input type="text" name="adresse2" class="moyeninput" id="adresse2">
                    </div>
                    <div class="label">
                        <label>
                            Code postal* :
                        </label>
                        <input type="text" name="code_post" id="code_post" class="moyeninput" pattern="^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$" required>
                        <span>Le code postal ne doit être constitué que de 5 chiffres</span>
                    </div>
                    <div class="label">
                        <label>
                            Ville* :
                        </label>
                        <input type="text" name="ville" id="ville" class="moyeninput" pattern="^(?:[A-ZÀÂÄÉÈÊËÎÏÔÖÙÛÜÇ][a-zàâäéèêëîïôöùûüç’']*\.?\s?)+(?:[A-ZÀÂÄÉÈÊËÎÏÔÖÙÛÜÇa-zàâäéèêëîïôöùûüç’'\- ]*)$" required>
                        <span>La ville ne doit pas commencer par une majuscule et ne doit pas contenir de chiffres.</span>
                    </div>
                </section>
            </div>
            <div class="boutton">
                <button type="submit" id="valider" >Créer le compte</button>
                <a href="connexionVendeur.php" class="bouton">Retour</a>
            </div>
        </form>
    </main>
    <?php include("../includes/backoffice/footer.php"); ?>
    <script>
        
        let mail = document.getElementById("email");
        let confMail = document.getElementById("email_conf");
        let valider = document.getElementById("valider");

        let mdp = document.getElementById("mdp");
        let confMdp = document.getElementById("mdp_conf");
        confMail.addEventListener("focusout", verifFormat);
        confMdp.addEventListener("focusout", verifFormat);
        let btnValider = document.getElementById("valider");
        btnValider.addEventListener("click", verifFormatClic);
        

        function verifMailMdp(evt){
            console.log("cc");
            evt.preventDefault();
            if(confMail.value != mail.value){
                evt.preventDefault();
                confMail.classList.add("invalid");
            }
            else{
                confMail.classList.remove("invalid");
            }
            if(confMdp.value != mdp.value){
                evt.preventDefault();
                confMdp.classList.add("invalid");
            }
            else{
                confMdp.classList.remove("invalid");
            }
        }    
        function verifFormat(evt){
            if(evt.type == "focusout"){
                if(mail.value != confMail.value){
                    console.log("cc");
                    confMail.classList.add("invalid");
                }
                else{
                    confMail.classList.remove("invalid");
                    confMail.setAttribute("isvalid", false);
                }
                if(mdp.value != confMdp.value){
                    confMdp.classList.add("invalid");
                    onfMail.setAttribute("invalid", true);
                }
                else{
                    confMdp.classList.remove("invalid");
                }
            }
        }
        function verifFormatClic(evt){
            if(confMail.value != mail.value){
                confMail.classList.add("invalid");
                evt.preventDefault();

            }
            if(confMdp.value != mdp.value){
                confMdp.classList.add("invalid");
                evt.preventDefault();

            }
        }
  
    </script>


</body>
</html>

