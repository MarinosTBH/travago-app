<?php
// Start the session
session_start();
define('BASEPATH', true);
require 'config/connect.php';

// check if already logged in
if (isset($_SESSION['USER']['id'])) {
    header("Location: /home");
} else {

    // checks if the server request is sent
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // if no credentials
        if (empty($_POST['email']) || empty($_POST['password'])) {
            echo "<p class='absolute w-full mx-auto text-center text-red-500'>Please provide your credentials</p>";
        } else {
            // read credentials and check it in database
            try {
                $email = $_POST['email'];
                $pass = $_POST['password'];

                //search for user by email
                $sql = "SELECT * from users WHERE email=:email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->execute();
                $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userExists) {
                    $verifyPassword = password_verify($pass, $userExists['password']);
                    if ($verifyPassword) {
                        // redirect to concerned page
                        switch ($userExists['user_type']) {
                            case 'customer':
                                // if customer redirect to trips
                                echo '<script>window.location.replace("/trips")</script>';
                                break;
                            case 'admin':
                                // if admin redirect to admin panel
                                echo '<script>window.location.replace("/agency")</script>';
                                break;
                            case 'agency':
                                // if agency redirect to dashboard
                                echo '<script>window.location.replace("/agency")</script>';
                                break;
                            default:
                                echo '<script>window.location.replace("/home")</script>';
                                break;
                        }
                        $_SESSION['USER'] = $userExists;
                    } else {
                        // password false
                        echo "<p class='absolute w-full mx-auto text-center text-red-500'>Invalid credentials</p>";
                    }
                } else {
                    echo "<p class='absolute w-full mx-auto text-center text-red-500'>Email dosen't exist</p>";
                }
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
                echo '<script type="text/javascript">alert("' . $error . '");</script>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/styles/output.css" rel="stylesheet">
    <title>Login</title>
</head>

<body>
    <div class="flex h-full">
        <div class="hidden lg:flex">
            <img src="/public/registration.avif" alt="Your Company">
        </div>
        <div class="w-full flex min-h-full flex-col items-center justify-center px-6 py-12 lg:px-8">
        <?php
            if ($_SERVER['HTTP_REFERER'] == 'http://localhost:3000/home') {
                echo "<p class='w-full text-center' style='color:green;font-weight:bold;'>You must login first</p>";
            } ?>
            <!-- Image and title -->
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                    alt="Your Company">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your
                    account</h2>
            </div>
            <!-- Form -->
            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                <form class="space-y-6" action="/login" method="POST">
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                            address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            <div class="text-sm">
                                <a href="/reset-password"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot
                                    password?</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                            in</button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Not a member?
                    <a href="/register/agency" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                        Register</a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>