set schema 'alizon';

INSERT INTO
    TVA (nomTVA, tauxTVA)
VALUES ('super-réduite', 5.5),
    ('réduite', 10),
    ('normale', 20);

INSERT INTO
    Tarification (nomTarif, tauxTarif)
VALUES ('tarif1', 2),
    ('tarif2', 5),
    ('tarif3', 8),
    ('tarif4', 10),
    ('tarif5', 15);

INSERT INTO
    Photo (urlPhoto)
VALUES ('/img/fromage2.jpg'),
    ('/img/fromageTourteau.jpg'),
    ('/img/madameLoik.jpg'),
    ('/img/pulljaune.jpg'),
    ('/img/short2.jpg'),
    ('/img/tasseBretonne.jpg'),
    ('/img/t-shirt.jpg'),
    ('/img/t-shirt2.jpg'),
    ('/img/t-shirtbleu.jpg'),
    ('/img/Boiteasucrebreton.jpg'),
    ('/img/boitebigoudenne.jpg'),
    ('/img/boitebretonne.jpg'),
    ('/img/boitegalette.jpg'),
    ('/img/boitegalette2.jpg'),
    ('/img/botte.jpg'),
    ('/img/botte2.jpg'),
    ('/img/botte3.jpg'),
    ('/img/bougiebateau.jpg'),
    ('/img/bougiephare.jpg'),
    ('/img/bougietriskel.jpg'),
    ('/img/coussin.jpg'),
    ('/img/echarpebleugris.jpg'),
    ('/img/echarpebretagne.jpg'),
    ('/img/galette.jpg'),
    ('/img/gourdebretagne.jpg'),
    ('/img/horloge.jpg'),
    ('/img/horloge2.jpg'),
    ('/img/moulin2.jpg'),
    ('/img/robebretonne.jpg'),
    ('/img/robemarinière.jpg'),
    ('/img/robemarinière2.jpg'),
    ('/img/savon2.jpg'),
    ('/img/serviette3.jpg'),
    ('/img/serviette2.jpg'),
    ('/img/shortBretagne.jpg'),
    ('/img/tapisbreton2.jpg'),
    ('/img/tapisbreton.jpg'),
    ('/img/tapis.jpg'),
    ('/img/tapissouris.jpg'),
    ('/img/trousse5.jpg'),
    ('/img/trousse2.jpg'),
    ('/img/trousse3.jpg'),
    ('/img/trousse4.jpg'),
    ('/img/tshirt6.jpg'),
    ('/img/tshirt2.jpg'),
    ('/img/tshirt3.jpg'),
    ('/img/tshirt4.jpg'),
    ('/img/tshirt5.jpg'),
    ('/img/tshirt7.jpg'),
    ('/img/tshirt8.jpg'),
    ('/img/the.jpg'),
    ('/img/lait.jpg'),
    ('/img/fromage.jpg'),
    ('/img/cafe.jpg'),
    ('/img/tshirt.jpg'),
    ('/img/pantalon.jpg'), --
    ('/img/veste.jpg'), --
    ('/img/mocassins.jpg'),
    ('/img/chapeau.jpg'),
    ('/img/gourde.jpg'),
    ('/img/tasse.jpg'),
    ('/img/sac.jpg'), --
    ('/img/lunettes.jpg'),
    ('/img/stylo.jpg'), --
    ('/img/pommes.jpg'),
    ('/img/miel.jpg'),
    ('/img/chocolat.jpg'),
    ('/img/confiture.jpg'),
    ('/img/huile.jpg'),
    ('/img/biscottes.jpg'),
    ('/img/pull.jpg'), --
    ('/img/parka.jpg'), --
    ('/img/sweat.jpg'),
    ('/img/sandales.jpg'),
    ('/img/echarpe.jpg'),
    ('/img/gants.jpg'), --
    ('/img/short.jpg'),
    ('/img/robe.jpg'),
    ('/img/bougie.jpg'),
    ('/img/boite.jpg'),
    ('/img/plaid.jpg'),
    ('/img/moulin.jpg'),
    ('/img/iris.jpg'),
    ('/img/argent.jpg'),
    ('/img/soleil.jpg'),
    ('/img/luxe.jpg'),
    ('/img/savon.jpg'),
    ('/img/trousse.jpg'),
    ('/img/serviette.jpg'),
    ('/img/peignoir.jpg'),
    ('/img/cristal.jpg'),
    ('/img/yoga.jpg'),
    ('/img/carafe.jpg'),
    ('/img/bouteille.jpg'),
    ('/img/tente.jpg'),
    ('/img/couchage.jpg'),
    ('/img/boussole.jpg'),
    ('/img/torche.jpg'),
    ('/img/thermos.jpg'),
    ('/img/bottes.jpg'),
    ('/img/tshirtalamer.jpg'),
    ('/img/tshirtcrabe.jpg'),
    (
        '../img/photosProduit/imgErr.jpg'
    );

INSERT INTO
    Client (
        pseudo,
        dateCreation,
        dateNaissance,
        nom,
        prenom,
        email,
        mdp,
        numTel,
        cmtBlq,
        cmtBlqMod
    )
VALUES (
        'Bernard',
        '2025-09-25',
        '2003-04-03',
        'Mulish',
        'Isigor',
        'isizou@gmail.com',
        MD5('1234'),
        '0605040608',
        false,
        false
    ),
    (
        'Constance',
        '2025-10-26',
        '2006-05-08',
        'Pilup',
        'Eude',
        'Eudeux@gmail.com',
        MD5('1234'),
        '0704090506',
        false,
        false
    ),
    (
        'Titouan',
        '2025-10-26',
        '2001-09-11',
        'test',
        'test',
        'test@gmail.com',
        MD5('1234'),
        '0701480506',
        false,
        false
    ),
    (
        'Nanardeau',
        '2025-10-29',
        '2006-12-29',
        'Bernel',
        'michar',
        'moviestar@gmail.com',
        MD5('oupala!'),
        '0704090506',
        false,
        false
    );

INSERT INTO
    Vendeur (
        dateCreation,
        nom,
        prenom,
        pseudo,
        email,
        mdp,
        numTel,
        siren,
        raisonSociale
    )
VALUES (
        '2025-10-23',
        'admin',
        'Correc',
        'Luhan',
        'email@gmail.com',
        MD5('admin'),
        '0204826759',
        '000000000',
        'admin'
    ),
    (
        '2025-10-23',
        'Dupont',
        'Martin',
        'mdupont',
        'martin.dupont@gmail.com',
        MD5('Password123'),
        '0612345678',
        '812345678',
        'Dupont & Fils SARL'
    ),
    (
        '2025-09-15',
        'Moreau',
        'Léa',
        'lmoreau',
        'lea.moreau@gmail.com',
        MD5('L3a!Secure'),
        '0678912345',
        '352000799',
        'Moreau Boutique'
    ),
    (
        '2025-11-01',
        'Nguyen',
        'tnguyen',
        'Thierry',
        'thierry.nguyen@techsolutions.fr',
        MD5('TnG!2025'),
        '0780554433',
        '489765432',
        'Tech Solutions'
    );

