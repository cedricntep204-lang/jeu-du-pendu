 <?php
session_start();
include './BDD_conection.php';
include './head.php';
 
 $mysqlClient = new PDO(
  'mysql:host=localhost;dbname=jo;charset=utf8','root', '');

 try {
         $mysqlClient = new PDO('mysql:host=localhost;dbname=jo;charset=utf8','root', '');
 } catch (PDOException $e) {
     die($e->getMessage());
 }

// Connection
try {
    $mysqlClient = new PDO('mysql:host=localhost;dbname=jo;charset=utf8', 'root', '');
}    catch (PDOException $e) {
       die($e->getMessage());
}

// Requetes
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = $mysqlClient->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $query->bindParam(':username', $username);
    $query->bindParam(':password', $password);
    $query->execute();
}

if (isset($_POST['connect'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $mysqlClient->prepare("SELECT * FROM users WHERE username = :username");
    $query->bindParam(':username', $username);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo "Connexion réussie !";
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
// Fermeture de la connection
$mysqlClient = null; 
$dbh = null;

//?>
<h1>Inscription</h1>
<form method="post" action="./">
    <label>Username : </label>
    <input type="text" name="username"><br/>

    <label>Password : </label>
    <input type="password" name="password"><br/>

    <input type="submit" value="Valider" name="register">
</form>

<h1>Connexion</h1>
<form method="post" action="./">
    <label>Username : </label>
    <input type="text" name="username"><br/>

    <label>Password : </label>
    <input type="password" name="password"><br/>

    <input type="submit" value="Valider" name="connect">
</form>

<?php<?php
if (isset($_POST['register'])) {}
if (isset($_POST['connect'])) {
    $stmt = $mysqlClient->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(':username', $_POST['username']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['username'] = $_POST['username'];

        }

    }

    
}