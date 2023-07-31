<?php
session_start();



if (isset($_POST['commentID']) && isset($_POST['boardID'])) {
    $commentID = $_POST['commentID'];
    $boardID = $_POST['boardID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");

    // 댓글 삭제 쿼리를 준비하고 실행합니다.
    $query = "UPDATE reply SET content = '삭제된 댓글입니다', userID = '뽀짝이' WHERE idx = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫습니다.
    mysqli_close($conn);

    // 댓글 삭제 후, 해당 게시물 페이지로 리다이렉션합니다. (댓글 목록이 표시된 상태로)
   header("Location: cm.php?number=$boardID");
   exit();
} else {
    // 삭제를 위한 필수 데이터가 없는 경우, 예기치 않은 접근으로 간주하여 보안상 로그인 페이지로 이동시킵니다.
    header('Location: login.php');
    exit();
}
?>
