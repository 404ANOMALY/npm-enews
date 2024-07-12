<?php
require_once __DIR__ . '/../Model/modelPage.php';
//Fais un copie sttpppp

class ControllerPage 
{
    private $model;

    public function __construct($db) 
    {
        $this->model = new ModelPage($db);
    }

    public function index() 
    {
        $categories = $this->model->getCategories();
        $categoryId = isset($_GET['categorie']) ? $_GET['categorie'] : null;
        $currentArticleId = isset($_GET['id']) ? $_GET['id'] : null;
        $currentArticleTitle = isset($_GET['titre']) ? $_GET['titre'] : null;

        if ($categoryId) // Afficher les articles de la catégorie
        {
            $articles = $this->model->getArticlesByCategory($categoryId);
            $currentArticle = null;
            $previousArticle = null;
            $nextArticle = null;
        } 

        elseif ($currentArticleTitle) // Afficher un article spécifique par son titre
        {
            $currentArticle = $this->model->getArticleByTitle($currentArticleTitle);
            $articles = [];
            $previousArticle = null;
            $nextArticle = null;
        } 

        elseif ($currentArticleId) // Afficher un article spécifique par son ID
        {
            $currentArticle = $this->model->getArticleById($currentArticleId);
            if ($currentArticle) {
                $dateCreation = $currentArticle['dateCreation'];
                $previousArticle = $this->model->getPreviousArticle($dateCreation);
                $nextArticle = $this->model->getNextArticle($dateCreation);
            } else {
                $previousArticle = null;
                $nextArticle = null;
            }
            $articles = [];
        } 

        else // Afficher l'article le plus récent
        {
            $currentArticle = $this->model->getLatestArticle();
            if ($currentArticle) 
            {
                $dateCreation = $currentArticle['dateCreation'];
                $previousArticle = $this->model->getPreviousArticle($dateCreation);
                $nextArticle = $this->model->getNextArticle($dateCreation);
            } 
            else 
            {
                $previousArticle = null;
                $nextArticle = null;
            }
            $articles = [];
        }

        require __DIR__ . '/../View/index.php';
    }
}
?>
