DROP SCHEMA IF EXISTS alizon CASCADE;
CREATE SCHEMA alizon;
SET SCHEMA 'alizon';
CREATE EXTENSION IF NOT EXISTS unaccent;

CREATE TABLE Photo(
    urlPhoto VARCHAR(100) PRIMARY KEY NOT NULL
);

CREATE TABLE Compte(
    codeCompte SERIAL PRIMARY KEY NOT NULL,
    dateCreation DATE,
    nom VARCHAR(20),
    prenom VARCHAR(20),
    email VARCHAR(50) NOT NULL,
    mdp VARCHAR(32) NOT NULL,
    numTel VARCHAR(20)
);
CREATE TABLE Adresse(
    idAdresse SERIAL PRIMARY KEY NOT NULL,
    num VARCHAR(20) NOT NULL,
	nomRue VARCHAR(50) NOT NULL,
    codePostal VARCHAR(20) NOT NULL,
    nomVille VARCHAR(20) NOT NULL, 
	numAppart VARCHAR(20),
    complementAdresse VARCHAR(20)
);

CREATE TABLE Client(
    pseudo VARCHAR(20) NOT NULL,
    cmtBlq BOOLEAN,
    cmtBlqMod BOOLEAN,
	dateNaissance DATE,
	UNIQUE(codeCompte)
) INHERITS (Compte);

CREATE TABLE AdrFactCli(
	codeCompte INTEGER NOT NULL REFERENCES Client(codeCompte),
	idAdresse INTEGER NOT NULL REFERENCES Adresse(idAdresse)
);

CREATE TABLE Gestionaire(UNIQUE(codeCompte)) INHERITS (Compte);

CREATE TABLE Vendeur(
    SIREN VARCHAR(20) UNIQUE,
	pseudo VARCHAR(20) UNIQUE NOT NULL,
    raisonSociale VARCHAR(20),
	idAdresseSiege INTEGER REFERENCES Adresse(idAdresse),
	UNIQUE(codeCompte)
) INHERITS (Compte);

CREATE TABLE AdrSiegeSocial(
	codeCompte INTEGER NOT NULL REFERENCES Vendeur(codeCompte),
	idAdresse INTEGER NOT NULL REFERENCES Adresse(idAdresse)
);

CREATE TABLE TVA(
    nomTVA VARCHAR(20) PRIMARY KEY NOT NULL CHECK (nomTVA IN ('normale', 'réduite', 'super-réduite')),
    tauxTVA FLOAT 
);
CREATE TABLE Tarification(
    nomTarif VARCHAR(20) PRIMARY KEY NOT NULL CHECK (nomTarif IN ('tarif1', 'tarif2', 'tarif3', 'tarif4', 'tarif5')),
    tauxTarif FLOAT 
);

CREATE TABLE Produit(
    codeProduit SERIAL PRIMARY KEY NOT NULL,
    libelleProd VARCHAR(200) NOT NULL,
    descriptionProd VARCHAR(500) NOT NULL,
    prixHT  NUMERIC NOT NULL,
    nomTVA VARCHAR(20) REFERENCES TVA(nomTVA),--LIEN AVEC TVA
    prixTTC  NUMERIC,
	noteMoy FLOAT DEFAULT 0,
    spe1 VARCHAR(200), --en mètre
    spe2 VARCHAR(200), --en mètre
    spe3 VARCHAR(200), --en mètre
    dateCreaProduit TEXT NOT NULL, 
    dateModifProduit TEXT NOT NULL,
    qteStock INTEGER NOT NULL DEFAULT 0,
    Origine VARCHAR(20) NOT NULL check (Origine IN ('Breizh','France','Étranger')),
    Disponible BOOLEAN DEFAULT TRUE,
	nomTarif VARCHAR(20) REFERENCES Tarification(nomTarif),
    seuilAlerte INTEGER NOT NULL,
    urlPhoto VARCHAR(40) REFERENCES Photo(urlPhoto),
    codeCompteVendeur INTEGER REFERENCES Vendeur(codeCompte)	
);



CREATE TABLE Categorie(
    libelleCat VARCHAR(20) PRIMARY KEY NOT NULL
);

