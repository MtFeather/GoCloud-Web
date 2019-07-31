<?php
session_start();

require('dbconfig.php');
$account = $_POST['account'];
$password = $_POST['password'];
if(!empty($account) && !empty($password)) {
  try {
    $password = hash('sha256', $password);
    $stmt = $conn->prepare("SELECT id, account, name, password FROM student WHERE account = :account;");
    $stmt->bindParam(':account', $account);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($results && $password == $results['password']) {
      $_SESSION['user_id'] = $results['id'];
      $_SESSION['user_account'] = $results['account'];
      $_SESSION['user_name'] = $results['name'];
      echo 'success';
    } else {
      echo 'Invalid';
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  $conn = null;
}
?>
