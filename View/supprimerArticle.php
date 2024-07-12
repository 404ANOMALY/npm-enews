<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $articleId = $_GET['id'];

    // Utilisez cURL pour effectuer une requête DELETE à votre API REST
    $url = "http://localhost:8080/articles/{$articleId}";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    // Vérifiez la réponse de l'API pour confirmer la suppression
    if ($responseData && $responseData['success']) {
        // Redirection vers admin_editor.php après la suppression réussie
        header('Location: admin_editor.php');
        exit();
    } else {
        echo "<p>Erreur lors de la suppression de l'article.</p>";
    }
}
?>
