<?php
session_start();

// Configuration de la base de données
$host = 'localhost';
$dbname = 'quiz';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
    exit;
}

// Classe pour la bannière
class Banniere
{
    private $bgColor;
    private $text;

    public function __construct($bgColor, $text)
    {
        $this->bgColor = $bgColor;
        $this->text = $text;
    }

    // Méthode pour afficher la bannière
    public function afficherBanniere()
    {
        echo '<div class="banniere" style="background-color: ' . $this->bgColor . ';">' . $this->text . '</div>';
    }
}

// Classe pour gérer les quiz
class Quiz
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupère toutes les questions
    public function getQuestions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM questions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Met à jour les questions et leurs réponses
    public function updateQuestions($questions, $reponses)
    {
        foreach ($questions as $index => $question) {
            if (!empty($question) && isset($reponses[$index])) {
                // Ici, on suppose que la table `questions` a une colonne `question_id` pour identifier chaque question
                $stmt = $this->pdo->prepare("UPDATE questions SET question_text = :question, reponses = :reponses WHERE question_id = :question_id");
                $stmt->execute([ 
                    ':question' => htmlspecialchars($question),
                    ':reponses' => htmlspecialchars($reponses[$index]),
                    ':question_id' => $index + 1 // Remplacez `question_id` par l'ID réel
                ]);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les Questions du Quiz</title>
    <style>
        /* Style de la bannière */
        .banniere {
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            flex-direction: column;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .question-block {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php 
    $bgColor = "#416D9B";
    $text = "Modifier les Questions du Quiz";

    $banniere = new Banniere($bgColor, $text);
    
    $banniere->afficherBanniere();
    ?>

    <form method="POST">
        <h1>Modifier les Questions du Quiz</h1>

        <?php
        $quiz = new Quiz($pdo);

        $questions = $quiz->getQuestions();

        foreach ($questions as $index => $question) : ?>
            <div class="question-block">
                <label for="question_<?php echo $index; ?>">Question <?php echo $index + 1; ?></label>

                <label for="reponse_<?php echo $index; ?>">Réponse (0 = Faux, 1 = Vrai)</label>
                <div class="radio-buttons">
                    <label>
                        <input type="radio" name="reponses[<?php echo $index; ?>]" value="1" <?php echo ($question['reponses'] == '1') ? 'checked' : ''; ?>> Vrai
                    </label>
                    <label>
                        <input type="radio" name="reponses[<?php echo $index; ?>]" value="0" <?php echo ($question['reponses'] == '0') ? 'checked' : ''; ?>> Faux
                    </label>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit">Mettre à jour les questions</button>
    </form>

</body>
</html>