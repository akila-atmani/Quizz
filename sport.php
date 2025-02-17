<?php
// Inclure le fichier Database.php pour accéder à la classe Database
include('database.php');

// Créer une instance de la classe Database
$db = new Database();
$conn = $db->connect();  // Se connecter à la base de données

// Insérer les questions dans la base de données (si elles ne sont pas déjà présentes)
try {
    // Vérifier si les questions sont déjà présentes dans la base de données
    $questions = [
        "Le football est le sport le plus populaire au monde ?",
        "Usain Bolt détient le record du monde du 100m ?",
        "Michael Jordan a joué pour les Los Angeles Lakers ?"
    ];

    foreach ($questions as $question) {
        $stmt = $conn->prepare("SELECT * FROM questions WHERE questions = :question");
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->execute();

        // Si la question n'existe pas, on l'insère
        if ($stmt->rowCount() == 0) {
            $insertStmt = $conn->prepare("INSERT INTO questions (questions) VALUES (:question)");
            $insertStmt->bindParam(':question', $question, PDO::PARAM_STR);
            $insertStmt->execute();
        }
    }
} catch (PDOException $e) {
    echo "Erreur lors de l'insertion des questions : " . $e->getMessage();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les réponses de l'utilisateur
    $reponse1 = isset($_POST['q1']) && $_POST['q1'] === 'Oui' ? 1 : 0;
    $reponse2 = isset($_POST['q2']) && $_POST['q2'] === 'Oui' ? 1 : 0;
    $reponse3 = isset($_POST['q3']) && $_POST['q3'] === 'Oui' ? 1 : 0;

    try {
        // Mise à jour des réponses dans la base de données pour l'utilisateur spécifique (par exemple id-user = 1)
        $stmt = $conn->prepare("UPDATE questions SET reponses = :reponse1 WHERE `id-user` = 1 AND questions = 'Le football est le sport le plus populaire au monde ?'");
        $stmt->bindParam(':reponse1', $reponse1, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 1 envoyée avec succès !<br>";
        }

        $stmt = $conn->prepare("UPDATE questions SET reponses = :reponse2 WHERE `id-user` = 1 AND questions = 'Usain Bolt détient le record du monde du 100m ?'");
        $stmt->bindParam(':reponse2', $reponse2, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 2 envoyée avec succès !<br>";
        }

        $stmt = $conn->prepare("UPDATE questions SET reponses = :reponse3 WHERE `id-user` = 1 AND questions = 'Michael Jordan a joué pour les Los Angeles Lakers ?'");
        $stmt->bindParam(':reponse3', $reponse3, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 3 envoyée avec succès !<br>";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion des réponses : " . $e->getMessage();
    }
}

// Fermer la connexion après utilisation
$db->disconnect();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Sport</title>
    <link rel="stylesheet" href="css/sport.css">
</head>
<body>
<header>
<h1>Quiz Sport</h1>

    </header>
    
   
    <nav>
        <a href="index.php">Accueil</a>
        <a href="connexion.php">Déconnexion</a>
    </nav>

    <form action="#sport.php" method="post">
        <div>
            <p>1. Le football est le sport le plus populaire au monde ?</p>
            <label>
                <input type="radio" name="q1" value="Oui"> Oui
            </label><br>
            <label>
                <input type="radio" name="q1" value="Non"> Non
            </label>
        </div>

        <div>
            <p>2. Usain Bolt détient le record du monde du 100m ?</p>
            <label>
                <input type="radio" name="q2" value="Oui"> Oui
            </label><br>
            <label>
                <input type="radio" name="q2" value="Non"> Non
            </label>
        </div>

        <div>
            <p>3. Michael Jordan a joué pour les Los Angeles Lakers ?</p>
            <label>
                <input type="radio" name="q3" value="Oui"> Oui
            </label><br>
            <label>
                <input type="radio" name="q3" value="Non"> Non
            </label>
        </div>

        <div>
            <button type="submit">Soumettre</button>
        </div>
    </form>


    <footer>
        <p>&copy; 2025 Quiz & Découverte. Tous droits réservés.</p>
    </footer>
</body>
</html>