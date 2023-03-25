<?php
    session_start();
    if(key_exists('auth_status', $_SESSION)){
        if($_SESSION['auth_status'] === 'authorized' && $_SESSION['user_role'] === 'administrator'){
            header('location: admin_panel.php');
        }
        if($_SESSION['auth_status'] === 'authorized' && $_SESSION['user_role'] === 'regular_user'){
            header('location: books.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="my-gradient">

    <div class="text-center mt-5">
        <form class="col-4 offset-4 mt-5 custom-form border" action="login.php" method="post">
            <h1 class="custom-text-blue text-center my-heading mt-2">Login</h1>
            <div class="col-10 offset-1">
                <input type="text" name="username" id="login-username" class="form-control mt-5" placeholder="Username" required>
            </div>
            <div class="col-10 offset-1">
                <input type="password" name="password" id="login-password" class="form-control mt-2" placeholder="Password" required>
            </div>
            <div class="row">
                <button class="btn btn-primary mt-2 col-4 offset-4">
                    Log in
                </button>
            </div>
            <label class="text-dark mt-2" for="">Not our user yet? <a href="register.php">Register here</a></label>
        </form>
    </div>

    <?php

        function getAuthData(){

            $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
            mysqli_stmt_execute($stmt);
 
            $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            return($data);

        }
        
        function login(){

            $auth_data = getAuthData();
            $login_data = $_POST;
                        
            if($auth_data && $auth_data['username'] === $login_data["username"] && $auth_data['password'] === $login_data["password"]){
                $_SESSION['auth_status'] = 'authorized';
                $_SESSION['my_id'] = $auth_data['id'];

                if($auth_data['role'] === 'regular_user'){
                    $_SESSION['user_role'] = 'regular_user';
                    header("location: books.php");
                }
                if($auth_data['role'] === 'administrator'){
                    $_SESSION['user_role'] = 'administrator';
                    header('location: admin_panel.php');
                }
            }
            else{
                echo 
                    "
                    <div class=\"alert alert-danger w-25 text-center me-auto ms-auto mt-2\" role=\"alert\">
                        Invalid credentials!
                    </div>
                    ";
            }
        }
        
        if(key_exists('username', $_POST) && key_exists('password', $_POST)){
            login();
        }

    ?>

</body>
</html>