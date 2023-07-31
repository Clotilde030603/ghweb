<!DOCTYPE html>
<html>
<head>
    <title>댓글</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .comment-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .comment {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #f0f0f0;
            border-radius: 5px;
        }
        .comment-info {
            font-size: 12px;
            color: #888;
        }
        .comment-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .comment-form textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }
        .comment-form button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="comment-container">
        <h2>댓글 목록</h2>
        <?php
        session_start();

        // 댓글 목록을 가져오는 함수 
        function getComments($boardID) {
            $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
            $query = "SELECT idx, userID, content, date FROM reply WHERE boardID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $boardID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $comments = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $comments[] = $row;
            }
        
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        
            return $comments;
        }

        function getWriter($boardID) {
            // DB 연결 정보와 쿼리 실행 코드는 여기에 추가
            $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");
            $query = "SELECT id FROM board2 WHERE number = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $boardID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            $writerID = mysqli_fetch_assoc($result)['id'];
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        
            return $writerID;
        }

        $number = $_GET['number'];

        // 댓글 목록을 가져옴
        $comments = getComments($number);

        // 댓글 목록을 표시
        $boardWriter = getWriter($number);
        

        $isadmin = ($_SESSION['roll'] === 'admin');

        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<div class="comment-info">';
            echo '작성자: ' . $comment['userID'] . ' | 작성일: ' . $comment['date'];

            // 삭제된 댓글인지 확인
            $isDeletedComment = ($comment['content'] === 'deleted');
            if($comment['userID'] != '뽀짝이'){
                if ($_SESSION['userID'] === $comment['userID'] || $_SESSION['userID'] === $boardWriter || $isadmin) {
                    echo '<form method="post" action="cm_delete.php">';
                    echo '<input type="hidden" name="boardID" value="' . $number . '">';
                    echo '<input type="hidden" name="commentID" value="' . $comment['idx'] . '">'; // 댓글 테이블에 commentID 열이 있다고 가정합니다.
                    echo '<button type="submit">삭제</button>';
                    echo '</form>';
                }
    
                if ($_SESSION['userID'] === $comment['userID']) {
                    echo '<form method="post" action="cm_edit.php">';
                    echo '<input type="hidden" name="boardID" value="' . $number . '">';
                    echo '<input type="hidden" name="commentID" value="' . $comment['idx'] . '">';
                    echo '<input type="hidden" name="updated_content" value="' . $comment['content'] . '">';
                    echo '<button type="submit">수정</button>';
                    echo '</form>';
                }
            }

            echo '</div>';

            // 댓글이 삭제된 경우 '삭제된 댓글입니다'라고 표시합니다.
            if ($comment['content'] === 'deleted') {
                echo '<p>삭제된 댓글입니다</p>';
            } else {
                echo '<p>' . $comment['content'] . '</p>';
            }

            echo '</div>';
        }
        ?>

    <div class="comment-form">
        <h2>댓글 작성</h2>
        <!-- 댓글 작성 폼 -->
        <form method="post" action="cm_write_ok.php?number=<?php echo $number ?>">
            <textarea name="content" placeholder="댓글을 입력하세요..."></textarea>
            <br>
            <button type="submit">댓글 작성</button>
        </form>
    </div>
     <!-- 버튼으로 만들어진 해당 게시물로 돌아가는 링크 -->
     <form method="get" action="f_view.php">
        <input type="hidden" name="number" value="<?php echo $number ?>">
        <button type="submit">게시물로 돌아가기</button>
    </form>

</body>
</html>