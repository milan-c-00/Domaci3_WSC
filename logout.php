<?php
    session_start();

    if($_POST['auth_status'] != 'authorized'){
        session_unset();
        session_destroy();
        header('location: login.php');
    }
    else{
        header('location: index.php');
    }

?>