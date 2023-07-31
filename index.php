<?php require "top.php";
session_start();
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
    <!-- 추가 스타일 시트 enlace -->
    <style>
      /* Flexbox container to center the search box */
      #search_box {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Optional: Adjust the height to fit your layout */
      }
    </style>

    <!-- 검색 컨테이너 시작 -->
    <div id="search_box">
      <form action="search.php" method="get">
        <?php
        // 'userID' 세션 변수가 설정되어 있는지 확인하여 사용자가 로그인한 상태인지 확인합니다.
        if (isset($_SESSION['userID'])) {
          echo '<select name="catgo">';
          echo '<option value="title">제목</option>';
          echo '<option value="id">작성자</option>';
          echo '<option value="content">내용</option>';
          echo '</select>';
          echo '<input type="text" name="search" size="50" required="required" />';
          echo '<button>검색</button>';
        }
        ?>
      </form>
    </div>
    <!-- 검색 컨테이너 끝 -->

    
  </body>
</html>