<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Transactions.php");

if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){
    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
    $stmt->execute([$_COOKIE["admin_name"], $_COOKIE["admin_password"]]);

    $data = $stmt->fetch(PDO::FETCH_OBJ);
    if($data){
        //that means admin is logged in
        admin_Segments::header();
?>
        <div class="dashboard_div" style="background-color:#01123c;border:none">

        <h1 style="text-align:center;margin-top:130px">Registered Users on Future Finance</h2>

        <!-- "refresh" class -->
        <a href = '' style='color:#2b8eeb;font-size:19px;margin:0 3px 0 6px;padding:6px 9px;background-color:#000;border-radius:4px' class="refresh">
        
        <i class='fa fa-refresh'></i>
        <span style="font-size:18px; font-weight:bold">&nbsp; Refresh</span>

        </a> 
        <!-- End of "refresh" class -->

        <br /><br /> 
<?php
        //check if admin is searching for someone:
?>
        <input type="text" onkeyup="ajax_search()" id="search_input" class="input" placeholder="Enter username: try: abc" style="border:1px solid #2b8eeb;width:75%"/> 
        
        <i class="fa fa-search" onclick ="search_icon()" style="padding:7px;border-radius:4px;font-size:16px;color:#fff;background-color:#2b8eeb"></i>

        <div id="search" style="position:absolute;width:75%"></div>
        
        <div class='main'>    <!-- 'main' div starts -->
<?php
        //To Delete User:
        if(isset($_POST["remove_user"])){
            //check if user still exists
            $ds_stmt = $pdo->prepare("SELECT * FROM investors WHERE user_id = ?");
            $ds_stmt->execute([$_POST["remove_user"]]);
    
            $ds_data = $ds_stmt->fetch(PDO::FETCH_OBJ);
            if($ds_data){ 
                //then delete
                $dd_stmt = $pdo->prepare("DELETE FROM investors WHERE user_id = ?");
                $dd_stmt->execute([$_POST["remove_user"]]);

                echo "<h4 style='color:red'>User: ", $ds_data->username, " has been deleted successfully</h4>";
            }
        }


        //To Edit Transactions:
        if(isset($_POST["edit_transaction"])){

            $update_stmt = $pdo->prepare("UPDATE transactions SET tr_type = ?, tr_amount = ?, profit = ? WHERE tr_id = ? AND tr_type = ?");
            $update_stmt->execute([$_POST["transaction_type"],$_POST["transaction_amount"],Null,$_POST["edit_transaction"],$_POST["transaction_type"]]);

            if($_POST["transaction_type"] == "Invest"){
                $update_stmt2 = $pdo->prepare("UPDATE transactions SET profit = ?,tr_from = ? WHERE tr_id = ?");
                $update_stmt2->execute([$_POST["transaction_amount"],'deposit_wallet',$_POST["edit_transaction"]]);
            }
            echo "<h4 style='color:#ff9100'>Transaction updated successfully.</h4>";

        }


        //To Delete Transactions:
        if(isset($_POST["delete_transaction"])){

            $delete_stmt = $pdo->prepare("DELETE FROM transactions WHERE tr_id = ?");
            $delete_stmt->execute([$_POST["delete_transaction"]]);

            echo "<h4 style='color:red'>Transaction has been deleted successfully</h4>";

        }



        //To add Transactions:
        if(isset($_POST["add_transaction"])){

            if($_POST["add_transaction_type"] == "Invest"){
                $deposit_stmt1 = $pdo->prepare("INSERT INTO transactions(user_id, tr_type, tr_amount,tr_time,last_profit_time,profit,tr_wallet_address,tr_payment_method,tr_from) VALUES(?,?,?,?,?,?,?,?,?)");
                 $deposit_stmt1->execute([$_POST["add_transaction"],$_POST["add_transaction_type"],$_POST["add_transaction_amount"],date("Y-m-d", time()),date("Y-m-d", time()),$_POST["add_transaction_amount"],$_POST["add_transaction_wa"],$_POST["add_transaction_wt"],'deposit_wallet']); 

            } else {
                $deposit_stmt = $pdo->prepare("INSERT INTO transactions(user_id, tr_type, tr_amount,tr_time,tr_wallet_address,tr_payment_method) VALUES(?,?,?,?,?,?)");
                $deposit_stmt->execute([$_POST["add_transaction"],$_POST["add_transaction_type"],$_POST["add_transaction_amount"],date("Y-m-d", time()),$_POST["add_transaction_wa"],$_POST["add_transaction_wt"]]);    
            }

            
            

            echo "<h3 style='color:#ff9100'>Transaction added Successfully</h3>";
        }



        //To Deposit Transactions:
        /*
        if(isset($_POST["deposit"])){

            $deposit_stmt = $pdo->prepare("INSERT INTO transactions(user_id, tr_type, tr_amount,tr_time) VALUES(?,?,?,?)");
            $deposit_stmt->execute([$_POST["depositer_id"],"Deposit",$_POST["deposit"],date("Y-m-d", time())]);

            echo "<h3 style='color:#ff9100'>Deposited Successfully</h3>";
        }

        */

        //Mail Investor:

        if(isset($_POST["message_to_investor"]) && !empty($_POST["message_to_investor"])){

            $messageFromAdmin = nl2br(htmlentities($_POST["message_to_investor"]));

            $message = <<<HTML
                <html>
                <head>
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                            <link rel="stylesheet" href="https://futurefinanceinvestment.com/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
            
                </head>
                <body style ="font-family:Trirong;">
                    <center>
                        <img src="https://futurefinanceinvestment.com/static/images/logo.png" style="margin-left:36%;margin-right:36%;width:25%;"/>
                    </center>
                    <h2 style="color:#00008b;font-family:Arimo;text-align:center">Future Finance Investment</h2>
        
            HTML;

            $sender = "admin@futurefinanceinvestment.com";

            $headers = "From: $sender \r\n";
            $headers .="Reply-To: $sender \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8\r\n";

            $mail = mail($_POST["investor_mail"],"Message From Future Finance Investment",$message.$messageFromAdmin."</body></html>", $headers);

            if($mail){
            ?>

                <div id="message_success" style="background-color:#ff9100;color:#fff;
                border-radius:3px;padding:4px;margin:8px 8px;display:block;position:fixed;top:40%;width:80%;
                box-shadow:0px 0px 9px 0px #fff">
                    
                    <div class="clear">
                        <span class="float:right"><b>A Mail has been sent to the investor </b></span>
                        &nbsp;&nbsp;&nbsp;

                        <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                    </div>
                </div>

            <?php
            } else {
                echo "Sorry, an error occurred, Mail not sent";
            }

        }


        //Select and view all users for easy decision making:

        //A Simple Pagination Algorithm:
        $p = 1;
        $num_of_rows = 10;

        if(isset($_GET["page"])){
            $p = htmlentities($_GET["page"]);
            if(!is_numeric($p) || $p < 1){
                $p = 1;
            }
        }
        
        $page_to_call = ($p - 1)*$num_of_rows;

        //count entire users:
        $u_search_stmt = $pdo->prepare("SELECT * FROM investors ORDER BY user_id DESC LIMIT ?, ?");
        $u_search_stmt->execute([0, 1000]);

        $num_of_users = count($u_search_stmt->fetchAll(PDO::FETCH_OBJ));

        $max = ceil($num_of_users/$num_of_rows);
        // -- end of pagination algorithm --

        
        //first check if admin searched for someone in particular
        if(isset($_GET["user"])){
            $search_q = htmlentities($_GET["user"]);

            $u_search_stmt = $pdo->prepare("SELECT * FROM investors WHERE username LIKE ? ORDER BY user_id DESC LIMIT ?, ?");
            $u_search_stmt->execute(["%$search_q%",$page_to_call, $num_of_rows]);

            $u_data = $u_search_stmt->fetchAll(PDO::FETCH_OBJ);
        }  else {
            //if no particular person is searched for, call out everyone:
            $u_stmt = $pdo->prepare("SELECT * FROM investors ORDER BY user_id DESC LIMIT ?, ?");
            $u_stmt->execute([$page_to_call, $num_of_rows]);
    
            $u_data = $u_stmt->fetchAll(PDO::FETCH_OBJ);
        }

        if(count($u_data)>0){     
            $i = 0;
            foreach($u_data as $d){
                $i += 1;
?>
        <div class="everything-both-buttons-nd-hidden-divs" style='line-height:30px'>    
                <!--
                <a href="/confirm-deposit" 
                style="background-color:green;
                    padding:3px;border-radius:6px;color:#fff;
                    margin-left:6px;text-align:center;height:24px">Deposit</a>  
                -->
     
                <div class="visible_buttons">

                    <?=$i + (($p - 1)*$num_of_rows)?>. &nbsp;<b style='font-size:21px'> <?=$d->username?> </b> <br />

                    <button onclick = "create_content('transactions',<?=$i?>)" style="background-color:blue" 
                    class="show_hidden_divs_button">
                        <i class="fa fa-spinner"></i> Transactions 
                    </buton>

                    <button onclick = "create_content('referred-by',<?=$i?>)" style="background-color:#888"
                    class="show_hidden_divs_button">
                        <i class="fa fa-eye"></i> Referred By? 
                    </buton>

                    <button onclick = "create_content('message',<?=$i?>)" style="background-color:#ff9100"
                    class="show_hidden_divs_button">
                        <i class="fa fa-envelope"></i> Message
                    </buton>
                    
                    <button onclick = "create_content('remove',<?=$i?>)" style="background-color:red"
                    class="show_hidden_divs_button">
                        <i class="fa fa-warning"></i> Remove 
                    </buton>
                </div>


            <div class="clear">
            <div style="margin-top:18px;" class="all-hidden-divs">

            <!-- To make all hidden div content to appear in the same spot on display: -->
            <div id="content_space<?=$i?>" class="calculator" style=""> 
            <!-- style="display:block creates undesirable problems like making the div not to appear even onclick" -->
            </div>

            <!--hidden section 1: View Transactions-->
            <div id="transactions<?=$i?>" style="display:none;border:2px solid blue;border-radius:6px;margin-top:12px;padding:4px;">
                <?php
                    $t_stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY tr_id DESC LIMIT ?, ?");
                    $t_stmt->execute([$d->user_id, 0, 100]);
                    $t_data = $t_stmt->fetchAll(PDO::FETCH_OBJ);

                    if(count($t_data)>0){  
                        foreach($t_data as $t){
                ?>
                        <form method="post" action="">
                        <h4><?=$t->tr_time?></h4><hr />
                        <select name="transaction_type" style="padding:4px;background-color:#fff;border-radius:3px">
                            <option value="<?=$t->tr_type?>"><?=$t->tr_type?>: </option>
                            <option value="Deposit">Deposit: </option>
                            <option value="Invest">Invest: </option>
                            <option value="Withdraw">Withdraw: </option>
                            <option value="referral">referral: </option>
                        </select> &nbsp;
                        <input type="number" value="<?=$t->tr_amount?>" name="transaction_amount" style="border-radius:3px;border:1px solid #fff;padding:4px"/> <br />
                        <?php //edit transactions coming here very soon.. Remove transactions too || -- done today:16th May 2022 11:02pm ?>
                        
                        <?php 
                            if($t->tr_type == "Deposit"){
                        ?>
                                <?php 
                                    /*
                                        <b>Interest Rate</b>: <?=$transactions->rate($t->tr_amount)?>% every day for <?=$transactions->num_of_days($t->tr_amount)?> days<br />
                                    */
                                ?>
                        <?php       
                            } else if($t->tr_type == "Invest"){
                        ?>
                                <b>Interest Rate</b>: <?=$transactions->rate($t->tr_amount)?>% every day for <?=$transactions->num_of_days($t->tr_amount)?> days<br />
                                <b>Total Profit</b>: <?=$t->profit?>
                        <?php
                            } else if($t->tr_type == "Withdraw"){
                        ?>
                                <br />

                                <span class="admin_invest_now_button" style="background-color:#2b8eeb"><?=$t->tr_payment_method?></span> 

                                <br />

                                <input style="height:24px;border:1px solid #2b8eeb;border-right:30px solid #2b8eeb;width:80%;border-radius:4px;margin-top:8px" id = 'wallet_address' value="<?=$t->tr_wallet_address ?>"/>
                                
                                <i style="margin-left:-27px" class="fa fa-copy" onclick="copyText('wallet_address')"></i><br />
                        <?php 
                            }
                        ?>

                        <hr />

                        <div class="clear">
                            <div style="float:right">
                                <input name = "edit_transaction" type="hidden" value="<?=$t->tr_id?>"/>
                                <input type="submit" class="admin_invest_now_button" value="Edit"/>

                                <span class="admin_invest_now_button" onclick="show_div('confirm_delete_transaction<?=$i?>')"; style="background-color:red">Delete</span> 
                            </div>
                        </div>
                <?php
                        echo "<hr /></form>";
                ?>

                        <form method ="post" id='confirm_delete_transaction<?=$i?>' class="pop_up" style="display:none">
                            Are you sure you want to delete Transaction: <b><?=$t->tr_type?>:</b> $<?=$t->tr_amount?> for user: <b><?=$d->username?></b><br />
                            
                            <input name = "delete_transaction" type="hidden" value="<?=$t->tr_id?>"/>
                            <input type="submit" class="admin_invest_now_button" value="Yes"/> &nbsp;
                            
                            <span class="admin_invest_now_button" onclick="show_div('confirm_delete_transaction<?=$i?>')"; style="background-color:red">Cancel</span> <br />
                        </form>
                
                <?php

                        }  

                    }
                ?>

                <span onclick="show_div('add_transaction<?=$i?>')"> <i class="fa fa-plus" style="color:#fff;background-color:blue;padding:5px;border-radius:3px"></i> &nbsp; <span style="color:#fff;background-color:blue;padding:5px;border-radius:3px">Add Transaction</span> </span>   
                
                <div id="add_transaction<?=$i?>" style="display:none">
                    <form method = "post" action ="">
                    <b>Transaction Type:</b> <br />
                    <input type="radio" name="add_transaction_type" value="Deposit"/> Deposit<br />
                    <input type="radio" name="add_transaction_type" value="Invest"/> Invest<br />
                    <input type="radio" name="add_transaction_type" value="Withdraw"/>Withdraw<br />
                    <input type="radio" name="add_transaction_type" value="referral"/> Referral<br />

                    <input type="number" name="add_transaction_amount" placeholder="Amount: $0.00" style="border-radius:3px;border:1px solid #fff;padding:4px;margin:10px 0px 10px 0px"/> <br />
                    <input type="text" name="add_transaction_wa" placeholder="Wallet Address:" style="border-radius:3px;border:1px solid #fff;padding:4px;margin:5px 0px 15px 0px"/> <br />
                    <b>Wallet Type: </b>
                        <select name="add_transaction_wt" style="padding:4px;background-color:#fff;border-radius:3px">
                            <option value="bitcoin">Bitcoin: </option>
                            <option value="ethereum">Ethereum: </option>
                            <option value="usdt">USDT: </option>
                            <option value="perfect_money">Perfect Money: </option>
                        </select> 

                        <div class="clear">
                            <div style="float:right">
                                <input name = "add_transaction" type="hidden" value="<?=$d->user_id?>"/>
                                <input type="submit" class="admin_invest_now_button" value="Add"/>
                                <span class="admin_invest_now_button" onclick="show_div('add_transaction<?=$i?>')"; style="background-color:red">Cancel</span> 
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!--End of transaction div-->



            <!--hidden section 2: Referred By?-->
            <div id="referred-by<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;padding:4px;">
                
                <?php
                    if(!empty($d->referred_by)){
                        echo "Referred By: <b>", $d->referred_by, "</b>";
                    } else {
                        echo "Not referred";
                    }
                ?>

            </div>
            <!--End of referred by div-->


            <!--hidden section 3: Message-->
            <div id="message<?=$i?>" style="display:none;border:2px solid #888;border-radius:6px;margin-top:12px;
                padding:4px;">

                <form method="post" action="">
                <!-- -->
                <span style="position:absolute;left:48px;">To:</span> 
                
                <input type="text" id="investor_mail<?=$i?>" style=
                "border-left:30px solid #ff9100;border-right:30px solid #ff9100;border-radius:4px;
                height:21px;width:70%;margin-bottom:15px"
                <?php if(!empty($d->user_email)){ ?> value="<?=$d->user_email?>" <?php } ?> 
                name="investor_mail"/>

                <i style="margin-left:-27px" class="fa fa-copy" onclick="copyText('investor_mail<?=$i?>')"></i>

                <textarea style="width:75%;height:100px;
                border-radius:4px" name="message_to_investor">Hello <?=$d->real_name?>, </textarea>

                <div class="clear">
                    <div style="float:left">
                        <input type="submit" class="admin_invest_now_button" value="Send Mail"/>
                        <span class="admin_invest_now_button" onclick="hide_content_space('message',<?=$i?>)"; style="
                        background-color:red">
                        Cancel</span> 
                    </div>
                </div>

                </form>

            </div>
            <!--End of message div-->



            <!-- hidden section 4: Remove User -->
            
            <div id="remove<?=$i?>" style="display:none;border:2px solid red;border-radius:6px;margin-top:12px;padding:3px">

            <form method="post" action="" id="message_form<?=$i?>" class="pop_up">
            <span style="text-align:center">Are you sure you want to remove user: <b style="font-size:18px;color:red;border-bottom:2px solid #fff"><?=$d->username?>?</b> &nbsp;

            <b>This can't be Undone</b></span><br /><br />

            <input type="hidden"  name="remove_user" value="<?=$d->user_id?>"/>

            <input type="submit" value="Remove" style="background-color:red;
                    padding:3px;margin:3px;border-radius:6px;color:#fff;border:none;height:24px;"/> 

            <!--Cancel "Remove User" (Don't remove):-->
            <!--onclick = "show_div('remove <= $i >')"-->
            <span onclick="hide_content_space('remove',<?=$i?>)" style="background-color:#ff9100;
                    padding:3px;border-radius:6px;color:#fff;
                    margin-left:6px;text-align:center;height:24px;border:none">
                    Cancel 
            </span>    
            </form>

            </div> <!--End of remove<=i> div-->

            </div><!--End of hidden divs-->
            </div><!--End of hidden divs clear class-->   
        </div> <hr /><br /><!--End of Both Buttons and hidden divs-->
        
        <!--End of all - both buttons hidden divs-->

<?php
            }
        }
?>

        <!--Paginator-->
        <div class="clear" style="font-weight:bold;font-size:18px">
            <?php if($p > 1) { ?> 
                <div style="float:left">
                   <b>
                       <a href="?page=<?=$p-1?>" style="color:#fff"><i class="fa fa-angle-left">&nbsp; Previous</i></a>
                    </b>
                </div> 
            <?php } ?>

            <?php if($p < $max) { ?> 
                <div style="float:right">
                    <b>
                        <a href="?page=<?=$p+1?>" style="color:#fff">Next &nbsp;<i class="fa fa-angle-right"></i></a>
                    </b>
                </div> 
            <?php } ?>
        </div> <!-- End of Paginator -->

        </div> <!-- End of class 'main_div' -->

<?php
    }else{
        //redirect
        header("location:/admin");
    }

    echo "</div>"; //end of 'main' div
    admin_Segments::footer();
} else {
    //redirect
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

    function create_content(varia, numb) {
        get_id = varia + (numb).toString();
        //get_html = document.getElementById(get_id).innerHTML;

        get_id2 = "content_space" + numb;

        document.getElementById(get_id2).innerHTML = "";
        document.getElementById(get_id2).innerHTML = document.getElementById(get_id).innerHTML;
        document.getElementById(get_id2).style = "display:block";
    }

    function hide_content_space(cont_name, numbr){
        
        con_space = "content_space" + (numbr).toString();
        
        document.getElementById(con_space).innerHTML = ""; //for internet explorer
        document.getElementById(con_space).style = "display:none";
    }

    function ajax_search(){
        sq = document.getElementById("search_input").value;
        obj = new XMLHttpRequest;
        obj.onreadystatechange = function(){
            if(obj.readyState == 4){
                document.getElementById("search").innerHTML = obj.responseText;
            }
        }

        obj.open("GET","/ajax_search.php?search_query="+sq+"&page=site-users");
        obj.send(null);
    }

    function search_icon(){
        location = "/site-users/" + document.getElementById("search_input").value;
    }
</script>