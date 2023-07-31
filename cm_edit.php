<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Replace 'login.php' with the actual login page URL
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the comment ID and board ID from the form
    $commentID = $_POST['commentID'];
    $boardID = $_POST['boardID'];

    

    // Get the updated content from the form
    $updatedContent = $_POST['updated_content'];

    

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>댓글 수정</title>
    <!-- Add any necessary CSS styles here -->
</head>
<body>
    <h2>댓글 수정</h2>
    <form method="post" action="cm_edit-ok.php">
        <input type="hidden" name="boardID" value="<?php echo $_POST['boardID']; ?>">
        <input type="hidden" name="commentID" value="<?php echo $_POST['commentID']; ?>">
        <textarea name="updated_content" placeholder="댓글을 수정하세요..."><?php echo $_POST['updated_content']; ?></textarea>
        <br>
        <button type="submit">댓글 수정 완료</button>

    </form>
</body>
</html>
