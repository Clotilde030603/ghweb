<?php
   $db_id="root";
   $db_pw="0epK81g3yp<r";
   $db_name="test";
   $db_domain="localhost";

   $db=mysqli_connect($db_domain,$db_id,$db_pw,$db_name) or die("error");

   if($db){
     echo "ok";
   }
   else {
     echo "no";
   }

  function mq($sql){
    global $db;
    return $db->query($sql);
  }

  ?>