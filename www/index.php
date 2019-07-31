<?php
session_start();
if( isset($_SESSION['user_id']) ){
  header("Location: vm.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link rel="stylesheet" type="text/css" href="assets/css/patternfly.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/patternfly-additions.min.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/patternfly.min.js"></script>
</head>
<body class="login-pf">
<div class="login-pf-page">

    
    
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
        <header class="login-pf-page-header">
          <img class="login-pf-brand" src="assets/img/Logo_Horizontal_Reversed.svg" alt=" logo" />
          <p>
            Welcome to cloud virtual classroom system!
          </p>
        </header>
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="card-pf">
              <header class="login-pf-header">
                <h1>Log In to Your Account</h1>
                <span class="text-danger text-center"></span>
              </header>
              <form id="loginForm">
                <div class="form-group">
                  <label class="sr-only" for="exampleInputEmail1">Email address</label>
                  <input type="text" class="form-control  input-lg" id="account" placeholder="Account">
                  <span class="help-block collapse">Enter your account</span>
                </div>
                <div class="form-group">
                  <label class="sr-only"  for="exampleInputPassword1">Password</label>
                  <input type="password" class="form-control input-lg" id="password" placeholder="Password">
                  <span class="help-block collapse">Enter your password</span>
                </div>
                <div class="form-group login-pf-settings">
                      <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Log In</button>
              </form>
              <p class="login-pf-signup">Need an account?<a href="#">Sign up</a></p>
            </div><!-- card -->
            
            <footer class="login-pf-page-footer">
              <ul class="login-pf-page-footer-links list-unstyled">
                <li><a class="login-pf-page-footer-link" href="#">Terms of Use</a></li>
                <li><a class="login-pf-page-footer-link" href="#">Help</a></li>
                <li><a class="login-pf-page-footer-link" href="#">Privacy Policy</a></li>
              </ul>
            </footer>
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- col -->
    </div><!-- row -->
  </div><!-- container -->
</div><!-- login-pf-page -->

<script src="assets/js/gocloud-login.js"></script>
</body>
</html>

    
