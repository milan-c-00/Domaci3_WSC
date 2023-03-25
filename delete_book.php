<?php

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'administrator'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'administrator'){
        header('location: error_page.php');
    }

    function deleteBook(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $book = (int)$_POST['book-id'];

        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $book);
        mysqli_stmt_execute($stmt);

    }
    
    deleteBook();
    header('location: admin_panel.php');

?>