<?php
require_once "pdo.php";

if (isset($_POST['lecturer'])) {
  header("Location: lecturerLogin.php");
  return;
}
if (isset($_POST['student'])) {
  header("Location: studentLogin.php");
  return;
}
?>
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

  <body style="background-image: url('uni.jpeg'); height:100%; background-position: center; background-repeat: no-repeat; background-size: cover;">

    <!-- Javascript -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    <div class="container">
      <div class="position-absolute mb-5 top-50 start-50 translate-middle text-dark p-5" style="background:rgba(255,255,255,.8)">

        <form method = "post">
          <h1 class="text-center mb-5">FYP Appointment Booking System</h1>

          <div class="mb-2 mt-2 text-center">
            <input class="btn btn-dark btn-lg shadow-lg" type="submit" value="I'm a Lecturer" name="lecturer" />
            <br /><br />
            <input class="btn btn-dark btn-lg shadow-lg" type="submit" value="I'm a Student" name="student" />
          </div>

        </form>
      </div>
    </div>
  </body>
</html>
