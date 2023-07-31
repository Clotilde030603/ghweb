<?php
$db_id="root";
$db_pw="0epK81g3yp<r";
$db_name="User";
$db_domain="localhost";


  session_start();
  session_destroy();
  unset($_SESSION['userId']);
  
  header( 'Location: index.php' );
  
?>

