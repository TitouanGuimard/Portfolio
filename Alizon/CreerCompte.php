<?php 
    if(isset($_GET["erreur"])){
        $erreur = $_GET["erreur"];
    }
    else{
        $erreur = NULL;
    }
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer compte</title>
    <link href="./css/style/creerCompteFront.css" rel="stylesheet" type="text/css">
    <link href="./css/components/fonts.css" rel="stylesheet" type="text/css">
    <link href="./bootstrap-5.3.8-dist/css/bootstrap.css" media="all" type="text/css" rel="stylesheet">
</head>
<body>   
    <main>
    <a href="index.php"><img src="../../img/logo_alizon_front.svg" alt="logo-alizon" title="logo-alizon"/></a>
        <form action="enreg.php" method="post" enctype="multipart/form-data">
            <h2>Création de compte</h2>
            <?php if($erreur == "mail"){
                echo "<p style=\"color:red\">Adresse e-mail déjà utilisée</p>";   
            }?>
            <?php
            if($erreur == "pseudo"){
                echo "<p style=\"color:red\">Pseudonyme déjà utilisé</p>";
            }
            ?>
            <label for="identifiant">Identifiant *</label>
            <input type="text" name="pseudo" placeholder="Identifiant..." id="identifiant" pattern="[A-Za-z._0-9]{3,20}" minlength="3" required/> 
            <span>L'identifiant doit faire entre 2 et 20 caractères (lettres, ".", "_" acceptés)</span>
            <div id="nomPrenomCli">
                <div class="labelInput">
                    <label for="nomCli">Nom *</label>
                    <input type="text" name="nom" placeholder="Nom..." id="nomCli" pattern="[A-Za-z-]{3,40}" minlength="3" required/>
                </div>
                <div class="labelInput">
                    <label for="prenomCli">Prénom *</label>
                    <input type="text" name="prenom" placeholder="Prénom..." pattern="[A-Za-z]{3,20}" id="prenomCli" minlength="3" required/>
                </div>
            </div>
            <label for="photoCli">Photo de profil</label>
            <input type="file" name="photo" id="photoCli" accept="image/*"/>
            <label for="mailCli">Adresse e-mail *</label>

            <input type="text" name="mail" placeholder="E-mail..." id="mailCli" required/>
            <span>Le mail doit être de la forme "abc@def.gh"</span>
            <label for="confMailCli">Confirmer adresse mail *</label>
            <input type="text" name="confMail" id="confMailCli"/>
            <span>Les deux adresses e-mail doivent être identiques</span>
            <label for="numTelCli">Numéro de téléphone</label>
            <input type="text" name="numTel" id="numTelCli" pattern="\d{10}"/>
            <span>Le numéro doit être de la forme 0102030405</span>
            <label for="dateNaiss">Date de naissance *</label>
            <input type="date" name="dateNaiss" class="boutonSec" id="dateNaiss" onChange="verifDate(event)" required/>
            <span>La date de naissance doit être antérieure à la date du jour</span>
            <h3>Adresse</h3> <!-- essayer de faire display grid pour adresse !-->
            <div class="container-fluid p-0">
                <div class="row ">
                    <div class="col-3 labelInput">
                        <label for="numRueCli">Numéro</label>
                        <input type="text" name="numRue" placeholder="1, 2A, 3Bis etc." id="numRueCli"/>
                    </div>
                    <div class="col-9 labelInput">
                        <label for="nomRueCli">Nom de la rue, voie</label>
                        <input type="text" name="nomRue" placeholder="Ex : Rue des lilas" id="nomRueCli"/>
                    </div>
                </div>
                <div class="row ">
                    <div class="col-4 labelInput">
                        <label for="codePostalCli">Code postal</label>
                        <input type="text" name="codePostal" id="codePostalCli" pattern="^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$"/>
                        <span>Le code postal doit être de la forme 01234</span>
                    </div>
                    <div class="col-8 labelInput">
                        <label for="villeCli">Ville</label>
                        <input type="text" name="ville" id="villeCli"/>
                    </div>
                </div>
            </div>
            <label for="numAptCli">Numéro d'appartement</label>
            <input type="text" name="numApt" id="numAptCli"/>
            <label for="compAdrCli">Complément</label>
            <input type="text" name="comp" placeholder="Numéro de bâtiment, d'escalier etc." id="compAdrCli"/>
            <label for="mdpCli">Mot de passe *</label>
            <input type="password" name="mdp" placeholder="Mot de passe..." id="mdpCli" pattern="[A-Za-z0-9?,.;:§!$£*µù%]{2,20}" required />
            <span>Le mot de passe doit faire entre 2 et 20 caractères</span>
            <label for="confMdpCli">Confirmer mot de passe *</label>
            <input type="password" name="confMdp" id="confMdpCli" required/>
            <span>Les deux mots de passe doivent être identiques</span>
                </br>
            <p>En continuant, vous acceptez <a href="CGU.php#CGU">les conditions d'utilisation</a> et de vente d'Alizon.</p>
            <input class="bouton" type="submit" id="creerCompte" value="Créer un compte"/>
        </form>   
        <aside>
            <figure>
                <img src="../../img/line_1.svg"/>
                <p>Déjà un compte ?</p>
                <img src="../../img/line_1.svg"/>
            </figure>
            <div class="bouton-connexion">
                <a href="ConnexionClient.php" class="bouton">Se connecter</a>
                <a href="Catalogue.php" class="btnJaune">Retour</a>
            </div>
        </aside>

    </main>
    <?php include('./includes/footer.php');?>

    <script>
        let mail = document.getElementById("mailCli");
        let confMail = document.getElementById("confMailCli");
        let mdp = document.getElementById("mdpCli");
        let confMdp = document.getElementById("confMdpCli");

        let formatMail = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,4}$/;
        

        
        mail.addEventListener("focusout", verifFormat);
        confMail.addEventListener("focusout", verifFormat);
        confMdp.addEventListener("focusout", verifFormat);
        creerCompte.addEventListener("click", verifFormatClic);
        function verifFormat(evt){
            if(evt.type == "focusout"){
                if(formatMail.test(mail.value) == false && evt.target == mail){
                    mail.classList.add("invalid");
                    console.log("cc");
                }
                else{
                    mail.classList.remove("invalid");
                }
                if(mail.value != confMail.value && evt.target == confMail){

                    confMail.classList.add("invalid");
                    evt.preventDefault();
                }
                else{
                    confMail.classList.remove("invalid");
                }
                if(mdp.value != confMdp.value && evt.target == confMdp){

                    confMdp.classList.add("invalid");
                }
                else{
                    confMdp.classList.remove("invalid");
                }


            }
        }
        function verifFormatClic(evt){
            if(confMail.value != mail.value){
                evt.preventDefault();
                confMail.classList.add("invalid");
            }
            if(confMdp.value != mdp.value){
                evt.preventDefault();
                confMdp.classList.add("invalid");
            }
        }
        function verifDate(evt){
            let elemDate = document.getElementById("dateNaiss");
            let date = document.getElementById("dateNaiss").value;
            
            date = Date.parse(date);
            let mtn = Date.now();
            // Date limite : aujourd'hui - 100 ans
            const dateLimite = new Date();
            dateLimite.setFullYear(mtn.getFullYear() - 120);

            if(date > mtn){
                elemDate.classList.add("invalid");
            }
            else{
                elemDate.classList.remove("invalid");
            }
        }


    </script>
</body>
</html>