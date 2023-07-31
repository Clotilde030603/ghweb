<?php
session_start();
if (!isset($_SESSION['userID'])) {
    // 로그인되어 있지 않은 경우 로그인 페이지로 이동하거나 처리할 로직을 추가할 수 있습니다.
    header("Location: login.php");
    exit;
}

$writerName = $_SESSION['userID'];
?>

<!DOCTYPE>
 
<html>
<head>
        <meta charset = 'utf-8'>
</head>
<style>
        table.table2{
                border-collapse: separate;
                border-spacing: 1px;
                text-align: left;
                line-height: 1.5;
                border-top: 1px solid #ccc;
                margin : 20px 10px;
        }
        table.table2 tr {
                 width: 50px;
                 padding: 10px;
                font-weight: bold;
                vertical-align: top;
                border-bottom: 1px solid #ccc;
        }
        table.table2 td {
                 width: 100px;
                 padding: 10px;
                 vertical-align: top;
                 border-bottom: 1px solid #ccc;
        }
 
</style>
<body>
        <form method ="POST" action = "q_write_action.php" enctype="multipart/form-data">
        <table  style="padding-top:50px" align = center width=700 border=0 cellpadding=2 >
                <tr>
                <td height=20 align= center bgcolor=#ccc><font color=white>Q&A 글쓰기</font></td>
                </tr>
                <tr>
                <td bgcolor=white>
                <table class = "table2">
                        <tr>
                            <td>작성자</td>
                            <td>
                                <input type="" name="name" size=20 value="<?php echo $writerName; ?>" readonly>
                            </td>
                        </tr>
 
                        <tr>
                        <td>제목</td>
                        <td><input type = text name = "title" size=60></td>
                        </tr>
 
                        <tr>
                        <td>내용</td>
                        <td><textarea name = "content" cols=85 rows=15></textarea></td>
                        </tr>
 
                        <tr>
                        <td>비밀글</td>
                        <td><input type ="checkbox" value="1" name = "secret"></td>
                        </tr>

                        <tr>
                        <td>파일</td>
                        <td><input class ="file" name="b_file" type="file" ></td>
                        </tr>

        </table>
 
                        <center>
                        <input type = "submit" value="작성">
                        </center>
                </td>
                </tr>
        </table>
        </form>
</body>
</html>