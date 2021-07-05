<?php
require_once "pdo.php";
session_start();
?>
<?php

if (isset($_POST['cancel'])) {
  header("Location: lecture_timeTable.php?username=".urlencode($_GET['username']));
  return;
}

if (isset($_POST['date']) && isset($_POST['startingtime']) && isset($_POST['endingtime']) && isset($_POST['id']) ) {
$sql="UPDATE timetable SET date =:date, startingtime=:startingtime, endingtime=:endingtime WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
  ':date'=> $_POST['date'],
  ':startingtime'=>$_POST['startingtime'],
  ':endingtime' => $_POST['endingtime'],
  ':id'=>$_POST['id']));
  $_SESSION['success']='Record updated';
  header("Location: lecture_timeTable.php?username=".urlencode($_GET['username']));
  return;
}
$stmt =$pdo->prepare("SELECT * FROM timetable where id =:xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false){
  $_SESSION['error'] = 'Bad value for id';
  header("Location: lecture_timeTable.php?username=".urlencode($_GET['username']));
  return;
}
$n = htmlentities($row['date']);
$e = htmlentities($row['startingtime']);
$p = htmlentities($row['endingtime']);
$id = $row['id'];
?>

<form method ="post">
  <label for="Date" class="control-label">Date</label>
  <input type="date"  name="date" value="<?=$n?>" class="form-control">

  <label for="Starting_Time" class="control-label">Starting Time</label>
  <input type="time"  name="startingtime" value="<?=$e?>" class="form-control">

  <label for="Ending_Time" class="control-label">Ending Time</label>
  <input type="time"  name="endingtime" value="<?=$p?>" class="form-control"><br/>

  <input type="hidden" name = "id" value="<?=$id?>">
  <input type="submit" value = "Update"/>
<input type="submit" value = "Cancel" name= "cancel">
</form>
