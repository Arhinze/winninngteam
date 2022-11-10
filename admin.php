<?php

include_once("views/Segments.php");
if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        header("location:/admin-dashboard");
    }
}

Segments::header();

$check_admin = "";
$remember_admin = "";

if(isset($_POST["admin_name"])){
    $remember_admin = $_POST["admin_name"];   
}

if(isset($_POST["user_code"])){
    $admincode = $_POST["user_code"];
    if($admincode == $_POST["code"]){
        $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
        $stmt->execute([$_POST["admin_name"], $_POST["admin_password"]]);
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($data)>0){
            setcookie("admin_name", $_POST["admin_name"], time()+(24*3600), "/");
            setcookie("admin_password", $_POST["admin_password"], time()+(24*3600), "/");

            header("location:/admin-dashboard");

        } else{
            setcookie("admin_name", $_POST["admin_name"], time()-(24*3600), "/");
            setcookie("admin_password", $_POST["admin_password"], time()-(24*3600), "/"); 

            $check_admin = "<div class='invalid'>Wrong Username/password Combination</div>";
        }

    } else if(empty($admincode)){
        echo '<div class="invalid"><i class="fa fa-warning"></i> Please Enter the 6 Digit Code</div>';
    } else {
        echo '<div class="invalid"><i class="fa fa-warning"></i> Wrong Captcha</div>';
    }
} else {
   // 
}
?>

<div class="sign-in-box" style="margin-top:90px">
    <div class="sign-in-welcome">
        <h2>Control Panel</h2>
    </div>

    <?=$check_admin?>

    <form method="post" action=""> 
        <br />Username:<br />
        <input type="text" placeholder="Username" class="input" name="admin_name" value="<?=$remember_admin?>" required/>    
           
        <br />Password:<br />
        <input type = "password" placeholder = "Password: *****" name = "admin_password" class="input" required/>

       <?php include($_SERVER["DOCUMENT_ROOT"]."/views/captcha.php"); ?>
    
        <button type="submit" class="button">Login <i class="fa fa-telegram"></i> </button> <br />
    </form>
</div>

<?php
Segments::footer();
?>