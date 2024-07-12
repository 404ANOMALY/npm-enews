<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-news - Modifier un Article</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="top">
            <div>
                <img src="logo.png" alt="Image" class="logo">
            </div>
            <div>
                <h1>E-NEWS</h1>
                <h3>Modifier un article</h3>
            </div>
        </div>

        <div class="modifier-article">
            <h2>Modification d'article</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
                $articleId = $_GET['id'];

                // Récupérer les détails de l'article depuis l'API REST
                $url = "http://localhost:8080/articles/{$articleId}";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $article = json_decode($response, true);

                if ($article) {
            ?>
            <form method="post" action="modifierArticle.php">
                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= $article['titre'] ?>" required>
                
                <label for="contenu">Contenu :</label>
                <textarea id="contenu" name="contenu" rows="5" required><?= $article['contenu'] ?></textarea>
                
                <button type="submit">Enregistrer les modifications</button>
            </form>
            <?php
                } else {
                    echo "<p>Article non trouvé.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
