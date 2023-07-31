<?php 
    $board  = $_GET['board'];  // 게시글 종류
    $number = $_GET['number']; // 게시글 번호
    $idx = $_GET['idx'];

    if($board === 'board1'){ 
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board1');
        $query = "select file, link from $board where number =$number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);
    }

    else if($board === 'board2'){ 
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board2');
        $query = "select file, link from $board where number =$number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);
    }

    else if($board === 'board3'){
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3');
        $query = "select file, link from $board where number =$number";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);    
    }

    else if($board === 're'){
        $connect = mysqli_connect('localhost', 'root', '0epK81g3yp<r', 'Board3');
        $query = "select file, link from $board where idx =$idx";
        $result = $connect->query($query);
        $row = mysqli_fetch_assoc($result);    
    }
    
    $path = $row['link'];
    $name = $row['file'];       
    header("content-type: application/octetstream");
    header("Content-disposition: attachment; filename=".$name);
    header("content-length: ".filesize($path));
    header('Content-Transfer-Encoding: binary');
    ob_clean();
    readfile("$path");
    exit;
?>
