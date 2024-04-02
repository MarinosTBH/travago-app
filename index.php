<?php
ini_set('display_errors', 1);
// This is the front controller for the application 
$request = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query string from request URI
$viewDir = 'views/';

// Define routes with regular expressions to capture dynamic parts
$routes = array(
    '' => array(
        'view' => 'home.php',
        'pattern' => '/^\/\/?$/'
    ),
    '/home' => array(
        'view' => 'home.php',
        'pattern' => '/^\\/home$/'
    ),
    '/about' => array(
        'view' => 'about.php',
        'pattern' => '/^\\/about$/'
    ),
    '/admin' => array(
        'view' => 'admin.php',
        'pattern' => '/^\\/admin$/'
    ),
    '/trips' => array(
        'view' => 'trips.php',
        'pattern' => '/^\\/trips$/'
    ),
    '/profile' => array(
        'view' => 'profile.php',
        'pattern' => '/^\\/profile$/'
    ),
    '/login' => array(
        'view' => 'login/index.php',
        'pattern' => '/^\\/login$/'
    ),
    '/reset-password' => array(
        'view' => 'login/reset-password.php',
        'pattern' => '/^\\/reset-password$/'
    ),
    '/register' => array(
        'view' => 'register/agency.php',
        'pattern' => '/^\\/register$/'
    ),
    '/register/agency' => array(
        'view' => 'register/agency.php',
        'pattern' => '/^\\/register\/agency$/'
    ),
    '/register/customer' => array(
        'view' => 'register/customer.php',
        'pattern' => '/^\\/register\/customer$/'
    ),
    '/agency' => array(
        'view' => 'agency/index.php',
        'pattern' => '/^\\/agency$/'
    ),
    '/agency/trips' => array(
        'view' => 'agency/trips/index.php',
        'pattern' => '/^\\/agency\/trips$/'
    ),
    '/agency/trips/add-trip' => array(
        'view' => 'agency/trips/add-trip.php',
        'pattern' => '/^\\/agency\/trips\/add-trip$/'
    ),
    '/agency/trips/edit-trip' => array(
        'view' => 'agency/trips/edit-trip.php',
        'pattern' => '/^\\/agency\/trips\/edit-trip(\?tripId=\d+)?$/'
    ),
    '/agency/circuits' => array(
        'view' => 'agency/circuits/index.php',
        'pattern' => '/^\\/agency\/circuits$/'
    ),
    '/agency/circuits/add-circuit' => array(
        'view' => 'agency/circuits/add-circuit.php',
        'pattern' => '/^\\/agency\/circuits\/add-circuit$/'
    ),
    '/agency/circuits/edit-circuit' => array(
        'view' => 'agency/circuits/edit-circuit.php',
        'pattern' => '/^\\/agency\/circuits\/edit-circuit(\?circuitId=\d+)?$/'
    ),
    '/agency/vehicles' => array(
        'view' => 'agency/vehicles/index.php',
        'pattern' => '/^\\/agency\/vehicles$/'
    ),
    '/agency/vehicles/add-vehicle' => array(
        'view' => 'agency/vehicles/add-vehicle.php',
        'pattern' => '/^\\/agency\/vehicles\/add-vehicle$/'
    ),
    '/agency/vehicles/edit-vehicle' => array(
        'view' => 'agency/vehicles/edit-vehicle.php',
        'pattern' => '/^\\/agency\/vehicles\/edit-vehicle(\?vehicleId=\d+)?$/'
    ),
    '/agency/customers' => array(
        'view' => 'agency/customers.php',
        'pattern' => '/^\\/agency\/customers$/'
    ),
    // Add more routes as needed...
);


// Function to match the request URI against defined routes
function matchRoute($request, $routes)
{
    foreach ($routes as $route => $details) {
        if (preg_match($details['pattern'], $request, $matches)) {
            return array('view' => $details['view'], 'matches' => $matches);
        }
    }
    return null;
}

// Match the request URI to a route
$routeMatch = matchRoute($request, $routes);

if ($routeMatch !== null) {
    $view = $routeMatch['view'];
    require $viewDir . $view;
} else {
    // Handle 404
    http_response_code(404);
    require $viewDir . '404.php';
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/public/favicon.svg" type="image/x-icon" />
    <title>Travago</title>
</head>

<body>
    <!-- Your HTML content here -->
</body>

</html>
