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
    <title>Online library</title>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="my-gradient">
    
    <?php

    ?>

    <h1 class="text-center mt-5 custom-text-blue">Your favourite online library</h1>

    <div class="col-6 offset-3 mt-5 landing-container">
        <h3 class="custom-text-blue text-center mt-3">Get full experience of our service</h3>
        <h5 class="custom-text-purple text-center mt-2 col-10 offset-1">Become one of our full access users in only few steps and start browsing our library completely free</h5>
        <div class="row mt-3 mb-3">
            <a href="register.php" class="btn btn-outline-primary col-3 offset-2">
                Register
            </a>
            <a href="login.php" class="btn btn-primary col-3 offset-2">
                Login
            </a>
        </div>
    </div>


</body>
</html>