<!-- 
    This file is used to check if the user is logged in and has the correct role to access the current page.
    If the user is not logged in or does not have the correct role, they will be redirected to the login page.
    This file should be included at the top of each page that requires authentication.
 -->
<?php
// Start the session
session_start();
// assign a variable to the user type

$userType = $_SESSION['USER']['user_type'] ?? null;
// Define routes based on user roles
switch ($userType) {
    case 'admin':
        // Admin can visit all pages
        // No need to perform any checks
        break;
    case 'agency':
        // Agency can visit only dashboard pages
        $allowedPages = [
            '/home',
            '/agency',
            '/agency/trips',
            '/agency/trips/add-trip',
            '/agency/trips/edit-trip',
            '/agency/trips/manage-seats',
            '/agency/circuits',
            '/agency/circuits/add-circuit',
            '/agency/circuits/edit-circuit',
            '/agency/vehicles',
            '/agency/vehicles/add-vehicle',
            '/agency/vehicles/edit-vehicle',
            '/agency/customers',
            '/profile',
        ];
        break;
    case 'user':
        // Customer can only visit trips
        $allowedPages = [
            '/',
            '/home',
            '/trips',
            '/profile'
        ];
        break;
    default:
        // Redirect to a default page or show an error message
        header("Location: /login");
        exit;
}

// Check if the current page is allowed for the user's role
$currentPage = strtok($_SERVER["REQUEST_URI"], '?');
if (!in_array($currentPage, $allowedPages ?? []) && $userType !== 'admin') {
    // Page is not allowed for the user's role
    // Redirect to a default page or show an error message
    header("Location: /404.php");
    exit;
}
