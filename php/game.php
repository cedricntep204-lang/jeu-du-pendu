<?php
require_once '../config.php';
include_once 'header.php';
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Définir les paramètres de jeu par difficulté
$vies_max = 7; 

$base_par_lettre = [
    'facile' => 10,
    'moyen' => 15,
    'difficile' => 20
];

$malus_par_erreur = [
    'facile' => 5,
    'moyen' => 7,
    'difficile' => 10
];

$bonus_victoire = [
    'facile' => 50,
    'moyen' => 100,
    'difficile' => 200
];

// Gestion du choix de difficulté
if (isset($_POST['difficulte'])) {
    $_SESSION['difficulte'] = $_POST['difficulte'];
    unset($_SESSION['jeu']); // Réinitialiser le jeu
}

$difficulte = $_SESSION['difficulte'] ?? 'facile';

// Initialisation d'une nouvelle partie
if (!isset($_SESSION['jeu']) || isset($_POST['nouvelle_partie'])) {
    // Récupérer un mot aléatoire de la base de données selon la difficulté
    $stmt = $db->prepare("SELECT id, mots FROM mots WHERE difficulte = ? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$difficulte]);
    $mot_data = $stmt->fetch();

    if ($mot_data) {
        $_SESSION['jeu'] = [
            'mot_id' => $mot_data['id'],
            'mot' => strtoupper($mot_data['mots']),
            'lettres_trouvees' => [],
            'lettres_testees' => [], // Toutes les lettres déjà testées
            'erreurs' => 0, // Nombre d'erreurs (0 à 7)
            'points' => 0,
            'difficulte' => $difficulte,
            'message' => ''
        ];
    }
}

$message = $_SESSION['jeu']['message'] ?? '';
$_SESSION['jeu']['message'] = ''; // Réinitialiser le message

// Traitement d'une lettre proposée
if (isset($_POST['lettre']) && !empty($_POST['lettre'])) {
    $lettre = strtoupper(trim($_POST['lettre'][0]));

    // Vérifier si la lettre a déjà été testée
    if (in_array($lettre, $_SESSION['jeu']['lettres_testees'])) {
        $message = "⚠️ Lettre déjà testée !";
    } else {
        // Ajouter la lettre aux lettres testées
        $_SESSION['jeu']['lettres_testees'][] = $lettre;

        // Vérifier si la lettre est dans le mot
        if (strpos($_SESSION['jeu']['mot'], $lettre) !== false) {
            // Lettre correcte : révéler TOUTES les occurrences
            $_SESSION['jeu']['lettres_trouvees'][] = $lettre;
            $nb_occurrences = substr_count($_SESSION['jeu']['mot'], $lettre);
            $points_gagnes = $nb_occurrences * $base_par_lettre[$difficulte];
            $_SESSION['jeu']['points'] += $points_gagnes;
            $message = "✅ Bonne lettre ! +{$points_gagnes} points ({$nb_occurrences} occurrence(s))";
        } else {
            // Lettre incorrecte : perte d'une vie
            $_SESSION['jeu']['erreurs']++;
            $_SESSION['jeu']['points'] -= $malus_par_erreur[$difficulte];
            if ($_SESSION['jeu']['points'] < 0) {
                $_SESSION['jeu']['points'] = 0;
            }
            $message = " Mauvaise lettre ! -{$malus_par_erreur[$difficulte]} points";
        }
    }
}

// Traitement d'un mot proposé
if (isset($_POST['mot_propose']) && !empty($_POST['mot_propose'])) {
    $mot_propose = strtoupper(trim($_POST['mot_propose']));

    if ($mot_propose === $_SESSION['jeu']['mot']) {
        // Mot correct : victoire immédiate + bonus
        $_SESSION['jeu']['lettres_trouvees'] = str_split($_SESSION['jeu']['mot']);
        $_SESSION['jeu']['points'] += $bonus_victoire[$difficulte];
        $message = " Mot correct ! Bonus de +{$bonus_victoire[$difficulte]} points !";
    } else {
        // Mot incorrect : perte de 2 vies
        $_SESSION['jeu']['erreurs'] += 2;
        $malus_total = $malus_par_erreur[$difficulte] * 2;
        $_SESSION['jeu']['points'] -= $malus_total;
        if ($_SESSION['jeu']['points'] < 0) {
            $_SESSION['jeu']['points'] = 0;
        }
        $message = "&#128148 Mauvais mot ! -2 vies et -{$malus_total} points";
    }
}

// Construire l'affichage du mot avec les lettres trouvées
$mot_affiche = '';
$mot_complet = true;
foreach (str_split($_SESSION['jeu']['mot']) as $lettre) {
    if (in_array($lettre, $_SESSION['jeu']['lettres_trouvees'])) {
        $mot_affiche .= $lettre . ' ';
    } else {
        $mot_affiche .= '_ ';
        $mot_complet = false;
    }
}

$jeu_termine = false;
$victoire = false;

// Vérifier victoire
if ($mot_complet) {
    $jeu_termine = true;
    $victoire = true;

    if (empty($message)) {
        $message = "🎉 Félicitations ! Vous avez trouvé le mot : " . $_SESSION['jeu']['mot'];
    }

    // Enregistrer la partie dans la base de données (une seule fois)
    if (!isset($_SESSION['jeu']['enregistre'])) {
        $stmt = $db->prepare("INSERT INTO jeux (user_id, mots_id, tentatives, victory, points) VALUES (?, ?, ?, 1, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_SESSION['jeu']['mot_id'],
            $_SESSION['jeu']['erreurs'],
            $_SESSION['jeu']['points']
        ]);
        $_SESSION['jeu']['enregistre'] = true;
    }
}

