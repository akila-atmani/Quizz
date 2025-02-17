<?php
// Inclure le fichier Database.php pour accéder à la classe Database
include('Database.php');

// Créer une instance de la classe Database
$db = new Database();
$conn = $db->connect();  // Se connecter à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les réponses de l'utilisateur
    $reponse1 = isset($_POST['reponse1']) ? (int)$_POST['reponse1'] : 0;
    $reponse2 = isset($_POST['reponse2']) ? (int)$_POST['reponse2'] : 0;
    $reponse3 = isset($_POST['reponse3']) ? (int)$_POST['reponse3'] : 0;

    try {
        // Si tu veux mettre à jour la colonne 'reponse' pour un utilisateur spécifique (par exemple id-user = 1)
        // Mise à jour pour la question 1
        $stmt = $conn->prepare("UPDATE questions SET reponse = :reponse1 WHERE `id-user` = 1 AND question = 'Les Misérables'");
        $stmt->bindParam(':reponse1', $reponse1, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 1 envoyée avec succès !<br>";
        }

        // Mise à jour pour la question 2
        $stmt = $conn->prepare("UPDATE questions SET reponse = :reponse2 WHERE `id-user` = 1 AND question = 'La première guerre mondiale a commencé en 1914'");
        $stmt->bindParam(':reponse2', $reponse2, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 2 envoyée avec succès !<br>";
        }

        // Mise à jour pour la question 3
        $stmt = $conn->prepare("UPDATE questions SET reponse = :reponse3 WHERE `id-user` = 1 AND question = 'Albert Einstein a inventé la théorie de la relativité'");
        $stmt->bindParam(':reponse3', $reponse3, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Réponse pour la question 3 envoyée avec succès !<br>";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
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
    <title>Quiz Culture Générale</title>
    <link rel="stylesheet" href="css/culture.css">
</head>
<body>
    <header> <h1>Quiz Culture Générale</h1>
</header>
<nav>
        <a href="index.php">Accueil</a>
        <a href="connexion.php">Déconnexion</a>
    </nav>
   

    <form action="culture.php" method="post">
        <div>
            <p>1. "Les Misérables" a été écrit par Victor Hugo ?</p>
            <label>
                <input type="radio" name="q1" value="Oui"> Oui
            </label><br>
            <label>
                <input type="radio" name="q1" value="Non"> Non
            </label>
        </div>

        <div>
            <p>2. La première guerre mondiale a commencé en 1914 ?</p>
            <label>
                <input type="radio" name="q2" value="Oui"> Oui
            </label><br>
            <label>
                <input type="radio" name="q2" value="Non"> Non
            </label>
        </div>

        <div>
            <p>3. Albert Einstein a inventé la théorie de la relativité ?</p>
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