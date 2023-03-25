<?php

    session_start();

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'regular_user'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'regular_user'){
        header('location: error_page.php');
    }

    $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My books</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="my-gradient">


    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <form action="" method="" class="d-flex me-auto">
                <a href="books.php" class="btn btn-outline-primary">All books</a>
            </form>
            <form class="d-flex ms-auto" action="logout.php" method="post">
                <input type="hidden" name="auth_status" value="">
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
            </div>
        </div>
    </nav>

    <h1 class="text-center custom-text-blue ms-auto me-auto"> My books</h1>

    <div class="container w-75 text-center mt-5">
        <table class="table text-center table-striped admin-panel border">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Start date</th>
                    <th>Return</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    $sql = "SELECT books.id AS book_id, books.name, user_book.id AS borrow_id, user_book.start_date FROM books, user_book WHERE books.id = user_book.book_id AND user_book.user_id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['my_id']);
                    mysqli_stmt_execute($stmt);

                    $data = [];
                    $books = mysqli_stmt_get_result($stmt);

                    while ($row =  mysqli_fetch_assoc($books)) {
                        $data[] = $row;
                    }

                    foreach ($data as $book) {

                        $book_id = $book['book_id'];
                        $name = $book['name'];
                        $borrow_id = $book['borrow_id'];
                        $start_date = $book['start_date'];
                        $my_id = $_SESSION['my_id'];

                        echo
                        "
                            <tr>
                                <td>$name</td>
                                <td>$start_date</td>
                                <td>
                                    <form action=\"return_book.php\" method=\"post\">
                                        <input type=\"hidden\" name=\"borrow_id\" value=\"$borrow_id\"/>
                                        <input type=\"hidden\" name=\"book_id\" value=\"$book_id\"/>
                                        <button class=\"btn btn-warning\">Return</button>
                                    </form>
                                </td>
                            </tr>
                        ";
                    }

                ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>