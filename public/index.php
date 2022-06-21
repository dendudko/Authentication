<title>Authentication</title>
<?php

use Controllers\Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once dirname(__DIR__) . "/vendor/autoload.php";
$loader = new FilesystemLoader(dirname(__DIR__) . "/src/templates");
$twig = new Environment($loader);
$controller = new Controller($twig);

if (isset($_COOKIE['login']) && isset($_COOKIE['lHash'])) {
    $controller->authorized();
} else {
    $controller->unauthorized();
}

?>
