<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-news - Authentification</title>
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

        <div class="authentif">
            <h2>Connexion</h2>
            <form method="post" action="authentification.php">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Se connecter</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];

                try {
                    // Création du client SOAP
                    $client = new SoapClient('http://127.0.0.1:8000/?wsdl', array('trace' => 1));

                    // Appel de la méthode d'authentification du service SOAP
                    $result = $client->authenticate(['username' => $username, 'password' => $password]);

                    if ($result) {
                        session_start();
                        $_SESSION['user'] = $result;
                        header('Location: admin_editor.php');
                        exit();
                    } else {
                        echo "<p>Nom d'utilisateur ou mot de passe incorrect.</p>";
                    }
                } catch (SoapFault $fault) {
                    echo "<p>Erreur : " . $fault->getMessage() . "</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
