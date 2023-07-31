<?php
require "top.php";
session_start();
$showWriteLink = true; // 글쓰기 링크를 보일지 여부를 초기화

// 세션에 userID가 존재하는 경우, 즉 로그인된 상태라면
if (isset($_SESSION['userID'])) {
    $session_userID = $_SESSION['userID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'User') or die("connect fail");

    // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    // roll 값이 'admin'인 경우에만 글쓰기 링크를 보이지 않도록 설정
    if ($userRoll === 'admin') {
        $showWriteLink = true;
    } else {
        $showWriteLink = true; // 모든 사용자에게 글쓰기 링크를 보이도록 수정
    }

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
</head>
<style>
        table{
                margin-left: auto;
                margin-right: auto;
                border-top: 1px solid #444444;
                border-collapse: collapse;
        }
        tr{
                border-bottom: 1px solid #444444;
                padding: 10px;
        }
        td{
                border-bottom: 1px solid #efefef;
                padding: 10px;
        }
        table .even{
                background: #efefef;
        }
        .text{
                text-align:center;
                padding-top:20px;
                color:#000000
        }
        .text:hover{
                text-decoration: underline;
        }
        a:link {color : #57A0EE; text-decoration:none;}
        a:hover { text-decoration : underline;}
        body{text-align: center;}
</style>

<body>
    <h2 align="center">Q&A</h2>

      <!-- 추가 스타일 시트 enlace -->
      <style>
      /* Flexbox container to center the search box */
      #search_box {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 5vh; /* Optional: Adjust the height to fit your layout */
      }
    </style>
     <!-- 검색 컨테이너 시작 -->
     <div id="search_box">
      <form action="q_search.php" method="get">
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

    <table align="center">
        <thead align="center">
            <tr>
                <td width="50" align="center">번호</td>
                <td width="500" align="center">제목</td>
                <td width="100" align="center">작성자</td>
                <td width="200" align="center">날짜</td>
            </tr>
        </thead>
        <tbody>

            <?php
            // 게시판 데이터 조회
            $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3') or die("connect fail");
            $query = "select * from board3 order by number desc";
            $result = $connect->query($query);
            $total = mysqli_num_rows($result);



            while ($rows = mysqli_fetch_assoc($result)) {
                 // 'secret' 값이 1인 게시물이며, 사용자가 관리자이거나 게시물 작성자인지 확인
                $isSecretPost = $rows['secret'] == 1;
                $isAdmin = $userRoll === 'admin';
                $isPostAuthor = isset($_SESSION['userID']) && $_SESSION['userID'] === $rows['id'];

                if (!$isSecretPost || $isAdmin || $isPostAuthor) {
                    if ($total % 2 == 0) {
                        echo '<tr class="even">';
                    } else {
                        echo '<tr>';
                    }
            ?>

                <td width="50" align="center"><?php echo $total ?></td>
                <td width="500" align="center">
                    <a href="q_view.php?number=<?php echo $rows['number'] ?>">
                        <?php echo $rows['title'] ?>
                    </a>
                </td>
                <td width="100" align="center"><?php echo $rows['id'] ?></td>
                <td width="200" align="center"><?php echo $rows['date'] ?></td>
            </tr>
            <?php
                $total--;
            }
        }
            ?>
        </tbody>
    </table>
    <?php if ($showWriteLink) : ?>
    <div class="text">
        <font style="cursor: hand" onClick="location.href='./q_write.php'">글쓰기</font>
    </div>
    <?php endif; ?>
</body>
</html>