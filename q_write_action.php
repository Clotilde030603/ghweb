<?php
    $connect = mysqli_connect("localhost", "root", "0epK81g3yp<r", "Board3") or die("connect fail");

    $id = $_POST['name'];       // 작성자
    //$password = $_POST['pw'];   // 비밀번호
    $title = $_POST['title'];   // 제목
    $content = $_POST['content']; // 내용
    $date = date('Y-m-d H:i:s'); // 작성일시
    $secret=(int)$_POST['secret'];
    $o_name = $_FILES['b_file']['name'];

    // Handling file upload
    $uploadDir = "/file/";
    $targetFile = $uploadDir . $o_name;  //     /file/r.txt
   

if (!empty($_FILES['b_file']['tmp_name']) && is_uploaded_file($_FILES['b_file']['tmp_name'])) {

    move_uploaded_file($_FILES['b_file']['tmp_name'], $targetFile);
    
} else {
      // 파일이 선택되지 않은 경우에는 빈 파일과 파일 경로를 사용
      $fileName = "";
      $uniqueFileName = "";
      $targetFile = "";

 }
   

    $query = "INSERT INTO board3 (title, content, date, id, password, secret, file, link ) 
            VALUES ('$title', '$content', '$date', '$id', '0', '$secret', '$o_name', '$targetFile')";
    $result = $connect->query($query);
    
    if ($result) {
        echo "<script>alert('글 등록에 성공했습니다.');window.location.href='q_Board.php'; </script>";
    } else {
        echo "글 등록에 실패했습니다.";
    }

    mysqli_close($connect);

?>
