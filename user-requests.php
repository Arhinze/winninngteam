<?php
//admin_segments would include connections
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");
if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
    //that means admin is logged in:
        admin_Segments::header();
 ?>       
 
        <div class="dashboard_div" style="background-color:#01123c;border:none">
        
        <h1 style="text-align:center;margin-top:130px">Users Requests</h2>
        
        <!-- "refresh" class -->
        <a href = '' style='color:#2b8eeb;font-size:19px;margin:0 3px 0 6px;padding:6px 9px;background-color:#000;border-radius:4px' class="refresh">
        
        <i class='fa fa-refresh'></i>
        <span style="font-size:18px; font-weight:bold">&nbsp; Refresh</span>
        
        </a> 
        <!-- End of "refresh" class -->
        
        <br /><br /> 


        <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter username: try: abc" style="border:1px solid #2b8eeb;width:75%"/> 
        
        <i class="fa fa-search" onclick ="search_icon()" style="padding:7px;border-radius:4px;font-size:16px;color:#fff;background-color:#2b8eeb"></i>

        <div id="search" style="position:absolute;width:75%"></div>
  
<?php
        if(isset($_POST["accept"])){
            
 
            if($_POST["request_type"] == "Deposit") {

                $istmt = $pdo->prepare("INSERT INTO transactions(user_id,tr_type,tr_amount,tr_time,tr_img) VALUES(?,?,?,?,?)");
                $istmt->execute([$_POST["user_id"],$_POST["request_type"],$_POST["request_amount"],$_POST["request_time"],$_POST["request_image"]]);
            
                //add bonus for depositer's referer:
                
                $sel_ref_stmt = $pdo->prepare("SELECT * FROM investors WHERE user_id = ?");
                $sel_ref_stmt->execute([$_POST["user_id"]]);
                $sel_ref_data = $sel_ref_stmt->fetch(PDO::FETCH_OBJ);

                if(!(empty($sel_ref_data->referred_by))){
                    //that means he has a referer:
                    $sel_ref_stmt2 = $pdo->prepare("SELECT * FROM investors WHERE username = ?");
                    $sel_ref_stmt2->execute([$sel_ref_data->referred_by]);
                    $sel_ref_data2 = $sel_ref_stmt2->fetch(PDO::FETCH_OBJ);

                    //insert profit for Referer

                    $istmt3 = $pdo->prepare("INSERT INTO transactions(user_id, tr_type, tr_amount, tr_time, tr_img) VALUES(?,?,?,?,?)");
                    $amt = (10/100)*$_POST["request_amount"];
                    $istmt3->execute([$sel_ref_data2->user_id, "referral",$amt,$_POST["request_time"],$sel_ref_data->username]);

                    //Depositer's referer's name inputed as tr_img for easier database schema -- this is semantically wrong sha..
                    //I'll fix it some day

                    
                    
                    //Mail  Referer:
                    $rqRefName = $sel_ref_data->referred_by;
                    $rqName = $_POST["realName"];
                    $rqUsername = $sel_ref_data->username;
                    $rqRefAmt = (1/10)*$_POST["request_amount"];
                    $rqType = $_POST["request_type"];
        
                    ini_set("display_errors", 1);
        
                    $message = <<<HTML
                        <html>
                        <head>
                        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                                <link rel="stylesheet" href="https://futurefinanceinvestment.com/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
            
                        </head>
                        <body style ="font-family:Trirong;">
                        <div style="position:relative">
                            <img src="https://futurefinanceinvestment.com/static/images/logo.png" style="margin-left:36%;margin-right:36%;width:25%;position:absolute"/>
                        </div>
                        <h2 style="color:#00008b;font-family:Arimo;text-align:center">Future Finance Investment</h2>
                            <p  style ="font-family:Trirong;">Congrats $rqRefName, You've just earned <b>\$$rqRefAmt</b> as referral bonus on the deposit made by your referee:<b> $rqName </b> with username:<b>$rqUsername</b>. </p>

                            <p>Kindly visit the <b><a href="https://futurefinanceinvestment.com/referred-users"  style="color:#ff3c00">Referred Users</a></b> page on your dashboard to view investors you've referred to us and the <b><a href="https://futurefinanceinvestment.com/referred-commissions"  style="color:#ff3c00">Referred Commissions</a></b> page to view your earnings.</p>
        
                            <p style="margin-bottom:30px">For further enquiries, you can check out our <b><a href="https://futurefinanceinvestment.com/faqs"  style="color:#ff3c00">Frequently asked questions</a></b> page or <b><a href="https://futurefinanceinvestment.com/contact" style="color:#ff3c00">contact us</a></b> directly if our page doesn't answer your questions.</p>
                            
                            <a href="https://futurefinanceinvestment.com/referred-commissions" style="color:#ff3c00;font-size:18px;padding:2%;border-radius:6px;box-shadow:0px 0px 3px #ff3c00;border:2px solid #ff3c00;width:8%;margin-left:30%;margin-right:20%">Referred Commissions</a>
                        </body>
                        </html>
        
                    HTML;
        
                    $sender = "admin@futurefinanceinvestment.com";
        
                    $headers = "From: $sender \r\n";
                    $headers .="Reply-To: $sender \r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type:text/html; charset=UTF-8\r\n";
        
                    $mail = mail($sel_ref_data2->user_email,"Congratulations, Your Referee Just Made a Deposit",$message, $headers);
        
                    if($mail){
                        echo "<br />A Mail has been sent to the referer";
                    } else {
                        echo "Sorry, an error occurred, Mail not sent";
                      }

                }


            } else if($_POST["request_type"] == "Withdraw"){

                $istmt = $pdo->prepare("INSERT INTO transactions(user_id,tr_type,tr_amount,tr_time,last_profit_time,tr_wallet_address,tr_payment_method,tr_from) VALUES(?,?,?,?,?,?,?,?)");
                $istmt->execute([$_POST["user_id"],$_POST["request_type"],$_POST["request_amount"],$_POST["request_time"],$_POST["request_time"],$_POST["request_wallet_address"],$_POST["request_payment_method"],$_POST["request_from"]]);

            
            } else if($_POST["request_type"] == "Invest") {

                $istmt = $pdo->prepare("INSERT INTO transactions(user_id,tr_type,tr_amount,tr_time,last_profit_time,profit,tr_from) VALUES(?,?,?,?,?,?,?)");
                $istmt->execute([$_POST["user_id"],$_POST["request_type"],$_POST["request_amount"],date("Y-m-d h:i:s", time()),date("Y-m-d h:i:s", time()),$_POST["request_amount"],$_POST["request_from"]]);
            
            }




            //Mail User: Subject: Your request has been accepted.(Withdraw, deposit or Invest)
            $rqName = $_POST["realName"];
            $rqAmt = $_POST["request_amount"];
            $rqType = $_POST["request_type"];

            function fix_request($a){
                if($a == "Invest"){
                    return "Investment";
                } else if($a == "Withdraw") {
                    return "Withdrawal";
                } else {
                    return $a;
                }
            }

            $new_rqType = fix_request($rqType);

            ini_set("display_errors", 1);

            $message = <<<HTML
                <html>
                <head>
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                        <link rel="stylesheet" href="https://futurefinanceinvestment.com/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
    
                </head>
                <body style ="font-family:Trirong;">
                <div style="position:relative">
                    <img src="https://futurefinanceinvestment.com/static/images/logo.png" style="margin-left:36%;margin-right:36%;width:25%;position:absolute"/>
                </div>
                <h2 style="color:#00008b;font-family:Arimo;text-align:center">Future Finance Investment</h2>
                    <p  style ="font-family:Trirong;">Congrats $rqName, Your<b> $new_rqType </b>request of \$$rqAmt has been approved by the blockchain.</p>
                    <p>Kindly visit the <b><a href="https://futurefinanceinvestment.com/transactions"  style="color:#ff3c00">Transactions</a></b> page on your dashboard to confirm.</p>
                    <p><b>PS:</b> How do you like our services? Tell a friend about us today and stand a chance of earning up to <b>10%</b> of their deposit as referral commission.</p>

                    <p style="margin-bottom:30px">For further enquiries, you can check out our <b><a href="https://futurefinanceinvestment.com/faqs"  style="color:#ff3c00">Frequently asked questions</a></b> page or <b><a href="https://futurefinanceinvestment.com/contact" style="color:#ff3c00">contact us</a></b> directly if our page doesn't answer your questions.</p>
                    
                    
                    <a href="https://futurefinanceinvestment.com/transactions" style="color:#ff3c00;font-size:18px;padding:2%;border-radius:6px;box-shadow:0px 0px 3px #ff3c00;border:2px solid #ff3c00;width:8%;margin-left:30%;margin-right:20%">View Transactions</a>
                </body>
                </html>

            HTML;

            $sender = "admin@futurefinanceinvestment.com";

            $headers = "From: $sender \r\n";
            $headers .="Reply-To: $sender \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8\r\n";

            $mail = mail($_POST["userEmail"],"Approved $new_rqType Request On Future Finance Investment",$message, $headers);

            if($mail){
                echo "<br />A Mail has been sent to the user";
            } else {
                echo "Sorry, an error occurred, Mail not sent";
              }





            //Delete From user_requests table as data already exists in transactions Table
            //check if request still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM user_requests WHERE rq_id = ?");
            $ds_stmt->execute([$_POST["request_id"]]);
        
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //get username for ease of access to front-end users
                $ns = $pdo->prepare("SELECT * FROM investors WHERE user_id=?");
                $ns->execute([$ds_data->user_id]);
                $nd = $ns->fetch(PDO::FETCH_OBJ);
                
                if($nd) $name = $nd->username;

                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM user_requests WHERE rq_id = ?");
                $dd_stmt->execute([$_POST["request_id"]]);
    
                echo "<h2>User: ", $name,"'s request has been approved successfully</h2>";
            }
        }


        
        if(isset($_POST["reject"])) {
            //Delete From user_requests table
            //check if request still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM user_requests WHERE rq_id = ?");
            $ds_stmt->execute([$_POST["request_id"]]);
        
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //get username for ease of access to front-end users
                $ns = $pdo->prepare("SELECT * FROM investors WHERE user_id=?");
                $ns->execute([$ds_data->user_id]);
                $nd = $ns->fetch(PDO::FETCH_OBJ);

                if($nd) $name = $nd->username;

                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM user_requests WHERE rq_id = ?");
                $dd_stmt->execute([$_POST["request_id"]]);
    
                echo "<h2 style='color:red'>User: ", $name,"'s request has been Deleted successfully</h2>";
            }
        }
        


        //select and view all users' requests:
        //first check if admin searched for someone in particular
        if(isset($_GET["user"])){
            $search_q = htmlentities($_GET["user"]);

            $u_search_stmt = $pdo->prepare("SELECT * FROM investors WHERE username LIKE ? ORDER BY user_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",0, 100]);
    
            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
            
            foreach($u_data as $u_search_data){
                $urq_stmt = $pdo->prepare("SELECT * FROM user_requests WHERE user_id = ? ORDER BY rq_id DESC LIMIT ?, ?");
                $urq_stmt->execute([$u_search_data->user_id, 0, 100]);

                $urq_data = $urq_stmt->fetchAll(PDO::FETCH_OBJ);
            }
        }  else {
            // if not, then select all:
            $urq_stmt = $pdo->prepare("SELECT * FROM user_requests ORDER BY rq_id DESC LIMIT ?, ?");
            $urq_stmt->execute([0,100]);

            $urq_data = $urq_stmt->fetchAll(PDO::FETCH_OBJ);
        }


       // $urq_stmt = $pdo->prepare("SELECT * FROM user_requests ORDER BY rq_id DESC LIMIT ?, ?");
       // $urq_stmt->execute([0,100]);
       // $urq_data = $urq_stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($urq_data)>0){
            $i = 0;
            foreach($urq_data as $u_d){
                $name = "";

                $user_Email = "";
                $real_Name = "";

                echo "<div class='main' style=margin-top:12px>";

                //get username for ease of access to front-end users:

                $ns = $pdo->prepare("SELECT * FROM investors WHERE user_id=?");
                $ns->execute([$u_d->user_id]);
                $nd = $ns->fetch(PDO::FETCH_OBJ);

                if($nd) {
                   $name = $nd->username; 

                   $user_Email = $nd->user_email;
                   $real_Name = $nd->real_name;
                }
                

                $i+=1;
?>
                <b><?=$i?>. <?=$name?> <br /><br />   <?=$u_d->rq_type?>:</b> <?=$u_d->rq_amount?> 
                <div class="clear" style="margin-bottom:18px">
                    <div style="float:left">
                        <form method="post" action="" id="<?=$i?>">

                            <input type="hidden" name="request_id" value="<?=$u_d->rq_id?>"/>
                            <input type="hidden" name="user_id" value="<?=$u_d->user_id?>"/>
                            <input type="hidden" name="request_type" value="<?=$u_d->rq_type?>"/>
                            <input type="hidden" name="request_amount" value="<?=$u_d->rq_amount?>"/>
                            <input type="hidden" name="request_time" value="<?=$u_d->rq_time?>"/>
                            <input type="hidden" name="request_payment_method" value="<?=$u_d->rq_payment_method?>"/>
                            <input type="hidden" name="request_from" value="<?=$u_d->rq_from?>"/>

                            <input type="hidden" name="userEmail" value="<?=$user_Email?>"/>
                            <input type="hidden" name="realName" value="<?=$real_Name?>"/>

                            <?php 
                            if($u_d->rq_type=="Deposit"){
                            ?>
                                <input type="hidden" name="request_image" value="<?=$u_d->rq_img?>"/>
                            <?php
                            } else {
                            ?>
                                <input type="hidden" name="request_wallet_address" value="<?=$u_d->rq_wallet_address?>"/>
                            <?php
                            }?>
                            
                            <br />
                            <input type="submit" name="accept" value = "Accept" style="background-color:blue; padding:3px;
                            border-radius:6px;color:#fff;text-align:center;height:24px;border:none"/>
                        </form>
                    </div><br />

                    <button style="background-color:red;padding:3px;border-radius:6px;color:#fff;text-align:center;height:24px;border:none;float:left;margin-left:12px" onclick="show_div('reject<?=$i?>')">
                        Reject
                    </button>

                    <button style="background-color:#ff9100;padding:3px;border-radius:6px;color:#fff;text-align:center;height:24px;border:none;float:left;margin-left:12px" onclick="show_div('view<?=$i?>')">
                        <?php 
                            if($u_d->rq_type == "Deposit"){
                                echo "<i class='fa fa-eye'></i> View Image";
                            } else if($u_d->rq_type == "Withdraw"){
                                echo "<i class='fa fa-eye'></i> View Wallet";
                            }
                        ?>
                    </button>

                    <div id="view<?=$i?>" style="display:none">
                        <div class="calculator">
                        <?php
                            if($u_d->rq_type == "Deposit"){
                        ?>
                            <img src="<?=$u_d->rq_img?>" style="width:100px;height:70px"/>
                        <?php
                        } else if($u_d->rq_type == "Withdraw"){
                        ?>
                            <input type="text" class="investor_input" id="wallet_c<?=$i?>" value="<?=$u_d->rq_wallet_address?>"/><i class="fa fa-copy" onlick="copyText('wallet_c<?=$i?>')" style="margin-left:-32px"></i>
                        <?php
                        }
                        ?>
                        </div>
                    </div>

                    <div id ="reject<?=$i?>" style="display:none;background-color:#000;position:fixed;top:21%;padding:10px;box-shadow:0px 0px 9px 0px #ff9100;
                    border-radius:6px;margin:16px;text-align:center">
                        <div class="clear">
                            <i class="fa fa-times" style="float:right" onclick="show_div('reject<?=$i?>')"></i>
                        </div>

                        <form method="post" action ="">
                            Are you sure you want to delete User: <?=$name?>'s <b><?=$u_d->rq_type?> <?=$u_d->rq_amount?></b> request? <b>This can't be undone?</b>
                            <br /><br />

                            <input type="hidden" name="request_id" value="<?=$u_d->rq_id?>"/>
                            <input type="submit" name="reject" value = "Reject" style="background-color:red;
                            padding:3px;border-radius:6px;color:#fff;
                            text-align:center;height:24px;border:none"/>
                        </form> <br />

                        <button style="background-color:#ff9100;padding:3px;border-radius:6px;color:#fff;text-align:center;height:24px;border:none;margin-left:12px" onclick="show_div('reject<?=$i?>')">
                        Cancel
                        </button>
                    </div>
                </div><hr />

                </div><!--End of each main div-->

<?php
            }
        }
        echo "</div>"; //end of dashboard_div class
        admin_Segments::footer();
    } else {
        header("location:/admin");
    }
} else {
    header("location:/admin");
}
?>

<script>
    function show_div(vari) {
        if (document.getElementById(vari).style.display == "none") {
            document.getElementById(vari).style.display = "block";
        } else if (document.getElementById(vari).style.display == "block") {
            document.getElementById(vari).style.display = "none";
        }
    }

    function copyText(linkText){
        x = document.getElementById(linkText);

        x.select();
        x.setSelectionRange(0, 99999);

        //navigator.clipboard.writeText(x.value);
        document.execCommand('copy');
        alert("copied text: " + x.value);
    }

    function ajax_search(){
        sq = document.getElementById("search_input").value;
        obj = new XMLHttpRequest;
        obj.onreadystatechange = function(){
            if(obj.readyState == 4){
                document.getElementById("search").innerHTML = obj.responseText;
            }
        }

        obj.open("GET","/ajax_search.php?search_query="+sq+"&page=user-requests");
        obj.send(null);
    }

    function search_icon(){
        location = "/user-requests/" + document.getElementById("search_input").value;
    }

    
</script>