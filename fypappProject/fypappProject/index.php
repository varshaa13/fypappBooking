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
  </head>

  <body>
<form method = "post">
  <h1>FYP Student Management System</h1>

  <input type="submit" value="I'm a Lecturer" name="lecturer" />
    <input type="submit" value="I'm a Student" name="student" />

</form>
  </body>
  </html>
