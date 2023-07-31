<?php
require "top.php";
session_start();
$showWriteLink = true; // 글쓰기 링크를 보일지 여부를 초기화

// 사용자가 정렬 옵션을 선택한 경우, 쿠키에 정렬 옵션을 저장
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    setcookie("board_order", $order, time() + (86400 * 30), "/"); // 쿠키 유효기간: 30일
} else {
    // 쿠키에 정렬 옵션이 없는 경우, 기본 정렬 방식은 순번순으로 설정
    if (isset($_COOKIE['board_order'])) {
        $order = $_COOKIE['board_order'];
    } else {
        $order = "number";
    }
}

// 사용자가 선택한 정렬 옵션에 따라 게시판 목록을 조회
$connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
if ($order === "hit") {
    // 조회순으로 정렬
    $query = "SELECT * FROM board2 ORDER BY hit DESC";
} elseif ($order === "thumbup") {
    // 추천순으로 정렬
    $query = "SELECT * FROM board2 ORDER BY thumbup DESC";
} else {
    // 기본적으로 순번순으로 정렬
    $query = "SELECT * FROM board2 ORDER BY number DESC";
}

$result = $connect->query($query);
$total = mysqli_num_rows($result);
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
</style>
<body>
    <h2 align="center">자유게시판</h2>

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
      <form action="f_search.php" method="get">
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
                <td width="50" align="center">조회수</td>
                <td width="50" align="center">추천수</td>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($rows = mysqli_fetch_assoc($result)) { //DB에 저장된 데이터 수 (열 기준)
                if ($total % 2 == 0) {
                    echo '<tr class="even">';
                } else {
                    echo '<tr>';
                }
            ?>
                <td width="50" align="center"><?php echo $total ?></td>
                <td width="500" align="center">
                    <a href="f_view.php?number=<?php echo $rows['number'] ?>">
                        <?php echo $rows['title'] ?>
                    </a>
                </td>
                <td width="100" align="center"><?php echo $rows['id'] ?></td>
                <td width="200" align="center"><?php echo $rows['date'] ?></td>
                <td width="50" align="center"><?php echo $rows['hit'] ?></td>
                <td width="50" align="center"><?php echo $rows['thumbup'] ?></td>
            </tr>
            <?php
                $total--;
            }
            ?>
        </tbody>
    </table>

    <?php if ($showWriteLink) : ?>
        <div class="text">
            <font style="cursor: hand" onClick="location.href='./f_write.php'">글쓰기</font>
        </div>
    <?php endif; ?>
 
    <!-- 순번순, 조회순, 추천순 정렬 버튼 -->
    <div class="text">
        <a href="?order=number" style="margin-right: 10px;">순번순</a>
        <a href="?order=hit" style="margin-right: 10px;">조회순</a>
        <a href="?order=thumbup">추천순</a>
    </div>
</body>
</html>