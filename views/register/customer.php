<?php
session_start();
define('BASEPATH', true); //access connection script if you omit this line file will be blank
require 'config/connect.php';

// check if already logged in
echo $_SESSION['USER'];
if (isset($_SESSION['USER'])) {
    header("Location: /home");
} else {

    if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phone']) || empty($_POST['passport_number'])) {
            echo '<p class="text-center text-red-500">All fields are required</p>';
        } else {

            try {
                $user = $_POST['username'];
                $email = $_POST['email'];
                $pass = $_POST['password'];
                $phone = $_POST['phone'];
                $passport_number = $_POST['passport_number'];
                $user_type = 'customer';

                //encrypt password
                $pass = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

                //Check if username exists
                $sql = "SELECT COUNT(username) AS num FROM users WHERE email =      :email";
                $stmt = $pdo->prepare($sql);

                $stmt->bindValue(':email', $email);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row['num'] > 0) {
                    echo '<script>alert("Email already exists")</script>';
                } else {

                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, passport_number, user_type) 
            VALUES (:username,:email, :password, :phone, passport_number, :user_type)");
                    $stmt->bindParam(':username', $user);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $pass);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':passport_number', $passport_number);
                    $stmt->bindParam(':user_type', $user_type);



                    if ($stmt->execute()) {
                        echo '<script>alert("New account created.")</script>';
                        //redirect to another page
                        echo '<script>window.location.replace("/login")</script>';
                    } else {
                        echo '<script>alert("An error occurred")</script>';
                    }
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
    <title>Register as an agency</title>
</head>

<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create a user account</h2>
        </div>

        <p class="sm:mx-auto sm:w-full sm:max-w-sm text-xs mt-10">
            By creating this account, you'll gain access to exclusive trip offers, personalized recommendations, and exciting updates on upcoming adventures. Get ready to embark on unforgettable journeys with us!</a>
        </p>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="/register/agency" method="post">
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Full name</label>
                    <div class="mt-2">
                        <input id="username" name="username" type="text" autocomplete="username" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone number</label>
                    <div class="mt-2">
                        <input id="phone" name="phone" type="phone" autocomplete="phone" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                <div>
                    <label for="passport_number" class="block text-sm font-medium leading-6 text-gray-900">Passport
                        number</label>
                    <div class="mt-2">
                        <input id="passport_number" name="passport_number" type="passport_number" autocomplete="passport_number" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Sign up
                    </button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Already have an account?
                <a href="/login" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                    Login</a>
            </p>
        </div>
    </div>

</body>

</html>