<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'quiz';
    private $user = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            exit;
        }
    }
}
?>
<?php
class User {
    private $db;
    private $conn;

    public function __construct($db) {
        $this->db = $db;
        $this->conn = $this->db->connect();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];

                if($user['role'] == 'admin'){

                header("Location: admin.php");
                }
                else {
                header("Location: index.php");
            }
                exit;
            } else {
                return "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();

    $user = new User($db);

    $message = $user->login($username, $password);

    if ($message) {
        echo $message;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" href="css/akila.css"> 
</head>
<body>
    <h2>Connexion</h2>

    <form action="connexion.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type="submit">Se connecter</button>

      
        
        <p>inscrivez vous ! <a href="inscription.php">S'inscrire </a></p>
    </form>

    <footer>
        <p>&copy; 2025 Quiz & Découverte. Tous droits réservés.</p>
    </footer>
</body>
</html>