CREATE TABLE SousCat(
    libCat VARCHAR(20) REFERENCES Categorie(libelleCat),
    libSousCat VARCHAR(20) REFERENCES Categorie(libelleCat),
    PRIMARY KEY(LibCat,LibSousCat)
);

CREATE TABLE Categoriser(
	codeProduit INTEGER NOT NULL REFERENCES Produit(codeProduit),
	libelleCat VARCHAR(20) NOT NULL REFERENCES Categorie(libelleCat),
	PRIMARY KEY(codeProduit, libelleCat)
);

CREATE TABLE Reduction(
	idReduction SERIAL PRIMARY KEY NOT NULL,
    dateDebut DATE,
    dateFin DATE,
    remise FLOAT
);
CREATE TABLE Promotion(
	idPromotion SERIAL PRIMARY KEY NOT NULL,
    dateDebut DATE,
    dateFin DATE
);

CREATE TABLE Facture(
    noFact SERIAL PRIMARY KEY NOT NULL,
    montantFact FLOAT,
    nomDest VARCHAR(20),
    prenomDest VARCHAR(20),
    idAdresseFact INTEGER REFERENCES Adresse(idAdresse)
);
CREATE TABLE Carte(
	idCarte SERIAL PRIMARY KEY NOT NULL,
    numCarte VARCHAR(20) NOT NULL,
    nomTit VARCHAR(20),
    prenomTit VARCHAR(20),
    CVC TEXT NOT NULL,
    dateExp TEXT NOT NULL
);

CREATE TABLE Panier(
    idPanier SERIAL PRIMARY KEY NOT NULL,
    codeCompte INTEGER REFERENCES Client(codeCompte),
    dateCreaP TEXT,
    dateModifP TEXT,
    prixTTCtotal NUMERIC DEFAULT 0,
    prixHTtotal NUMERIC DEFAULT 0
);


CREATE TABLE Commande(
    numCom SERIAL PRIMARY KEY NOT NULL,
	codeCompte INTEGER NOT NULL REFERENCES Client(codeCompte),
    dateCom DATE,
    prixTTCtotal NUMERIC DEFAULT 0, 
    prixHTtotal NUMERIC DEFAULT 0,
    idCarte INTEGER REFERENCES Carte(idCarte),
	bordereau INTEGER
);
CREATE TABLE Livraison(
    idLivraison SERIAL PRIMARY KEY NOT NULL,
	--numCom INTEGER REFERENCES Commande(numCom),
    dateCommande DATE,
    dateEncaissement DATE,
    datePreparation DATE,
    dateExpedition DATE,
    statutLiv VARCHAR(20)
);
CREATE TABLE AdrLiv(
	--idLivraison INTEGER NOT NULL REFERENCES Livraison(idLivraison),
	numCom INTEGER NOT NULL REFERENCES Commande(numCom),
	idAdresse INTEGER NOT NULL REFERENCES Adresse(idAdresse)
);
CREATE TABLE Avis(
    numAvis SERIAL PRIMARY KEY NOT NULL,
	codeProduit INTEGER REFERENCES Produit(codeProduit),
	codeCompteCli INTEGER REFERENCES Client(codeCompte),
    noteProd FLOAT,
    commentaire VARCHAR(512),
    datePublication TEXT
);

CREATE TABLE Reponse(
    numReponse SERIAL PRIMARY KEY NOT NULL,
	numAvis INTEGER REFERENCES Avis(numAvis),
	dateReponse DATE,
    commentaire VARCHAR(20)
);

CREATE TABLE Signalement(
    idSignalement SERIAL PRIMARY KEY NOT NULL,
    motif VARCHAR(20),
    dateSignalement DATE,
	numAvis INTEGER REFERENCES Avis(numAvis)
);

CREATE TABLE FaireSignalement(
	codeCompte INTEGER REFERENCES Compte(codeCompte),
	idSignalement INTEGER REFERENCES Signalement(idSignalement),
	PRIMARY KEY(codeCompte, idSignalement)
);

CREATE TABLE ProdUnitCommande(
    codeProduit INTEGER REFERENCES Produit(codeProduit),
    numCom INTEGER REFERENCES Commande(numCom),
    prixTTCtotal NUMERIC(20,2),
    prixHTtotal NUMERIC(20,2),
    qteProd NUMERIC(20,2),
    PRIMARY KEY(codeProduit,numCom)
);
CREATE TABLE ProdUnitPanier(
    codeProduit INTEGER REFERENCES Produit(codeProduit),
    idPanier INTEGER REFERENCES Panier(idPanier) ON DELETE CASCADE,    
    qteProd NUMERIC(20,2),
	prixTTCtotal NUMERIC(20,2),
	prixHTtotal NUMERIC(20,2),

    PRIMARY KEY(codeProduit,idPanier)
);


