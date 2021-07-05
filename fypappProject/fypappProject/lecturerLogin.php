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
   header('Location: homeLecturer.php');
   exit;
}else if( isset($_COOKIE['rememberme'] )){

   // Decrypt cookie variable value
   $userid = decryptCookie($_COOKIE['rememberme']);

   // Fetch records
   $stmt = $pdo->prepare("SELECT count(*) as cntUser FROM lecturer WHERE id=:id");
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

     $stmt = $pdo->query("SELECT count(*) as cntUser,id FROM lecturer WHERE username='$username' and password='$password'");
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
        header ( "Location:homeLecturer.php?username=".urlencode($_POST['txt_uname']));
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

</head>

<body>
<div class="container">
 <form method="post" action="">
   <div id="div_login">
     <h1>Login</h1>
     <div>
        <input type="text" class="textbox" name="txt_uname" placeholder="Username" />
     </div>
     <div>
        <input type="password" class="textbox" name="txt_pwd" placeholder="Password"/>
     </div>
     <div>
        <input type="checkbox" name="rememberme" value="1" />&nbsp;Remember Me
     </div>

     <div>
       <a href = "signUpLecturer.php">Sign up</a>
        <input type="submit" value="Submit" name="but_submit" />
          <input type="submit" value="Back" name="Back" />
     </div>
   </div>
 </form>
</div>
</body>
</html>
