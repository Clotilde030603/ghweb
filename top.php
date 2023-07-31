<?php
// 세션 시작
session_start();

// 세션에 userID가 존재하는 경우, 즉 로그인된 상태라면
if (isset($_SESSION['userID'])) {
    $session_userID = $_SESSION['userID'];
    $userName = $session_userID . '님';
}
?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content=""width="device-width, initial-scale=1.0">

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/style_index.css">

    <script src="https://kit.fontawesome.com/4a9f58f3cf.js"></script>

    <title>Hyun's</title>

  </head>

  <body>
    <header>
      <div class="header_container">
        <div class="logo_container">
          <a><a href="index.php">Hyun's Home</a>
        </div>

        <div class="nav_container" id="nav_menu">
            <div class="menu_container">
              <ul class="menu">
              <li class="menu_board"><a class="menu_title" href="/Board.php">공지사항</a></li>
              <?php if (isset($_SESSION['userID'])) { ?>
              <li class="menu_board"><a class="menu_title" href="/f_Board.php">자유게시판</a></li>
              <li class="menu_board"><a class="menu_title" href="/q_Board.php">Q&A</a></li>
              
              
              <?php } ?>
              </ul>
            </div>

            <div class="enter_container">
            <ul class="login">
            <?php
                // 세션에 userID가 존재하는 경우, 즉 로그인된 상태라면
                    if (isset($_SESSION['userID'])) {
                          echo '<li class="menu_login"><a class="menu_title" href="#">' . $userName . '</a></li>';
                          echo '<li class="menu_login"><a class="menu_title" href="/logout.php">로그아웃</a></li>';
                     }
                     else{
                          echo '<li class="menu_login"><a class="menu_title" href="/login.php">로그인</a></li>';
                          echo '<li class="menu_login"><a class="menu_title" href="/register.php">회원가입</a></li>';
                     }
                     ?>

            </ul>           
          </div>
          

        </div>
      </div>
    </header>

    
    
  </body>
</html>
<link rel="stylesheet" href="">
<link rel="stylesheet" href="">