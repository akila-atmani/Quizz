<?php


class User {
    private $pdo;


    public function __construct($host, $dbname, $user, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            exit;
        }
    }

    public function checkEmailExists($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO user (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}

?>
<?php


$host = 'localhost';
$dbname = 'quiz';
$user = 'root';
$password = '';

$userClass = new User($host, $dbname, $user, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);

    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    if ($userClass->checkEmailExists($email)) {
        echo "Cet email est déjà utilisé.";
        exit;
    }

    if ($userClass->registerUser($username, $password, $email)) {
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: ../Quiz_web/connexion.php");
    } else {
        echo "Une erreur s'est produite lors de l'inscription. Essayez à nouveau.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="css/akila.css"> 
</head>
<body>
   
    <h2>Inscrivez-vous !</h2>
    
    <form action="inscription.php" method="POST">
        <label for="username">Nom :</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="email">Adresse email :</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br><br>

        <button type="submit">S'inscrire ! </button>
        
        <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </form>

    <footer>
        <p>&copy; 2025 Restaurant du Monde. Tous droits réservés.</p>
    </footer>
</body>
</html>
