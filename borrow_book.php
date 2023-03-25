<?php

    session_start();

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'regular_user'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'regular_user'){
        header('location: error_page.php');
    }
    
    function borrowBook(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $book_id = (int)$_POST['book_id'];
        $user_id = (int)$_POST['user_id'];
        $availability = 'Borrowed';
        
        $sql = "INSERT INTO user_book(user_id, book_id, start_date) VALUES (?,?,NOW())";    // Current user borrowing book
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $book_id);
        mysqli_stmt_execute($stmt);

        $sql = "UPDATE books SET availability = ? WHERE id = ?";        // Updating books availability 
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $availability, $book_id);
        mysqli_stmt_execute($stmt);

    }
    
    borrowBook();
    header('location: my_books.php');

?>