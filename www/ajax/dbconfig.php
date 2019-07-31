<?php
$host='localhost';
$port='5432';
$dbname='gocloud';
$user='user';
$password='password';

try {
  $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