CREATE TABLE Vote(
    numAvis INTEGER REFERENCES Avis(numAvis),
    codeCompte INTEGER REFERENCES Client(codeCompte),
    typeVote INTEGER CHECK (typeVote IN (-1, 0, 1)),
    PRIMARY KEY(numAvis, codeCompte)
);

CREATE TABLE FairePromotion(
	codeProduit INTEGER REFERENCES Produit(codeProduit),
	idPromotion INTEGER REFERENCES Promotion(idPromotion),
	urlPhoto VARCHAR REFERENCES Photo(urlPhoto),
	PRIMARY KEY(codeProduit, idPromotion)
);

CREATE TABLE FaireReduction(
	codeProduit INTEGER REFERENCES Produit(codeProduit),
	idReduction INTEGER REFERENCES Reduction(idReduction),
	PRIMARY KEY(codeProduit, idReduction)
);

CREATE TABLE Publier(
	codeCompte INTEGER REFERENCES Client(codeCompte),
	numAvis INTEGER REFERENCES Avis(numAvis),
	PRIMARY KEY(codeCompte, numAvis)
);

CREATE TABLE JustifierAvis(
	urlPhoto VARCHAR REFERENCES Photo(urlPhoto),
	numAvis INTEGER REFERENCES Avis(numAvis),
	PRIMARY KEY(urlPhoto, numAvis)
);

CREATE TABLE Profil(
	urlPhoto VARCHAR REFERENCES Photo(urlPhoto),
	codeClient INTEGER REFERENCES Client(codeCompte),
	PRIMARY KEY(urlPhoto, codeClient)
);

--FONCTIONS--

--PrixTTC = prixHT * tauxTVA--
CREATE OR REPLACE FUNCTION calcul_prixTTC()
RETURNS TRIGGER AS 
$$
	BEGIN
		SELECT NEW.prixHT * (1 + (tva.tauxTVA / 100)) INTO NEW.prixTTC
		FROM alizon.TVA tva WHERE tva.nomTVA = NEW.nomTVA;
		RETURN NEW;
	END;
$$ 
LANGUAGE plpgsql;

CREATE TRIGGER trg_calcul_prixTTC
BEFORE INSERT OR UPDATE ON Produit
FOR EACH ROW
EXECUTE FUNCTION calcul_prixTTC();
--ProdUnitCommande.PrixTTC = produit.prixTTC--
--ProdUnitCommande.PrixHT = produit.prixHT--

CREATE FUNCTION duplique_prixHT()
RETURNS TRIGGER AS $$
declare
    idRemiseExists INTEGER;
    tauxRemise float;
BEGIN
    idRemiseExists = (SELECT idReduction from alizon.FaireReduction WHERE codeProduit = NEW.codeProduit);
    IF idRemiseExists IS NOT NULL THEN
        tauxRemise = (SELECT remise FROM Reduction WHERE idReduction = idRemiseExists);
        SELECT (Produit.prixHT * (1 - tauxRemise / 100)) * NEW.qteProd INTO NEW.prixHTtotal 
        FROM alizon.Produit WHERE Produit.codeProduit = NEW.codeProduit;
    ELSE
        SELECT Produit.prixHT * NEW.qteProd INTO NEW.prixHTtotal
        FROM alizon.Produit WHERE Produit.codeProduit = NEW.codeProduit;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE FUNCTION duplique_prixTTC()
RETURNS TRIGGER AS $$ 
declare
	idRemiseExists INTEGER;
	tauxRemise float;
	prodTauxTVA float;
	prodNomTVA VARCHAR;
