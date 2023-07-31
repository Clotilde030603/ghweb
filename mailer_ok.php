<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  
  // PHPMailer 선언
  $mail = new PHPMailer(true);
  // 디버그 모드(production 환경에서는 주석 처리한다.)
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
  // SMTP 서버 세팅
  $mail->isSMTP();
  try {

    $userID = $_POST['userID'];
    $userEmail = $_POST['userEmail'];

	  $authcode = rand(100000, 999999);
	
    // 구글 smtp 설정
    $mail->Host = "smtp.gmail.com";
    // SMTP 암호화 여부
    $mail->SMTPAuth = true;
    // SMTP 포트
    $mail->Port = 465;
    // SMTP 보안 프초트콜
    $mail->SMTPSecure = "ssl";
    // gmail 유저 아이디
    $mail->Username = "gi6402906@gmail.com";
    // gmail 패스워드
    $mail->Password ="qaaowfrycsgvnjty";
    // 인코딩 셋
    $mail->CharSet = 'utf-8'; 
    $mail->Encoding = "base64";
    
    // 보내는 사람
    $mail->setFrom('admin@gmail.com', 'Hyun');
    // 받는 사람
    $mail->AddAddress("$userEmail", $userID); 
    
    // 본문 html 타입 설정
    $mail->isHTML(true);
    // 제목
    $mail->Subject = 'Hyun 이메일 인증코드';
    // 본문 (HTML 전용)
    $mail->Body    = 'Hyun 인증 코드: <b>'.$authcode.'</b>';
    // 본문 (non-HTML 전용)
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->Send();
    echo "해당 이메일에 인증 코드를 전송하였습니다.";
  } catch (Exception $e) {
    echo $e->getMessage();
  }

$userID = $_POST['userID'];
$userEmail = $_POST['userEmail'];

if (!is_null($userID) && !is_null($userEmail) && !is_null($authcode)) {
    $db_id = "root";
    $db_pw = "0epK81g3yp<r";
    $db_name = "User";
    $db_domain = "localhost";

    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
    $sql = "SELECT userID FROM userauth WHERE userID = '$userID';";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $userID_e = $row['userID'];
    }
    if ($userID == $userID_e) {
        $wu = 1;
    } else {
        $dbconn = "INSERT INTO userauth (userID, userEmail, authcode) VALUES ('$userID', '$userEmail', '$authcode');";
        mysqli_query($conn, $dbconn);
    }
}

