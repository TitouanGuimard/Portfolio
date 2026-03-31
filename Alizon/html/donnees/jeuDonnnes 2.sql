set schema 'alizon';
INSERT INTO TVA(nomTVA,tauxTVA) 
VALUES
('Réduit',5.5),
('Intermédiaire',10),
('Normal',20);

INSERT INTO Produit (libelleProd, descriptionProd, prixHT, nomTVA, hauteur, longueur, largeur, qteStock, seuilAlerte, urlPhoto, codeCompteVendeur)
VALUES
-- Nourriture
('Fraises', 'Fruit frais de Plougastel', 6.20, 'Réduit', NULL, NULL, NULL, 200, 20, NULL, NULL),
('Thé noir', 'Qualité supérieure, fabrique de Carhaix', 5.60, 'Réduit', NULL, NULL, NULL, 150, 15, NULL, NULL),
('Lait', '1L demi-écrémé', 1.50, 'Réduit', NULL, NULL, NULL, 100, 10, NULL, NULL),
('Fromage', 'Camembert AOP', 3.80, 'Réduit', NULL, NULL, NULL, 60, 10, NULL, NULL),
('Café', 'Moulu 250g', 4.90, 'Réduit', NULL, NULL, NULL, 80, 10, NULL, NULL),

-- Vêtements
('T-shirt Armor lux', 'Coton blanc M', 15.00, 'Intermédiaire', NULL, NULL, NULL, 50, 10, NULL, NULL),
('Jean à motif', 'denim 42', 45.00, 'Intermédiaire', NULL, NULL, NULL, 40, 5, NULL, NULL),
('Veste papillon', 'Noire homme L', 70.00, 'Intermédiaire', NULL, NULL, NULL, 25, 5, NULL, NULL),
('Derbies', 'Cuir marron 40', 90.00, 'Intermédiaire', NULL, NULL, NULL, 35, 5, NULL, NULL),
('Casquette Cobrec', 'Bleue ajustable', 12.00, 'Intermédiaire', NULL, NULL, NULL, 60, 5, NULL, NULL),

-- Objets divers
('Lampe rouge', 'De chevet LED', 25.00, 'Intermédiaire', 0.35, 0.20, 0.20, 30, 4, NULL, NULL),
('Gourde', 'Inox 1L', 18.00, 'Intermédiaire', 0.25, 0.08, 0.08, 45, 5, NULL, NULL),
('Coussin brodé', 'Velours bleu', 22.00, 'Intermédiaire', 0.15, 0.40, 0.40, 40, 5, NULL, NULL),
('Tasse Bretagne', 'Céramique blanche', 8.00, 'Intermédiaire', 0.10, 0.08, 0.08, 80, 8, NULL, NULL),
('Sac à dos', 'Noir imperméable', 35.00, 'Intermédiaire', 0.45, 0.30, 0.20, 20, 3, NULL, NULL),

-- Produits de luxe
('Montre LeDu', 'Acier argenté', 150.00, 'Normal', NULL, NULL, NULL, 10, 2, NULL, NULL),
('Bleu de Chanel', 'Eau de toilette 100ml', 75.00, 'Normal', NULL, NULL, NULL, 15, 2, NULL, NULL),
('Collier', 'Or plaqué', 120.00, 'Normal', NULL, NULL, NULL, 8, 1, NULL, NULL),
('Lunettes Sandrine', 'Soleil noires', 60.00, 'Normal', NULL, NULL, NULL, 25, 3, NULL, NULL),
('Stylo à bille', 'thème Océan, haut de gamme', 40.00, 'Normal', NULL, NULL, NULL, 30, 3, NULL, NULL);

INSERT INTO Adresse(num, codePostal, nomVille, nomRue) VALUES
(10, '75001', 'Paris', 'Prad-land'),
(04,  '69003', 'Lyon', 'Kergaradec'),
(22, '13001', 'Marseille', 'Plougastel'),
(01,  '59000', 'Lille','Rue la bienfaisance'),
(07,  '06000', 'Nice','Avenue de la libération'),
(15, '33000', 'Bordeaux','Rue de la forêt'),
(02, '33000', 'Bordeaux','Rue Edouard Branly'),
(19, '33000', 'Bordeaux','Le Quedel');

INSERT INTO Client(pseudo, dateCreation, nom, prenom, email, mdp, numTel) VALUES
('Zigor','2025-09-25','Mulish','Isigor','isizou@gmail.com','bababou0','0605040608'),
('Eude02','2025-10-26','Pilup','Eude','Eudeux@gmail.com','oupala!','0704090506'),
('test','2025-10-26','test','test','test@gmail.com','test','0701480506'),
('Nanardeau','2025-10-29','Bernel','michar','moviestar@gmail.com','oupala!','0704090506');

INSERT INTO Vendeur(dateCreation, nom, prenom, email, mdp, numTel, siren, raisonSociale) VALUES
('2025-10-23','test','test','email@gmail.com','admin','020482675','000000000','admin'),
('2025-10-23', 'Dupont', 'Martin', 'martin.dupont@gmail.com', 'Password123', '0612345678', '812345678', 'Dupont & Fils SARL'),
('2025-09-15', 'Moreau', 'Léa', 'lea.moreau@gmail.com', 'L3a!Secure', '0678912345', '352000799', 'Moreau Boutique'),
('2025-11-01', 'Nguyen', 'Thierry', 'thierry.nguyen@techsolutions.fr', 'TnG!2025', '0780554433', '489765432', 'Tech Solutions');


INSERT INTO Categorie(libelleCat) VALUES
('Alimentaire'),
('Vêtements'),
('Beauté'),
('Intérieur'),
('Papeterie'),
('Boissons'),
('Fruits & Légumes'),
('Epicerie sucrée'),
('Epicerie salée'),
('Accessoire'),
('Chaussures'),
('Haut'),
('Bas'),
('Parfum'),
('Décoration'),
('Cuisine'),
('Stylo');

INSERT INTO SousCat(libCat,libSousCat) VALUES
('Alimentaire', 'Boissons'),
('Alimentaire', 'Fruits & Légumes'),
('Alimentaire', 'Epicerie sucrée'),
('Alimentaire', 'Epicerie salée'),
('Vêtements', 'Accessoire'),
('Vêtements', 'Chaussures'),
('Vêtements', 'Haut'),
('Vêtements', 'Bas'),
('Beauté','Parfum'),
('Intérieur', 'Décoration'),
('Intérieur', 'Cuisine'),
('Papeterie', 'Stylo');
