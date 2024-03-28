<!-- 
    This file is used to check if the user is logged in and has the correct role to access the current page.
    If the user is not logged in or does not have the correct role, they will be redirected to the login page.
    This file should be included at the top of each page that requires authentication.
 -->
<?php
// Start the session
session_start();
define('BASEPATH', true);
require 'connect.php';

// Define routes based on user roles
switch ($_SESSION['USER']['user_type']) {
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
            '/agency/circuits',
            '/agency/vehicles',
            '/agency/customers',
            '/profile',
        ];
        break;
    case 'customer':
        // Customer can only visit trips
        $allowedPages = [
            '/home',
            '/trips',
            '/profile',
        ];
        break;
    default:
        // Redirect to a default page or show an error message
        header("Location: login");
        exit;
}

// Check if the current page is allowed for the user's role
$currentPage = basename($_SERVER['PHP_SELF']);
if (!in_array($currentPage, $allowedPages ?? []) && $_SESSION['USER']['user_type'] == 'customer') {
    // Page is not allowed for the user's role
    // Redirect to a default page or show an error message
    header("Location: unauthorized.php");
    exit;
}
