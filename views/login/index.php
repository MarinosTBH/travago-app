<?php
// Start the session
session_start();
require 'config/connect.php';
$user = $_SESSION['USER']['user_type'] ?? null;

// check if already logged in
$error = "";
if (isset($user)) {
    header("Location: /home");
} else {

    // checks if the server request is sent
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // if no credentials
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $error = "Please provide your credentials!";
        } else {
            // read credentials and check it in database
            try {
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $sql = "SELECT 
                u.id,
                u.username,
                u.email,
                u.address,
                u.phone,
                u.password,
                u.isVerified,
                u.user_type,
                c.id AS company_id,
                c.name AS company_name
                FROM 
                    users u
                INNER JOIN 
                    companies c ON u.company_id = c.id
                WHERE 
                    u.email = :email";

                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->execute();
                $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userExists) {
                    $verifyPassword = password_verify($pass, $userExists['password']);
                    if ($verifyPassword) {

                        // check active or not 
                        if ($userExists['isVerified'] == 0) {
                            $error = "Your account is not active yet. Please contact customer support to activate your account.";
                        } else {

                            // redirect to concerned page
                            $_SESSION['USER'] = $userExists;
                            switch ($userExists['user_type']) {
                                case 'user':
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
                        }
                    } else {
                        // password false
                        $error = "Invalid credentials, check your email or password.";
                    }
                } else {
                    $error = "Email dosen't exist";
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
    <link href="styles/output.css" rel="stylesheet">
    <title>Login</title>
    <style>
        .background {
            height: 100vh;
            background: url('https://tailwindui.com/img/ecommerce-images/category-page-01-image-card-02.jpg') no-repeat;
            background-size: 100% 100%;
        }
    </style>
</head>

<body>
    <div class="flex h-full relative">
        <div class="hidden lg:flex w-full background">
        </div>
        <div class="w-full flex min-h-full flex-col items-center justify-center px-6 py-12 lg:px-8">
            <?php
            if (isset($_SERVER['HTTP_REFERER'])) {
                if (($_SERVER['HTTP_REFERER'] == 'http://localhost/') || ($_SERVER['HTTP_REFERER'] == 'http://localhost/home')) {
                    echo "<p class='w-full text-center' style='color:green;font-weight:bold;'>You must login first</p>";
                }
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
                <?php
                if ($error) {
                    echo "<p class='w-full mx-auto text-center text-red-500'>$error</p>";
                } ?>

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