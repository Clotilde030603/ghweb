<?php
session_start();
require "top.php";

$wu = 0; // Initialize $wu to 0

$userID = $_POST['userID'];
$userPW = $_POST['userPW'];
$userName = $_POST['userName'];
$userEmail = $_POST['userEmail'];

if (!is_null($userID) && !is_null($userPW) && !is_null($userName) && !is_null($userEmail)) {
    $db_id = "root";
    $db_pw = "0epK81g3yp<r";
    $db_name = "User";
    $db_domain = "localhost";

    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
    $sql = "SELECT userID FROM user WHERE userID = '$userID';";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $userID_e = $row['userID'];
    }
    if ($userID == $userID_e) {
        $wu = 1;
    } else {
        // 비밀번호를 해시화하여 저장
        $hashedPW= password_hash($userPW, PASSWORD_DEFAULT);
        $dbconn = "INSERT INTO user (userID, userPW, userName, userEmail) VALUES ('$userID', '$hashedPW', '$userName', '$userEmail');";
        mysqli_query($conn, $dbconn);

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
        $sql = "SELECT * FROM userauth WHERE userEmail = '$userEmail';";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_array($result);

        //print_r($_POST['authcode']);
        //print_r($rows);
        
        // 이메일 인증 성공시에만 회원가입 성공 메시지 출력
        if (($_POST['authcode'] == $rows['authcode'])) {
            echo "<script>alert('회원가입 성공.'); document.location.href='login.php'; </script>";
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="CSS/style_register.css">

    <title>Hyun's Registration</title>
    <style>
        body { font-family: sans-serif; font-size: 20px; }
        input, button { font-family: inherit; font-size: inherit; }
    </style>
</head>
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>

<body>
<div class="login-wrapper">
    <h1>회원 가입</h1>
    <form method="POST" action="register.php" id="login-form">
        <p><input type="text" name="userID" class="userID" placeholder="ID" required></p>
        <p><input type="password" name="userPW" placeholder="Password" required></p>
        <p><input type="text" name="userName" placeholder="Nickname" required></p>
        <input type="text" name="userEmail" class="userEmail" placeholder="Email" required>
        <!-- 버튼을 클릭할 때만 인증 코드 입력란을 보여줍니다. -->
        <div id="auth-code-input" style="display: block;">
            <p><input type="text" name="authcode"  placeholder="인증 코드"></p>
        </div>
        <p><button id="Emailbtn" class="e_btn" type="button">이메일 인증</button></p>
        <input type="submit" value="회원 가입">

        <?php
        if ($wu == 1) {
            echo "<p>사용자 이름이 중복되었거나 인증 코드가 일치하지 않습니다.</p>";
        }
        if ($wp == 1) {
            echo "<p>비밀번호가 일치하지 않습니다.</p>";
        }
        ?>
    </form>
</div>
<script>
    $(()=>{
        $(document).on("click", ".e_btn", function(){
            $.ajax({
                type:"post",
                url : "mailer_ok.php",
                data : {"userID" : $(".userID").val(), "userEmail" : $(".userEmail").val()},
                success : function(data){
                    if(data){
                        alert(data);
                    } else {
                        if(msg != ''){
                            alert(msg);
                        }
                        if(move != ''){
                            document.location.href = "../index.php";
                        }
                        
                    }
                }
        	});
        })
    })
</script>
</body>
</html>