// Vérifier défaite (7 erreurs = pendu complet)
if ($_SESSION['jeu']['erreurs'] >= $vies_max) {
    $jeu_termine = true;
    $victoire = false;

    if (empty($message)) {
        $message = " Perdu ! Le mot était : " . $_SESSION['jeu']['mot'];
    }

    // Enregistrer la partie dans la base de données (une seule fois)
    if (!isset($_SESSION['jeu']['enregistre'])) {
        $stmt = $db->prepare("INSERT INTO jeux (user_id, mots_id, tentatives, victory, points) VALUES (?, ?, ?, 0, ?)");
        $stmt->execute([
            $_SESSION['user_id'],
            $_SESSION['jeu']['mot_id'],
            $_SESSION['jeu']['erreurs'],
            $_SESSION['jeu']['points']
        ]);
        $_SESSION['jeu']['enregistre'] = true;
    }
}

$vies_restantes = $vies_max - $_SESSION['jeu']['erreurs'];

?>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .game-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .game-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .game-header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .user-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info span {
            font-weight: bold;
            color: #333;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .difficulty-selector {
            text-align: center;
            margin-bottom: 30px;
        }

        .difficulty-selector form {
            display: inline-flex;
            gap: 10px;
        }

        .difficulty-btn {
            padding: 10px 20px;
            border: 2px solid #667eea;
            background-color: white;
            color: #667eea;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }

        .difficulty-btn:hover {
            background-color: #667eea;
            color: white;
        }

        .difficulty-btn.active {
            background-color: #667eea;
            color: white;
        }

        .game-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-box h3 {
            margin: 0 0 10px 0;
            font-size: 0.9em;
            opacity: 0.9;
        }

        .stat-box .value {
            font-size: 2em;
            font-weight: bold;
        }

        .word-display {
            text-align: center;
            font-size: 3em;
            letter-spacing: 10px;
            margin: 40px 0;
            font-weight: bold;
            color: #333;
            min-height: 80px;
        }

        .letter-input {
            text-align: center;
            margin: 30px 0;
        }

        .letter-input input {
            width: 80px;
            height: 80px;
            font-size: 2em;
            text-align: center;
            border: 3px solid #667eea;
            border-radius: 10px;
            text-transform: uppercase;
            margin-right: 10px;
        }

        .letter-input button {
            padding: 20px 40px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .letter-input button:hover {
            background-color: #764ba2;
        }

        .letters-used {
            margin: 30px 0;
        }

        .letters-used h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .letter-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .letter-badge {
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .letter-badge.correct {
            background-color: #28a745;
            color: white;
        }

        .letter-badge.incorrect {
            background-color: #dc3545;
            color: white;
        }

        .message {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 1.3em;
            font-weight: bold;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }

        .new-game-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        .new-game-btn:hover {
            background-color: #218838;
        }

        .hangman-drawing {
            text-align: center;
            margin: 20px 0;
        }

        .hangman-drawing img {
            max-width: 250px;
            height: auto;
        }

        .word-input {
            margin: 20px 0;
            text-align: center;
        }

        .word-input input[type="text"] {
            padding: 12px;
            font-size: 1.2em;
            border: 2px solid #667eea;
            border-radius: 8px;
            width: 60%;
            text-transform: uppercase;
            text-align: center;
        }

        .word-input button {
            padding: 12px 30px;
            font-size: 1.1em;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }

        .word-input button:hover {
            background-color: #5568d3;
        }

        .alert-message {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
        }

        .alert-message.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 2px solid #bee5eb;
        }
    </style>


<body>
    <div class="game-container">
        <div class="game-header">
            <h1>JEU DU PENDU</h1>
        </div>

        <div class="user-info">
            <span> Joueur : <?php echo htmlspecialchars($_SESSION['pseudo']); ?></span>
            <a href="logout.php" class="logout-btn">Déconnexion</a>
        </div>

        <!-- Sélecteur de difficulté -->
        <div class="difficulty-selector">
            <form method="POST">
                <button type="submit" name="difficulte" value="facile"
                        class="difficulty-btn <?php echo $difficulte === 'facile' ? 'active' : ''; ?>">
                        Facile
                </button>
                <button type="submit" name="difficulte" value="moyen"
                        class="difficulty-btn <?php echo $difficulte === 'moyen' ? 'active' : ''; ?>">
                        Moyen
                </button>
                <button type="submit" name="difficulte" value="difficile"
                        class="difficulty-btn <?php echo $difficulte === 'difficile' ? 'active' : ''; ?>">
                        Difficile
                </button>
            </form>
        </div>

        <!-- Statistiques du jeu -->
        <div class="game-stats">
            <div class="stat-box">
                <h3>VIES RESTANTES</h3>
                <div class="value"><?php echo $vies_restantes; ?> / <?php echo $vies_max; ?></div>
            </div>
            <div class="stat-box">
                <h3>POINTS</h3>
                <div class="value"><?php echo $_SESSION['jeu']['points']; ?></div>
            </div>
            <div class="stat-box">
                <h3>DIFFICULTÉ</h3>
                <div class="value"><?php echo strtoupper($difficulte); ?></div>
            </div>
        </div>

        <!-- Dessin du pendu -->
        <div class="hangman-drawing">
            <?php
            // Limiter l'affichage à pendu7.svg maximum
            $etape_pendu = min($_SESSION['jeu']['erreurs'], 7);
            ?>
            <img src="../source/pendu<?php echo $etape_pendu; ?>.svg"
                 alt="Pendu étape <?php echo $etape_pendu; ?>">
        </div>

        <!-- Affichage du mot -->
        <div class="word-display">
            <?php echo $mot_affiche; ?>
        </div>

        <!-- Message d'information -->
        <?php if (!empty($message)): ?>
            <div class="alert-message <?php echo (strpos($message, '✅') !== false || strpos($message, '🎉') !== false) ? 'success' : (strpos($message, '⚠️') !== false ? 'info' : 'error'); ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($jeu_termine): ?>
            <div class="message <?php echo $victoire ? 'success' : 'error'; ?>">
                <?php
                if ($victoire) {
                    echo "🎉 Félicitations ! Vous avez trouvé le mot : " . $_SESSION['jeu']['mot'];
                } else {
                    echo "😢 Perdu ! Le mot était : " . $_SESSION['jeu']['mot'];
                }
                ?>
            </div>
            <form method="POST">
                <button type="submit" name="nouvelle_partie" class="new-game-btn">🎮 Nouvelle Partie</button>
            </form>
        <?php else: ?>
            <!-- Saisie de lettre -->
            <form method="POST" class="letter-input">
                <input type="text" name="lettre" maxlength="1" pattern="[A-Za-z]"
                       required autofocus placeholder="?">
                <button type="submit">Proposer une lettre</button>
            </form>

            <!-- Proposition de mot complet -->
            <div class="word-input">
                <form method="POST" style="display: inline;">
                    <input type="text" name="mot_propose" pattern="[A-Za-z]+"
                           placeholder="Proposer le mot complet" required>
                    <button type="submit">Valider le mot</button>
                </form>
                <p style="font-size: 0.9em; color: #666; margin-top: 10px;">⚠️ Attention : un mot incorrect = - 2 vies !</p>
            </div>
        <?php endif; ?>

        <!-- Lettres testées -->
        <?php if (!empty($_SESSION['jeu']['lettres_testees'])): ?>
            <div class="letters-used">
                <h3>📝 Lettres testées :</h3>
                <div class="letter-list">
                    <?php
                    // Séparer les lettres correctes et incorrectes
                    foreach ($_SESSION['jeu']['lettres_testees'] as $lettre):
                        $is_correct = in_array($lettre, $_SESSION['jeu']['lettres_trouvees']);
                    ?>
                        <span class="letter-badge <?php echo $is_correct ? 'correct' : 'incorrect'; ?>">
                            <?php echo $lettre; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Bouton nouvelle partie (toujours disponible) -->
        <?php if (!$jeu_termine): ?>
            <form method="POST">
                <button type="submit" name="nouvelle_partie" class="new-game-btn"
                        style="background-color: #ffc107; margin-top: 30px;">
                     Recommencer
                </button>
            </form>
        <?php endif; ?>

        <!-- Lien vers le classement -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="classements.php" style="color: #667eea; text-decoration: none; font-weight: bold;">
                 Voir le classement
            </a>
        </div>
    </div>
</body>
