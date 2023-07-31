<?php
session_start();

// 사용자가 로그인되어 있는지 확인
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

    // roll 값이 'admin'인 경우에만 삭제 권한을 부여
    if ($userRoll === 'admin') {
        // 게시물 삭제를 처리하는 부분
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board1') or die("connect fail");
        $number = $_GET['number'];

        // 게시물 삭제 쿼리 실행
        $query = "DELETE FROM board1 WHERE number=$number";
        $result = $connect->query($query);

        // 삭제 성공 시 알림창 띄우고, 게시판 목록으로 리다이렉션
        if ($result) {
            echo '<script>alert("게시물이 삭제되었습니다.");</script>';
            header("Location: ./Board.php");
            exit();
        } else {
            echo "게시물 삭제에 실패하였습니다.";
        }
    } else {
        echo "권한이 없습니다.";
    }

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);
} else {
    echo "로그인이 필요합니다.";
}
?>