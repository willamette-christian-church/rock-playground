<?php

require_once 'config.php';

$goto = empty($_SESSION['goto']) ? '/' : $_SESSION['goto'];

$temp = '';
// Check to see if an action is set.
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : '';

if ($action === 'login_contact') {
    header('Content-Type: application/json');

    $authURL = $rockURL . '/api/Auth/Login';
    $params = array(
        'Username'=>$_POST["Username"],
        'Password'=>$_POST["Password"],
        'Persisted'=>true
    );
    $params = json_encode($params);

    $ch = curl_init();
    $headers = [];
    curl_setopt($ch, CURLOPT_URL, $authURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    $raw_content = curl_exec( $ch );
    $err = curl_errno( $ch );
    $errmsg = curl_error( $ch );
    $header = curl_getinfo( $ch ); 
    curl_close( $ch );

    $header_content = substr($raw_content, 0, $header['header_size']);
    $body_content = trim(str_replace($header_content, '', $raw_content));

    // let's extract cookie from raw content for the viewing purpose         
    $cookiepattern = "#set-cookie:\\s+(?<cookie>[^=]+=[^;]+)#m"; 
    preg_match_all($cookiepattern, $header_content, $matches); 
    $cookiesOut = implode("; ", $matches['cookie']);
    $expiresPattern = "#expires=(?<date>...);#m";
    preg_match_all($expiresPattern, $header_content, $expirations);
    if (preg_match('/expires=(.*?)\;/', $header_content, $exp) == 1) {
        $expiration = ($exp[1]);
    }
    $expiration = strtotime($expiration);
    setcookie('.ROCK', ltrim($cookiesOut, '.ROCK='), $expiration, '/');

    header("Location: " . $goto);
    die('Successfully Authenticated');
} else {
    // If no action is set, just show the form

    include 'header.php';

    $temp .= '  <div class="login-page">
    <div class="container">

    <div class="row">
        <div class="ml-auto mr-auto col-md-6 col-lg-4">
            <form method="post" class="my-5 eg-quick-form account-login">
                <div class="card-login card">
                <div class="card-header">
                    <div class="card-header">
                    <h3 class="header text-center"><i class="nc-icon nc-planet text-info text-xlarge text-primary"></i></h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="nc-icon nc-single-02"></i>
                        </span>
                    </div>
                    <input type="text" name="Username" id="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="nc-icon nc-key-25"></i>
                        </span>
                    </div>
                    <input type="password" name="Password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <span class="text-muted small d-block">Use your Rock credentials to log in.</span>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn-round mb-3 btn btn-primary btn-block">Sign In</button>
                    <span id="login_user" class="">
                        <input name="action" value="login_contact" style="display: none;" type="input" hidden>
                    </span>
                </div>
                </div>
            </form>
        </div>';
    echo $temp;

    include 'footer.php';
}