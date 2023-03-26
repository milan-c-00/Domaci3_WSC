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
    <title>Register</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="my-gradient">

    <div class="text-center mt-5">  
        <form class="col-4 offset-4 mt-5 custom-form border" action="" method="post">
            <h1 class="custom-text-blue text-center my-heading mt-2">Register</h1>
            <div class="col-10 offset-1">
                <input type="text" name="first-name" id="reg-first-name" class="form-control mt-5" placeholder="First name" required>
            </div>
            <div class="col-10 offset-1">
                <input type="text" name="last-name" id="reg-last-name" class="form-control mt-2" placeholder="Last name" required>
            </div>
            <div class="col-10 offset-1">
                <input type="email" name="email" id="reg-email" class="form-control mt-2" placeholder="Email" required>
            </div>
            <div class="col-10 offset-1">
                <input type="text" name="username" id="reg-username" class="form-control mt-2" placeholder="Username" required>
            </div>
            <div class="col-10 offset-1">
                <input type="password" name="password" id="reg-password" class="form-control mt-2" placeholder="Password" required>
            </div>
            <div class="row">
                <button class="btn btn-primary mt-2 col-4 offset-4">
                    Register
                </button>
            </div>
            <label class="text-dark mt-2" for="">Already registered? <a href="login.php">Login here</a></label>
        </form>
    </div>

    <?php

        function register(){

            $conn = mysqli_connect('localhost', 'root', '', 'online_library_db');
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
    
            $first_name = $_POST['first-name'];
            $last_name = $_POST['last-name'];
            $name = $first_name ." ". $last_name;
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = 'regular_user';

            $sql = "INSERT INTO users(name, email, username, password, role) VALUES (?,?,?,?,?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $username, $password, $role);
            mysqli_stmt_execute($stmt);

        }

        if(key_exists('username', $_POST) && key_exists('email', $_POST) && key_exists('first-name', $_POST) && key_exists('last-name', $_POST) && key_exists('password', $_POST)){
            register();
            header('location: login.php');
        }

    ?>

</body>
</html>