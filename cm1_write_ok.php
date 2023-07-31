<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Note 데이터베이스에 답글 정보를 저장하는 함수
function saveComment($boardID, $userID, $content, $o_name, $targetFile) {
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3') or die("connect fail");
    $query = "INSERT INTO re (boardID, userID, content, date, file, link) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $boardID, $userID, $content, $o_name, $targetFile); // 수정된 바인딩 타입
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// 답글 작성 폼에서 전송된 데이터 받아오기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boardID = $_GET['number']; // 게시물 번호
    $userID = $_SESSION['userID']; // 로그인 세션 아이디를 사용자 ID로 받아옴
    $content = $_POST['content']; // 댓글 내용
    $o_name = $_FILES['b_file']['name'];

    // Handling file upload
    $uploadDir = "/file/";
    $targetFile = $uploadDir . $o_name;

    if (!empty($_FILES['b_file']['tmp_name']) && is_uploaded_file($_FILES['b_file']['tmp_name'])) {
        move_uploaded_file($_FILES['b_file']['tmp_name'], $targetFile);
    } else {
        // 파일이 선택되지 않은 경우에는 빈 파일과 파일 경로를 사용
      $fileName = "";
      $uniqueFileName = "";
      $targetFile = "";
    }

    // 답글 정보를 Note 데이터베이스의 'reply' 테이블에 저장
    saveComment($boardID, $userID, $content, $o_name, $targetFile);

    // 댓글 작성 후, 해당 게시물 페이지로 리다이렉션 (댓글 목록이 표시된 상태로)
    header("Location: cm1.php?number=$boardID");
    exit();
}
?>
