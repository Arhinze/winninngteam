<?php
include_once("views/Dashboard_Segments.php");
include_once("views/Transactions.php");

if((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
        //that means the person is logged in:
        Dashboard_Segments::header();
        echo "<h1 style='margin-top:102px;text-align:center'>Transactions</h1> <hr />";


        //This variable is needed to check if the user has a pending or already approved transaction.
        $transaction_status = 
                            '<div class="main" style="font-size:18px;text-align:center">You don\'t have any transactions yet.<br /><br /><br />
                            
                                <center>
                                
                                <a href="/deposit" class="action_button" style="background-color:#2b8eeb;color:#fff;border:1px solid #fff;border-radius:4px">
                                
                                Make Deposit
                                &nbsp;
                                
                                <i class="fa fa-angle-right"></i>
                                <i class="fa fa-angle-right"></i>
                                </a>

                                <a href="/withdraw" class="action_button" style="background-color:#01123c;color:#fff;border:1px solid #fff;border-radius:4px">
                                 
                                Make Withdrawal
                                &nbsp;
                                
                                <i class="fa fa-angle-right"></i>
                                <i class="fa fa-angle-right"></i>
                                </a>

                                </center>
                                
                                <br />
                            </div>';  
        

        //Pending Transactions:

        $r_stmt = $pdo->prepare("SELECT * FROM user_requests WHERE user_id = ? ORDER BY rq_id DESC LIMIT ?, ?");
        $r_stmt->execute([$data->user_id, 0, 100]);
        $r_data = $r_stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($r_data)>0){  
            $transaction_status = "";

            echo "<h3 style='color:#2b8eeb;text-align:center'>Pending Transactions:</h3> <hr />";

            foreach($r_data as $r){
?>
            <div class="main" style="margin-top:12px">
            <h4 style="color:#ff9100;font-size:18px"><?=$r->rq_time?></h4>

            <b style="font-size:18px"><?=$r->rq_type?></b>: $<?=$r->rq_amount?><br /><br />

            <?php //edit transactions coming here very soon.. that is for admins ?>
            
            <?php  
                if($r->rq_type == "Invest"){
            ?>  
                    <b style="font-size:18px">Interest Rate</b>: <?=$transactions->rate($r->rq_amount)?>% everyday for <?=$transactions->num_of_days($r->rq_amount)?> days <br /><br />   
            <?php
                }
            ?>

                <b style="font-size:18px">Status</b>: Pending &nbsp;&nbsp; 
                <span class="blink"><i class="fa fa-circle" style="color:#ff9100;font-size:12px"></i></span> <br /><br />
                <i style="font-size:14px">This transaction is awaiting blockchain verification</i> 
            
                <hr />
            </div>      
<?php
            }  
            echo "<br /><br />";
        }  //end of pending transactions





        //Approved Transactions:
        $t_stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY tr_id DESC LIMIT ?, ?");
        $t_stmt->execute([$data->user_id, 0, 100]);
        $t_data = $t_stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($t_data) > 0){  
            $transaction_status = "";

            echo "<h3 style='color:#2b8eeb;text-align:center'>Approved Transactions:</h3> <hr />";
            foreach($t_data as $t){
?>
            <div class="main" style="margin-top:12px">
            <h4 style="color:#ff9100;font-size:18px">
                <?=$t->tr_time?>
            </h4>
            <b style="font-size:18px"><?=$t->tr_type?></b>: $<?=$t->tr_amount?><br /><br />

            <?php //edit transactions coming here very soon.. that is for admins ?>
            
            <?php  
                if($t->tr_type == "Invest"){
            ?>
                    <b style="font-size:18px">Interest Rate</b>: <?=$transactions->rate($t->tr_amount)?>% everyday for <?=$transactions->num_of_days($t->tr_amount)?> days<br /><br />
                    
                    <b style="font-size:18px">Total Profit</b>: $<?=$t->profit?> &nbsp; 
                    
                    <span class="blink"><i class="fa fa-long-arrow-up" style="color:#ff9100"></i></span>        
           <?php
                }
            ?>
            <hr />
            </div>
            
<?php
            }  
        } //End of Approved Transactions
        echo $transaction_status;

        Dashboard_Segments::footer();
    } else {
        header("location:/login");
    }
} else {
    header("location:/login");
}
?>