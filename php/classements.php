<?php
session_start();
require_once '../config.php';

// Statistiques globales
$stats_query = $db->query("
    SELECT
        COUNT(*) as total_parties,
        SUM(victory) as total_victoires,
        MAX(points) as meilleur_score
    FROM jeux
");
$stats = $stats_query->fetch();

// Classement par joueur (total des points)
$classement_joueurs = $db->query("
    SELECT
        u.pseudo,
        COUNT(j.id) as nb_parties,
        SUM(j.victory) as nb_victoires,
        SUM(j.points) as total_points,
        MAX(j.points) as meilleur_score
    FROM users u
    LEFT JOIN jeux j ON u.id = j.user_id
    GROUP BY u.id, u.pseudo
    HAVING nb_parties > 0
    ORDER BY total_points DESC
    LIMIT 10
")->fetchAll();

?>

    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #9a8ce1ff 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .classement-container {
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card .value {
            color: #f5576c;
            font-size: 2.5em;
            font-weight: bold;
        }

        .classement-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .classement-section h2 {
            color: #f5576c;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .classement-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        .classement-table thead {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .classement-table th {
            padding: 15px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 1px;
        }

        .classement-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }



        .classement-table tbody tr {
            transition: background-color 0.3s;
        }

        .classement-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .rank {
            font-size: 1.5em;
            font-weight: bold;
            width: 60px;
            text-align: center;
        }

        .rank.gold {
            color: #FFD700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .rank.silver {
            color: #C0C0C0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .rank.bronze {
            color: #CD7F32;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
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

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 1.2em;
        }

        @media (max-width: 768px) { /*si la largeur de l'écran est de 768 pixels ou moins...*/
            .header-section h1 {
                font-size: 2em;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .classement-table {
                font-size: 0.85em;
            }

            .classement-table th,
            .classement-table td {
                padding: 10px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="classement-container">
        <!-- En-tête -->
        <div class="header-section">
            <h1>🏆CLASSEMENT</h1>
            <p>Les meilleurs joueurs du Pendu</p>
        </div>

        <!-- Boutons de navigation -->
        <div class="nav-buttons">
            <a href="../index.php" class="nav-btn">🏠 Accueil</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="game.php" class="nav-btn">🎮 Jouer</a>
                <a href="logout.php" class="nav-btn">🚪 Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="nav-btn">🔐 Connexion</a>
            <?php endif; ?>
        </div>

        <!-- Statistiques globales -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de parties</h3>
                <div class="value"><?php echo number_format($stats['total_parties']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Victoires</h3>
                <div class="value"><?php echo number_format($stats['total_victoires']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Meilleur score</h3>
                <div class="value"><?php echo number_format($stats['meilleur_score']); ?></div>
            </div>
        </div>

        <!-- Classement par joueur -->
        <div class="classement-section">
            <h2>👥 Classement des Joueurs</h2>
            <?php if (count($classement_joueurs) > 0): ?>
                <table class="classement-table">
                    <thead>
                        <tr>
                            <th>Rang</th>
                            <th>Joueur</th>
                            <th>Parties jouées</th>
                            <th>Victoires</th>
                            <th>Total points</th>
                            <th>Meilleur score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classement_joueurs as $index => $joueur):
                            $rank_class = '';
                            if ($index === 0) $rank_class = 'gold';
                            elseif ($index === 1) $rank_class = 'silver';
                            elseif ($index === 2) $rank_class = 'bronze';

                            $medal = '';
                            if ($index === 0) $medal = '🥇';
                            elseif ($index === 1) $medal = '🥈';
                            elseif ($index === 2) $medal = '🥉';
                        ?>
                            <tr>
                                <td class="rank <?php echo $rank_class; ?>">
                                    <?php echo $medal ? $medal : ($index + 1); ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($joueur['pseudo']); ?></strong></td>
                                <td><?php echo $joueur['nb_parties']; ?></td>
                                <td><?php echo $joueur['nb_victoires']; ?></td>
                                <td><strong><?php echo number_format($joueur['total_points']); ?></strong></td>
                                <td><?php echo number_format($joueur['meilleur_score']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                     Aucun joueur n'a encore joué. Soyez le premier !
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>