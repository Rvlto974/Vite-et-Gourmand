<?php
// Démarrer la session
session_start();

// Initialiser le système de traduction
require_once __DIR__ . '/../app/helpers/Language.php';
Language::init();

// Tracking analytics
require_once __DIR__ . '/../app/middleware/AnalyticsMiddleware.php';
AnalyticsMiddleware::track();

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/config/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Router simple
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Déterminer le controller et l'action
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$action = isset($url[1]) && !empty($url[1]) && !is_numeric($url[1]) ? $url[1] : 'index';

// Si le deuxième segment est numérique, c'est un ID pour l'action index
if (isset($url[1]) && is_numeric($url[1])) {
    $params = array_slice($url, 1);
} else {
    $params = array_slice($url, 2);
}

// Vérifier si le controller existe
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        echo "Action '$action' non trouvée dans $controllerName";
    }
} else {
    require_once __DIR__ . '/../app/controllers/ErrorController.php';
    $errorController = new ErrorController();
    $errorController->notFound();
}