<?php
$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';
var_dump($__DIR__);

switch ($request) {
    case '/home':
        require __DIR__ . $viewDir . 'home.php';
    case '/about':
        require __DIR__ . $viewDir . 'about.php';
    case '/admin':
        require __DIR__ . $viewDir . 'admin.php';

    case '//login':
        require __DIR__ . $viewDir . 'login.php';
    case '/register':
        require __DIR__ . $viewDir . 'login.php';
    case '/register/agency':
        require __DIR__ . $viewDir . 'register/customer.php';
    case '/register/customer':
        require __DIR__ . $viewDir . 'register/customer.php';

    case '/agency':
        require __DIR__ . $viewDir . 'agency/index.php';
    case '/customers':
        require __DIR__ . $viewDir . '/views/customers.php';
    case '/trips':
        require __DIR__ . '/views/trips.php';

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}
