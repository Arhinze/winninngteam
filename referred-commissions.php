<?php

include_once("views/Dashboard_Segments.php");

if((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
    //that means user is logged in
        Dashboard_Segments::header();
        echo "<div class='dashboard_div' style='padding:12px'><h1>Referred Commissions: </h1><hr /><div style='line-height:23px;font-size:18px'>";

        $ref_com_stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND tr_type=?");
        $ref_com_stmt->execute([$data->user_id, "referral"]);
        $ref_com_data = $ref_com_stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($ref_com_data)>0){
            $i = 0;
            $total = 0;
            foreach($ref_com_data as $rcd){
                $i++;
                $total += $rcd->profit;
?>                
                <?=$i, ". <b>$", $rcd->tr_amount, "</b> - <span style='color:#2b8eeb'>",  date("D M jS Y - h:i a", strtotime($rcd->tr_time)),"</span><br />&nbsp;&nbsp;&nbsp;&nbsp;  Your Referee: <b>", $rcd->tr_img, "</b> deposited $", (($rcd->tr_amount)*10)  ?><br /><br />

<?php
            } 
        echo "<hr />Total: <b>$", $total, "</b> earned as referral commission.<br /><br />

            <a href='/dashboard' style='color:#2b8eeb'>View other earnings &nbsp;<i class='fa fa-angle-right'></i> </a><br /><br />";
        } else {
            echo "Sorry, No commisions yet. Either you have not invited anyone or those you invited are yet to make an investment.
                <br /><br /> Kindly check back later.";
        }  
        
        echo "</div></div>";

        Dashboard_Segments::footer();
    }else {
        header("location:/login");
    }
}else {
    header("location:/login");
}