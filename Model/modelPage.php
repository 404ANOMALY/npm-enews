<?php
class ModelPage 
{
    // tu as sauvegardé la derniere version???
    private $conn;

    public function __construct($db) 
    {
        $this->conn = $db;
    }

    public function getCategories() 
    {
        $sql = "SELECT * FROM Categorie";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticlesByCategory($categoryId) 
    {
        $sql = "SELECT * FROM Article WHERE categorie = :categoryId ORDER BY dateCreation DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($id) 
    {
        if ($id) 
        {
            $sql = "SELECT * FROM Article WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } 
        else 
        {
            return $this->getLatestArticle();
        }
    }

    public function getLatestArticle() 
    {
        $sql = "SELECT * FROM Article ORDER BY dateCreation DESC LIMIT 1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPreviousArticle($dateCreation) 
    {
        $sql = "SELECT * FROM Article WHERE dateCreation < :dateCreation ORDER BY dateCreation DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':dateCreation', $dateCreation, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNextArticle($dateCreation) 
    {
        $sql = "SELECT * FROM Article WHERE dateCreation > :dateCreation ORDER BY dateCreation ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':dateCreation', $dateCreation, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getArticleByTitle($title) 
    {
        $sql = "SELECT * FROM Article WHERE titre = :title";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
