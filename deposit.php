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
        //that means the person is logged in:
        Dashboard_Segments::header();
        
        if(isset($_POST["deposit"])){
                //insert deposit and image proper:

                /*
                //upload image:
                if ($_FILES){
                    $name = $_FILES['payment_proof']['name'];
                    switch($_FILES['payment_proof']['type']) {
                        case "image/jpg" : $ext = "jpg"; break;
                        case 'image/jpeg': $ext = 'jpg'; break;
                        case 'image/gif': $ext = 'gif'; break;
                        case 'image/png': $ext = 'png'; break;
                        case 'image/tiff': $ext = 'tif'; break;
                        default: $ext = ''; break;
                    }
                    if ($ext) {
                        $n = "static/user-uploads/mcf-$name.$ext";
                        // if(file_exists($n)){
                        //    echo "Sorry, Filename already exists, try changing the file name and try again";
                        //} else {
                        //    move_uploaded_file($_FILES['payment_proof']['tmp_name'], $n);
                        //    echo "file uploaded successfully<br />
                        //        Request placed successfully.";
                        //}  
                        move_uploaded_file($_FILES['payment_proof']['tmp_name'], $n);
                        echo "Request placed successfully.";
                        
                    } else {
                        $n = "";
                        echo "Sorry, a file upload error occurred. Either the file '$name' you inputed is not an accepted image file or you failed to upload any file.";
                    }
                }
                //--end of image upload
                */

                //insert deposit proper
                $deposit_stmt = $pdo->prepare("INSERT INTO user_requests(user_id, rq_type, rq_amount,rq_time, rq_img,rq_payment_method) VALUES(?,?,?,?,?,?)");
                $deposit_stmt->execute([$_POST["depositer_id"],$_POST["request_type"],$_POST["deposit"],date("Y-m-d h:i:s", time()),$_POST["payment_proof"],$_POST["wallet_type"]]);
            
            

                //Mail User:
                $i_name = $data->real_name;
                $dep = $_POST["deposit"];
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
                            <p  style ="font-family:Trirong;">Hello $i_name, Your <b>Investment</b> of $dep has been injected into the blockchain and is currently awaiting approval by the system.</p>
                            <p>Do well to check the Transaction page on your dashboard from time to time to view your profits.</p>
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

                $mail = mail($data->user_email,"Your Deposit On Future Finance Investment",$message, $headers);

                if($mail){
                    echo "<br />A Mail has been sent to you";
                } else {
                    echo "Sorry, an error occurred, Mail not sent";
                  }
            
            
            
            
            }
?>
 
<div class="dashboard_div">
    
<h1 style="margin:3% 7%">Make Deposit<hr /></h1>

<div class="calculator">

<b style="font-size:19px">Step 1:</b> Choose Payment Method:
<br /><br />

<form method="post" action="" enctype="multipart/form-data">

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

<br />
<b style="font-size:19px">Step 2:</b> Copy wallet address below and send screenshot of proof of payment for verification by the admins.

<br />

<!--Admin Crypto Wallets -->
<!--BITCOIN-->
<input type = "text" class="investor_input2" id="btc_c" value="bc1q9uagkg5zjuvntcrwdel2y3g0mcm523k39jftth"/>
<i class="fa fa-copy" style="margin-left:-62px" onclick="copyText('btc_c')"> BTC</i>

<!--ETHEREUM-->
<input type = "text" class="investor_input2" id="eth_c" value="0x589A11BC83eCce06b0d00Ba793d92c185b9E4941"/>
<i class="fa fa-copy" style="margin-left:-62px" onclick="copyText('eth_c')"> ETH</i>

<!--USDT TRC20-->
<input type = "text" class="investor_input2" id="usdt_trc20_c" value="TWZ61KHa5rdCxMaqEeKMmBLerVM6kSpRo6"/>
<i class="fa fa-copy" style="margin-left:-74px;font-size:10px;" onclick="copyText('usdt_trc20_c')"> USDT TRC20</i>

<!-- BITCOIN CASH  -->
<input type = "text" class="investor_input2" id="btc_c_c" value="bnb1lw5fh2q7gvwn9nq36h8gg5rradf5su36har0jv"/>
<i class="fa fa-copy" style="margin-left:-69px;font-size:10px;" onclick="copyText('btc_c_c')"> BTC CASH</i>

<br /><br /><br />

<!-- Show more crypto wallets-->

<span class="action_button" onclick="show_div('more_wallets')">
    View More &nbsp;<i class="fa fa-angle-down"></i>
</span>

