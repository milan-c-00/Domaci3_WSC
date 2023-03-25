<?php

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'regular_user'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'regular_user'){
        header('location: error_page.php');
    }

    function returnBook(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $book_id = (int)$_POST['book_id'];
        $borrow_id = (int)$_POST['borrow_id'];
        $availability = 'Available';
        
        $sql = "DELETE FROM user_book WHERE id = ?";    
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $borrow_id);
        mysqli_stmt_execute($stmt);

        $sql = "UPDATE books SET availability = ? WHERE id = ?";        // Updating books availability 
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $availability, $book_id);
        mysqli_stmt_execute($stmt);

    }
    
    returnBook();
    header('location: my_books.php');

?>