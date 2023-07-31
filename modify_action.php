<?php
// 데이터베이스 연결
$connect = mysqli_connect("localhost", "root", "0epK81g3yp<r", "Board1") or die ("connect fail");

// 사용자로부터 전달된 데이터 받아오기
$number = $_POST['number'];
$title = $_POST['title'];
$content = $_POST['content'];
$date = date('Y-m-d H:i:s');

// Prepared Statements를 사용하여 SQL Injection 방지
$query = "UPDATE board1 SET title=?, content=?, date=? WHERE number=?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $date, $number);

// 쿼리 실행
$result = mysqli_stmt_execute($stmt);

if ($result) {
    // 수정 성공 시 알림창 띄우고, 해당 게시글로 리다이렉션
    echo '<script>alert("수정되었습니다.");</script>';
    header("Location: ./view.php?number=$number");
    exit();
} else {
    echo "fail";
}

// 연결과 관련된 자원 정리
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>