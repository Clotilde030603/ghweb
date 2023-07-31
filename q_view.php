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

$connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3');
$number = $_GET['number'];
session_start();

// 사용자가 로그인 상태인지 확인
$showButtons = false;

// 게시물 정보 가져오기
$query = "SELECT title, content, date, id, secret, file, link FROM board3 WHERE number = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $number);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_assoc($result);

// 해당 게시물의 작성자와 로그인 사용자를 비교하여 수정, 삭제 버튼 표시 여부 결정
$showButtons = false; // 초기값을 false로 설정

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

// 게시물에 답글이 달렸는지 확인하는 함수
function hasReplies($boardID) {
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3') or die("데이터베이스 연결 실패");
    $query = "SELECT COUNT(*) as count FROM re WHERE boardID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $boardID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $replyCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $replyCount > 0;
}

// 게시물에 답글이 달렸는지 확인
$can_delete_mod = true;
if (hasReplies($number)) {
    $can_delete_mod = false;
    
}
?>

<!-- 스타일 시트 추가 -->
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
        width: 40px;
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



<!-- 게시물 내용 출력 -->
<table class="view_table" align="center">
    <tr>
        <td colspan="5" class="view_title"><?php echo $rows['title']?></td>
    </tr>
    <tr>
        <td class="view_id">작성자</td>
        <td class="view_id2"><?php echo $rows['id']?></td>
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
            <a href="download.php?board=board3&number=<?= $number ?>"><?= $rows['file'] ?></a>
        <?php else: ?>
            파일 없음
        <?php endif; ?>
        </td>
    </tr>

</table>

<!-- MODIFY & DELETE -->
<div class="view_btn">
<button class="view_btn1" onclick="location.href='./q_Board.php'">목록으로</button>
    <?php if ($_SESSION['userID']): ?>
            <!-- 수정버튼 -->
        <?php if (($_SESSION['userID'] === $rows['id']) && $can_delete_mod): ?>
            <button class="view_btn1" onclick="location.href='./q_modify.php?number=<?= $number ?>'">수정</button>
        <?php endif; ?>

            <!-- 삭제 버튼 -->
        <?php if (($_SESSION['userID'] === $rows['id']) && $can_delete_mod): ?>
            <button class="view_btn1" onclick="location.href='./q_delete.php?number=<?= $number ?>'">삭제</button>
        <?php elseif (($_SESSION['roll'] === 'admin') && $can_delete_mod): ?>
            
            <button class="view_btn1" onclick="location.href='./q_delete.php?number=<?= $number ?>'">삭제</button>
        <?php endif; ?>

        <button class="view_btn1" onclick="location.href='./cm1.php?number=<?= $number ?>'">답글</button>
       
 
 

        
        
    <?php endif; ?>
</div>