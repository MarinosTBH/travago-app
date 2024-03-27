<?php
session_start();
include 'menu-bar.php';

define('BASEPATH', true);
require 'config/connect.php';

if ($_SESSION['USER']['user_type'] == "admin") {

  try {
    // get users from the database with pdo
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
} else if ($_SESSION['USER']['user_type'] == "agency") {
  try {
    // get users from the database with pdo
    $stmt = $pdo->prepare("SELECT * FROM users WHERE company_id = :company_id");
    $stmt->bindParam(':company_id', $_SESSION['USER']['company_id']);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/styles/output.css" rel="stylesheet">
  <title>Customers</title>
</head>

<body>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th scope="col" class="px-6 py-3">
              Id
            </th>
            <th scope="col" class="px-6 py-3">
              User
            </th>
            <th scope="col" class="px-6 py-3">
              Email
            </th>
            <th scope="col" class="px-6 py-3">
              Active
            </th>
            <th>
              Role
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($users as $user) {
            $active = $user['isVerified'] == 1 ? 'Yes' : 'No';
            echo '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">';
            echo '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">' . $user['id'] . '</td>';
            echo '<td class="px-6 py-4">' . $user['username'] . '</td>';
            echo '<td class="px-6 py-4">' . $user['email'] . '</td>';
            echo '<td class="px-6 py-4">' . $active . '</td>';
            echo '<td class="px-6 py-4">' . $user['user_type'] . '</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>