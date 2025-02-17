<?php
class Banniere
{
    private $bgColor;
    private $text;

    public function __construct($bgColor, $text)
    {
        $this->bgColor = $bgColor;
        $this->text = $text;
    }

    public function afficherBanniere()
    {
        echo '<div class="banniere" style="background-color: ' . $this->bgColor . ';">' . $this->text . '</div>';
    }
}

class Button
{
    private $label;
    private $url;
    private $class;

    public function __construct($label, $url, $class = 'btn')
    {
        $this->label = $label;
        $this->url = $url;
        $this->class = $class;
    }

    public function afficherButton()
    {
        echo '<a href="' . $this->url . '" class="' . $this->class . '">' . $this->label . '</a>';
    }
}

$bgColor = "#416D9B";
$text = "Quiz & Découverte";

$banniere = new Banniere($bgColor, $text);

$btnAjouter = new Button("Ajouter", "ajouter.php");
$btnModifier = new Button("Modifier", "modifier.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page avec Bannière et Boutons</title>
    <style>
        .banniere {
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
            margin-top: 50px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>

    <?php $banniere->afficherBanniere(); ?>

    <div class="container">
        <?php
            $btnAjouter->afficherButton();
            $btnModifier->afficherButton();
        ?>
    </div>

</body>
</html>