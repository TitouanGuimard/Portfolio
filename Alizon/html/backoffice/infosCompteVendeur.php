<?php 
    session_start();
    
    if(!array_key_exists("codeCompte", $_SESSION)){
        header("location:connexionVendeur.php");
    }
    $codeCompte = $_SESSION["codeCompte"];
    if(array_key_exists("erreur", $_GET)){
        $erreur = $_GET["erreur"];

    }
    else{
        $erreur = "";
    }
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
    $codeCompte = $_SESSION["codeCompte"];
    //$codeCompte = 1;
    $compte = $bdd->query("SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'")->fetch();
    
    $adresse = $bdd->query("SELECT * FROM alizon.Adresse adresse INNER JOIN alizon.AdrSiegeSocial fact ON adresse.idAdresse = fact.idAdresse WHERE codeCompte = '".$codeCompte."'")->fetch();
    if(!isset($_SESSION["mdpValide"])){
        $_SESSION["mdpValide"] = 0;
    }

$sql = "SELECT * FROM alizon.Vendeur WHERE codeCompte = '".$codeCompte."'";
$stmt = $bdd->query($sql);
$vendeur = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[BackOffice] Mon compte</title>
    <link href="../css/style/backoffice/infosCompteVendeur.css" rel="stylesheet" type="text/css">
    <link href="../css/components/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>  
    <a href="index.php" class="btn-retour" >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-chevron-left-icon lucide-square-chevron-left">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="m14 16-4-4 4-4"/>
                </svg>
                Retour
        </a> 
    <main>
    
        <?php if($_SESSION["mdpValide"] == -1):?>
            <h2 class="erreur">Mot de passe incorrect</h2>
        <?php endif?>
    <div class="logo">
        <a href="accueil.php"><img src="../img/logoAlizonBack.svg" alt="Alizon"></a>
        <h1>Back Office</h1>
    </div>
    
        <h2>Mon compte</h2>
        <h2 hidden>Modifier mes informations</h2>
        <div class="containerForm">
  
        <form action="modifCompteVendeur.php" method="post" enctype="multipart/form-data">
            <details open>
                <summary><h3>Profil responsable V</h3></summary>

                <div id="profResp">
                
                    <div class="labelInput">
                        <label for="identifiant">Identifiant</label>
                        <?php if($erreur == "pseudo"):?>
                            <p class="erreur">Pseudonyme déjà utilisé</p>
                        <?php endif?>
                        <input type="text" name="pseudo" id="identifiant" pattern="[A-Za-z._0-9]{2,20}" value="<?php echo $compte["pseudo"]?>" required disabled/> 
                        <span>L'identifiant doit faire entre 2 et 20 caractères (lettres, ".", "_" acceptés)</span>
                    </div>
                    <div id="nomPrenomVendeur">
                        <div class="labelInput">
                            <label for="nomVendeur">Nom</label>
                            <input type="text" name="nom" id="nomVendeur" value="<?php echo $compte["nom"] ?>" required disabled/>
                        </div>
                        <div class="labelInput">
                            <label for="prenomVendeur">Prénom</label>
                            <input type="text" name="prenom" id="prenomVendeur" value="<?php echo $compte["prenom"]?>" required disabled/>
                        </div>
                    </div>
                    <div class="labelInput">
                    <label for="mailVendeur">Adresse e-mail</label>
                    <?php if($erreur == "email"):?>
                        <p class="erreur">Adresse e-mail déjà utilisée</p>
                    <?php endif?>
                    <input type="text" name="email" id="mailVendeur" value="<?php echo "mail"?>" required disabled/>
                    
                    <span>Le mail doit être de la forme "abc@def.gh"</span>
                    <span>Les deux adresses e-mail doivent être identiques</span>
                    </div>
                    <div class="labelInput">
                    <label for="numTelVendeur">Numéro de téléphone</label>
                    <input type="text" name="numTel" id="numTelVendeur" pattern="\d{10}" value="<?php echo $compte["numtel"]?>"disabled/>
                    <span>Le numéro doit être dans le format suivant : 0102030405</span>
                    </div>

                </div>
            </details>
            
            <hr>
            <details>
                <summary><h3>Informations entreprise V</h3></summary>

                    <div id="entreprise" class="container-fluid">
                        
                        <div class="labelInput">
                            <label for="siren">Numéro de SIREN</label>
                            <input type="text" name="siren" id="siren" value="<?php echo $compte["siren"]?>"disabled/>
                        </div>
                        <div class="labelInput">
                            <label for="raisonSoc">Raison sociale</label>
                            <?php if($erreur == "raisonSoc"):?>
                                <p class="erreur">Raison sociale déjà utilisée</p>
                            <?php endif?>
                            <input type="text" name="raisonSoc" id="raisonSoc" value="<?php echo $compte["raisonsociale"]?>" disabled/>
                        </div>

    
                        
                            <div id="numNomRue">
                                <div class="labelInput">
                                    <label for="numRueVendeur">Numéro</label>
                                    <input type="text" name="num" id="numRueVendeur" value="<?php echo $adresse["num"]?>"disabled/>
                                </div>
                                <div class="labelInput">
                                    <label for="nomRueVendeur">Nom de la rue, voie</label>
                                    <input type="text" name="nomRue" id="nomRueVendeur" value="<?php echo $adresse["nomrue"]?>"disabled/>
                                </div>
                            </div>
                            <div id="cpVille">
                                <div class="labelInput">
                                    <label for="codePostalVendeur">Code postal</label>
                                    <input type="text" name="codePostal" id="codePostalVendeur" pattern="^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$" value="<?php echo $adresse["codepostal"]?>" disabled/>
                                </div>
                                <div class="labelInput">
                                    <label for="villeVendeur">Ville</label>
                                    <input type="text" name="nomVille" id="villeVendeur" value="<?php echo $adresse["nomville"]?>" disabled/>
                                </div>
                            </div>
                                <div class="labelInput">
                                    <label for="compAdrVendeur">Complément</label>
                                    <input type="text" name="complementAdresse" id="compAdrVendeur" value="<?php if($adresse["complementadresse"]){echo $adresse["complementadresse"];}else{echo "";}?>" disabled/>
                                </div>
                            
                            
                        </div>

                </div>
            </details>
            

            <!--<label for="mdpVendeur">Mot de passe</label>
            <input type="password" name="mdp" id="mdpVendeur" pattern="[A-Za-z0-9?,.;:§!$£*µù%]{2,20}" required disabled/>
            <span>Le mot de passe doit faire entre 2 et 20 caractères</span>
            <span>Les deux mots de passe doivent être identiques</span> !-->    
            <button class="bouton" id="valider" hidden>Valider</button>

        </form>   

        <div class="boutonsCompte">

            <button class="bouton" id="modifInfos" popovertarget="mdpValider" onclick="modifierInfos()">Modifier informations</button>
            <div popover="auto" id="mdpValider">
                <form action="verifMdpModif.php" method="post">
      
                    <label for="mdpPourValider">Entrez votre mot de passe</label>
                    <input type="password" name="mdpPourValider" id="mdpPourValider" required/>
      
                    <input type="submit" class="bouton" value="Valider"/> 
                </form>
            </div>
            <button popovertarget="overlaymdp" class="bouton" id="modifmdp">Modifier mot de passe</button>
            
            <div popover="auto" id="overlaymdp">
                <form action="verifMdpModif.php?modifMdp=1" method="post">
                    <h2>Modifier le mot de passe</h2>
                    <label for="mdpActuel">Mot de passe actuel</label>
                    <input type="password" name="mdpPourValider" id="mdpActuel"/>
                    <label for="mdpModifVendeur">Mot de passe</label>
                    <input type="password" name="mdpModifVendeur" id="mdpModifVendeur" pattern="[A-Za-z0-9?,.;:§!$£*µù%]{2,20}" required/>
                    <span>Le mot de passe doit faire entre 2 et 20 caractères</span>
                    <label for="confMdpModifVendeur">Confirmer le mot de passe</label>
                    <input type="password" name="confMdpModifVendeur" id="confMdpModifVendeur" required/>
                    <span>Les deux mots de passe doivent être identiques</span>
                    <input type="submit" id="validerModifMdp" class="bouton" value="Valider"/>
                </form>
            </div>
            <button class="bouton" id="annuler" onclick="annuler()" hidden>Annuler</button>
            
        </div>
        </div>
        <button class="btnJaune" id="deconnexion" onclick="deconnecter()">Se déconnecter</button>



    </main>
    <?php include('../includes/backoffice/footer.php');?>

    <script>

                

        <?php
        if($_SESSION["mdpValide"] == 1):?>
            //Rendre modifiables les champs SAUF dateNaissance
            document.querySelectorAll("h2")[1].removeAttribute("hidden");
            document.querySelectorAll("h2")[0].setAttribute("hidden", null);
            document.getElementById("valider").removeAttribute("hidden");
            document.getElementById("annuler").classList.add("annuler");
            document.getElementById("annuler").removeAttribute("hidden");


            document.getElementById("modifInfos").setAttribute("hidden", null);

            document.getElementById("modifmdp").setAttribute("hidden", null);
            

            let taille = console.log(document.querySelectorAll("input:disabled").length);
            while(document.querySelector("input:disabled")){
      
                document.querySelector("input:disabled").classList.add("modifiable");
                document.querySelector("input:disabled").removeAttribute("disabled");
                
            }




            //Vérifications de formats
            let mail = document.getElementById("mailVendeur");
            let formatMail = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,4}$/;        
            mail.addEventListener("focusout", verifFormat);   

            function verifFormat(evt){
                if(evt.type == "focusout"){
                    if(formatMail.test(mail.value) == false && evt.target == mail){
                        mail.classList.add("invalid");
                    }
                    else{
                        mail.classList.remove("invalid");
                    }

                }
            }
            
        <?php endif?>
        
        //Vérification mot de passe = confirmer mot de passe
        let mdp = document.getElementById("mdpModifVendeur");
        let confMdp = document.getElementById("confMdpModifVendeur");
        let btnValiderMdp = document.getElementById("validerModifMdp");
        btnValiderMdp.addEventListener("click", verifMdp);
        confMdp.addEventListener("focusout", verifMdp);


        function annuler(){
           <?php 
           $_SESSION["mdpValide"] = 0?>

           window.location.reload();  

        }


        function verifMdp(evt){
            if(mdp.value != confMdp.value){
                confMdp.classList.add("invalid");
                evt.preventDefault();
            }
            else{
                confMdp.classList.remove("invalid");
                

            }

        }
        
        function deconnecter(){

            window.location.href = "modifCompteVendeur.php?traitement=deconnecter";
        }


    </script>
    <script src="../js/overlayCompteVendeur.js"></script>
</body>
</html>