BEGIN
	idRemiseExists = (SELECT idReduction from alizon.FaireReduction WHERE codeProduit = NEW.codeProduit);
	prodNomTVA = (SELECT nomTVA from produit where codeProduit = NEW.codeProduit);
	IF idRemiseExists IS NOT NULL THEN
		prodTauxTVA = (SELECT tauxTVA from TVA where nomTVA = prodNomTVA);
		tauxRemise = (SELECT remise FROM Reduction WHERE idReduction = idRemiseExists);

		SELECT ((Produit.prixHT * (1 - tauxRemise / 100)) * (1 + prodTauxTVA / 100)) * NEW.qteProd INTO NEW.prixTTCtotal 
		FROM alizon.Produit WHERE Produit.codeProduit = NEW.codeProduit;
	else
		SELECT Produit.prixTTC * NEW.qteProd INTO NEW.prixTTCtotal
		FROM alizon.Produit WHERE Produit.codeProduit = NEW.codeProduit;
	end if;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER trg_dupli_prixTTC
AFTER INSERT OR UPDATE ON ProdUnitCommande
FOR EACH ROW
EXECUTE FUNCTION duplique_prixTTC();
--Triggers prixHT et prixTTC--

CREATE TRIGGER trg_dupli_prixHT_Panier
BEFORE INSERT OR UPDATE ON ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION duplique_prixHT();

CREATE TRIGGER trg_dupli_prixTTC_Panier
BEFORE INSERT OR UPDATE ON ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION duplique_prixTTC();

--PrixTTCPanier = Somme(PrixTTC * qtProd)--



CREATE FUNCTION PanierFinalTTC()
RETURNS TRIGGER AS $$
BEGIN 
	UPDATE alizon.Panier SET prixTTCtotal = (SELECT SUM(PUP.prixTTCtotal) FROM ProdUnitPanier PUP WHERE PUP.idPanier = NEW.idPanier ) WHERE Panier.idPanier = NEW.idPanier;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_panier_finTTC
AFTER INSERT OR UPDATE OR DELETE ON alizon.ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION PanierFinalTTC();

CREATE FUNCTION PanierFinalDeleteTTC()
RETURNS TRIGGER AS $$
BEGIN 
	UPDATE alizon.Panier SET prixTTCtotal = (SELECT SUM(PUP.prixTTCtotal) FROM ProdUnitPanier PUP WHERE PUP.idPanier = OLD.idPanier ) WHERE Panier.idPanier = OLD.idPanier;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_panier_deleteTTC
AFTER DELETE ON alizon.ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION PanierFinalDeleteTTC();

CREATE FUNCTION PanierFinalHT()
 
RETURNS TRIGGER AS $$
BEGIN 
	UPDATE alizon.Panier SET prixHTtotal = (SELECT SUM(PUP.prixHTtotal) FROM ProdUnitPanier PUP WHERE PUP.idPanier = NEW.idPanier) WHERE Panier.idPanier = NEW.idPanier;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_panier_finHT
AFTER INSERT OR UPDATE OR DELETE ON ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION PanierFinalHT();

CREATE FUNCTION PanierFinalDeleteHT()
 
RETURNS TRIGGER AS $$
BEGIN 
	UPDATE alizon.Panier SET prixHTtotal = (SELECT SUM(PUP.prixHTtotal) FROM ProdUnitPanier PUP WHERE PUP.idPanier = OLD.idPanier) WHERE Panier.idPanier = OLD.idPanier;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_panier_deleteHT
AFTER DELETE ON alizon.ProdUnitPanier
FOR EACH ROW
EXECUTE FUNCTION PanierFinalDeleteHT();
--PrixHTPanier = Somme(PrixHT * qtProd)--




--prixTotalTTC dans commande = somme(prixUnitTTC * qteProd)--

CREATE FUNCTION calcul_prixTotalTTCCom()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE alizon.Commande SET prixTTCtotal = (SELECT SUM(PUC.prixTTCtotal) FROM alizon.ProdUnitCommande PUC WHERE PUC.numCom = NEW.numCom) WHERE Commande.numCom = NEW.numCom;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_calcul_prixTotalTTCCom
AFTER INSERT OR UPDATE ON alizon.ProdUnitCommande
FOR EACH ROW
EXECUTE FUNCTION calcul_prixTotalTTCCom();

