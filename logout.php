<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Segments.php");

$cookies = ["user_email","password"];

foreach ($cookies as $c) {
    if(isset($_COOKIE["$c"])){
        setcookie($c, $_COOKIE["$c"], time()-(24*3600), "/");
    }
}

//Segments::header();
?>

<div class="dashboard_div" style="text-align:center;padding-bottom:21px">
    <h3>You've Logged Out Successfully</h3>
    
    You can now Proceed to <a href="/"><i class="fa fa-home"></i> Home</a></b>
</div>

<?php
//Segments::footer();
?>
