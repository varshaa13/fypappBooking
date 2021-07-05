<?php
require_once "pdo.php";
session_start();
$salt = 'XyZzy12*_';

if(isset($_POST['submit'])) {
  if(isset($_POST['name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm']) ){

    //Set user inputs with html injection prevetion to new variables
    $name = htmlentities($_POST['name']);
    $matric = htmlentities($_POST['username']);
    $email = htmlentities($_POST['email']);
    $password = htmlentities($_POST['password']);
    $confirm = htmlentities($_POST['confirm']);

    $stmt = $pdo->prepare('INSERT INTO lecturer (name, username, password, email)
                          VALUES (:name, :username, :password, :email)');

    $stmt->execute(array(
      ':name' => $_POST['name'],
      ':username' => $_POST['username'],
      ':password' => hash('md5', $salt.$_POST['password']),
      ':email' => $_POST['email'] )
    );


    $_SESSION['success'] = "Successfully Registered";
    header("Location: signUpLecturer.php?username=".urlencode($_POST['username']));
    return;
  } else {
    $_SESSION['failure'] = "Registration Failed!";
    header("Location: signUpLecturer.php");
    return;
  }
}
?>

<!-- HTML Starts Here -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>FYP Appointment Booking System</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <style>

  div.transbox {
    height: 500px;
    width: 600px;
  }

  </style>
  <body>
<div class="container">
      <div class="position-absolute mt-5 mb-5 p-5 top-50 start-50 translate-middle transbox">
        <h1 class="text-center">Sign Up</h1>

        <?php
          if (isset($_SESSION['failure'])) { //If there is error message
            print '<p class="text-center" style="color:red">';
            print $_SESSION['failure'];
            print "</p>\n";
            unset($_SESSION['failure']);
          }
          if (isset($_SESSION['success'])) { //If there is success message
          	print '<p class="text-center" style="color:green">';
          	print $_SESSION['success'];
          	print "</p>\n";
            unset($_SESSION['success']);
          }
        ?>

        <!-- Starting of form -->
        <form method="POST" name="signUp" onSubmit="return validateForm()">
          <div class="mb-3 row text-center">
            <div class="col-sm-8 text-center" >
              <input class="form-control" type="text" placeholder="Name" name="name" id="name" class="form-control mt-2">
            </div>
            <div class="invalid-feedback">
              Please enter your Name
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-8">
              <input class="form-control" type="text" placeholder="Matric Number" name="username" id="username" class="form-control">
            </div>
            <div class="invalid-feedback">
              Please enter your Matric Number
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-8">
              <input class="form-control" type="text" placeholder="Email" name="email" id="email" class="form-control">
            </div>
            <div class="invalid-feedback">
              Please enter your Email
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-8">
              <input class="form-control" type="password" placeholder="Password" name="password" id="password" class="form-control">
            </div>
            <div class="invalid-feedback">
              Please enter your Password
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-8">
              <input class="form-control" type="password" placeholder="Confirm Password" name="confirm" id="confirm" class="form-control">
            </div>
            <div>
            </div>
          </div>

          <div class="mb-2 mt-2 text-center">
            <input class="btn btn-success" type="submit" value="Sign Up" name="submit">
          </div>
          <div class="mb-2 mt-2 text-center">
            <p>Already signed up? <a href="lecturerLogin.php">Login</a></p>
          </div>
        </form>
        <!-- End of form -->

    </div>
</div>
    <script type="text/javascript">
      function validateForm() {
        if (document.signUp.name.value == null || document.signUp.name.value == "") {
          alert("Please enter your Full Name!");
          return false;
        }
        if (document.signUp.username.value == null || document.signUp.username.value == "") {
          alert("Please enter your Matric Number!");
          return false;
        }
        if (document.signUp.email.value == null || document.signUp.email.value == "") {
          alert("Please enter your Email Address!");
          return false;
        }
        if (document.signUp.password.value == null || document.signUp.password.value == "") {
          alert("Please enter a unique password!");
          return false;
        } else {
            if (document.signUp.confirm.value == null || document.signUp.confirm.value == "") {
              alert("Please confirm password!");
              return false;
            } else {
                if (document.signUp.password.value != document.signUp.confirm.value) {
                  alert("Password does not match!Try again!");
                  return false;
                }
              }
          }

      }
    </script>
  </body>
</html>
