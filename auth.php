<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['goto'] = $_SERVER['REQUEST_URI'];
}
$_SESSION['goto'] = empty($_SESSION['goto']) ? '/' : $_SESSION['goto'];
if (($_SERVER['REQUEST_URI'] != 'login.php') && ($_SERVER['REQUEST_URI'] != $_SESSION['goto'])) {
    $_SESSION['goto'] = $_SERVER['REQUEST_URI'];    
}
if (!isset($_COOKIE['_ROCK']) || $_COOKIE['_ROCK'] === '') {
    header("Location: login.php");
    die('Not authorized');
}