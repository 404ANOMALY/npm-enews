<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-news</title>
    <link rel="stylesheet" href="View/style.css">
</head>
<body>
    <div class="container">
        <div class="top">
            <div>
                <img src="View/logo.png" alt="Image" class="logo">
            </div>
            <div>
                <h1>E-NEWS</h1>
                <h3>L'actualité polytechnicienne</h3>
            </div>
        </div>

        <div class="categories">
            <a href='index.php' class='category-link'>Accueil</a>
            <?php
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $categorie_id = $category['id'];
                        $libelle = $category['libelle'];
                        echo "<a href='index.php?categorie=$categorie_id' class='category-link'>$libelle</a>";
                    }
                } else {
                    echo "Aucune catégorie trouvée";
                }
            ?>
            <a href='View/serviceweb.php' class='category-link'>Service Web</a>
            <a href='View/authentification.php' class='category-link'>Se connecter</a>
        </div>


        <div class="news">
            <!-- Afficher le contenu de l'article -->
            <?php if ($currentArticleTitle && !empty($currentArticle)): ?>
                <div class="article">
                    <h2><?= $currentArticle['titre'] ?></h2>
                    <p><?= $currentArticle['contenu'] ?></p>
                </div>

            <!-- Affichage article par categorie-->
            <?php elseif (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article">
                        <h2><a href="index.php?titre=<?= urlencode($article['titre']) ?>"><?= $article['titre'] ?></a></h2>
                        <p><?= substr($article['contenu'], 0, 700) ?>...</p>
                    </div>
                <?php endforeach; ?>

            <!-- Affichage avec les boutons-->
            <?php elseif (!empty($currentArticle)): ?>
                <div class="latest-article">
                    <div class="article">
                        <h2><a href="index.php?titre=<?= urlencode($currentArticle['titre']) ?>"><?= $currentArticle['titre'] ?></a></h2>
                        <p><?= substr($currentArticle['contenu'], 0, 500) ?>...</p>
                    </div>

                    <div class="navigation">
                        <?php if (!empty($previousArticle)): ?>
                            <a href="index.php?id=<?= $previousArticle['id'] ?>" class="btn-prev">Précédent</a>
                        <?php endif; ?>

                        <?php if (!empty($nextArticle)): ?>
                            <a href="index.php?id=<?= $nextArticle['id'] ?>" class="btn-next">Suivant</a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <p>Pas de nouvelle actualité pour le moment, à bientôt...</p>
            <?php endif; ?>
        </div>
    </div>  
</body>
</html>
