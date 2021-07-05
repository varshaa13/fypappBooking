<?php
require_once "pdo.php";

if(!isset($_GET['email'])){
  die("Name parameter missing");
}

session_start();
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: index.php');
  return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) ) {
  $make = htmlentities($_POST['make']);
  $year = htmlentities($_POST['year']);
  $mileage = htmlentities($_POST['mileage']);

  if (strlen($make) < 1) {
    $_SESSION['error']  = "Make is required";
    header("Location: autos.php?email=".urlencode($_GET['email']));
    return;
  }
  else if (strlen($year) < 1) {
    $_SESSION['error']  = "Year is required";
    header("Location: autos.php?email=".urlencode($_GET['email']));
    return;
  }
  else if (strlen($mileage) < 1) {
    $_SESSION['error']  = "Mileage is required";
    header("Location: autos.php?email=".urlencode($_GET['email']));
    return;
  } else if (!is_numeric($year) || !is_numeric($mileage)) {
    $_SESSION['error'] = "Mileage and year must be numeric";
    header("Location: autos.php?email=".urlencode($_GET['email']));
    return;
  } else {
    $stmt = $pdo->prepare('INSERT INTO autos
      (make, year, mileage) VALUES ( :mk, :yr, :mi)');
      $stmt->execute(array(
        ':mk' => htmlentities($_POST['make']),
        ':yr' => htmlentities($_POST['year']),
        ':mi' => htmlentities($_POST['mileage']))
      );
      $_SESSION['success'] = "Result inserted";
      header("Location: autos.php?email=".urlencode($_GET['email']));
      return;
    }
  }
  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <title>203842 - Uvaaneswary A/P Rajendran</title>

  </head>
  <style>
  body{
    background-color: #ffd280;
  }
  * {
    box-sizing: border-box;
  }

  input[type=text], input[type=number], select, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
  }

  label {
    padding: 12px 12px 12px 0;
    display: inline-block;
  }

  input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: right;
    margin-left: 6px;
    margin-top: 5px;
  }

  input[type=submit]:hover {
    background-color: #45a049;
  }

  .container {
    border-radius: 5px;
    background-color: #fffab5;
    padding: 20px;
  }

  .col-25 {
    float: left;
    width: 25%;
    margin-top: 6px;
  }

  .col-75 {
    float: left;
    width: 75%;
    margin-top: 6px;
  }

  .row:after {
    content: "";
    display: table;
    clear: both;
  }

</style>

<body>
  <div class="container">
    <h1>Tracking Autos for <?php echo htmlentities($_GET['email']); ?></h1>
  
  </body>
</html>
