<?php
require_once 'Model/connexion.php';
require_once 'Controller/controllerPage.php';

$controller = new ControllerPage($conn);
$controller->index();
?>
