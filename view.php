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
                $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board1');
                $number = $_GET['number'];
                session_start();
                $query = "select title, content, date, hit, id, file, link from board1 where number =$number";
                $result = $connect->query($query);
                $rows = mysqli_fetch_assoc($result);
        ?>
 
        <table class="view_table" align=center>
        <tr>
                <td colspan="4" class="view_title"><?php echo $rows['title']?></td>
        </tr>
        <tr>
                <td class="view_id">작성자</td>
                <td class="view_id2"><?php echo $rows['id']?></td>
                <td class="view_hit">조회수</td>
                <td class="view_hit2"><?php echo $rows['hit']?></td>
        </tr>
 
 
        <tr>
                <td colspan="4" class="view_content" valign="top">
                <?php echo $rows['content']?></td>
        </tr>

        <tr>
        <td>파일:</td>
        <td>
        <?php if (!empty($rows['file'])): ?>
            <a href="download.php?board=board1&number=<?= $number ?>"><?= $rows['file'] ?></a>
        <?php else: ?>
            파일 없음
        <?php endif; ?>
        </td>
        </tr>

        </table>

    <?php

$showButtons = false; // 수정과 삭제 버튼을 보일지 여부를 초기화

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

    // roll 값이 'user'인 경우에만 수정과 삭제 버튼을 보이지 않도록 설정
    if ($userRoll === 'admin') {
        $showButtons = true;
    } else {
        $showButtons = false;
    }

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);
}
?>
 
 <?php
$connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board1');
$number = $_GET['number'];

// 사용자의 IP 주소와 게시물 번호가 'click' 테이블에 최근 1시간 내에 있는지 확인합니다.
$userIP = $_SERVER['REMOTE_ADDR'];
$currentTime = date('Y-m-d H:i:s');
$hour_ago = date('Y-m-d H:i:s', strtotime('+1 hour'));
$last_view_query = "SELECT * FROM click WHERE userIP = '$userIP' AND boardID = $number ORDER BY date DESC LIMIT 1";
$last_view_result = $connect->query($last_view_query);
$last_date = $last_view_result->fetch_assoc()['date'];
$last_view_row = mysqli_num_rows($last_view_result);
$afterhour = date('Y-m-d H:i:s', strtotime('+1 hour'));


if (($last_view_row === 0) || (strtotime($currentTime) > strtotime($last_date))) {
    // 사용자가 컨텐츠를 1시간 이내에 열람하지 않은 경우, 조회수를 증가시키고 noteren 테이블에 열람 정보를 추가합니다.
    $connect->query("UPDATE board1 SET hit = hit + 1 WHERE number = $number");
    $connect->query("INSERT INTO click (userIP, boardID, date) VALUES ('$userIP', $number, '$afterhour')");
} else {
    // 사용자가 컨텐츠를 1시간 이내에 이미 열람한 경우, 조회수를 다시 증가시키지 않고 아무런 추가 동작을 수행하지 않습니다.
}

// 게시물 정보 가져오기
$query = "SELECT title, content, date, hit, id FROM board1 WHERE number =$number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);
?>

        <!-- MODIFY & DELETE -->
        <div class="view_btn">
    <?php if ($showButtons) : ?>
        <button class="view_btn1" onclick="location.href='./Board.php'">목록으로</button>
        <button class="view_btn1" onclick="location.href='./modify.php?number=<?= $number ?>&id=<?= $_SESSION['userid'] ?>'">수정</button>
        <button class="view_btn1" onclick="location.href='./delete.php?number=<?= $number ?>&id=<?= $_SESSION['userid'] ?>'">삭제</button>
    <?php else : ?>
        <button class="view_btn1" onclick="location.href='./Board.php'">목록으로</button>
    <?php endif; ?>
</div>