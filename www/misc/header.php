<?php
session_start();

if( !isset($_SESSION['user_id']) ){
  header("Location: header.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link rel="stylesheet" type="text/css" href="assets/css/patternfly.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/patternfly-additions.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/gocloud.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/datatables.min.js"></script>
  <script src="assets/js/patternfly.min.js"></script>
</head>
<body>
<?php
require_once('misc/nav.php');
?>
