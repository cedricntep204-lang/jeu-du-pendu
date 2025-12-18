<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LE PENDU </title>
    
    <link rel="stylesheet" href="/jeu-du-pendu/css/header.css">
    <link rel="stylesheet" href="/jeu-du-pendu/css/main.css">
    
</head>

<body>
    <div class="titre">
        <h1> LE PENDU </h1>
    </div>
    <div class="Slogan">
        <h2> Les Mots Ne Tiennent Qu'à Un Fil </h2>
    </div>

    <div class="image-homme-idee"><img src="/jeu-du-pendu/source/Homme_idee.jpg"></div>
    <div class="image-corde"><img src="/jeu-du-pendu/source/cpendu"></div>

    <div class="menu-container">
        <button class="menu-button" id="Nouvelle-partie">Nouvelle partie</button>
        <button class="menu-button" id="Voir-le-classement">Voir le classement</button>
        <button class="menu-button" id="Règles du jeu">Règles du jeu</button>
    </div>

    <script>
        // Redirection vers register.php
        document.getElementById('Nouvelle-partie').addEventListener('click', function() {
            window.location.href = '/jeu-du-pendu/php/register.php';
        });

        // Redirection vers classements.php
        document.getElementById('Voir-le-classement').addEventListener('click', function() {
            window.location.href = '/jeu-du-pendu/php/classements.php';
        });

        // Redirection vers rules.php
        document.getElementById('Règles du jeu').addEventListener('click', function() {
            window.location.href = '/jeu-du-pendu/php/rules.php';
        });
    </script>
    
    </html>