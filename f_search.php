<?php
if (empty($_REQUEST["search_word"])) { // 검색어가 empty일 때 예외처리를 해준다.
    $search_word = "";
} else {
    $search_word = $_REQUEST["search_word"];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Board1 검색 결과</title>
    <style>
        h1 {
            text-align: center;
        }

        table {
            width: 90%;
            margin-left: 5%;
            border-top: 1px solid #444444;
            border-collapse: collapse;
        }

        tr {
            width: 32%;
            border-bottom: 1px solid #444444;
        }

        td {
            width: 32%;
            border-bottom: 1px solid #efefef;
        }

        table .even {
            background: #efefef;
        }

        .text {
            text-align: center;
            padding-top: 20px;
            color: #000000
        }

        .text:hover {
            text-decoration: underline;
        }

        a:link {
            color: #57A0EE;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h1>게시물 검색 결과</h1>

    <?php
    // 데이터베이스 연결 정보
    $db_id = "root";
    $db_pw = "0epK81g3yp<r";
    $db_name = "Board2";
    $db_domain = "localhost";

    // 사용자가 검색한 키워드 (검색 폼에서 전달된 값)
    $search_keyword = $_GET['search'];
    $_search_cat = $_GET['catgo'];

    // 데이터베이스 연결
    $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);

    // 연결 확인
    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    // SQL 쿼리 작성
    $sql = "SELECT * FROM board2 WHERE $_search_cat LIKE '%$search_keyword%'";

    // 쿼리 실행
    $result = $conn->query($sql);

    // 검색 결과가 있을 때
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>제목</th><th>작성자</th><th>내용</th></tr>";

        // 검색 결과 행 출력
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='f_view.php?number=" . $row['number'] . "'>" . $row['title'] . "</a></td>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['content'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        // 검색 결과가 없을 때
        echo "검색 결과가 없습니다.";
    }

    // 연결 종료
    $conn->close();
    ?>

</body>

</html>