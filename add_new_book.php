<?php

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'administrator'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'administrator'){
        header('location: error_page.php');
    }
    
    function addNewBook(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $name = $_POST['name'];
        $author = (int)$_POST['selected-author'];
        $description = $_POST['description'];
        $availability = 'Available';

        $sql = "INSERT INTO books(name, author, description, availability) VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $author, $description, $availability);
        mysqli_stmt_execute($stmt);

    }
    
    addNewBook();
    header('location: admin_panel.php');

?>