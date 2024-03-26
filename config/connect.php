<?php
defined('BASEPATH') or exit('No direct script access allowed');

$host = "127.0.0.1";
$dbname = "travago";
$username = "root";
$password = "root";
$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die('Connection failed: ' . $e->getMessage());
}
