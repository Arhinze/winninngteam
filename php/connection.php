<?php
$dbhost = "localhost";
$dbname = "voting_system";
$dbuser = "root";
$dbpass = "";

$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


//Custom authorization function:
function authourized($obj) {
    if ((isset($_COOKIE["user_email"])) && ((isset($_COOKIE["password"])))) {
        $user_email = $_COOKIE["user_email"];
        $user_password = $_COOKIE["password"];
    
        $stmt = $obj->prepare("SELECT * FROM user WHERE user_email = ? AND user_password = ?");
        $stmt->execute([$user_email, $user_password]);
        
        $data = $stmt->fetch(PDO::FETCH_OBJ);
    
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }
}

date_default_timezone_set('Africa/Lagos');

//include_once($_SERVER["DOCUMENT_ROOT"]."/php/auto-invest.php");