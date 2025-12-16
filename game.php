<?php

if (!isset($_SESSION['game'])) {
    $stmt = $pdo->prepare("SELECT word FROM words WHERE difficulty=? ORDER BY RAND() LIMIT 1");
    $stmt->execute([$_POST['difficulty']]);
    $word = $stmt->fetchColumn();

    $_SESSION['game'] = [
        'word' => $word,
        'found' => [],
        'tried' => [],
        'lives' => 6,
        'difficulty' => $_POST['difficulty']
    ];

    // Lettres révélées au début
    $reveal = ['easy'=>2,'medium'=>1,'hard'=>0][$_POST['difficulty']];
    $letters = array_unique(str_split($word));
    shuffle($letters);
    $_SESSION['game']['found'] = array_slice($letters, 0, $reveal);
}
?>
<?php

// proposer une lettre
if (isset($_POST['letter'])) {
    $letter = strtolower($_POST['letter']);
    if (!in_array($letter, $_SESSION['game']['tried'])) {
        $_SESSION['game']['tried'][] = $letter;
        if (strpos($_SESSION['game']['word'], $letter) !== false) {
            $_SESSION['game']['found'][] = $letter;
        } else {
            $_SESSION['game']['lives']--;
        }
    }
}

// proposer un mot
if (isset($_POST['word'])) {
    $word = strtolower($_POST['word']);
    if ($word === $_SESSION['game']['word']) {
        // Mot correct : révéler toutes les lettres
        $_SESSION['game']['found'] = array_unique(str_split($_SESSION['game']['word']));
    } else {
        // Mot incorrect : perdre une vie
        $_SESSION['game']['lives']--;
    }
}







?>
<h1> 🎮 Jeu du pendu</h1>

<!-- VIES -->
<p>Vies restantes : <?= $_SESSION['game']['lives'] ?></p>

<!-- MOT À DEVINER -->
<p class="mot">
<?php
foreach (str_split($_SESSION['game']['word']) as $lettre) {
    if (in_array($lettre, $_SESSION['game']['found'])) {
        echo $lettre . " ";
    } else {
        echo "_ ";
    }
}
?>
</p>

<!-- LETTRES DÉJÀ TESTÉES -->
<p>Lettres testées :
<?= implode(", ", $_SESSION['game']['tried']) ?>
</p>

<!-- PROPOSER UNE LETTRE -->
<form method="POST">
    <input type="text" name="letter" maxlength="1" required>
    <button>Proposer une lettre</button>
</form>

<!-- PROPOSER UN MOT -->
<form method="POST">
    <input type="text" name="word" placeholder="Proposer le mot entier">
    <button>Proposer le mot</button>
</form>

<!-- RECOMMENCER -->
<form action="reset.php">
    <button>Nouvelle partie</button>
</form>

</body>
</html>
