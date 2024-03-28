<!-- Router -->
<?php
// This is the front controller for the application 
$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';
// start db config 
switch ($request) {
        // Home pages
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;
    case '/home':
        require __DIR__ . $viewDir . 'home.php';
        break;
    case '/about':
        require __DIR__ . $viewDir . 'about.php';
        break;
    case '/admin':
        require __DIR__ . $viewDir . 'admin.php';
        break;
    case '/trips':
        require __DIR__ . $viewDir . 'trips.php';
        break;
    case '/profile':
        require __DIR__ . $viewDir . 'profile.php';
        break;
        // Login and Register
    case '/login':
        require __DIR__ . $viewDir . 'login/index.php';
        break;
    case '/reset-password':
        require __DIR__ . $viewDir . 'login/reset-password.php';
        break;
    case '/register':
        require __DIR__ . $viewDir . 'register/agency.php';
        break;
    case '/register/agency':
        require __DIR__ . $viewDir . 'register/agency.php';
        break;
    case '/register/customer':
        require __DIR__ . $viewDir . 'register/customer.php';
        break;
        // Agency pages
    case '/agency':
        require __DIR__ . $viewDir . 'agency/index.php';
        break;
    case '/agency/vehicles':
        require __DIR__ . $viewDir . 'agency/vehicles.php';
        break;
    case '/agency/circuits':
        require __DIR__ . $viewDir . 'agency/circuits.php';
        break;
    case '/agency/customers':
        require __DIR__ . $viewDir . 'agency/customers.php';
        break;
    case '/agency/trips':
        require __DIR__ . $viewDir . 'agency/trips.php';
        break;
        
    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
        break;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/public/favicon.svg" type="image/x-icon"/>
    <title>Travago</title>
</html>