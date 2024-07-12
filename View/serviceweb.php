<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-news</title>
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
     
        <div class="soap-rest">
            <div class="soap">
                <h2>SOAP</h2>

            </div>

            <div class="rest">
                <h2>REST</h2>
                <p>Pour récupérer la liste de tous les articles en JSON <a href="http://localhost:8080/articles">cliquez ici</a> </p>
                <p>Pour récupérer la liste de tous les articles en XML <a href="http://localhost:8080/xml/articles">cliquez ici</a> </p>
                <br/>
                <p>Pour récupérer la liste des articles regroupés en catégories en JSON <a href="http://localhost:8080/articles/categorized">cliquez ici</a> </p>
                <p>Pour récupérer la liste des articles regroupés en catégories en XML <a href="http://localhost:8080/xml/articles/categorized">cliquez ici</a> </p>
                <br/>
                <p>Pour récupérer la liste des articles appartenant à une catégorie fournie par l’utilisateur en JSON <a href="http://localhost:8080/articles/category/{categoryID}">cliquez ici</a> </p>
                <p>Pour récupérer la liste des articles appartenant à une catégorie fournie par l’utilisateur en XML <a href="http://localhost:8080/xml/articles/category/{categoryID}">cliquez ici</a> </p>
            </div>
        </div>
        
    </div>  
</body>
</html>
