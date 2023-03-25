<?php

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'administrator'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'administrator'){
        header('location: error_page.php');
    }

    function editBook(){

        $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $sql = "UPDATE books SET name = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $description, $id);
        mysqli_stmt_execute($stmt);

    }
    
    editBook();
    header('location: admin_panel.php');

?>