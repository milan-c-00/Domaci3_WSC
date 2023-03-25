<?php

    session_start();

    if($_SESSION['auth_status'] != 'authorized' && $_SESSION['user_role'] != 'administrator'){
        header('location: login.php');
    }
    if($_SESSION['auth_status'] != 'authorized' || $_SESSION['user_role'] != 'administrator'){
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
    <title>Admin panel</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script defer src="functions.js"></script>

</head>
<body class="my-gradient">

    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <button class="btn btn-outline-primary ms-2" href="#" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAuthor" aria-expanded="false" aria-controls="collapseAuthor">
                Add new author
            </button>
            <button class="btn btn-outline-success ms-2" href="#" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBook" aria-expanded="false" aria-controls="collapseBook">
                Add new book
            </button>
            <h1 class="text-center custom-text-blue ms-auto me-auto">Administrator panel</h1>
            <form class="d-flex ms-auto" action="logout.php" method="post">
                <input type="hidden" name="auth_status" value="">
                <button class="btn btn-outline-secondary" type="submit">Logout</button>
            </form>
            </div>
        </div>
    </nav>

    

    <div class="collapse" id="collapseAuthor">
        <div class="container w-25 mt-5">
            <form class="custom-form" action="add_new_author.php" method="post">
                <h4 class="text-center custom-text-blue">Add new author</h4>
                <div class="mt-2">
                    <input type="text" name="author" id="new-author" placeholder="Enter author name" required class="form-control">
                </div>
                <div class="mt-2 text-center">
                    <button type="submit" class="btn btn-primary mt-2">Add</button>
                </div>
            </form>
        </div>
    </div>
    <div class="collapse"  id="collapseBook">
        <div class="container w-25 mt-5">
            <form class="custom-form" action="add_new_book.php" method="post">
                <h4 class="text-center custom-text-blue">Add new book</h4>
                <div class="mt-2">
                    <input type="text" name="name" id="new-name" placeholder="Enter book name" required class="form-control">
                </div>
                <div class="mt-2">

                    <select class="form-select" required name="selected-author">
                        <option disabled selected value="">Choose author</option>
                    <?php

                        $sql = "SELECT * FROM author";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_execute($stmt);
    
                        $data = [];
                        $books = mysqli_stmt_get_result($stmt);
    
                        while ($row =  mysqli_fetch_assoc($books)) {
                            $data[] = $row;
                        }

                        foreach ($data as $author) {
                            $author_id = $author['id'];
                            $author_name = $author['name'];
                            echo
                            "
                                <option name=\"author\" value=\"$author_id\">$author_name</option>
                            ";
                        }

                    ?>
                    </select>
                
                </div>
                <div class="mt-2">
                    <textarea name="description" id="new-description" cols="30" rows="5" placeholder="Enter book description" required class="form-control"></textarea>
                </div>
                <div class="mt-2 text-center">
                    <button type="submit" class="btn btn-success mt-2">Add</button>
                </div>
            </form>
        </div>
    </div>

    <div class="collapse"  id="collapseEdit">
        <div class="container w-25 mt-5" id="edit-book-form">
           
        </div>
    </div>

    <div class="container text-center mt-5">
        <table class="table table-striped admin-panel border">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Availability</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php

                    // $sql = "SELECT * FROM books";
                    $sql = "SELECT books.id, books.name, books.author, author.name as author_name, books.description, books.availability FROM books, author WHERE books.author = author.id";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt);

                    $data = [];
                    $books = mysqli_stmt_get_result($stmt);

                    while ($row =  mysqli_fetch_assoc($books)) {
                        $data[] = $row;
                    }

                    foreach ($data as $book) {

                        $id = $book['id'];
                        $name = $book['name'];
                        $author_name = $book['author_name'];
                        $author = $book['author'];
                        $description = $book['description'];
                        $availability = $book['availability'];

                        echo
                        "
                            <tr>
                                <td>$id</td>
                                <td id=\"name-$id\">$name</td>
                                <td id=\"author-$id\">$author_name</td>
                                <td id=\"description-$id\">$description</td>
                                
                                <td id=\"availability-$id\">$availability</td>
                                <td>
                                    <form class=\"col me-auto ms-auto\" method=\"post\">
                                        <input type=\"hidden\" value=\"$id\"></input>
                                        <button type=\"button\" class=\"btn btn-outline-warning col-12\" onclick=\"fillModal($id)\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapseEdit\" aria-expanded=\"false\" aria-controls=\"collapseEdit\">
                                            Edit
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action=\"delete_book.php\" method=\"post\">
                                        <input type=\"hidden\" name=\"book-id\" value=\"$id\"/>
                                        <button class=\"btn btn-outline-danger\">Delete</button>
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