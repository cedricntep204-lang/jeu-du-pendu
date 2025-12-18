<?php
session_start();
?>

    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #9a8ce1ff 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .rules-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h1 {
            color: white;
            font-size: 3em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        .header-section p {
            color: white;
            font-size: 1.2em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 30px;
            background-color: white;
            color: #f5576c;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: inline-block;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background-color: #fff;
        }

        .rules-section {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .rules-section h2 {
            color: #f5576c;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #f5576c;
            padding-bottom: 10px;
        }

        .rules-section h3 {
            color: #f5576c;
            font-size: 1.5em;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .rules-section p, .rules-section ul {
            color: #333;
            font-size: 1.1em;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .rules-section ul {
            list-style: none;
            padding-left: 0;
        }

        .rules-section ul li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }

        .rules-section ul li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #f5576c;
            font-weight: bold;
            font-size: 1.3em;
        }

        .difficulty-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .difficulty-table th,
        .difficulty-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .difficulty-table th {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .difficulty-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .badge.facile {
            background-color: #28a745;
            color: white;
        }

        .badge.moyen {
            background-color: #ffc107;
            color: #333;
        }

        .badge.difficile {
            background-color: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2em;
            }

            .rules-section {
                padding: 20px;
            }

            .difficulty-table {
                font-size: 0.85em;
            }
        }


    </style>

<body>
    <div class="rules-container">
        <!-- En-tête -->
        <div class="header-section">
            <h1>RÈGLES DU JEU</h1>
            <p>Découvrez comment jouer au Jeu du Pendu</p>
        </div>

        <!-- Boutons de navigation -->
        <div class="nav-buttons">
            <a href="../index.php" class="nav-btn">🏠 Accueil</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="game.php" class="nav-btn">🎮 Jouer</a>
                <a href="classements.php" class="nav-btn">🏆 Classement</a>
                <a href="logout.php" class="nav-btn">🚪 Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn">🔐 Connexion</a>
                <a href="register.php" class="nav-btn">📝 Inscription</a>
            <?php endif; ?>
        </div>

        <!-- Objectif du jeu -->
        <div class="rules-section">
            <h2>🎯 Objectif du Jeu</h2>
            <p>Le but du jeu est de <strong>deviner un mot caché</strong> en proposant des lettres ou le mot complet avant que le pendu ne soit complètement dessiné.</p>
            <p>Vous disposez de 7 vies pour trouver le mot !</p>
        </div>

        <!-- Comment jouer -->
        <div class="rules-section">
            <h2>🎮 Comment Jouer</h2>

            <h3>1️⃣ Choisir une difficulté</h3>
            <p>Avant de commencer, sélectionnez un niveau de difficulté :</p>
            <ul>
                <li><span class="badge facile">Facile</span> : Mots courts et simples</li>
                <li><span class="badge moyen">Moyen</span> : Mots de difficulté moyenne</li>
                <li><span class="badge difficile">Difficile</span> : Mots longs et complexes</li>
            </ul>

            <h3>2️⃣ Proposer des lettres</h3>
            <p><strong>✅ Lettre correcte :</strong> Toutes les occurrences de cette lettre sont révélées dans le mot.</p>
            <p><strong>❌ Lettre incorrecte :</strong> Vous perdez 1 vie et des points.</p>
            <p><strong>⚠️ Lettre déjà testée :</strong> Aucune pénalité ! Vous ne perdez pas de vie.</p>

            <h3>3️⃣ Proposer le mot complet</h3>
            <p><strong>✅ Mot correct :</strong> Victoire immédiate + bonus de points !</p>
            <p><strong>❌ Mot incorrect :</strong> Vous perdez 2 vies</span> et des points.</p>

            <h3>4️⃣ Conditions de victoire et défaite</h3>
            <p><strong>🎉 Victoire :</strong> Vous trouvez toutes les lettres du mot ou devinez le mot complet.</p>
            <p><strong>😢 Défaite :</strong> Vous atteignez 7 erreurs (le pendu est complètement dessiné).</p>
        </div>

        <!-- Système de points -->
        <div class="rules-section">
            <h2>💰 Système de Points</h2>
            <p>Chaque difficulté a son propre système de points :</p>

            <table class="difficulty-table">
                <thead>
                    <tr>
                        <th>Difficulté</th>
                        <th>Points par lettre</th>
                        <th>Malus par erreur</th>
                        <th>Bonus victoire</th>
                        <th>Malus mot incorrect</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge facile">Facile</span></td> <!-- span pour styliser un mot precis -->
                        <td>+10 points</td>
                        <td>-5 points</td>
                        <td>+50 points</td>
                        <td>-10 points (2 vies)</td>
                    </tr>
                    <tr>
                        <td><span class="badge moyen">Moyen</span></td>
                        <td>+15 points</td>
                        <td>-7 points</td>
                        <td>+100 points</td>
                        <td>-14 points (2 vies)</td>
                    </tr>
                    <tr>
                        <td><span class="badge difficile">Difficile</span></td>
                        <td>+20 points</td>
                        <td>-10 points</td>
                        <td>+200 points</td>
                        <td>-20 points (2 vies)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

