<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_COOKIE, ".ROCK=" . $_COOKIE['_ROCK']);

    $result = curl_exec($curl);

    curl_close($curl);
    
    return json_decode($result);
}

function logOut () {
    // Initialize the session.
    // If you are using session_name("something"), don't forget it now!
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    if (isset($_COOKIE['_ROCK'])) {
        $_COOKIE['_ROCK'] = '';
        unset($_COOKIE['_ROCK']);
        setcookie('.ROCK', '', time() - 42000, '/');
    }

    // Finally, destroy the session.
    session_destroy();
}