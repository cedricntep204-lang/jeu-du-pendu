<DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css\resetCss.css">
    <link rel="stylesheet" href="css\main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
     <title >Document</title>
</head>

<body>
    <header>
        <h1>JEU DU PENDU</h1>
        <p> .....</p>
    </header>

    <main>
        <section class="login">
            <div class="left>
                 <h1><span>I</span>nscrition</h1>
                 <ing src="source/imageConnection.png" alt="Image de connexion">











            <h2>Connexion</h2>
            <form action="authenticate.php" method="POST" class="login-form">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
        </section>
    </main>