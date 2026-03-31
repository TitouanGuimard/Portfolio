<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Installation - Projet Alizon</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }
        h1{
            color:#333;
        }
        h2{
            color:#444;
        }
        code{
            background:#eee;
            padding:5px;
            display:block;
            margin:10px 0;
        }
        .container{
            background:white;
            padding:30px;
            border-radius:10px;
            max-width:800px;
        }
        .back-button{
            margin-bottom:20px;
        }
        .back-button button{
            padding:10px 15px;
            font-size:14px;
            border:none;
            border-radius:6px;
            background-color:#333;
            color:white;
            cursor:pointer;
        }
        .back-button button:hover{
            background-color:#555;
        }
    </style>
</head>

<body>

<div class="container">

<div class="back-button">
<button onclick="history.back()">← Retour</button>
</div>

<h1>Projet Alizon</h1>

<h2>Installation</h2>

<h3>1. Installer PostgreSQL</h3>
<p>
Télécharger et installer PostgreSQL depuis le site officiel :
</p>
<p>
<a href="https://www.postgresql.org/" target="_blank">
https://www.postgresql.org/
</a>
</p>

<p>Pendant l'installation, retenir :</p>
<ul>
<li>le nom d'utilisateur (souvent <b>postgres</b>)</li>
<li>le mot de passe</li>
</ul>

<h3>2. Créer la base de données</h3>

<p>Créer une base appelée <b>alizon</b> avec la commande :</p>

<code>
CREATE DATABASE alizon;
</code>

<p>
Cela peut être fait avec pgAdmin ou avec le terminal.
</p>

<h3>3. Importer la base de données</h3>

<p>Dans le terminal, exécuter :</p>

<code>
psql -U postgres -d alizon -f database/alizon.sql
</code>

<p>
Cela va créer les tables et insérer les données nécessaires.
</p>

<h3>4. Lancer le site</h3>

<p>
Ouvrir le dossier du projet puis lancer le serveur web selon la technologie utilisée.
</p>

<p>Exemple :</p>

<code>
npm install
npm start
</code>

</div>

</body>
</html>