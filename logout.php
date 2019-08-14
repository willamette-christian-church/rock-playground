<?php

require_once 'config.php';

$_SESSION['goto'] = empty($_SESSION['goto']) ? '/' : $_SESSION['goto'];

logOut();

header("Location: login.php");
die ('Logged Out');