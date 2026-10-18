<?php

namespace DsBeautyAcademy\Controllers;

class FrontController
{
    private $url;
    private $controller;
    private $method;
    private $params;
    private $defaultController = 'dashboard';

    // Matriz de permisos RBAC por Rol según el Mapa Navegacional
    private $permissionsByRole = [
        'Administrador' => [
            'dashboard', 'curso', 'recurso', 'clase', 'estudiante',
            'instructor', 'diploma', 'especialidad', 'matricula',
            'evaluacion', 'certificado', 'pago', 'comprobante_pago',
            'banco', 'reporte', 'configuracion'
        ],
        'Instructor' => [
            'dashboard', 'curso', 'clase', 'evaluacion', 'diploma'
        ],
        'Estudiante' => [
            'dashboard', 'curso', 'clase', 'evaluacion', 'certificado'
        ]
    ];

    // Mapeo especial de nombres de rutas hacia archivos de controlador
    private $routeMap = [
        'pago'             => 'ComprobantePagoController.php',
        'comprobante_pago' => 'ComprobantePagoController.php',
        'login'            => 'LoginController.php',
        'logout'           => 'LoginController.php',
    ];

    public function __construct()
    {
        $this->url = $_GET['url'] ?? $this->defaultController;
        $this->parseUrl();
    }

    private function parseUrl()
    {
        $url = explode('/', filter_var(rtrim($this->url, '/'), FILTER_SANITIZE_URL));
        $this->controller = strtolower($url[0] ?? $this->defaultController);
        $this->method = $url[1] ?? '';
        $this->params = array_slice($url, 2);
    }

    public function run()
    {
        $controllerName = $this->controller;
        $isLogout = ($controllerName === 'logout' || ($controllerName === 'login' && ($_GET['action'] ?? '') === 'logout'));

        // 1. Si no hay sesión iniciada y no está en login ni logout, redirigir a login
        if (!in_array($controllerName, ['login', 'logout']) && empty($_SESSION['usuario_id'])) {
            header('Location: ?url=login');
            exit;
        }

        // 2. Si ya está autenticado e intenta ir a login (salvo que sea logout), redirigir al dashboard
        if ($controllerName === 'login' && !empty($_SESSION['usuario_id']) && !$isLogout) {
            header('Location: ?url=dashboard');
            exit;
        }

        // 3. Control de permisos RBAC para rutas privadas (excluyendo login y logout)
        if (!in_array($controllerName, ['login', 'logout'])) {
            $userRole = $_SESSION['rol'] ?? 'Invitado';
            $allowedRoutes = $this->permissionsByRole[$userRole] ?? [];

            // Si el rol no tiene la ruta permitida
            if (!in_array($controllerName, $allowedRoutes, true)) {
                http_response_code(403);
                $error403 = __DIR__ . '/../views/error/403.php';
                if (file_exists($error403)) {
                    require $error403;
                } else {
                    echo "<h2 style='color:#fff;text-align:center;padding-top:50px;'>403 - Acceso Denegado: Tu rol ($userRole) no tiene permisos para este módulo.</h2>";
                }
                exit;
            }
        }

        // 4. Despacho del controlador
        if (isset($this->routeMap[$controllerName])) {
            $controllerFile = __DIR__ . '/' . $this->routeMap[$controllerName];
        } else {
            $controllerFile = __DIR__ . '/' . ucfirst($this->controller) . 'Controller.php';
        }

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