<?php
session_start();
require_once "pdo.php";

if (isset($_POST['Back'])) {
  header("Location: index.php");
  return;
}

// Encrypt cookie
function encryptCookie( $value ) {

   $key = hex2bin(openssl_random_pseudo_bytes(4));

   $cipher = "aes-256-cbc";
   $ivlen = openssl_cipher_iv_length($cipher);
   $iv = openssl_random_pseudo_bytes($ivlen);

   $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

   return( base64_encode($ciphertext . '::' . $iv. '::' .$key) );
}

// Decrypt cookie
function decryptCookie( $ciphertext ) {

   $cipher = "aes-256-cbc";

   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);

}

// Check if $_SESSION or $_COOKIE already set
if( isset($_SESSION['userid']) ){
   header('Location: homeStudent.php');
   exit;
}else if( isset($_COOKIE['rememberme'] )){

   // Decrypt cookie variable value
   $userid = decryptCookie($_COOKIE['rememberme']);

   // Fetch records
   $stmt = $pdo->prepare("SELECT count(*) as cntUser FROM student WHERE id=:id");
   $stmt->bindValue(':id', (int)$userid, PDO::PARAM_INT);
   $stmt->execute();
   $count = $stmt->fetchColumn();

   if( $count > 0 ){
      $_SESSION['userid'] = $userid;
      header("Location: homeStudent.php?username=".urlencode($_POST['txt_uname']));
      return;
   }
}

$salt = 'XyZzy12*_';
// On submit
if(isset($_POST['but_submit'])){

   $username = $_POST['txt_uname'];
   $password = hash('md5', $salt.$_POST['txt_pwd']);

   if ($username != "" && $password != ""){

     // Fetch records

     $stmt = $pdo->query("SELECT count(*) as cntUser,id FROM student WHERE username='$username' and password='$password'");
     $record= $stmt->fetch(PDO::FETCH_ASSOC);

     $count = $record['cntUser'];

     if($count > 0){
        $userid = $record['id'];

        if ( isset ( $_POST ['rememberme'] ) ){

           // Set cookie variables
           $days = 30;
           $value = encryptCookie( $userid );

           setcookie ( "rememberme", $value, time() + ( $days * 24 * 60 * 60 * 1000 ));
        }

        $_SESSION['userid'] = $userid;
        header ( "Location:homeStudent.php?username=".urlencode($_POST['txt_uname']));
        return ;
    } else {
      echo " Invalid username and password " ;
    }

  }

}
?>

<!DOCTYPE html>
<html>
<head>
  <title>FYP Appointment Booking System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <style>

  body {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  height: 500px;
  width: auto;
  }

  input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
  }

  h1{
  color: white;
  }

  a{
  text-decoration: underline;
  }

  .textbox{
  align-items: center;
  }

  div.transbox {
  width: 500px;
  }

</style>

<body>
<div class="container">
 <form method="post" action="">
   <div class="transbox">
   <div id="div_login">
     <h1>Login</h1>
     <div>
        <input class="form-control" type="text" class="textbox" name="txt_uname" placeholder="Username" />
     </div>
     <div><br/>
        <input class="form-control" type="password" class="textbox" name="txt_pwd" placeholder="Password"/>
     </div>
     <div>
        <input type="checkbox" name="rememberme" value="1" />&nbsp;Remember Me
     </div>

     <div>
       <input class="btn btn-success" type="submit" value="Login" name="but_submit" />
         <input class="btn btn-info" type="submit" value="Back" name="Back" /> <br/><br/>
         <p> Don't have any account? <a href = "signUpStudent.php">Sign up</a></p>
     </div>
   </div>
 </form>
</div>
</body>
</html>