<div id="more_wallets" style="display:none"><br />
    <!--USDT ERC20-->
    <input type = "text" class="investor_input2" id="usdt_erc20_c" value="0x589A11BC83eCce06b0d00Ba793d92c185b9E4941"/>
    <i class="fa fa-copy" style="margin-left:-74px;font-size:10px;" onclick="copyText('usdt_erc20_c')"> USDT ERC20</i>

    <!--BNB-->
    <input type = "text" class="investor_input2" id="bnb_c" value="bnb1lw5fh2q7gvwn9nq36h8gg5rradf5su36har0jv"/>
    <i class="fa fa-copy" style="margin-left:-62px" onclick="copyText('bnb_c')"> BNB</i>

    <!--BNB P20-->
    <input type = "text" class="investor_input2" id="bnb_p20_c" value="0x589A11BC83eCce06b0d00Ba793d92c185b9E4941"/>
    <i class="fa fa-copy" style="margin-left:-69px;font-size:10px;" onclick="copyText('bnb_p20_c')"> BNB P20</i>

    <!--SMART CHAIN-->
    <input type = "text" class="investor_input2" id="smart_chain_c" value="0x589A11BC83eCce06b0d00Ba793d92c185b9E4941"/>
    <i class="fa fa-copy" style="margin-left:-62px" onclick="copyText('smart_chain_c')"> BSC</i>
</div>

<br />
</div>


<!--calculator-->
<div class="calculator">
    <h2>Calculator</h2>

    <b style="font-size:19px">Step 3:</b>Calculate the Crypto Currency equivalence of the investment you want to make.
    <br />

<?php
    /*
    <input type = "text" class="investor_input" name="deposit" placeholder="usd" id="usd" onkeyup="calculate('usd','btc',<?=$btc_rate?>)" required/><span style="margin-left:-32px">USD</span><br />
    <input type = "text" class="investor_input" placeholder="btc" id = "btc" onkeyup="calculate('btc','usd',<?=$btc_rate?>)" required/><span style="margin-left:-32px">BTC</span><br />

    <input type = "text" class="investor_input" placeholder="eth" id = "eth" onkeyup="calculate('btc','usd',<?=$btc_rate?>)" required/><span style="margin-left:-32px">ETH</span><br />
    <input type = "text" class="investor_input" placeholder="usdt" id = "usdt" onkeyup="calculate('btc','usd',<?=$btc_rate?>)" required/><span style="margin-left:-38px">USDT</span><br />
    <input type = "text" class="investor_input" placeholder="pm" id = "pm" onkeyup="calculate('btc','usd',<?=$btc_rate?>)" required/><span style="margin-left:-32px">PM</span><br />
    */
?>    

    <input type = "text" class="investor_input" name="deposit" placeholder="usd" id="usd" onkeyup="calculate('usd')" required/><span style="margin-left:-39px">USD</span><br />
    <input type = "text" class="investor_input" placeholder="btc" id = "btc" onkeyup="calculate('btc')" name="<?=$rates[0]?>" required/><span style="margin-left:-39px">BTC</span><br />

    <input type = "text" class="investor_input" placeholder="eth" id = "eth" onkeyup="calculate('eth')" name="<?=$rates[1]?>" required/><span style="margin-left:-39px">ETH</span><br />
    <input type = "text" class="investor_input" placeholder="usdt" id = "usdt" onkeyup="calculate('usdt')" name="<?=$rates[2]?>" required/><span style="margin-left:-45px">USDT</span><br />
    <input type = "text" class="investor_input" placeholder="pm" id = "pm" onkeyup="calculate('pm')" name="<?=$rates[3]?>" required/><span style="margin-left:-39px">PM</span><br />
    
</div>

<!-- Screenshot-->

<!-- 

<div class="calculator">
<h2>Send Screenshot</h2><hr/>
<p><b style="font-size:19px">Step 4:</b> Send Proof of payment:</p>
Supported Formats:png, jpg, jpeg, gif<br />
<input type="file" name="payment_proof" style="background-color:#2b8eeb;border-radius:6px;color:#fff;padding:5px" value="choose image"/><br />

<input type="hidden" name = "depositer_id" value="<=$data->user_id>"/>
<input type="hidden" name="request_type" value="Deposit"/>

<br />
<input type="submit" value="Place Deposit" style="border:0px;background-color:#2b8eeb;border-radius:6px;color:#fff;font-size:16px;padding:8px"/>

</div>
-->



<!-- Reference number-->

<div class="calculator">
<h2>Reference Number</h2><hr/>
<p><b style="font-size:19px">Step 4:</b> Send the Transaction Reference Number as Proof of Payment:</p>

<input type="text" name="payment_proof" style="background-color:#fff;border-radius:6px;color:#01123c;border:1px solid #2b8eeb;width:70%;height:24px" placeholder="Enter Transaction Reference Number"/><br />

<input type="hidden" name = "depositer_id" value="<?=$data->user_id?>"/>
<input type="hidden" name="request_type" value="Deposit"/>

<br />
<input type="submit" value="Place Deposit" style="border:0px;background-color:#2b8eeb;border-radius:6px;color:#fff;font-size:16px;padding:8px"/>

</div>

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
/*
    function calculate(input_id1,input_id2,rate){
        if(document.getElementById(input_id1).placeholder == "usd"){
            document.getElementById(input_id2).value = (document.getElementById(input_id1).value)/rate ; 
       } else {
            document.getElementById(input_id2).value = (document.getElementById(input_id1).value)*rate ; 
        }
    }
*/   

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