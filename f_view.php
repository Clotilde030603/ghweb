<style>
.view_table {
border: 1px solid #444444;
margin-top: 30px;
}
.view_title {
height: 30px;
text-align: center;
background-color: #cccccc;
color: white;
width: 1000px;
}
.view_id {
text-align: center;
background-color: #EEEEEE;
width: 30px;
}
.view_id2 {
background-color: white;
width: 60px;
}
.view_hit {
background-color: #EEEEEE;
width: 30px;
text-align: center;
}
.view_hit2 {
background-color: white;
width: 60px;
}
.view_content {
padding-top: 20px;
border-top: 1px solid #444444;
height: 500px;
}
.view_btn {
width: 700px;
height: 200px;
text-align: center;
margin: auto;
margin-top: 50px;
}
.view_btn1 {
height: 50px;
width: 100px;
font-size: 20px;
text-align: center;
background-color: white;
border: 2px solid black;
border-radius: 10px;
}
.view_comment_input {
width: 700px;
height: 500px;
text-align: center;
margin: auto;
}
.view_text3 {
font-weight: bold;
float: left;
margin-left: 20px;
}
.view_com_id {
width: 100px;
}
.view_comment {
width: 500px;
}
</style>


<?php
$connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2');
$number = $_GET['number'];
session_start();

// 사용자가 로그인 상태인지 확인
$showButtons = false;

// 게시물 정보 가져오기
$query = "SELECT title, content, date, hit, thumbup, id, file, link FROM board2 WHERE number = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $number);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_assoc($result);

// 해당 게시물의 작성자와 로그인 사용자를 비교하여 수정, 삭제 버튼 표시 여부 결정
if (isset($_SESSION['userID']) && $_SESSION['userID'] === $rows['id']) {
    $showButtons = true;
}

// 데이터베이스 연결
$conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'User') or die("connect fail");

// userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
$query = "SELECT roll FROM user WHERE userID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['userID']);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $userRoll);
mysqli_stmt_fetch($stmt);

// statement와 연결을 닫음
mysqli_stmt_close($stmt);

// 데이터베이스 연결을 닫음
mysqli_close($conn);

// 만약 사용자의 roll 값이 "admin"이면 수정 및 삭제 버튼 표시
if ($userRoll === 'admin') {
    $showButtons = true;
}

// 해당 사용자가 해당 게시물을 이미 추천했는지 확인
$conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
$query = "SELECT COUNT(*) FROM rcd WHERE userID = ? AND boardID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $_SESSION['userID'], $number);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

// 이미 추천한 경우, 추천 취소 버튼 표시
$alreadyRecommended = ($count > 0);

