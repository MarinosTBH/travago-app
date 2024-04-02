<!-- 
    Connection to the database using PDO (PHP Data Objects) to connect to a MySQL database. 
    This file should be included at the top of each page that requires a connection to the database.
 -->
<?php

$host = "localhost";
$dbname = "travago";
$username = "root";
$password = "root";
$dsn = "mysql:host=$host;dbname=$dbname";

//$host = "sql206.infinityfree.com";
//$dbname = "if0_36274448_travago";
//$username = "if0_36274448";
//$password = "eqFuaSTN14juukE";
//$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die('Connection failed: ' . $e->getMessage());
}
?>