INSERT INTO
    Client (
        pseudo,
        dateCreation,
        dateNaissance,
        nom,
        prenom,
        email,
        mdp,
        numTel,
        cmtBlq,
        cmtBlqMod
    )
VALUES (
        'Camille',
        '2025-11-21',
        '1999-03-03',
        'Guillou',
        'Camille',
        'camille@gmail.com',
        MD5('camille1'),
        '0649786246',
        false,
        false
    ),
    (
        'AudreyM',
        '2025-11-21',
        '1989-09-04',
        'Asma',
        'Audrey',
        'audrey3@gmail.com',
        MD5('Audrey3'),
        '0748956215',
        false,
        false
    );

INSERT INTO Produit (libelleProd, descriptionProd, prixHT, nomTVA, spe1, spe2, spe3, qteStock, Origine, Disponible, seuilAlerte, urlPhoto, codeCompteVendeur)
VALUES
-- Nourriture
('Thé noir', 'Qualité supérieure, fabrique de Carhaix', 5.60, 'réduite', NULL, NULL, NULL, 150, 'Étranger', true, 15, '/img/the.jpg', 6),
('Lait', '1L demi-écrémé', 1.50, 'réduite', NULL, NULL, NULL, 100, 'Breizh', true, 10, '/img/lait.jpg', 7),
('Fromage', 'Camembert AOP', 3.80, 'réduite', NULL, NULL, NULL, 60, 'Breizh', true, 10, '/img/fromage.jpg', 8),
('Café', 'Moulu 250g', 4.90, 'réduite', 'Informations de sécurité:Conserver dans un endroit frais et sec', 'Ingrédients:Café en grains torréfiés', 'Conseils pour utilisation:Insérez les grains dans le broyeur, puis laissez la machine verser votre café comme vous le souhaitez', 80, 'Breizh', true, 10, '/img/cafe.jpg', 5);

INSERT INTO Produit (libelleProd, descriptionProd, prixHT, nomTVA, spe1, spe2, spe3, qteStock, Origine, seuilAlerte, urlPhoto, codeCompteVendeur, nomTarif)
VALUES
    -- Nouriture
    (
        'Miel de sarrasin',
        'Un miel sombre et puissant produit dans des ruches installées au cœur des campagnes bretonnes. Sa saveur boisée et son parfum profond le rendent particulièrement apprécié des amateurs de produits authentiques. Parfait pour sucrer tisane, desserts ou tartines.',
        8.90,
        'réduite',
        NULL,
        NULL,
        NULL,
        90,
        'Breizh',
        8,
        '/img/miel.jpg',
        8,
        'tarif1'
    ),
    (
        'Chocolat noir 80%',
        'Un chocolat intense fabriqué avec du cacao issu de plantations équitables. Ses arômes profonds et sa légère amertume le rendent parfait pour les connaisseurs ou pour la pâtisserie. Chaque tablette est travaillée pour garantir une fondance idéale.',
        3.10,
        'réduite',
        NULL,
        NULL,
        NULL,
        150,
        'France',
        6,
        '/img/chocolat.jpg',
        8,
        'tarif1'
    ),
    (
        'Confiture de fraise',
        'Confiture préparée avec des fraises mûries au soleil et cuites en petite quantité pour garantir une saveur fruitée intense. Sa texture onctueuse et son goût authentique la rendent parfaite pour les tartines, les crêpes ou les desserts faits maison.',
        4.80,
        'réduite',
        NULL,
        NULL,
        NULL,
        85,
        'Breizh',
        8,
        '/img/confiture.jpg',
        6,
        'tarif1'
    ),
    (
        'Huile d’olive',
        'Huile extra vierge issue d’une première pression à froid. Son parfum fruité et sa saveur équilibrée en font un ingrédient essentiel pour la cuisine du quotidien. Idéale pour assaisonnement, cuisson douce ou marinade.',
        7.50,
        'réduite',
        NULL,
        NULL,
        NULL,
        100,
        'Étranger',
        8,
        '/img/huile.jpg',
        8,
        'tarif1'
    ),
    (
        'Biscottes complètes',
        'Des biscottes croustillantes élaborées avec de la farine complète pour un petit-déjeuner nutritif. Leur texture légère et leur goût délicat en font une alternative saine au pain traditionnel. Elles se conservent longtemps sans perdre leur croquant.',
        2.40,
        'réduite',
        NULL,
        NULL,
        NULL,
        130,
        'France',
        15,
        '/img/biscottes.jpg',
        8,
        'tarif1'
    ),
    (
        'Boite à sucre',
        'Une charmante boîte à sucre bretonne qui allie tradition et authenticité. Ornée de motifs inspirés de la Bretagne, elle apporte une touche régionale à votre cuisine tout en conservant parfaitement votre sucre. Idéale comme souvenir ou objet déco au style breton.',
        7.40,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/Boiteasucrebreton.jpg',
        6,
        'tarif2'
    ),
    (
        'Boite bigoudenne à galette',
        'Une jolie boîte Bigoudène décorée de motifs typiquement bretons, rendant hommage à la culture et aux traditions du pays Bigouden. Pratique et esthétique, elle est parfaite pour ranger sucre, biscuits ou petits trésors tout en apportant une touche d’authenticité à votre intérieur.',
        9.40,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/boitebigoudenne.jpg',
        7,
        'tarif2'
    ),
    (
        'Boite bretonne à galette',
        'Une boîte bretonne au charme authentique, décorée de symboles et motifs inspirés de la Bretagne. Idéale pour conserver sucre, biscuits ou petits objets, elle allie praticité et esprit régional pour une touche bretonne dans votre cuisine ou votre maison.',
        10.00,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/boitebretonne.jpg',
        8,
        'tarif2'
    ),
    (
        'Boite à galette',
        'Une belle boîte à galettes bretonnes, décorée avec soin et pensée pour conserver toute la fraîcheur et le croquant des biscuits. Parfaite pour ajouter une touche régionale et gourmande à votre cuisine',
        14.99,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/boitegalette.jpg',
        5,
        'tarif2'
    ),
    (
        'Boite à galette',
        'Une boîte à galettes inspirée du savoir-faire breton, ornée de motifs rappelant la mer et les terres de Bretagne. Un objet à la fois utile et décoratif, symbole d’authenticité et de convivialité.',
        11.99,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/boitegalette2.jpg',
        6,
        'tarif2'
    ),
    (
        'Boite à galette',
        'Boîte à galettes bretonnes pratique et décorative, parfaite pour conserver vos biscuits avec style.',
        9.00,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/galette.jpg',
        8,
        'tarif2'
    ),
    (
        'Le Manicamp',
        'un fromage de vache français originaire des environs de Manicamp, dans le département de l Aisne. ',
        3.40,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/fromage2.jpg',
        5,
        'tarif2'
    ),
    (
        'Le Touteau',
        'Un fromage créer exclusivement en bretagne',
        5.40,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/fromageTourteau.jpg',
        6,
        'tarif1'
    ),
    (
        'Madame Loik',
        'Très agréable en bouche et donnant une vraie sensation de goût frais, cette texture unique est devenue une référence au rayon des fromages et ne cesse de séduire',
        6.99,
        'réduite',
        NULL,
        NULL,
        NULL,
        180,
        'Breizh',
        15,
        '/img/madameLoik.jpg',
        7,
        'tarif1'
    ),

