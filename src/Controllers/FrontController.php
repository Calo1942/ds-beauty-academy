<?php

namespace DsBeautyAcademy\Controllers;

class FrontController
{
    private $url;
    private $controller;
    private $method;
    private $params;
    private $defaultController = 'dashboard';

    // Constructor: procesa la URL
    public function __construct()
    {
        $this->url = $_GET['url'] ?? $this->defaultController;
        $this->parseUrl();
    }

    // Analiza la URL para separar controlador, método y parámetros
    private function parseUrl()
    {
        $url = explode('/', filter_var(rtrim($this->url, '/'), FILTER_SANITIZE_URL));
        $this->controller = $url[0] ?? $this->defaultController;
        $this->method = $url[1] ?? '';
        $this->params = array_slice($url, 2);
    }

    // Ejecuta la lógica principal: incluye el archivo del controlador
    public function run()
    {
        $controllerFile = __DIR__ . '/' . ucfirst($this->controller) . 'Controller.php';
        if (file_exists($controllerFile)) {
            require $controllerFile;
        } else {
            http_response_code(404);
            $errorView = __DIR__ . '/../views/error/404.php';
            if (file_exists($errorView)) {
                require $errorView;
            } else {
                echo "<h1>404 - Módulo no encontrado</h1>";
            }
            exit;
        }
    }
}