<?php

function checkAuth() {
    if (!isset($_SESSION['auth'])) {
        header("Location: loginform.php");
        exit();
    }
}

function authenticate($email, $password) {
    include('config/db_conn.php');
    
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    $query = "SELECT * FROM teachers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }
    
    $user = mysqli_fetch_assoc($result);
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['auth'] = true;
        $_SESSION['auth_user'] = $user;
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: loginform.php");
    exit();
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['auth']) && basename($_SERVER['PHP_SELF']) != 'loginform.php') {
    header("Location: loginform.php");
    exit();
}

if (isset($_POST['logoutbtn'])) {
    logout();
}

?>
