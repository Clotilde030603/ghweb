<?php
require "top.php";
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];
    $wu = 0; // Initialize $wu variable
    $wp = 0; // Initialize $wp variable
    
    if ( !is_null( $userID ) && !is_null($userPW)) {
        $db_id="root";
        $db_pw="0epK81g3yp<r";
        $db_name="User";
        $db_domain="localhost";

        $conn=mysqli_connect($db_domain,$db_id,$db_pw,$db_name);

        if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT userPW, roll FROM user WHERE userID = '".$userID."';";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        $encrypted_userPW = null;
        $row = mysqli_fetch_array($result);
        $encrypted_userPW = $row['userPW'];
        

        if (is_null($encrypted_userPW)) {
            $wu = 1;
        } else {
          if (password_verify($userPW, $encrypted_userPW)) {
            session_start();
            
            $_SESSION['roll'] = $row['roll'];
            
            $_SESSION[ 'userID' ] = $userID;
            echo "<script>alert('로그인 성공.'); window.location.href='index.php';</script>";
            
            exit;
        } else {
            // 비밀번호가 틀림
            $wp = 1;
            
        }
          
            // Insert user registration logic here, but make sure to hash the password before insertion.
            // For example, you can use password_hash() function.
            // $hashed_password = password_hash($userPW, PASSWORD_DEFAULT);
            // Then use $hashed_password in the SQL query.
        }
    }
?>

<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="CSS/style_login.css">

    <title>로그인</title>
    <style>
      body { font-family: sans-serif; font-size: 20px; }
      input, button { font-family: inherit; font-size: inherit; }
    </style>
  </head>
  <body>
  <div class="login-wrapper">
    <h1>로그인</h1>
    <form action="login.php" method="POST">
      <p><input type="text" name="userID" placeholder="ID" required></p>
      <p><input type="password" name="userPW" placeholder="Password" required></p>
      <p><input type="submit" value="로그인"></p>
      <?php
        if ($wu == 1){
          echo "<p>ID가 존재하지 않습니다.</p>";
        }
        if ($wp == 1) {
          echo "<p>비밀번호가 틀렸습니다.</p>";
        }
      ?>
    </form>
  </div>
  </body>
</html>
