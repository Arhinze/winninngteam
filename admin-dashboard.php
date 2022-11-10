<?php

//admin_segments would include connections to DB
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");
if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){

    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
    //that means admin is logged in:
        admin_Segments::header();
        admin_Segments::body();
?>


<?php 
        admin_Segments::footer();
    } else {
        header("location:/admin");
    }
 } else {
    header("location:/admin");
}
?>