// 추천 버튼 또는 추천 취소 버튼 클릭 처리
if (isset($_POST['recommend']) && $_SESSION['userID']) {
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");

    if ($alreadyRecommended) {
        // 이미 추천한 경우, 추천 취소 처리
        // 'board1' 테이블의 thumbup 값을 감소
        $query = "UPDATE board2 SET thumbup = thumbup - 1 WHERE number = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // 'rcd' 테이블에서 사용자의 추천 정보 삭제
        $query = "DELETE FROM rcd WHERE userID = ? AND boardID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $_SESSION['userID'], $number);
    } else {
        // 추천하지 않은 경우, 추천 처리
        // 'board1' 테이블의 thumbup 값을 증가
        $query = "UPDATE board2 SET thumbup = thumbup + 1 WHERE number = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // 'rcd' 테이블에 사용자의 추천 정보 추가
        $query = "INSERT INTO rcd (userID, boardID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $_SESSION['userID'], $number);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    // 현재 페이지로 다시 이동 (새로고침 방지)
    header("Location: {$_SERVER['PHP_SELF']}?number=$number");
    exit();
}


// 사용자의 IP 주소와 게시물 번호가 'click' 테이블에 최근 1시간 내에 있는지 확인합니다.
$userIP = $_SERVER['REMOTE_ADDR'];
$currentTime = date('Y-m-d H:i:s');
$hour_ago = date('Y-m-d H:i:s', strtotime('+1 hour'));
$last_view_query = "SELECT * FROM cl WHERE userIP = '$userIP' AND boardID = $number ORDER BY date DESC LIMIT 1";
$last_view_result = $connect->query($last_view_query);
$last_date = $last_view_result->fetch_assoc()['date'];
$last_view_row = mysqli_num_rows($last_view_result);
$afterhour = date('Y-m-d H:i:s', strtotime('+1 hour'));


if (($last_view_row === 0) || (strtotime($currentTime) > strtotime($last_date))) {
    // 사용자가 컨텐츠를 1시간 이내에 열람하지 않은 경우, 조회수를 증가시키고 noteren 테이블에 열람 정보를 추가합니다.
    $connect->query("UPDATE board2 SET hit = hit + 1 WHERE number = $number");
    $connect->query("INSERT INTO cl (userIP, boardID, date) VALUES ('$userIP', $number, '$afterhour')");
} else {
    // 사용자가 컨텐츠를 1시간 이내에 이미 열람한 경우, 조회수를 다시 증가시키지 않고 아무런 추가 동작을 수행하지 않습니다.
}


?>




<table class="view_table" align="center">
    <tr>
        <td colspan="5" class="view_title"><?php echo $rows['title']?></td>
    </tr>
    <tr>
        <td class="view_id">작성자</td>
        <td class="view_id2"><?php echo $rows['id']?></td>
        <td class="view_hit">조회수</td>
        <td class="view_hit2"><?php echo $rows['hit']?></td>
        <td class="view_hit">추천수</td>
        <td class="view_hit2"><?php echo $rows['thumbup']?></td>
    </tr>

    <tr>
        <td colspan="5" class="view_content" valign="top">
            <?php echo $rows['content']?>
        </td>
    </tr>

    <tr>
        <td>파일:</td>
        <td>
        <?php if (!empty($rows['file'])): ?>
            <a href="download.php?board=board2&number=<?= $number ?>"><?= $rows['file'] ?></a>
        <?php else: ?>
            파일 없음
        <?php endif; ?>
        </td>
    </tr>

</table>

<!-- MODIFY & DELETE -->
<div class="view_btn">
    <?php if ($_SESSION['userID']): ?>
        <?php if ($userRoll === 'admin' && $_SESSION['userID'] === $rows['id']): ?>
            <button class="view_btn1" onclick="location.href='./f_Board.php'">목록으로</button>
            <button class="view_btn1" onclick="location.href='./f_modify.php?number=<?= $number ?>'">수정</button>
            <button class="view_btn1" onclick="location.href='./f_delete.php?number=<?= $number ?>'">삭제</button>
        <?php elseif ($userRoll === 'admin'): ?>
            <button class="view_btn1" onclick="location.href='./f_Board.php'">목록으로</button>
            <button class="view_btn1" onclick="location.href='./f_delete.php?number=<?= $number ?>'">삭제</button>
        <?php elseif ($_SESSION['userID'] === $rows['id']): ?>
            <button class="view_btn1" onclick="location.href='./f_Board.php'">목록으로</button>
            <button class="view_btn1" onclick="location.href='./f_modify.php?number=<?= $number ?>'">수정</button>
            <button class="view_btn1" onclick="location.href='./f_delete.php?number=<?= $number ?>'">삭제</button>
        <?php else: ?>
            <button class="view_btn1" onclick="location.href='./f_Board.php'">목록으로</button>
        <?php endif; ?>

        <?php
        if ($alreadyRecommended) {
            echo '<form method="post">';
            echo '<input type="submit" name="recommend" value="추천 취소" class="view_btn1">';
            echo '</form>';
        } else {
            echo '<form method="post">';
            echo '<input type="submit" name="recommend" value="추천" class="view_btn1">';
            echo '</form>';
        }
        ?>

        <!-- 댓글 버튼 추가 -->
        <button class="view_btn1" onclick="location.href='./cm.php?number=<?= $number ?>'">댓글</button>
    <?php else: ?>
        <!-- 로그인하지 않은 경우에도 댓글 버튼 표시 -->
        <button class="view_btn1" onclick="location.href='./cm.php?number=<?= $number ?>'">댓글</button>
    <?php endif; ?>
</div>