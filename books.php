<?php

    session_start();

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'regular_user'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'regular_user'){
        header('location: error_page.php');
    }

    $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');

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
    <title>Books</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="my-gradient">
    
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <form action="my_books.php" method="post" class="d-flex me-2">
                <button class="btn btn-outline-primary">My books</button>
            </form>
            <form class="d-flex me-2" action="books.php" method="get">
                <input class="form-control me-2" type="hidden" name="order" value="asc">
                <button class="btn btn-outline-info" type="submit">A-Z</button>
            </form>
            <form class="d-flex me-2" action="books.php" method="get">
                <input class="form-control me-2" type="hidden" name="order" value="desc">
                <button class="btn btn-outline-info" type="submit">Z-A</button>
            </form>
            <form class="d-flex me-auto" role="search" action="books.php" method="get">
                <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <form class="d-flex ms-auto" action="logout.php" method="post">
                <input type="hidden" name="auth_status" value="">
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
            
        </div>
    </nav>
    <h1 class="text-center custom-text-blue ms-auto me-auto">All books</h1>

    <div class="container text-center mt-5">
        <table class="table table-striped admin-panel border">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Availability</th>
                    <th>Borrow</th>
                </tr>
            </thead>
            
            <tbody>
                <?php

                    $term = "";
                    if(key_exists('search', $_GET)){
                        $term = $_GET['search'];
                        $term = "%$term%";
                        $sql = "SELECT books.id, books.name AS name, author.name AS author, books.description, books.availability FROM books, author WHERE books.author = author.id AND (books.name LIKE ? OR author.name LIKE ?)";
                    }
                    else{
                        $sql = "SELECT books.id, books.name AS name, author.name AS author, books.description, books.availability FROM books, author WHERE books.author = author.id";
                    }

                    if(key_exists('order', $_GET)){
                        if($_GET['order'] === 'asc'){
                            $sql = $sql . " ORDER BY books.name ASC";
                        }
                        else if($_GET['order'] === 'desc'){
                            $sql = $sql . " ORDER BY books.name DESC";
                        }
                        else{
                            $sql = $sql;
                        }
                    }

                    $stmt = mysqli_prepare($conn, $sql);
                    if($term){
                        mysqli_stmt_bind_param($stmt, 'ss', $term, $term);
                    }
                    mysqli_stmt_execute($stmt);

                    $data = [];
                    $books = mysqli_stmt_get_result($stmt);

                    while ($row =  mysqli_fetch_assoc($books)) {
                        $data[] = $row;
                    }

                    foreach ($data as $book) {

                        $id = $book['id'];
                        $name = $book['name'];
                        $author = $book['author'];
                        $description = $book['description'];
                        $availability = $book['availability'];
                        $my_id = $_SESSION['my_id'];

                        echo
                        "
                            <tr>
                                <td id=\"name-$id\">$name</td>
                                <td id=\"author-$id\">$author</td>
                                <td id=\"description-$id\">$description</td>
                                <td id=\"availability-$id\">$availability</td>
                        ";
                        if($availability === 'Available'){
                            echo
                            "
                                    <td>
                                        <form action=\"borrow_book.php\" method=\"post\">
                                            <input type=\"hidden\" name=\"book_id\" value=\"$id\"/>
                                            <input type=\"hidden\" name=\"user_id\" value=\"$my_id\"/>
                                            <button class=\"btn btn-outline-success\">Borrow</button>
                                        </form>
                                    </td>
                                </tr>
                            ";
                        }
                        else{
                            echo
                            "
                                    <td>
                                        <form action=\"\" method=\"post\">
                                            <input type=\"hidden\" name=\"book_id\" value=\"$id\"/>
                                            <button class=\"btn btn-outline-success\" disabled >Borrow</button>
                                        </form>
                                    </td>
                                </tr>
                            ";
                        }
                    }
                    $data = [];
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>