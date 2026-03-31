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
        <section>
            <article>

                <h1 id="CGU">CGV</h1>
                <p>
                Dans le cadre d’une vente de biens et/ou de fourniture de prestations de services conclue à distance avec un client particulier (consommateur), les informations suivantes doivent être données avant la conclusion du contrat de vente ou de prestation de services :
                <ul>
                <li>Caractéristiques essentielles du bien ou du service vendu</li>
                <li>Prix du bien ou du service</li>
                <li>Date ou délai de livraison du bien ou du service vendu</li>
                <li>Informations relatives au professionnel</li>
                <li>Numéro d’identification de l’entreprise : numéro SIREN de l’entreprise</li>
                <li>Forme juridique de l’entreprise</li>
                <li>Coordonnées postales, téléphoniques et électroniques</li>
                <li>Informations liées aux garanties légales (notamment la garantie légale de conformité et la garantie légale des vices cachés ainsi que d’éventuelles garanties commerciales)</li>
                <li>Possibilité de recourir à un médiateur de la consommation</li>
                <li>Existence d’un droit de rétractation de 14 jours et ses modalités d’exercice et formulaire type de rétractation</li>
                <li>Le fait que le consommateur supporte les frais de renvoi du bien en cas de rétractation</li>
                <li>Modalités de résiliation du contrat</li>
                <li>Modalités de livraison d’un bien et/ou d’exécution d’une prestation de services</li>
                <li>Modes de règlement des litiges (notamment la loi et le tribunal compétent)</li>
                <li>Coût d’un appel téléphonique à distance, existence de codes de bonne conduite</li>
                <li>Assurances et garanties financières</li>
                </ul>
                </p>
                <div class="row">
                <h3>PROCÉDURE DU DOUBLE CLIC</h3><div class="separateur"></div>
                </div>
                <p>
                Selon la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique : Dans le cadre d'un contrat de commerce par voie électronique, toute acceptation d'une offre devra désormais prendre la forme d'un " double clic ", c'est-à-dire qu'après avoir passé sa commande, l'utilisateur devra pouvoir la vérifier et confirmer son acceptation ; conforter la liberté de la communication publique en ligne en France.
                </p>
                <div class="row">
                <h3>ACCUSÉ DE RÉCEPTION DE LA COMMANDE</h3><div class="separateur"></div>
                </div>
                Article 1127-2 - Code civil<br/>
                <strong>L'auteur de l'offre doit accuser réception sans délai injustifié, par voie électronique, de la commande qui lui a été adressée. La commande, la confirmation de l'acceptation de l'offre et l'accusé de réception sont considérés comme reçus lorsque les parties auxquelles ils sont adressés peuvent y avoir accès.</strong><br/>

                Si le professionnel n’exécute pas le contrat, il doit indiquer un délai de livraison, sinon il sera contraint de livrer ou d’exécuter le service sous un délai de 30 jours.<br/>
                Si la livraison n’est pas respectée, vous pouvez résoudre le contrat (c’est-à-dire annuler les obligations du contrat) en suivant les étapes d’une procédure.<br/>
                Le professionnel doit vous rembourser la totalité des sommes versées, au plus tard dans les quatorze jours suivants la date de dénonciation du contrat.<br/>
                Lorsque votre achat n’est pas conforme, vous devez le refuser, vous n’avez pas à payer les frais de retour.<br/>
                Création DÉCRET n°2015-1342 du 23 octobre 2015.<br/>
                L'accusé de réception prévu par l'article L. 112-3 (Code des relations entre le public et l’administration) comporte les mentions suivantes :

                <ul>
                <li>1° La date de réception de la demande et la date à laquelle, à défaut d'une décision expresse, celle-ci sera réputée acceptée ou rejetée ;</li>
                <li>2° La désignation, l'adresse postale et, le cas échéant, électronique, ainsi que le numéro de téléphone du service chargé du dossier ;</li>
                <li>3° Le cas échéant, les informations mentionnées à l'article L. 114-5, dans les conditions prévues par cet article.</li>
                </ul>
                Il indique si la demande est susceptible de donner lieu à une décision implicite de rejet ou à une décision implicite d'acceptation. Dans le premier cas, l'accusé de réception mentionne les délais et les voies de recours à l'encontre de la décision. Dans le second cas, il mentionne la possibilité offerte au demandeur de se voir délivrer l'attestation prévue à l'article L. 232-3.

                L’accusé de réception doit :
                <ul>
                <li>avoir été envoyé à chaque fois qu’un client reçoit une commande</li>
                <li>avoir été fourni sous 30 jours sauf si 	il est marqué sur le site un autre délai</li>
                <li>être fourni par voie électronique</li>
                <li>avoir la désignation, l'adresse postale et le numéro de téléphone du service chargé du dossier</li>
                <li>pouvoir être ou ne pas être ouvert par le client</li>
                </ul>
            </article>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

        <script src="./js/script.js"></script>
</body>


</html>