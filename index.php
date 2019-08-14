<?php

require_once 'config.php';
include 'auth.php';

$user = CallAPI('GET', $rockURL . '/api/People/GetCurrentPerson');

$temp = '';
$temp .= '<p>Logged in as ' . $user->NickName . ' ' . $user->LastName . '</p>';

include 'header.php';

echo $temp;
echo '<pre>';
print_r($user);
echo '</pre>';

include 'footer.php';