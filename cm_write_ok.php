<?php
session_start();

// Note 데이터베이스에 댓글 정보를 저장하는 함수
function saveComment($boardID, $userID, $content) {
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
    $query = "INSERT INTO reply (boardID, userID, content, date) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iss", $boardID, $userID, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// 댓글 작성 폼에서 전송된 데이터 받아오기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boardID = $_GET['number']; // 게시물 번호
    $userID = $_SESSION['userID']; // 로그인 세션 아이디를 사용자 ID로 받아옴
    $content = $_POST['content']; // 댓글 내용

    // 댓글 정보를 Note 데이터베이스의 'reply' 테이블에 저장
    saveComment($boardID, $userID, $content);

    // 댓글 작성 후, 해당 게시물 페이지로 리다이렉션 (댓글 목록이 표시된 상태로)
    header("Location: cm.php?number=$boardID");
    exit();
}
?>
