<?php
include_once("views/Dashboard_Segments.php");

//To be used in 1.) withdraw.php 2.) invest.php 3.) admin_of_admins.php
//Select the rates from database to use as 'value' for the input elements
$rates = [];
$br_stmt = $pdo->prepare("SELECT * FROM btc_rate LIMIT ?, ?");
$br_stmt->execute([0, 6]);
$br_data = $br_stmt->fetchAll(PDO::FETCH_OBJ);

foreach($br_data as $b){
    $rates[] = $b->br;
}


if((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
    //that means.. user is logged in:
        Dashboard_Segments::header();
                //check if user has enough money in deposit wallet address:
                    $cstmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
                    $cstmt->execute([$data->user_id]);
                    $cdata = $cstmt->fetchAll(PDO::FETCH_OBJ);
            
                    $gross_deposit = 0;
                    $minus_from_deposit = 0;
                    $deposit = 0;
            
                    $gross_interest = 0;
                    $minus_from_interest = 0;
                    $interest = 0;
                
                    if(count($cdata)>0){
                        foreach($cdata as $c){    
                            if($c->tr_type == "Deposit"){
                                $gross_deposit += $c->tr_amount;
                            } else if($c->tr_type == "referral"){    
                                $gross_interest += $c->tr_amount;
                            } else if($c->tr_type == "Invest"){ 
                                $gross_interest += $c->profit;
                                if($c->tr_from == "deposit_wallet"){
                                    $minus_from_deposit += $c->tr_amount;
                                } else if($c->tr_from == "interest_wallet"){
                                    $minus_from_interest += $c->tr_amount;
                                }
                            } else if($c->tr_type == "Withdraw"){ 
                                if($c->tr_from == "deposit_wallet"){
                                    $minus_from_deposit += $c->tr_amount;
                                } else if($c->tr_from == "interest_wallet"){
                                    $minus_from_interest += $c->tr_amount;
                                }
                            }
                        }
            
                        $deposit = $gross_deposit - $minus_from_deposit;
                        $interest = $gross_interest - $minus_from_interest;
                    }
            
            
                    if(isset($_POST["withdrawal_amount"])){
            
                        if($_POST["request_from"] == "deposit_wallet"){
                            //place withdraw request from deposit wallet balance
                            if($deposit >= $_POST["withdrawal_amount"]){
                                //Place withdraw request:
                                $deposit_stmt = $pdo->prepare("INSERT INTO user_requests(user_id, rq_type, rq_amount,rq_time,rq_wallet_address,rq_payment_method,rq_from) VALUES(?,?,?,?,?,?,?)");
                                $deposit_stmt->execute([$_POST["withdrawer_id"],$_POST["request_type"],$_POST["withdrawal_amount"],date("Y-m-d h:i:s", time()),$_POST["user_wallet_address"],$_POST["wallet_type"],$_POST["request_from"]]);
                                echo "Withdraw Request placed successfully.<br />";
                    
                    
                                //Mail User:
                                $w_name = $data->real_name;
                                $withd = $_POST["withdrawal_amount"];
                                $walletAddress = $_POST['user_wallet_address'];
                                $walletType = $_POST['wallet_type'];
                                
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
                                            <p  style ="font-family:Trirong;">Hello $w_name, Your Withdrawal Request of \$$withd from your <b>$walletType </b>wallet address:<b> $walletAddress</b> has been injected into the blockchain and is currently awaiting approval by the system.</p>
                                            <p>Do well to check the wallet address you inputed in the request process. Make sure it doesn't contain any errors as this would lead to rejection.</p>
                                            <p>Don't be scared though, you would receive 100% refund of your money.</p>
                                            <p>In fact, the money never really leaves your Future Finance Investment's Interest Wallet Address until full approval by the Blockchain.</p>
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

                                $mail = mail($data->user_email,"Your Investment On Future Finance Investment",$message, $headers);

                                if($mail){
                                    echo "<br />A Mail has been sent to you";
                                } else {
                                    echo "<br />Sorry, An error occurred. Mail not sent";
                                  }

                                //Mail function ends


            
                            } else{
                                echo "<div class='invalid'>Sorry, Insuffient Funds</div>";
                            }
            
            
            
            
                        } else if($_POST["request_from"] == "interest_wallet"){
            
            
            
                            //Place withdraw request from interest wallet balance
                            if($interest >= $_POST["withdrawal_amount"]){
                                //place withdraw request:
                                $deposit_stmt = $pdo->prepare("INSERT INTO user_requests(user_id, rq_type, rq_amount,rq_time,rq_wallet_address,rq_payment_method,rq_from) VALUES(?,?,?,?,?,?,?)");
                                $deposit_stmt->execute([$_POST["withdrawer_id"],$_POST["request_type"],$_POST["withdrawal_amount"],date("Y-m-d h:i:s", time()),$_POST["user_wallet_address"],$_POST["wallet_type"],$_POST["request_from"]]);
                    
                    
                                //Mail User:
                                $w_name = $data->real_name;
                                $withd = $_POST["withdrawal_amount"];
                                $walletAddress = $_POST['user_wallet_address'];
                                $walletType = $_POST['wallet_type'];
                                
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
                                            <p  style ="font-family:Trirong;">Hello $w_name, Your Withdrawal Request of \$$withd from your <b>$walletType </b>wallet address:<b> $walletAddress</b> has been injected into the blockchain and is currently awaiting approval by the system.</p>
                                            <p>Do well to check the wallet address you inputed in the request process. Make sure it doesn't contain any errors as this would lead to rejection.</p>
                                            <p>Don't be scared though, you would receive 100% refund of your money.</p>
                                            <p>In fact, the money never really leaves your Future Finance Investment's Interest Wallet Address until full approval by the Blockchain.</p>
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

                                $mail = mail($data->user_email,"Your Withdrawal Request On Future Finance Investment",$message, $headers);

                                if($mail){
                                    echo "<br />A Mail has been sent to you";
                                } else {
                                    echo "<br />Sorry, An error occurred. Mail not sent";
                                  }

                                //Mail function ends


            
                            } else{
                                echo "<div class='invalid'>Sorry, Insuffient Funds</div>";
                              }
            
            
            
                        }
                    } 
?>



 
<div class="dashboard_div">
<form method="post" action="">

<div class="calculator">
<h1>Place Withdraw Request</h1><hr />

<b style="font-size:19px">Step 1:</b> Choose Your Preferred Wallet:
<br /><br />

<input type="radio" name="wallet_type" value="bitcoin" required/> Bitcoin <br /><br />
<input type="radio" name="wallet_type" value="ethereum" required/> Ethereum <br /><br />
<input type="radio" name="wallet_type" value="usdt_trc20" required/> USDT TRC20<br /><br />
<input type="radio" name="wallet_type" value="bitcoin_cash" required/> Bitcoin Cash <br /><br />

<!-- Show more Payment Methods -->

<span class="action_button" onclick="show_div('more_payment_methods')">
    View More &nbsp;<i class="fa fa-angle-down"></i>
</span>

<div id="more_payment_methods" style="display:none"><br />

<input type="radio" name="wallet_type" value="usdt_erc20" required/> USDT ERC20<br /><br />
<input type="radio" name="wallet_type" value="bnb" required/> BNB <br /><br />
<input type="radio" name="wallet_type" value="bnb_p20" required/> BNB P20<br /><br />
<input type="radio" name="wallet_type" value="binance_smart_chain" required/> Binance Smart Chain <br /><br />

</div>

</div>


<div class="calculator">
<hr />

<b style="font-size:19px">Step 2:</b>Enter your wallet address below for verification by the blockchain system.
<br />

<input type = "text" class="investor_input" id = 'user_wallet_address' name = "user_wallet_address" placeholder="your wallet address: "/>
<i class="fa fa-copy" style="margin-left:-33px" onclick="copyText('user_wallet_address')"></i>

<br /><br />
</div>


<!--calculator-->
<div class="calculator">
    <h2>Calculator</h2>

    <b style="font-size:19px">Step 3:</b>Calculate the Crypto Currency equivalence of Your Withdrawal.
    <br />
    
    <input type = "text" class="investor_input" name="withdrawal_amount" placeholder="usd" id="usd" onkeyup="calculate('usd')" required/><span style="margin-left:-39px">USD</span><br />
    <input type = "text" class="investor_input" placeholder="btc" id = "btc" onkeyup="calculate('btc')" name="<?=$rates[0]?>" required/><span style="margin-left:-39px">BTC</span><br />

    <input type = "text" class="investor_input" placeholder="eth" id = "eth" onkeyup="calculate('eth')" name="<?=$rates[1]?>" required/><span style="margin-left:-39px">ETH</span><br />
    <input type = "text" class="investor_input" placeholder="usdt" id = "usdt" onkeyup="calculate('usdt')" name="<?=$rates[2]?>" required/><span style="margin-left:-45px">USDT</span><br />
    <input type = "text" class="investor_input" placeholder="pm" id = "pm" onkeyup="calculate('pm')" name="<?=$rates[3]?>" required/><span style="margin-left:-39px">PM</span><br />
    
</div>
<!--calculator ends-->


<!--withdraw from-->

<div class="calculator">
<h3>Withdraw Funds From:</h3> <hr />
<input type="radio" name="request_from" value="deposit_wallet" required/>Deposit Wallet Balance: $<?=$deposit?> <br /><br />
<input type="radio" name="request_from" value="interest_wallet" required/>Interest Wallet Balance (interest + capital): $<?=$interest?>

<br /><br />
<div style="text-align:center"><i style="font-size:14px;">Our System offers a seamless withdrawal policy that allows investors to withdraw both their interest and capital at any time, even before the end of their trading session.</i></div>
</div>

<!--Withdraw from ends-->



<!--hiddden inputs-->

<input type="hidden" name = "withdrawer_id" value="<?=$data->user_id?>"/>
<input type="hidden" name="request_type" value="Withdraw"/>

<br />
<input type="submit" value="Withdraw" class="button" style="margin-left:16px"/>

</form>

</div>


<?php
    Dashboard_Segments::footer();
    } else {
        header("location:/login");
    }
} else {
    header("location:/login");
}
?>  

<script>
    function calculate(input_id1){
        currency_array = ["btc","eth","usdt","pm"];
        if(document.getElementById(input_id1).placeholder == "usd"){
            for(i of currency_array.keys()){
                x = document.getElementById(currency_array[i]);
                rate = x.name;
                x.value = (document.getElementById(input_id1).value)/rate ; 
            }     
        } else {
            currency = document.getElementById(input_id1);
            for(j of currency_array.keys()){
                y = document.getElementById(currency_array[j]);
                rate = (currency.value)*(currency.name);
                y.value = (rate/(y.name)); 
            }   
            document.getElementById('usd').value = (currency.value)*(currency.name); 
        }
    }

    function show_div(vari) {
        if (document.getElementById(vari).style.display == "none") {
            document.getElementById(vari).style.display = "block";
        } else if (document.getElementById(vari).style.display == "block") {
            document.getElementById(vari).style.display = "none";
        }
    }
</script>