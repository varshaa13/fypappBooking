<?php
require_once "pdo.php";
session_start();
//$Student_ID = $_GET['email'];
$salt = 'XyZzy12*_';

if(isset($_POST['submit'])) {
  if(isset($_POST['name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm']) && isset($_POST['course']) && isset($_POST['lecturerid'])){

    //Set user inputs with html injection prevetion to new variables
    $name = htmlentities($_POST['name']);
    $matric = htmlentities($_POST['username']);
    $email = htmlentities($_POST['email']);
    $password = htmlentities($_POST['password']);
    $confirm = htmlentities($_POST['confirm']);
    $course = htmlentities($_POST['course']);
    $supervisor = htmlentities($_POST['lecturerid']);

    $stmt = $pdo->prepare('INSERT INTO student (name, username, password, email, course, lecturerid)
                          VALUES (:name, :username, :password, :email, :course, :lecturerid)');

    $stmt->execute(array(
      ':name' => $_POST['name'],
      ':username' => $_POST['username'],
      ':password' => hash('md5', $salt.$_POST['password']),
      ':email' => $_POST['email'],
      ':course' => $_POST['course'],
      ':lecturerid' => $_POST['lecturerid'] )
    );


    $_SESSION['success'] = "Successfully Registered";
    header("Location: signUpStudent.php?username=".urlencode($_POST['username']));
    return;

  } else {

    $_SESSION['failure'] = "Registration Failed!";
    header("Location: signUpStudent.php");
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
  </head>

  <body>

    <div class="container">
      <div class="position-absolute mt-5 mb-5 p-5">

        <h1 class="text-center">Register</h1>

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
          <div class="mb-3 row ">
            <div class="col-sm-8">
              <input class="form-control" type="text" placeholder="Name" name="name" id="name" class="form-control mt-2">
            </div>
            <div class="invalid-feedback">
              Please enter your Name
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-sm-8">
              <input class="form-control" type="number" placeholder="Matric Number" name="username" id="username" class="form-control">
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
              Please Confirm Your Password
            </div>
        <!--    <div class="">
              Please Confirm Your Password
            </div> -->
          </div>

          <div class="mb-3">
            <select class="form-select" placeholder="Course" name="course" id="course" aria-label="select example">
              <option disabled selected value="">Select a Course</option>
              <option value="Software Engineering">Bachelor of Software Engineering</option>
              <option value="Multimedia">Bachelor of Multimedia</option>
              <option value="Networking">Bachelor of Networking</option>
              <option value="Computer Science">Bachelor of Computer Science</option>
            </select>
            <div>Please select a valid Course</div>
          </div>


          <div class="mb-3">
            <select class="form-select" placeholder="Supervisor" name="lecturerid" id="lecturerid" aria-label="select example">
              <option disabled selected value="">Select your Supervisor</option>

              <?php

                  $stmt = $pdo->query("SELECT id, name FROM lecturer");
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                                    echo "<option value='" . $row['id'] . "'>" . $row['name']. "</option>";
                                  }
                                    ?>
            </select>
            <div>Please select a valid Supervisor</div>
          </div>


          <div class="mb-2 mt-2 text-center">
            <input class="btn btn-success" type="submit" value="Submit" name="submit">
          </div>
          <div class="mb-2 mt-2 text-center">
            <p>Already signed up? <a href="studentLogin.php">Login</a></p>
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
          if (document.signUp.course.selectedIndex == 0) {
            alert("Please enter your Course!");
            return false;
          }
          if (document.signUp.lecturerid.selectedIndex == 0) {
            alert("Please enter your Supervisor!");
            return false;
          }
      }
    </script>
  </body>
</html>
