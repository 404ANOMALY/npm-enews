package controllers

import (
	"encoding/xml"
	"net/http"
	"time"

	"rest/database"
	"rest/models"

	"github.com/gin-gonic/gin"
)

// NullTime représente une structure pour gérer les valeurs de temps nulles
type NullTime struct {
	Time  time.Time
	Valid bool // true si le temps est non NULL
}

// Scan analyse une valeur de la base de données en temps nul
func (nt *NullTime) Scan(value interface{}) error {
	if value == nil {
		nt.Time, nt.Valid = time.Time{}, false
		return nil
	}
	nt.Valid = true
	return convertAssign(&nt.Time, value)
}

func convertAssign(dest, src interface{}) error {
	switch d := dest.(type) {
	case *time.Time:
		switch s := src.(type) {
		case time.Time:
			*d = s
		case []byte:
			*d = parseDateTime(string(s))
		case string:
			*d = parseDateTime(s)
		}
	}
	return nil
}

func parseDateTime(str string) time.Time {
	t, _ := time.Parse("2006-01-02 15:04:05", str)
	return t
}

// GetAllArticlesJSON récupère tous les articles et les renvoie en JSON
func GetAllArticlesJSON(c *gin.Context) {
	rows, err := database.DB.Query("SELECT id, titre, contenu, dateCreation, dateModification, categorie FROM article")
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var articles []models.Article
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		articles = append(articles, article)
	}

	c.JSON(http.StatusOK, articles)
}

// GetArticlesByCategoryJSON récupère les articles par catégorie et les renvoie en JSON
func GetArticlesByCategoryJSON(c *gin.Context) {
	categoryID := c.Param("categoryID")

	rows, err := database.DB.Query("SELECT id, titre, contenu, dateCreation, dateModification, categorie FROM article WHERE categorie = ?", categoryID)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var articles []models.Article
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		articles = append(articles, article)
	}

	c.JSON(http.StatusOK, articles)
}

// GetCategorizedArticlesJSON récupère les articles catégorisés et les renvoie en JSON
func GetCategorizedArticlesJSON(c *gin.Context) {
	rows, err := database.DB.Query(`
        SELECT a.id, a.titre, a.contenu, a.dateCreation, a.dateModification, a.categorie, c.libelle
        FROM article a
        JOIN categorie c ON a.categorie = c.id
    `)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	categorizedArticles := make(map[string][]models.Article)
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		var libelle string
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie, &libelle); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		categorizedArticles[libelle] = append(categorizedArticles[libelle], article)
	}

	c.JSON(http.StatusOK, categorizedArticles)
}

// GetAllArticlesXML récupère tous les articles et les renvoie en XML
func GetAllArticlesXML(c *gin.Context) {
	rows, err := database.DB.Query("SELECT id, titre, contenu, dateCreation, dateModification, categorie FROM article")
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var articles []models.Article
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		articles = append(articles, article)
	}

	xmlData, xmlErr := xml.MarshalIndent(articles, "", "  ")
	if xmlErr != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": xmlErr.Error()})
		return
	}
	c.Data(http.StatusOK, "application/xml; charset=utf-8", xmlData)
}

// GetArticlesByCategoryXML récupère les articles par catégorie et les renvoie en XML
func GetArticlesByCategoryXML(c *gin.Context) {
	categoryID := c.Param("categoryID")

	rows, err := database.DB.Query("SELECT id, titre, contenu, dateCreation, dateModification, categorie FROM article WHERE categorie = ?", categoryID)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	var articles []models.Article
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		articles = append(articles, article)
	}

	xmlData, xmlErr := xml.MarshalIndent(articles, "", "  ")
	if xmlErr != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": xmlErr.Error()})
		return
	}
	c.Data(http.StatusOK, "application/xml; charset=utf-8", xmlData)
}

// GetCategorizedArticlesXML récupère les articles catégorisés et les renvoie en XML
func GetCategorizedArticlesXML(c *gin.Context) {
	rows, err := database.DB.Query(`
        SELECT a.id, a.titre, a.contenu, a.dateCreation, a.dateModification, a.categorie, c.libelle
        FROM article a
        JOIN categorie c ON a.categorie = c.id
    `)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	defer rows.Close()

	categorizedArticles := make(map[string][]models.Article)
	for rows.Next() {
		var article models.Article
		var dateCreation, dateModification NullTime
		var libelle string
		if err := rows.Scan(&article.ID, &article.Titre, &article.Contenu, &dateCreation, &dateModification, &article.Categorie, &libelle); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		article.DateCreation = dateCreation.Time
		article.DateModification = dateModification.Time
		categorizedArticles[libelle] = append(categorizedArticles[libelle], article)
	}

	xmlData, xmlErr := xml.MarshalIndent(categorizedArticles, "", "  ")
	if xmlErr != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": xmlErr.Error()})
		return
	}
	c.Data(http.StatusOK, "application/xml; charset=utf-8", xmlData)
}
