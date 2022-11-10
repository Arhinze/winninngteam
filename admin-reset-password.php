<?php
//admin_segments would include connections
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");
if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){

    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
    //that means admin is logged in:
        admin_Segments::header();
        echo "<div class='dashboard_div' style='padding:6px'><h1>Admin Reset Username/Password</h1><hr />";

        if(isset($_POST["admin_username"])) {
            if($_POST["password1"] == $_POST["password2"]){
                $us = $pdo->prepare("UPDATE `admin` SET admin_password=?, admin_name = ? WHERE admin_id = ?");
                $us->execute([$_POST["password1"],$_POST["admin_username"],$_POST["id"]]);

                echo "Passwords and Username Updated Successfuly. <b><a href='/admin'>Click here</a></b> to login again";
            } else {
                echo "<h2>Passwords don't match. Please try again</h2>";
            }
        }
?>
    <form method="post" action="">
        <h5>Username </h5>
        <input type="text" name="admin_username" class="input" value="<?=$data->admin_name?>"/>
        <h5>Password</h5>
        <input type="text" name = "password2" class="input" value="<?=$data->admin_password?>"/>
        <h5>Repeat Passsword</h5>
        <input type="text" name = "password1" class="input" value="<?=$data->admin_password?>"/>
        <input name="id" type="hidden" value="<?=$data->admin_id?>"/>
        <br />
        <input type="submit" value="Submit" class="button"/>
    </form>  

    </div> <!-- End of dashboard_div -->

<?php
    }
}
?>