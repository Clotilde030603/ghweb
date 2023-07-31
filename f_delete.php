<?php
session_start();

// 사용자가 로그인되어 있는지 확인
if (isset($_SESSION['userID'])) {
    $session_userID = $_SESSION['userID'];

    
    
        // 게시물 삭제를 처리하는 부분
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
        $number = $_GET['number'];

        // 게시물 삭제 쿼리 실행
        $query = "DELETE FROM board2 WHERE number=$number";
        $result = $connect->query($query);

        // 삭제 성공 시 알림창 띄우고, 게시판 목록으로 리다이렉션
        if ($result) {
            echo '<script>alert("게시물이 삭제되었습니다.");</script>';
            header("Location: ./f_Board.php");
            exit();
        } else {
            echo "게시물 삭제에 실패하였습니다.";
        }
    }

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);

?>