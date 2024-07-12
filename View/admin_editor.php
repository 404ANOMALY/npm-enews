<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-news - Espace Éditeur/Administrateur</title>
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
                <h3>L'actualité polytechnicienne</h3>
            </div>
        </div>

        <div class="articles">
            <h2>Articles</h2>
            <div class="article-list">
                <?php
                $url = 'http://localhost:8080/articles'; // URL de votre API REST

                // Fonction pour récupérer les données depuis l'API REST
                function fetchArticles($url) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    return json_decode($response, true);
                }

                // Récupérer les articles depuis l'API REST
                $articles = fetchArticles($url);

                // Afficher les articles récupérés
                if ($articles && !empty($articles)) {
                    foreach ($articles as $article) {
                        echo "<div class='article'>";
                        echo "<h3>{$article['titre']}</h3>";
                        echo "<p>{$article['contenu']}</p>";
                        echo "<div class='article-actions'>";
                        echo "<a href='modifierArticle.php?id={$article['id']}' class='edit-btn'>Modifier</a>";
                        echo " ";
                        echo "<a href='supprimerArticle.php?id={$article['id']}' class='delete-btn' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet article ?\");'>Supprimer</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun article trouvé.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
