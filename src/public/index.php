<?php
// Démarrer la session
session_start();

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
$action = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

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
    echo "Controller '$controllerName' non trouvé";
}
?>