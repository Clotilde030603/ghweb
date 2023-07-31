<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the comment ID and board ID from the form
    $commentID = $_POST['commentID'];
    $boardID = $_POST['boardID'];

    

    // Get the updated content from the form
    $updatedContent = $_POST['updated_content'];

    
// Connect to the database
    $conn = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2') or die("connect fail");

    // Prepare the query to update the comment content
    $query = "UPDATE reply SET content = ? WHERE idx = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $updatedContent, $commentID);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Comment content updated successfully, redirect back to the board page
        header("Location: cm.php?number=$boardID");
        exit();
    } else {
        // Error occurred while updating the comment content
        // You can handle the error here (e.g., display an error message)
        echo "Error occurred while updating the comment content.";
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
    ?>
