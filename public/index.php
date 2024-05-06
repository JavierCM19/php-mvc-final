<?php 

declare(strict_types=1);

use Framework\Exceptions\PageNotFoundException;

// Catches error information then passes it to the ErrorException function
set_error_handler(function(
    int $errno,
    string $errstr,
    string $errfile,
    int $errline
): bool
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Calls the custom exception handler
set_exception_handler(function (Throwable $exception) {

    if ($exception instanceof Framework\Exceptions\PageNotFoundException) {

        http_response_code(404);

        $template = "404.php";

    } else {
    
        http_response_code(500);

        $template = "500.php";

    }

    $show_errors = false;

    if ($show_errors) {

        ini_set("display_errors", "1");

        require ROOT_PATH . "/views/$template";

    } else {

        ini_set("display_errors", "0");

        ini_set("log_errors", "1");

        require ROOT_PATH . "/views/$template";

    }

    throw $exception;

});


define("ROOT_PATH", dirname(__DIR__));

define("WEB_ROOT", "/webdev/HenryFord/php/php-mvc-app/public");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

spl_autoload_register(function (string $class_name) {

    require ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});

$dotenv = new Framework\Dotenv;

$dotenv->load(ROOT_PATH . "/.env");

$router = new Framework\Router;

$router->add(WEB_ROOT . "/home/index", ["controller" => "home", "action" => "index"]);
$router->add(WEB_ROOT . "/products", ["controller" => "products", "action" => "index"]);
$router->add(WEB_ROOT . "/", ["controller" => "home", "action" => "index"]);
$router->add(WEB_ROOT . "/{controller}/{id:\d+}/{action}");
$router->add(WEB_ROOT . "/{controller}/{action}");
// $router->add("webdev/HenryFord/php/php-mvc-app/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
// $router->add("webdev/HenryFord/php/php-mvc-app/products/show", ["controller" => "products", "action" => "show"]);

$params = $router->matchRoute($path);

if ($params === false) {
    throw new PageNotFoundException("No matching route for '$path'.");
}

if (!empty($params["id"])) {
    $id = $params["id"];
} 
else {
    $id = NULL;
}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);

$controller_object = new $controller;

$controller_object->$action($id);
?>