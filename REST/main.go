package main

import (
	"rest/controllers"
	"rest/database"

	"github.com/gin-gonic/gin"
)

func main() {
	r := gin.Default()

	database.Connect()

	// Groupe de routes pour JSON
	jsonGroup := r.Group("/articles")
	{
		jsonGroup.GET("", controllers.GetAllArticlesJSON)
		jsonGroup.GET("/categorized", controllers.GetCategorizedArticlesJSON)
		jsonGroup.GET("/category/:categoryID", controllers.GetArticlesByCategoryJSON)
	}

	// Groupe de routes pour XML
	xmlGroup := r.Group("/xml/articles")
	{
		xmlGroup.GET("", controllers.GetAllArticlesXML)
		xmlGroup.GET("/categorized", controllers.GetCategorizedArticlesXML)
		xmlGroup.GET("/category/:categoryID", controllers.GetArticlesByCategoryXML)
	}

	r.Run(":8080") // Démarre le serveur sur le port 8080
}
