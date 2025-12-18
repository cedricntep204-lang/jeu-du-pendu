<?php
require_once '../config.php';
include_once 'header.php';

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation des champs
    if (empty($pseudo) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Vérifier si le pseudo ou l'email existe déjà
        $stmt = $db->prepare("SELECT id FROM users WHERE pseudo = ? OR email = ?");
        $stmt->execute([$pseudo, $email]);

        if ($stmt->fetch()) {
            $error = "Ce pseudo ou cet email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $db->prepare("INSERT INTO users (pseudo, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$pseudo, $email, $hashedPassword])) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<body>
    <div class="form-container">
        <h2>Inscription</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Mail</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>

        <div class="form-footer"> Vous avez déjà un compte ? <a href="login.php">Se connecter</a></div>
    </div>
</body>


    <style>
        body {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #f5f5f5;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8em;
            color: #333;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #17a2b8;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #138496;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .form-footer a {
            color: #17a2b8;
            text-decoration: none;
            font-weight: bold;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>