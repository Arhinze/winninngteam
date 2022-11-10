<?php

include_once("views/Dashboard_Segments.php");

Dashboard_Segments::header();
if((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
    //that means user is logged in
        echo "<div class='dashboard_div' style='padding:8px'><h1>Referrals: </h1> <hr />";
        $refstmt = $pdo->prepare("SELECT * FROM investors WHERE referred_by = ?");
        $refstmt->execute([$data->username]);
        $refdata = $refstmt->fetchAll(PDO::FETCH_OBJ);

        if(count($refdata)>0){
            $i = 0;
            foreach($refdata as $rd){   
                $i++;    
?>
            <?=$i, ". <b>", $rd->username, "</b> - <span style='color:#2b8eeb'>", date("D M jS Y - h:i a", strtotime($rd->entry_date)) ?></span><br /><br /> 

            
            


<?php
            }
?>
            </div>

            <div style="text-align:center">
                    <a href="/referred-commissions" class="action_button" style="background-color:#2b8eeb;color:#fff;border:1px solid #fff;border-radius:4px">
                    Referred Commisions
                
                    &nbsp;
                    <i class="fa fa-angle-right"></i>
                    <i class="fa fa-angle-right"></i>
                </a>
            </div><br /><br />
<?php
        }else {
            echo "No Referals yet.<br /> <br />Kindly Copy your referal link in <b><a href='/dashboard' style='color:#2b8eeb'>dashboard</a></b> and share with your friends to start earning from their investments.";
            
            echo "</div>";
        }

    

    Dashboard_Segments::footer();
    } else {
        header("location:/login");
    }
} else {
    header("location:/login");
}