-- Vetement
('Short de sport', 'Short léger conçu pour une pratique sportive régulière. Son tissu respirant et sa coupe ergonomique offrent un confort optimal, même lors d’efforts intenses. Idéal pour course à pied, fitness ou activités extérieures grâce à sa grande liberté de mouvement.', 18.00, 'super-réduite', NULL, NULL, NULL, 45, 'France', 5, '/img/short.jpg', 8, 'tarif1'),
('Sweat capuche', 'Sweat chaud muni d’une capuche ajustable. Fabriqué en coton épais, il offre une grande douceur et une excellente résistance. Sa coupe classique le rend adapté à un usage quotidien, autant pour le confort intérieur que les sorties en extérieur.', 35.00, 'super-réduite', 'TAILLE:S', 'MATIERE:Coton', NULL, 30, 'Breizh', 5, '/img/sweat.jpg', 6, 'tarif2'),
('Parka impermeable', 'Parka longue conçue pour affronter les intempéries. Son revêtement imperméable et sa doublure isolante assurent une protection optimale contre le vent et la pluie. Idéale pour les déplacements quotidiens en saison froide ou pluvieuse.', 85.00, 'super-réduite', 'TAILLE:L', 'MATIERE:inconnu', NULL, 20, 'France', 5, '/img/parka.jpg', 7, 'tarif3'),
('Robe été', 'Robe légère fabriquée dans un tissu fluide et agréable au toucher. Ses motifs colorés et sa coupe aérée en font une tenue parfaite pour les journées chaudes. Confortable et facile à porter, elle convient aussi bien à la plage qu’aux sorties estivales.', 28.00, 'super-réduite', NULL, NULL, NULL, 35, 'Étranger', 6, '/img/robe.jpg', 5, 'tarif1'),
('Bottes cuir', 'Bottes en cuir véritable offrant une grande robustesse et une finition élégante. Leur semelle résistante ainsi que leur maintien confortable permettent une utilisation prolongée, que ce soit en ville ou en environnement plus exigeant.', 110.00, 'super-réduite', NULL, NULL, NULL, 25, 'France', 3, '/img/bottes.jpg', 8, 'tarif3'),
('Pull laine', 'Pull tricoté en laine naturelle offrant chaleur et douceur. Sa coupe ajustée et son style intemporel en font un vêtement indispensable pour la saison froide. Fabriqué avec des matériaux de qualité pour garantir longévité et confort.', 42.00, 'super-réduite', NULL, NULL, NULL, 40, 'Breizh', 5, '/img/pull.jpg', 6, 'tarif1'),
('Écharpe douce', 'Écharpe longue et épaisse conçue pour offrir une chaleur optimale lors des journées froides. Son tissu doux ne pique pas et assure un confort prolongé. Disponible en plusieurs coloris pour s’adapter à tous les styles vestimentaires.', 16.00, 'super-réduite', NULL, NULL, NULL, 60, 'France', 8, '/img/echarpe.jpg', 8, 'tarif1'),
('Gants hiver', 'Gants chauds rembourrés, idéals pour faire face aux températures basses. Leur doublure interne assure une isolation efficace tandis que l’extérieur résistant protège du vent. Compatibles avec les écrans tactiles pour une utilisation pratique.', 14.00, 'super-réduite', NULL, NULL, NULL, 55, 'Breizh', 8, '/img/gants.jpg', 6, 'tarif1'),
('Sandales cuir', 'Sandales élégantes fabriquées en cuir souple de haute qualité. Leur semelle confortable et leur style épuré en font une chaussure idéale pour l’été. Conçues pour offrir un maintien stable lors de la marche.', 38.00, 'super-réduite', NULL, NULL, NULL, 40, 'France', 5, '/img/sandales.jpg', 6, 'tarif1'),
('Robe mariniere', 'Robe marinière élégante et confortable, parfaite pour un look décontracté avec une touche classique et intemporelle.', 20.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/robemarinière.jpg', 7, 'tarif1'),
('Robe mariniere', 'Robe marinière fabriquée avec soin dans des matériaux doux et de qualité. Ses rayures emblématiques apportent un style authentique et une note bretonne à votre tenue.', 20.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/robemarinière2.jpg', 8, 'tarif1'),
('Short', 'Short confortable et léger, parfait pour les journées chaudes ou les activités décontractées.', 20.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/shortBretagne.jpg', 8, 'tarif1'),
('T-shirt femme blanc', 'T-shirt confortable et polyvalent, idéal pour un look décontracté au quotidien.', 15.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tshirt6.jpg', 8, 'tarif1'),
('T-shirt blanc', 'T-shirt fabriqué avec soin dans un coton doux et respirant, offrant confort et style au quotidien. Parfait pour un look simple et authentique.', 11.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tshirt2.jpg', 5, 'tarif1'),
('T-shirt breton premium', 'T-shirt léger et agréable à porter, parfait pour toutes les occasions décontractées.', 14.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tshirt3.jpg', 6, 'tarif1'),
('T-shirt femme', 'T-shirt confectionné avec des matériaux de qualité, doux et respirant. Idéal pour un confort quotidien tout en gardant un style simple et naturel.', 18.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tshirt4.jpg', 7, 'tarif1'),
('T-shirt ptit breton', 'T-shirt élégant et confortable, parfait pour compléter une tenue décontractée ou habillée.', 15.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tshirt5.jpg', 8, 'tarif1'),
('Echarpe bleu et grise', 'Écharpe douce et élégante, idéale pour vous protéger du froid tout en ajoutant une touche de style à votre tenue.', 20.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/echarpebleugris.jpg', 6, 'tarif1'),
('Echarpe bretonne', 'Écharpe fabriquée avec soin dans des matériaux de qualité, chaude et agréable au toucher. Parfaite pour allier confort et élégance au quotidien.', 20.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/echarpebretagne.jpg', 7, 'tarif1'),
('Botte bretonne', 'Des bottes robustes et confortables, parfaites pour affronter la pluie ou les balades en plein air. Leur design sobre et leur finition soignée en font un indispensable du quotidien.', 30.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/botte.jpg', 7, 'tarif3'),
('Botte de pluit', 'Inspirées du style breton, ces bottes imperméables sont parfaites pour les balades en bord de mer ou les jours de pluie. Solides, pratiques et au charme authentique, elles rappellent l’esprit marin de la Bretagne.', 35.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/botte2.jpg', 8, 'tarif3'),
('Botte stylisé', 'Bottes imperméables et confortables, idéales pour le quotidien ou les escapades bretonnes. Pratiques, solides et stylées !', 29.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/botte3.jpg', 6, 'tarif3'),
('Pull jaune', 'Un pull confortable et tendance, conçu dans une maille douce pour un maximum de chaleur et de style. Sa couleur jaune apporte une touche de fraîcheur et d’énergie à toutes vos tenues.', 40.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/pulljaune.jpg', 8, 'tarif1'),
('Short', 'Un short léger et agréable à porter, parfait pour les journées ensoleillées ou le sport. Sa coupe moderne offre une grande liberté de mouvement tout en restant élégant.', 30.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/short2.jpg', 5, 'tarif1'),
('Tasse', 'Une tasse élégante et pratique, idéale pour savourer votre café, thé ou chocolat chaud. Fabriquée dans un matériau résistant, elle allie style et durabilité.', 3.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tasseBretonne.jpg', 6, 'tarif2'),
('Robe bretonne', 'Robe confectionnée avec soin dans des matériaux de qualité, douce et agréable à porter. Parfaite pour allier confort et style au quotidien.', 20.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/robebretonne.jpg', 6, 'tarif2'),

-- Interieur
('Boîte rangement', 'Boîte de rangement en plastique robuste, idéale pour organiser vêtements, documents ou jouets. Son couvercle hermétique protège le contenu de la poussière. Adaptée à un usage domestique ou professionnel grâce à sa grande capacité.', 12.00, 'super-réduite', NULL, NULL, NULL, 60, 'Breizh', 8, '/img/boite.jpg', 5, 'tarif2'),
('Bougie parfumée', 'Bougie coulée à la main avec cire naturelle. Son parfum discret mais durable permet de créer une ambiance apaisante dans n’importe quelle pièce. Le contenant esthétique peut être réutilisé une fois la bougie consommée.', 9.50, 'super-réduite', NULL, NULL, NULL, 80, 'France', 8, '/img/bougie.jpg', 5, 'tarif1'),
('Moulin poivre', 'Moulin de table en bois massif, équipé d’un mécanisme de broyage durable. Permet d’ajuster précisément la mouture selon les préférences culinaires. Un accessoire élégant et pratique pour rehausser les saveurs de vos plats.', 22.00, 'super-réduite', NULL, NULL, NULL, 40, 'France', 5, '/img/moulin.jpg', 8, 'tarif1'),
('Tapis  de salon', 'Tapis de salon tissé avec soin, offrant un toucher doux et une excellente résistance à l’usage. Ses motifs modernes permettent d’apporter une touche décorative chaleureuse à votre intérieur tout en assurant une bonne isolation du sol.', 55.00, 'super-réduite', NULL, NULL, NULL, 15, 'Étranger', 3, '/img/tapis.jpg', 5, 'tarif3'),
('Plaid laine', 'Plaid en laine chaude tissé avec une grande finesse. Parfait pour se réchauffer lors des soirées fraîches, il peut également servir d’élément décoratif sur canapé ou lit. Sa texture douce procure une sensation agréable au toucher.', 39.00, 'super-réduite', NULL, NULL, NULL, 30, 'France', 5, '/img/plaid.jpg', 6, 'tarif2'),
('Tapis d entrer de Terre de Legende', 'Tapis d’entrée pratique et résistant, idéal pour garder votre intérieur propre avec style.', 15.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tapisbreton2.jpg', 5, 'tarif3'),
('Tapis d entrer artisanal', 'Tapis d’entrée fabriqué avec soin dans des matériaux de qualité, robuste et esthétique. Parfait pour accueillir vos invités tout en apportant une touche chaleureuse à votre maison.', 15.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tapisbreton.jpg', 6, 'tarif3'),
('Tapis d entrer', 'Tapis d’entrée élégant et pratique, parfait pour protéger votre sol tout en ajoutant une touche déco à votre intérieur.', 15.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tapis.jpg', 7, 'tarif3'),
('Tapis de souris', 'Tapis de souris breton pratique et décoratif, parfait pour travailler avec confort tout en ajoutant une touche régionale à votre bureau.', 15.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/tapissouris.jpg', 7, 'tarif2'),
('Trousse artisanal', 'Trousse pratique et compacte, idéale pour ranger stylos, maquillage ou petits accessoires au quotidien.', 12.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/trousse5.jpg', 8, 'tarif1'),
('Trousse rose', 'Trousse fabriquée avec soin dans des matériaux de qualité, résistante et élégante. Parfaite pour organiser vos affaires tout en ajoutant une touche authentique à votre quotidien.', 15.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/trousse2.jpg', 5, 'tarif1'),
('Trousse bretonne', 'Trousse élégante et fonctionnelle, parfaite pour garder vos affaires organisées où que vous soyez.', 13.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/trousse3.jpg', 6, 'tarif1'),
('Trousse bleu et blanche', 'Trousse soigneusement conçue avec des matériaux de qualité, pratique et durable. Idéale pour ranger vos essentiels tout en apportant une touche artisanale à votre quotidien.', 10.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/trousse4.jpg', 7, 'tarif1'),
('Savon', 'Savon fabriqué avec soin à partir d’ingrédients naturels, respectueux de la peau. Parfait pour allier hygiène, douceur et authenticité dans votre routine quotidienne.', 10.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/savon2.jpg', 5, 'tarif2'),
('Serviette Breton de coeur', 'Serviette douce et absorbante, idéale pour le quotidien ou la plage.', 20.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/serviette3.jpg', 6, 'tarif2'),
('Serviette', 'Serviette fabriquée avec soin dans des matériaux de qualité, agréable au toucher et résistante. Parfaite pour allier confort et style dans votre routine quotidienne.', 15.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/serviette2.jpg', 7, 'tarif2'),
('Horloge bretonne', 'Horloge élégante et fonctionnelle, idéale pour décorer votre intérieur tout en gardant l’heure avec style.', 9.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/horloge.jpg', 7, 'tarif3'),
('Horloge de la bretagne', 'Horloge fabriquée avec soin dans des matériaux de qualité, alliant design et authenticité. Parfaite pour apporter une touche chaleureuse et décorative à votre maison.', 7.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/horloge2.jpg', 8, 'tarif3'),
('Statue moulin', 'Statue de moulin fabriquée avec soin dans des matériaux de qualité, reflétant un style authentique et artisanal. Idéale pour décorer votre espace avec charme et caractère.', 20.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/moulin2.jpg', 5, 'tarif3'),
('Bougie bateau', 'Une bougie élégante qui apporte chaleur et ambiance à votre intérieur. Son parfum délicat et sa flamme douce créent une atmosphère cosy et apaisante.', 7.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/bougiebateau.jpg', 5, 'tarif2'),
('Bougie phare', 'Illuminez votre maison avec cette bougie raffinée, idéale pour créer une ambiance chaleureuse et relaxante. Parfaite comme objet décoratif ou cadeau original.', 5.40, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/bougiephare.jpg', 7, 'tarif2'),
('Bougie bretonne', 'Une bougie artisanale, fabriquée avec soin à partir de matériaux de qualité. Son parfum subtil et sa flamme douce en font un objet à la fois esthétique et apaisant.', 7.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/bougietriskel.jpg', 8, 'tarif2'),
('Coussin', 'Coussin décoratif et confortable, idéal pour embellir votre intérieur et profiter d’un confort optimal.', 15.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/coussin.jpg', 5, 'tarif2'),
('Parfum iris', 'Eau de parfum mêlant notes florales d’iris et touches boisées. Son sillage élégant en fait une fragrance raffinée adaptée aux occasions spéciales. Le flacon soigné met en valeur ce parfum haut de gamme.', 85.00, 'normale', NULL, NULL, NULL, 18, 'Étranger', 3, '/img/iris.jpg', 5, 'tarif1'),
('Bracelet argent', 'Bijou en argent massif travaillé avec finesse. Léger et agréable à porter, il s’adapte à tous les styles vestimentaires. Livré dans un écrin élégant, il constitue également une excellente idée cadeau.', 70.00, 'normale', NULL, NULL, NULL, 20, 'France', 2, '/img/argent.jpg', 6, 'tarif1'),
('Lunettes soleil', 'Lunettes de soleil offrant une excellente protection UV. Leur monture légère et résistante assure un grand confort. Le design moderne et épuré permet de les porter en toutes occasions, été comme hiver.', 55.00, 'normale', NULL, NULL, NULL, 30, 'Breizh', 3, '/img/soleil.jpg', 5, 'tarif1'),
('Stylo luxe', 'Stylo haut de gamme au design élégant, doté d’une glisse fluide et régulière. Parfait pour la prise de notes ou la signature de documents importants. Présenté dans un coffret raffiné pour un effet hautement professionnel.', 45.00, 'normale', NULL, NULL, NULL, 30, 'France', 3, '/img/luxe.jpg', 8, 'tarif1'),
('Savon artisanal', 'Savon fabriqué à la main avec des huiles végétales de grande qualité. Son parfum discret et naturel en fait un produit agréable au quotidien. Respectueux de la peau, il convient même aux usages fréquents.', 3.80, 'réduite', NULL, NULL, NULL, 140, 'Breizh', 6, '/img/savon.jpg', 5, 'tarif1'),
('Trousse toilette', 'Trousse de toilette spacieuse et résistante conçue pour accueillir tous les essentiels de soin. Son tissu imperméable protège efficacement son contenu. Idéale pour voyages, déplacements ou utilisation quotidienne.', 14.00, 'super-réduite', NULL, NULL, NULL, 60, 'France', 8, '/img/trousse.jpg', 6, 'tarif1'),
('Serviette bain', 'Grande serviette de bain en coton épais, absorbante et douce au contact de la peau. Durable et résistante, elle conserve ses qualités lavage après lavage. Disponible dans plusieurs coloris.', 18.50, 'super-réduite', NULL, NULL, NULL, 45, 'Étranger', 5, '/img/serviette.jpg', 7, 'tarif2'),
('Peignoir coton', 'Peignoir confortable en coton moelleux conçu pour envelopper le corps d’une chaleur agréable après la douche. Son tissu épais assure une absorption optimale et une douceur durable.', 45.00, 'super-réduite', NULL, NULL, NULL, 30, 'France', 5, '/img/peignoir.jpg', 6, 'tarif2'),
('Vase breton', 'Vase breton fabriqué avec soin dans des matériaux de qualité, orné de motifs inspirés de la culture bretonne. Parfait pour apporter une note traditionnelle et chaleureuse à votre décoration.', 95.00, 'normale', NULL, NULL, NULL, 15, 'Étranger', 3, '/img/cristal.jpg', 6, 'tarif2'),
('Carafe verre', 'Carafe en verre soufflé à la bouche, offrant une grande transparence et une élégance naturelle. Idéale pour servir eau, vin ou jus lors des repas. Sa forme ergonomique assure une prise en main confortable.', 28.00, 'normale', NULL, NULL, NULL, 40, 'France', 5, '/img/carafe.jpg', 8, 'tarif1'),

-- Extérieur
('Tapis de yoga', 'Tapis de yoga antidérapant offrant une excellente adhérence au sol. Son épaisseur confortable réduit les impacts et assure un maintien stable pour toutes les postures. Idéal pour yoga, pilates et étirements.', 24.00, 'super-réduite', 'LONGUEUR:1m80', 'MATIERE:caoutchouc', NULL, 40, 'Étranger', 6, '/img/yoga.jpg', 8, 'tarif2'),
('Bouteille sport', 'Bouteille de sport légère conçue dans un plastique sans BPA. Son embout étanche et son format pratique permettent de l’emporter partout : salle de sport, bureau ou sorties extérieures.', 8.50, 'super-réduite', NULL, NULL, NULL, 100, 'Breizh', 15, '/img/bouteille.jpg', 8, 'tarif1'),
('Tente randonnée', 'Tente légère adaptée aux randonnées et bivouacs courts. Son montage rapide et sa toile résistante aux intempéries offrent un abri fiable. Compacte et facile à transporter, elle convient parfaitement aux excursions régulières.', 75.00, 'super-réduite', NULL, NULL, NULL, 18, 'France', 5, '/img/tente.jpg', 6, 'tarif3'),
('Sac couchage', 'Sac de couchage conçu pour offrir une bonne isolation thermique lors des nuits fraîches. Sa forme enveloppante assure un confort optimal et limite les déperditions de chaleur. Livré avec housse de compression.', 55.00, 'super-réduite', NULL, NULL, NULL, 30, 'France', 5, '/img/couchage.jpg', 7, 'tarif2'),
('Boussole pro', 'Boussole de randonnée précise et résistante, dotée d’un cadran lumineux pour une lecture facile, même par faible luminosité. Un outil fiable pour les passionnés d’orientation et les explorateurs.', 16.00, 'super-réduite', NULL, NULL, NULL, 70, 'Étranger', 8, '/img/boussole.jpg', 6, 'tarif1'),
('Lampe torche', 'Lampe torche compacte équipée d’une LED très puissante assurant une excellente visibilité de nuit. Son autonomie longue durée en fait un accessoire incontournable en randonnée, camping ou dépannage.', 12.90, 'super-réduite', NULL, NULL, NULL, 90, 'Breizh', 8, '/img/torche.jpg', 6, 'tarif1'),
('Gourde thermique', 'Gourde isotherme capable de conserver la chaleur ou la fraîcheur pendant plusieurs heures. Son revêtement robuste la protège des chocs et assure une étanchéité parfaite pour un transport sécurisé.', 22.00, 'super-réduite', NULL, NULL, NULL, 50, 'Étranger', 5, '/img/thermos.jpg', 8, 'tarif1'),
('Gourde', 'Gourde pratique et durable, idéale pour rester hydraté partout, à la maison, au travail ou en balade.', 15.00, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/gourde.jpg', 5, 'tarif2'),
('Termos', 'Termos fabriquée avec des matériaux de qualité, résistante et agréable à utiliser. Parfaite pour allier confort, praticité et style au quotidien.', 10.99, 'réduite', NULL, NULL, NULL, 180, 'Breizh', 15, '/img/gourdebretagne.jpg', 6, 'tarif2');

INSERT INTO Produit (libelleProd, descriptionProd, prixHT, nomTVA, spe1, spe2, spe3, qteStock, Origine, seuilAlerte, urlPhoto, codeCompteVendeur)
VALUES
    -- Vêtements
    (
        'T-shirt armor lux',
        'Coton blanc M',
        15.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        50,
        'Breizh',
        10,
        '/img/tshirt.jpg',
        6
    ),
    (
        'T-shirt crabe',
        'synthétique XXL',
        18.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        30,
        'Étranger',
        5,
        '/img/tshirtcrabe.jpg',
        7
    ),
    (
        'T-shirt à la vie, à la mer',
        'synthétique L',
        18.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        40,
        'Étranger',
        15,
        '/img/tshirtalamer.jpg',
        7
    ),
    (
        'Jean à motif',
        'denim 42',
        45.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        40,
        'Étranger',
        5,
        '/img/pantalon.jpg',
        7
    ),
    (
        'Veste papillon',
        'Noire homme L',
        70.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        25,
        'France',
        5,
        '/img/veste.jpg',
        8
    ),
    (
        'Derbies',
        'Cuir marron 40',
        90.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        35,
        'Breizh',
        5,
        '/img/mocassins.jpg',
        5
    ),
    (
        'Casquette Cobrec',
        'Bleue ajustable',
        12.00,
        'super-réduite',
        NULL,
        NULL,
        NULL,
        60,
        'Breizh',
        5,
        '/img/chapeau.jpg',
        6
    ),

-- Intérieur
('Tasse Bretagne', 'Céramique blanche', 8.00, 'super-réduite', 'MATIERE:Céramique', 'VOLUME/CONTENANCE:25cl', NULL, 80, 'Breizh', 8, '/img/tasse.jpg', 6),

-- Extérieur
('Gourde en acier', 'Inox 1L', 18.00, 'super-réduite', NULL, NULL, NULL, 45, 'Étranger', 5, '/img/gourde.jpg', 8),
('Sac à dos', 'Noir imperméable', 35.00, 'super-réduite', NULL, NULL, NULL, 20, 'France', 3, '/img/sac.jpg', 7),

-- Vêtements
(
    'Lunettes Sandrine',
    'Soleil noires',
    60.00,
    'normale',
    NULL,
    NULL,
    NULL,
    25,
    'Breizh',
    3,
    '/img/lunettes.jpg',
    7
),
(
    'T-shirt Breton',
    'T-shirt moderne et stylé, idéal pour affirmer un look simple et tendance au quotidien.',
    12.00,
    'super-réduite',
    NULL,
    NULL,
    NULL,
    50,
    'Breizh',
    10,
    '/img/tshirt7.jpg',
    5
),
(
    'T-shirt',
    'T-shirt conçu avec soin à partir de coton de qualité, doux et respirant. Allie confort, durabilité et authenticité pour un style intemporel.',
    14.99,
    'super-réduite',
    NULL,
    NULL,
    NULL,
    50,
    'Breizh',
    10,
    '/img/tshirt8.jpg',
    6
),

-- Papetrie
(
    'Stylo à bille',
    'thème Océan, haut de gamme',
    40.00,
    'normale',
    NULL,
    NULL,
    NULL,
    30,
    'Breizh',
    3,
    '/img/stylo.jpg',
    8
);

INSERT INTO
    Categorie (libelleCat)
VALUES ('Alimentaire'),
    ('Vêtements'),
    ('Hygiène'),
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
    ('Douche'),
    ('Décoration'),
    ('Cuisine'),
    ('Salle de bain'),
    ('Rangements'),
    ('Bien-être'),
    ('Electronique'),
    ('Stylo'),
    ('Sport'),
    ('Extérieur'),
    ('Activités');

INSERT INTO
    SousCat (libCat, libSousCat)
VALUES ('Alimentaire', 'Boissons'),
    (
        'Alimentaire',
        'Fruits & Légumes'
    ),
    (
        'Alimentaire',
        'Epicerie sucrée'
    ),
    (
        'Alimentaire',
        'Epicerie salée'
    ),
    ('Vêtements', 'Accessoire'),
    ('Vêtements', 'Chaussures'),
    ('Vêtements', 'Haut'),
    ('Vêtements', 'Bas'),
    ('Hygiène', 'Beauté'),
    ('Hygiène', 'Douche'),
    ('Intérieur', 'Décoration'),
    ('Intérieur', 'Cuisine'),
    ('Intérieur', 'Rangements'),
    ('Intérieur', 'Bien-être'),
    ('Intérieur', 'Salle de bain'),
    ('Intérieur', 'Electronique'),
    ('Intérieur', 'Sport'),
    ('Papeterie', 'Stylo'),
    ('Extérieur', 'Activités');

INSERT INTO
    Categoriser (codeProduit, libelleCat)
VALUES (1, 'Alimentaire'),
    (2, 'Alimentaire'),
    (3, 'Alimentaire'),
    (4, 'Alimentaire'),
    (5, 'Alimentaire'),
    (6, 'Alimentaire'),
    (7, 'Alimentaire'),
    (8, 'Alimentaire'),
    (9, 'Alimentaire'),
    (10, 'Alimentaire'),
    (11, 'Alimentaire'),
    (12, 'Alimentaire'),
    (13, 'Alimentaire'),
    (14, 'Alimentaire'),
    (15, 'Alimentaire'),
    (16, 'Alimentaire'),
    (17, 'Alimentaire'),
    (18, 'Alimentaire'),
    (19, 'Vêtements'),
    (20, 'Vêtements'),
    (21, 'Vêtements'),
    (22, 'Vêtements'),
    (23, 'Vêtements'),
    (24, 'Vêtements'),
    (25, 'Vêtements'),
    (26, 'Vêtements'),
    (27, 'Vêtements'),
    (28, 'Vêtements'),
    (29, 'Vêtements'),
    (30, 'Vêtements'),
    (31, 'Vêtements'),
    (32, 'Vêtements'),
    (33, 'Vêtements'),
    (34, 'Vêtements'),
    (35, 'Vêtements'),
    (36, 'Vêtements'),
    (37, 'Vêtements'),
    (38, 'Vêtements'),
    (39, 'Vêtements'),
    (40, 'Vêtements'),
    (41, 'Vêtements'),
    (42, 'Vêtements'),
    (43, 'Vêtements'),
    (44, 'Vêtements'),
    (45, 'Intérieur'),
    (46, 'Intérieur'),
    (47, 'Intérieur'),
    (48, 'Intérieur'),
    (49, 'Intérieur'),
    (50, 'Intérieur'),
    (51, 'Intérieur'),
    (52, 'Intérieur'),
    (53, 'Intérieur'),
    (54, 'Intérieur'),
    (55, 'Intérieur'),
    (56, 'Intérieur'),
    (57, 'Intérieur'),
    (58, 'Intérieur'),
    (59, 'Intérieur'),
    (60, 'Intérieur'),
    (61, 'Intérieur'),
    (62, 'Intérieur'),
    (63, 'Intérieur'),
    (64, 'Intérieur'),
    (65, 'Intérieur'),
    (66, 'Intérieur'),
    (67, 'Intérieur'),
    (68, 'Intérieur'),
    (69, 'Intérieur'),
    (70, 'Intérieur'),
    (71, 'Intérieur'),
    (72, 'Intérieur'),
    (73, 'Intérieur'),
    (74, 'Intérieur'),
    (75, 'Intérieur'),
    (76, 'Intérieur'),
    (77, 'Intérieur'),
    (78, 'Extérieur'),
    (79, 'Extérieur'),
    (80, 'Extérieur'),
    (81, 'Extérieur'),
    (82, 'Extérieur'),
    (83, 'Extérieur'),
    (84, 'Extérieur'),
    (85, 'Extérieur'),
    (86, 'Extérieur'),
    (87, 'Vêtements'),
    (89, 'Vêtements'),
    (90, 'Vêtements'),
    (91, 'Vêtements'),
    (92, 'Vêtements'),
    (93, 'Vêtements'),
    (94, 'Intérieur'),
    (95, 'Extérieur'),
    (96, 'Extérieur'),
    (97, 'Vêtements'),
    (98, 'Vêtements'),
    (99, 'Vêtements'),
    (100, 'Papeterie');

INSERT INTO
    Adresse (
        num,
        codePostal,
        nomVille,
        nomRue
    )
VALUES (
        10,
        '75001',
        'Paris',
        'Prad-land'
    ),
    (
        04,
        '69003',
        'Lyon',
        'Kergaradec'
    ),
    (
        22,
        '13001',
        'Marseille',
        'Plougastel'
    ),
    (
        01,
        '59000',
        'Lille',
        'Rue la bienfaisance'
    ),
    (
        07,
        '06000',
        'Nice',
        'Avenue de la libération'
    ),
    (
        15,
        '33000',
        'Bordeaux',
        'Rue de la forêt'
    ),
    (
        02,
        '33000',
        'Bordeaux',
        'Rue Edouard Branly'
    ),
    (
        19,
        '33000',
        'Bordeaux',
        'Le Quedel'
    ),
    (
        16,
        '22300',
        'Lannion',
        'Rue Jeanne d''Arc'
    ),
    (
        02,
        '29300',
        'Baye',
        'Rue du Saule'
    );

INSERT INTO
    AdrFactCli (codeCompte, idAdresse)
VALUES (1, 1),
    (2, 2),
    (3, 3),
    (4, 4),
    (9, 9),
    (10, 10);

INSERT INTO
    AdrSiegeSocial (codeCompte, idAdresse)
VALUES (5, 5),
    (6, 6),
    (7, 7),
    (8, 8);

insert into Panier (codeCompte, dateCreaP) VALUES (3, null);

insert into
    ProdUnitPanier (
        idPanier,
        codeProduit,
        qteProd
    )
VALUES (1, 1, 2),
    (1, 2, 2),
    (1, 4, 1),
    (1, 3, 1);

INSERT INTO
    Photo (urlPhoto)
VALUES (
        './img/photosProfil/Cunty.png'
    ),
    (
        './img/photosProfil/PDP_EU2.jpeg'
    ),
    (
        './img/photosProfil/PDP_tst.jpeg'
    ),
    (
        './img/photosProfil/PDP_BBl.jpeg'
    );

INSERT INTO
    Profil (urlPhoto, codeClient)
VALUES (
        './img/photosProfil/Cunty.png',
        1
    ),
    (
        './img/photosProfil/PDP_EU2.jpeg',
        2
    ),
    (
        './img/photosProfil/PDP_tst.jpeg',
        3
    ),
    (
        './img/photosProfil/PDP_BBl.jpeg',
        4
    );

insert into
    Avis (
        codeproduit,
        codecomptecli,
        noteprod,
        commentaire,
        datepublication
    )
VALUES (
        1,
        1,
        5,
        'J adore ce produit, il est vraiment bien, il est arrivé vite en plus',
        null
    ),
    (
        2,
        2,
        4,
        'Produit conforme à la description, satisfait de mon achat',
        null
    ),
    (
        5,
        3,
        2,
        'Le café n est pas à mon goût, je ne le rachèterai pas',
        null
    ),
    (
        4,
        3,
        1,
        'Aucune protection du produit dans le colis, il est arrivé abimé, je ne recommande pas ce vendeur',
        null
    ),
    (
        6,
        1,
        2,
        'Le produit est moyen',
        null
    ),
    (
        3,
        4,
        4,
        'Bon rapport qualité prix',
        null
    ),
    (
        5,
        2,
        5,
        'Excellent café, je le recommande vivement',
        null
    ),
    (
        7,
        3,
        3,
        'Le jean est correct mais la taille est un peu grande',
        null
    ),
    (
        10,
        4,
        4,
        'Casquette confortable et de bonne qualité',
        null
    ),
    (
        15,
        1,
        5,
        'Sac à dos super solide, très satisfait de mon achat',
        null
    ),
    (
        2,
        2,
        3,
        'J aime bien mais c est pas mon truc non plus',
        null
    );

insert into
    Carte (
        numCarte,
        nomTit,
        prenomTit,
        CVC,
        dateExp
    )
VALUES (
        '1234 5678 9123 4567',
        'test',
        'adalbert',
        '890',
        '2026-01-01'
    ),
    (
        '4567 1234 5678 9123',
        'Bernard',
        'Constance',
        '980',
        '2026-02-01'
    ),
    (
        '7890 1234 5678 9012',
        'Louennig',
        'frouad',
        '890',
        '2026-01-01'
    ),
    (
        '1357 9246 8014 7036',
        'Benoit',
        'Dubois',
        '980',
        '2026-02-01'
    ),
    (
        '0987 6543 2109 8765',
        'Johan',
        'titouan',
        '890',
        '2026-01-01'
    ),
    (
        '2567 2789 3688 2987',
        'vista',
        'dupond',
        '980',
        '2026-02-01'
    ),
    (
        '0789 6567 3456 2469',
        'Johan',
        'Chenonceau',
        '890',
        '2026-01-01'
    ),
    (
        '4356 8767 2590 3569',
        'Jean-christophe',
        'luhan',
        '980',
        '2026-02-01'
    ),
    (
        '3567 4790 3748 4789',
        'Gabin',
        'michel',
        '890',
        '2026-01-01'
    ),
    (
        '9876 7545 4345 2567',
        'cash',
        'visa',
        '980',
        '2026-02-01'
    );

insert into
    Commande (codeCompte, idCarte, dateCom)
VALUES (2, 2, '2025-11-23'),
    (1, 3, '2025-11-24'),
    (1, 1, '2025-11-25'),
    (1, 7, '2025-11-21'),
    (4, 8, '2025-11-19'),
    (4, 9, '2025-11-02'),
    (3, 6, '2025-11-06'),
    (2, 4, '2025-11-10'),
    (3, 5, '2025-11-15'),
    (2, 10, '2025-11-04');

insert into
    ProdUnitCommande (numCom, codeProduit, qteProd)
VALUES (1, 1, 2),
    (1, 2, 2),
    (1, 4, 1),
    (1, 5, 1),
    (2, 6, 1),
    (2, 8, 2),
    (3, 19, 2),
    (4, 76, 2),
    (4, 56, 2),
    (4, 34, 1),
    (5, 98, 2),
    (5, 34, 2),
    (5, 42, 1),
    (6, 23, 1),
    (6, 2, 1),
    (6, 92, 2),
    (7, 1, 2),
    (7, 53, 1),
    (7, 65, 1),
    (8, 1, 2),
    (8, 19, 1),
    (8, 94, 1),
    (9, 4, 2),
    (9, 3, 1),
    (9, 18, 1),
    (10, 23, 2),
    (10, 40, 1),
    (10, 67, 1);

insert into
    AdrLiv (numCom, idAdresse)
VALUES (1, 1),
    (2, 4),
    (3, 2),
    (4, 3),
    (5, 5),
    (6, 8),
    (7, 7),
    (8, 5),
    (9, 3),
    (10, 6);

select * from ProdUnitCommande;

select * from Commande;

select * from AdrLiv;

select * from adresse;

select * from Produit;

SELECT codeProduit
FROM ProdUnitCommande
WHERE
    numCom = 1
ORDER BY codeProduit
LIMIT 2;
--SELECT PUC.codeProduit FROM ProdUnitCommande PUC WHERE;
SELECT DISTINCT
    puc.numCom
FROM
    Produit p
    INNER JOIN ProdUnitCommande puc ON p.codeProduit = puc.codeProduit
where
    CodeCompteVendeur = 5
ORDER BY numCom;

SELECT * FROM alizon.Client;

SELECT * FROM alizon.Vendeur;

SELECT * FROM alizon.Panier;

select * from produit limit 1;

( SELECT AVG(noteProd) from Avis a where a.codeproduit = 5 );

select noteProd from avis where codeproduit = 5;

SELECT
    codeProduit,
    libelleProd,
    prixTTC,
    urlPhoto,
    noteMoy
FROM Produit
where
    Disponible = true
ORDER BY noteMoy DESC;

select * from avis where codeproduit = 1;

INSERT INTO alizon.Panier (prixTTCtotal) VALUES (0);

SELECT * FROM alizon.ProdUnitPanier;

SELECT
    codeProduit,
    libelleProd,
    prixTTC,
    urlPhoto
FROM Produit
where
    Disponible = true
ORDER BY prixTTC DESC;
SELECT SUM(prixHTtotal * qteprod) prixtotalht, SUM(prixttctotal * qteprod) prixtotalttc FROM alizon.ProdUnitCommande WHERE numCom = 1;
INSERT INTO alizon.ProdUnitCommande (codeProduit, numCom, qteProd) VALUES (3,1,2);
SELECT SUM(Produit.prixHT * PUC.qteprod) FROM alizon.ProdUnitCommande PUC INNER JOIN alizon.Produit ON PUC.codeProduit = Produit.codeProduit;
SELECT * FROM alizon.Commande WHERE numCom = 12;
UPDATE alizon.Commande SET bordereau = 20261032 WHERE numCom = 12;
SELECT * FROM alizon.ProdUnitCommande WHERE numCom = 1;

SELECT motif, commentaire, libelleprod, dateSignalement FROM alizon.Signalement
INNER JOIN alizon.Avis ON Signalement.numAvis = Avis.numAvis 
INNER JOIN alizon.Produit ON Avis.codeProduit = Produit.codeProduit;
--SELECT * FROM Produit WHERE unaccent(libelleProd) ILIKE unaccent('%pat%');
--SELECT profil.urlphoto, produit.libelleprod, client.pseudo, avis.noteprod, avis.commentaire FROM avis INNER JOIN produit ON (avis.codeproduit = produit.codeproduit) INNER JOIN client ON (avis.codecomptecli = client.codecompte) INNER JOIN profil ON (profil.codeclient = client.codecompte) ORDER BY avis.codeproduit;
--select SUM(prixttctotal) FROM ProdUnitPanier INTO Panier.prixTTCtotal;
--select * from ProdUnitPanier where idPanier = 1;
--select * from Panier where codecompte = 3;
--SELECT ALL count(*) from ProdUnitPanier where idPanier = 1;
--SELECT ALL codeProduit,qteprod from ProdUnitPanier where idPanier = 1;
--select * from client;
--select * from produit where codeproduit = 1;
--SELECT libelleProd,urlphoto,codecomptevendeur from Produit where codeProduit = 1;
--SELECT * from Vendeur where codecompte = 5;
--update ProdUnitPanier set qteProd = qteProd + 1 where idPanier = 1 AND codeProduit = 1;
--select all * from ProdUnitPanier where idPanier = 1;
--delete from ProdUnitPanier where idPanier = 1 and codeProduit = 1;
--select all * from ProdUnitPanier where idPanier = 1;
--delete from ProdUnitPanier where idPanier = 1;
--delete from  Panier where idPanier = 1;
--SELECT * FROM Categoriser;
--SELECT * FROM Categoriser where libelleCat = 'Alimentaire';