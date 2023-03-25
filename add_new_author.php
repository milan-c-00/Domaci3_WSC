<?php

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'administrator'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'administrator'){
        header('location: error_page.php');
    }

    function addNewAuthor(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $author = $_POST['author'];

        $sql = "INSERT INTO author(name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $author);
        mysqli_stmt_execute($stmt);

    }
    
    addNewAuthor();
    header('location: admin_panel.php');

?>