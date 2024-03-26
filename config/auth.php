<?php
// Start the session
session_start();
define('BASEPATH', true);
require ('connect.php');


// Define routes based on user roles
switch ($_SESSION['USER']['user_type']) {
    case 'admin':
        // Admin can visit all pages
        // No need to perform any checks
        break;
    case 'agency':
        // Agency can visit only dashboard pages
        $allowedPages = [
            '/agency/dashboard.php',
            '/agency/trips.php',
            '/agency/circuits.php',
            '/agency/vehicles.php',
            '/agency/customers.php',

        ];
        break;
    case 'customer':
        // Customer can only visit trips.php
        $allowedPages = ['trips.php'];
        break;
    default:
        // Redirect to a default page or show an error message
        header("Location: login.php");
        exit;
}

// Check if the current page is allowed for the user's role
$currentPage = basename($_SERVER['PHP_SELF']);
if (!in_array($currentPage, $allowedPages??[]) && $_SESSION['USER']['user_type'] != 'admin') {
    // Page is not allowed for the user's role
    // Redirect to a default page or show an error message
    header("Location: unauthorized.php");
    exit;
}
