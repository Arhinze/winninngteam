<?php

ini_set("session.use_only_cookies", 1);
include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Segments.php");

Segments::header();
?>

<?php

$remember_username = "";

if(isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(count($data)>0){
        header("location:/dashboard");
    }
} 



if (isset($_POST["user-email"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $remember_username = $_POST["username"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(count($data)>0){
        setcookie("username", $_POST["username"], time()+(24*3600), "/");
        setcookie("password", $_POST["password"], time()+(24*3600), "/");

        //redirect to dashboard
        header("location:/dashboard");

    } else {
?>
    <div class = "invalid">
        invalid username/password combination
    </div>
<?php 
    }
}
?>

<!--HTML:-->

<h1 style="margin:72px 0px -2px 0px;">Sign In</h1>
<hr/>
<div class="sign-in-box">
    <div class="sign-in-welcome">
        <span style="color:#fff">Welcome Back to</span><br />
        <b style="color:#57acfc;font-size:30px">Future Finance Investment</b>
    </div>

    <form method="post" action="/login"> 
        <input type="text" name="username" placeholder="Username" value="<?=$remember_username?>" class="input" style="margin-bottom:6px"/>    
        <input type = "password" name = "password" placeholder = "Password: *****" class="input"/><br />

        <button type="submit" class="button">Login <i class="fa fa-telegram"></i> </button> <br />

        Don't have an account? <b><a href="sign-up" style="font-weight:bold;font-size:18px">Sign Up</a></b>. <br />
        <b><a href="/reset-password" style="font-weight:bold;font-size:18px">Forgot Password?</a></b>
    </form>
</div>

<?php Segments::footer(); ?>