--prixTotalTTC dans commande = somme(prixUnitHT * qteProd)--
CREATE FUNCTION calcul_prixTotalHTCom()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE alizon.Commande SET prixHTtotal = (SELECT SUM(PUC.prixHTtotal) FROM alizon.ProdUnitCommande PUC WHERE PUC.numCom = NEW.numCom) WHERE Commande.numCom = NEW.numCom;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_calcul_prixTotalHTCom
AFTER INSERT OR UPDATE ON alizon.ProdUnitCommande
FOR EACH ROW
EXECUTE FUNCTION calcul_prixTotalHTCom();
-- Date création/Modification Panier

CREATE OR REPLACE FUNCTION alizon.dateModificationPanier()
RETURNS TRIGGER AS $$
DECLARE
    v_ts timestamptz;
BEGIN
    NEW.dateModifP := to_char(now(), 'DD/MM/YYYY HH24:MI:SS');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER trg_dateModif_Panier
BEFORE INSERT OR UPDATE ON alizon.Panier
FOR EACH ROW
EXECUTE FUNCTION alizon.dateModificationPanier();

CREATE OR REPLACE FUNCTION alizon.dateCreationPanier()
RETURNS TRIGGER AS $$
DECLARE
    v_ts timestamptz;
BEGIN
    NEW.dateCreaP := to_char(now(), 'DD/MM/YYYY HH24:MI:SS');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Date création Avis

CREATE TRIGGER trg_dateCrea_Panier
BEFORE INSERT ON alizon.Panier
FOR EACH ROW
EXECUTE FUNCTION alizon.dateCreationPanier();

CREATE OR REPLACE FUNCTION alizon.dateCreationAvis()
RETURNS TRIGGER AS $$
DECLARE
    v_ts timestamptz;
BEGIN
    NEW.datepublication := to_char(now(), 'DD/MM/YYYY HH24:MI:SS');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION calcul_tarifs()
RETURNS TRIGGER AS
$$
BEGIN
    SELECT NEW.prixHT + tarif.tauxTarif 
    INTO new.prixHT
    FROM alizon.Tarification tarif where tarif.nomTarif = NEW.nomTarif;
    
    SELECT NEW.prixHT * (1 + (tva.tauxTVA / 100)) 
    INTO NEW.prixTTC
    FROM alizon.TVA tva WHERE tva.nomTVA = NEW.nomTVA;
    RETURN NEW;
    END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER trg_calcul_tarifs
AFTER INSERT OR UPDATE ON Produit
FOR EACH ROW
EXECUTE FUNCTION calcul_tarifs();

CREATE TRIGGER trg_dateCrea_Avis
BEFORE INSERT ON alizon.Avis
FOR EACH ROW
EXECUTE FUNCTION alizon.dateCreationAvis();

-- Date de création et modif d'un avis
CREATE OR REPLACE FUNCTION alizon.dateCreationProduit()
RETURNS TRIGGER AS $$
DECLARE
    v_ts timestamptz;
BEGIN
    NEW.dateCreaProduit := to_char(now(), 'DD/MM/YYYY HH24:MI:SS');
    RETURN NEW;
END;
$$LANGUAGE plpgsql;

CREATE TRIGGER trg_dateCrea_Produit
BEFORE INSERT ON alizon.Produit
FOR EACH ROW
EXECUTE FUNCTION alizon.dateCreationProduit();

-- Date de création d'un avis
CREATE OR REPLACE FUNCTION alizon.dateModificationProduit()
RETURNS TRIGGER AS $$
DECLARE
    v_ts timestamptz;
BEGIN
    NEW.dateModifProduit := to_char(now(), 'DD/MM/YYYY HH24:MI:SS');
    RETURN NEW;
END;
$$LANGUAGE plpgsql;

CREATE TRIGGER trg_dateModif_Produit
BEFORE INSERT OR UPDATE  ON alizon.Produit
FOR EACH ROW
EXECUTE FUNCTION alizon.dateModificationProduit();

-- Moyenne d'un produit 
CREATE OR REPLACE FUNCTION alizon.MoyenneProduit()
RETURNS TRIGGER AS $$
BEGIN
	UPDATE alizon.Produit p SET noteMoy = 
		(SELECT AVG(noteProd) from Avis a where a.codeproduit = NEW.codeproduit) 
			where p.codeProduit = NEW.codeProduit;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_moyenne_Produit
AFTER INSERT OR UPDATE ON alizon.Avis
FOR EACH ROW
EXECUTE FUNCTION alizon.MoyenneProduit();
