<?php include('connDb.php'); ?>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style/accueil.css">

    <title>Alizon</title>
    <style>
        main {
            font-size: 18px;
        }

        .row {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        h3 {
            display: block;
            width: 30%;
            margin-top: 10px;
            margin-left: 10px;
        }

        @media screen and (max-width : 428px) {
            h3 {
                width: 80%;
            }
        }

        p {
            max-width: 1000px;

        }
        .pad{
            padding:20px;

        }
    </style>
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
    if (isset($_SESSION["codeCompte"]) && $estClient) {
        $idUser =  $_SESSION["codeCompte"];
        include 'includes/headerCon.php';
    } else {
        include 'includes/header.php';
    }
    ?>
    <main>
        <?php include 'includes/menuCompte.php'?>
        <section>
            <article>
                <h1 id="ML">Mentions légales</h1>

                <div class="row">
                    <h3>Directeur de la publication</h3>
                    <div class="separateur"></div>
                </div>
                <div class="row">
                    <ul> <strong>Nom </strong>: Mulish</ul>
                    <ul><strong> Prenom </strong>: Isigor </ul>
                </div>
                <ul><strong>Adresse </strong>: 22 rue Edouard Branly </ul>

                <div class="row">
                    <h3>Informations sur l'entreprise</h3>
                    <div class="separateur"></div>
                </div>
                <div class="row">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis tenetur maxime dolorem suscipit ratione quibusdam error. Omnis, quos consequuntur odit eius repellat quis ut ipsum odio sint magnam, fugiat impedit.</p>
                </div>
                <div class="row">
                    <h3 id="DCP">Données à caractère personnel</h3>
                    <div class="separateur"></div>
                </div>
                <div class="pad">

                    <h4>Responsable de traitement :</h4>
                    <p> Alizon reste seul responsable de traitement des données,
                        indépendamment des vendeurs.</p>
                    <h4>Destinataires :</h4>
                    <p> Les vendeurs auront accès aux informations de livraison, une plateforme
                        externe aura accès aux informations bancaires et le gestionnaire d’Alizon aura accès au
                        reste des données. En cas d’enquête, un organisme public représentant l’État peut accéder
                        à vos données personnelles.</p>
                    <h4>Base légale :</h4>
                    <p> En effectuant un achat, vous consentez aux mentions légales et aux CGV.
                        Licéité : Base venant du RGPD 2018
                        <a href="https://www.cnil.fr/fr/reglement-europeen-protection-donnees">Lien vers la CNIL</a>
                    </p>


                    <h4>Finalité du traitement :</h4>
                    <p> Le traitement des données est, d’une part, à des fins de sécurité
                        (mot de passe, adresse e-mail), d’autre part pour le bon déroulement d’une commande en
                        ligne (adresse de livraison, de facturation, informations bancaires) et à usage statistique.</p>

                    <h4>Conservation : </h4>
                    <p>14 jours en base active pour renvoi du produit et 5 ans en archive pour délai
                        de prescription sur un achat en ligne</p>
                    <h5>Droits :</h5>
                    <ul>
                        <li>Droit d’accès</li>
                        <li>Droit de rectification</li>
                        <li>Droit d’opposition</li>
                        <li>Droit à l’effacement</li>
                        <li>Droit à faire une réclamation</li>
                    </ul>

                    <h4>Données traitées : </h4>
                    <p>
                    <h5>Côté Client : </h5>nom, prénom, adresse postale, adresse e-mail, informations bancaires, numéro de téléphone, date de naissance.</p>
                    <p>
                    <h5>Côté vendeur : </h5>nom, prénom, adresse e-mail, numéro de téléphone responsable,
                    numéro SIRET, raison sociale, adresse postale du siège social.</p>
                </div>
            </article>
            <article>
                <div class="row">
                    <h3>Propriété intellectuelle</h3>
                    <div class="separateur"></div>
                </div>
                <div class="pad">
                    <p>Tous les textes et photos sont de la propriété du vendeur</p>
                    <p>Les marques, logos, signes ainsi que tous les contenus du site (textes, images, son…) font
                        l'objet d'une protection par le Code de la propriété intellectuelle et plus particulièrement par
                        le droit d'auteur.</p>
                    <p>La marque Alizon est une marque déposée par la COBREC. Toute représentation et/ou
                        reproduction et/ou exploitation partielle ou totale de cette marque, de quelque nature que ce
                        soit, est totalement prohibée.</p>
                    <p>L'utilisateur doit solliciter l'autorisation préalable du site pour toute reproduction, publication,
                        copie des différents contenus. Il s'engage à une utilisation des contenus du site dans un
                        cadre strictement privé, toute utilisation à des fins commerciales et publicitaires est
                        strictement interdite.</p>
                    <p>Toute représentation totale ou partielle de ce site par quelque procédé que ce soit, sans
                        l’autorisation expresse de l’exploitant du site Internet constituerait une contrefaçon
                        sanctionnée par l’article L 335-2 et suivants du Code de la propriété intellectuelle.</p>
                    <p>Il est rappelé conformément à l’article L122-5 du Code de propriété intellectuelle que
                        l’utilisateur qui reproduit, copie ou publie le contenu protégé doit citer l’auteur et sa source.
                    <p>
            </article>
            <article>
                <div class="row">
                    <h3>Sécurité informatique</h3>
                    <div class="separateur"></div>
                </div>
                <div class="pad">
                    <p>Pour toute personne accédant ou essaie de se maintenir au système d’information automatique de la
                        plateforme frauduleusement et sans l’accord de Alizon. le fait d'entraver ou de fausser ce
                        dernier, le fait d’introduire des informations, d'extraire, de détenir, de reproduire, de
                        transmettre, de supprimer ou de modifier frauduleusement les données encourt des
                        poursuites judiciaires, conformément à l<a href="https://www.legifrance.gouv.fr/codes/id/LEGISCTA000006149839/">’article 323</a> du code pénal.
                    </p>
                </div>
            </article>
        </section>
        <section>
            <h1 id="CGU">CGU</h1>

            <div class="row">
                <h3>Article 1 - Accès au site</h3>
                
            </div>
            <div class="pad">
                <p>Le site offre un accès gratuit à ses services, notamment la vente en ligne. L’utilisateur doit
disposer de son propre matériel et connexion.</p>
                            <p>Pour accéder aux services réservés, l’inscription est obligatoire et doit être faite avec des
informations exactes. L’utilisateur s’identifie ensuite avec un identifiant et un mot de passe.
En cas de force majeure, de maintenance ou de panne, l’éditeur n’est pas responsable des
interruptions.</p>
            </div>
            <div class="row">
                <h3>Article 2 - Collecte des données personnelles</h3>
                
            </div>
            <div class="pad">
                <p>Le site respecte la loi Informatique et Libertés.</p>
                <p>L’utilisateur dispose des droits d’accès, de rectification, suppression et d’opposition
concernant ses données, qu’il peut exercer via son espace personnel.</p>

            </div>
            <div class="row">
                <h3>Article 3 - Responsabilité</h3>
                
            </div>
            <div class="pad">
                <p>Les informations du site sont fournies à titre indicatif.</p>
                <p>Le site ne garantit pas l’exactitude des données et ne peut être tenu responsable des
                    erreurs, omissions ou modifications réglementaires ultérieures.</p>
                <p>L’utilisateur doit garder son mot de passe secret ; toute fuite relève de sa responsabilité.</p>
                <p>L’éditeur n’est pas responsable des virus ou dommages liés à l’utilisation du site.</p>
                <p>En cas de force majeure, la responsabilité du site ne peut être engagée.</p>
            </div>
            <div class="row">
                <h3>Article 4 - Liens hypertextes</h3>
                
            </div>
            <div class="pad">
                <p>
                    Les liens présents sur le site peuvent conduire vers des pages extérieures dont l’éditeur
                    n’assume aucune responsabilité.
                </p>
            </div>
            <div class="row">
                <h3>Article 5 - Publication par l’utilisateur</h3>
                
            </div>
            <div class="pad">
                <p>
                    L’utilisateur peut publier des avis mais doit respecter les règles de bonne conduite et la loi.
                    Le site peut modérer ou refuser une publication.</p>
                <p>L’utilisateur conserve ses droits, mais cède au site un droit gratuit et non exclusif d’utilisation
                    de ses contenus publiés (adaptation, reproduction, diffusion…).
                </p>
            </div>
            <div class="row">
                <h3>Article 6 - Droit applicable</h3>
                
            </div>
            <div class="pad">
                Le droit français s’applique. En cas de litige non résolu à l’amiable, les tribunaux français
                sont compétents.
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

        <script src="./js/script.js"></script>
</body>


</html>