<?php
include_once("views/Dashboard_Segments.php");

if((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]); 
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
        //that means the person is logged in:
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
                    //$gross_interest += $c->profit;

                    $gross_interest += $c->profit - $c->tr_amount;

                    if($c->tr_from == "deposit_wallet"){
                        $minus_from_deposit += $c->tr_amount;
                    } else if($c->tr_from == "interest_wallet"){
                        $minus_from_interest += $c->tr_amount;
                    }
                } else if($c->tr_type == "Withdraw"){ 
                    if($c->tr_from == "deposit_wallet"){
                        $minus_from_deposit += $c->tr_amount;
                    } else if($c->tr_from == "interest_wallet"){
                       // $minus_from_interest += $c->tr_amount;
                    }
                }
            }

            $deposit = $gross_deposit - $minus_from_deposit;
            $interest = $gross_interest - $minus_from_interest;
        }


        if(isset($_POST["invest"])){

            if($_POST["rq_from"] == "deposit_wallet"){
                //insert investment from deposit wallet balance
                if($deposit >= $_POST["invest_amount"]){
                    //insert Invest request:
                    $deposit_stmt = $pdo->prepare("INSERT INTO user_requests(user_id, rq_type, rq_amount,rq_time,rq_from) VALUES(?,?,?,?,?)");
                    $deposit_stmt->execute([$_POST["investor_id"],$_POST["request_type"],$_POST["invest_amount"],date("Y-m-d h:i:s", time()),$_POST["rq_from"]]);
        
        
                    //Mail User:
                    $i_name = $data->real_name;
                    $dep = $_POST["invest_amount"];
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
                            <p  style ="font-family:Trirong;">Hello $i_name, Your <b>Investment</b> of \$$dep has been injected into the blockchain and is currently awaiting approval by the system.</p>
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

                    $mail = mail($data->user_email,"Your Investment On Future Finance Investment",$message, $headers);

                    if($mail){
                        echo "<br />A Mail has been sent to you";
                    } else {
                        echo "Sorry, an error occurred, Mail not sent";
                      }

                      

                } else{
                    echo "<div class='invalid'>Sorry, Insuffient Funds</div>";
                }




            } else if($_POST["rq_from"] == "interest_wallet"){





                //insert investment from interest wallet balance
                if($interest > $_POST["invest_amount"]){
                    //insert Invest request:
                    $deposit_stmt = $pdo->prepare("INSERT INTO user_requests(user_id, rq_type, rq_amount,rq_time,rq_from) VALUES(?,?,?,?,?)");
                    $deposit_stmt->execute([$_POST["investor_id"],$_POST["request_type"],$_POST["invest_amount"],date("Y-m-d h:i:s", time()),$_POST["rq_from"]]);
        
        
                    //Mail User:
                    $i_name = $data->real_name;
                    $dep = $_POST["invest_amount"];
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

                    $mail = mail($data->user_email,"Your Investment On Future Finance Investment",$message, $headers);

                    if($mail){
                        echo "<br />A Mail has been sent to you";
                    } else {
                        echo "Sorry, an error occurred, Mail not sent";
                      }

                } else{
                    echo "<div class='invalid'>Sorry, Insuffient Funds</div>";
                  }



            }
        }
?>

<div class="dashboard_div">
        <div class="sign-in-welcome">
            <h2 style="color:#fff">Investment Plans</h2>
            <i class="fa fa-home" style="color:#2b8eeb"></i>&nbsp; <span  style="color:#2b8eeb">Home</span> - Investment Plans 
        </div>
               
               <div class="clear">
                   <a href="/transactions" style="float:right;padding:6px 16px;color:#fff;border-radius:6px;background-color:#2b8eeb;margin:3px 12px">
                    View My Investments
                   </a>
               </div>

    <div class="invest_now_div_parent" style="width:100%">

        <!--Basic-->

        <div class="invest_now_div">
            <h2 style="color:#2b8eeb">Basic Plan</h2>

            <div class="invest_now_text">
                Return 2%
            </div>
            <div class="invest_now_text">
                Every Day
            </div>
            <div class="invest_now_text">
                For 7 Days
            </div>
            <div class="invest_now_text">
                Total: 14% + &nbsp; <span class="invest_now_capital">capital</span>
            </div>

            <br /><br />

            <h2 style="color:#2b8eeb">$100 - $5000</h2> <br /> 

            <button class="invest_now_button" id="s_invest_now_button" onclick="show_div('silver')">Invest Now</button>
        </div>

        <!--Advanced-->
        
        <div class="invest_now_div">
            <h2 style="color:#2b8eeb">Advanced plan</h2>

            <div class="invest_now_text">
                Return 2.5%
            </div>
            <div class="invest_now_text">
                Every Day
            </div>
            <div class="invest_now_text">
                For 7 Days
            </div>
            <div class="invest_now_text">
                Total: 17.5% + &nbsp; <span class="invest_now_capital">capital</span>
            </div>

            <br /><br />

            <h2 style="color:#2b8eeb">$5000 - $9,999</h2> <br /> 

            <button class="invest_now_button" id="p_invest_now_button" onclick="show_div('platinum')">Invest Now</button>

        </div>

        <!--Premium Plan-->
        
        <div class="invest_now_div">
            <h2 style="color:#2b8eeb">Premium Plan</h2>

            <div class="invest_now_text">
                Return 3%
            </div>
            <div class="invest_now_text">
                Every Day
            </div>
            <div class="invest_now_text">
                For 7 Days
            </div>
            <div class="invest_now_text">
                Total: 21% + &nbsp; <span class="invest_now_capital">capital</span>
            </div>

            <br /><br />

            <h2 style="color:#2b8eeb">$10,000 - $19,999</h2> <br /> 

            <button class="invest_now_button" id="g_invest_now_button" onclick="show_div('gold')">Invest Now</button>

        </div>

        <!--Ultimate-->
        
        <div class="invest_now_div">
            <h2 style="color:#2b8eeb">Ultimate plan</h2>

            <div class="invest_now_text">
                Return 4%
            </div>
            <div class="invest_now_text">
                Every Day
            </div>
            <div class="invest_now_text">
                For 7 Days
            </div>
            <div class="invest_now_text">
                Total: 28% + &nbsp; <span class="invest_now_capital">capital</span>
            </div>

            <br /><br />

            <h2 style="color:#2b8eeb">$20,000 - $unlimited</h2> <br /> 

            <button class="invest_now_button" id="d_invest_now_button" onclick="show_div('diamond')">Invest Now</button>

        </div>
    </div>










    <!--Hidden Divs-->

    <!--Silver-->

    <form method="post" action="">
    <div style="width:100%">
    <div id="silver" class="" style="position:fixed;display:none;padding:16px;font-size:18px;line-height:36px;background-color:#01123c;box-shadow:0px 0px 6px #2b8eeb;color:#fff;border-radius:6px;width:70%;top:3%;left:12%">
        <div class="clear" style="font-size:21px"><b style="float:left">Confirm Invest on Silver</b> <label for="s_invest_now_button"><i class="fa fa-times" style="float:right"></i></label> </div> <hr />
        <div style="text-align:center">
            <b>Invest:</b> $100 - $5000 <br />
            <b>Interest:</b> 2 %<br />
            per 24 hours, 4 times <br />
        </div>
        <b>Select Wallet</b><br />
        <select name="rq_from" style="background-color:#2b8eeb;color:#fff;width:80%;height:30px">
            <option value="deposit_wallet">Deposit Wallet: $<?=$deposit?></option>
            <option value="interest_wallet">Interest Wallet: $<?=$interest?></option>
        </select>

        <br />

        <b>Invest Amount</b><br />
        <input type="number" class="input" name="invest_amount"/>

        <br /><hr />
            <div class="clear">
                <div style="float:right">
                    <input type="submit" class="invest_now_button" value="Yes"/>
                    <span class="invest_now_button" onclick="show_div('silver')"; style="background-color:red">No</span> 
                </div>
            </div>

            <input type="hidden" name="investor_id" value="<?=$data->user_id?>"/>
            <input type="hidden" name="request_type" value="Invest"/>
            <input type="hidden" name="invest"/>

    </div>
    </div>
    </form>




    <!--Platinum-->

    <form method="post" action="">
    <div style="width:100%">
    <div id="platinum" class="" style="position:fixed;display:none;padding:16px;font-size:18px;line-height:36px;background-color:#01123c;box-shadow:0px 0px 6px #2b8eeb;color:#fff;border-radius:6px;width:70%;top:3%;left:12%">
        <div class="clear" style="font-size:21px"><b style="float:left">Confirm Invest on Platinum</b> <label for="p_invest_now_button"><i class="fa fa-times" style="float:right"></i></label> </div> <hr />
        <div style="text-align:center">
            <b>Invest:</b>$5000 - $9,999 <br />
            <b>Interest:</b> 2.5 %<br />
            per 24 hours, 6 times <br />
        </div>
        <b>Select Wallet</b><br />
        <select name="rq_from" style="background-color:#2b8eeb;color:#fff;width:80%;height:30px">
            <option value="deposit_wallet">Deposit Wallet: $<?=$deposit?></option>
            <option value="interest_wallet">Interest Wallet: $<?=$interest?></option>
        </select>

        <br />

        <b>Invest Amount</b>
        <input type="number" class="input" name="invest_amount"/>

        <br /><hr />
            <div class="clear">
                <div style="float:right">
                    <input type="submit" class="invest_now_button" value="Yes"/>
                    <span class="invest_now_button" onclick="show_div('platinum')"; style="background-color:red">No</span> 
                </div>
            </div>

            <input type="hidden" name="investor_id" value="<?=$data->user_id?>"/>
            <input type="hidden" name="request_type" value="Invest"/>
            <input type="hidden" name="invest"/>

    </div>
    </div>
    </form>





    <!--Gold-->

    <form method="post" action="">

    <div style="width:100%">
    <div id="gold" class="" style="position:fixed;display:none;padding:16px;font-size:18px;line-height:36px;background-color:#01123c;box-shadow:0px 0px 6px #2b8eeb;color:#fff;border-radius:6px;width:70%;top:3%;left:12%">
        <div class="clear" style="font-size:21px"><b style="float:left">Confirm Invest on Gold</b> <label for="g_invest_now_button"><i class="fa fa-times" style="float:right"></i></label> </div> <hr />
        <div style="text-align:center">
            <b>Invest:</b> $10,000 - $19,999 <br />
            <b>Interest:</b> 3 %<br />
            per 24 hours, 6 times <br />
        </div>
        <b>Select Wallet</b><br />
        <select name="rq_from" style="background-color:#2b8eeb;color:#fff;width:80%;height:30px">
            <option value="deposit_wallet">Deposit Wallet: $<?=$deposit?></option>
            <option value="interest_wallet">Interest Wallet: $<?=$interest?></option>
        </select>

        <br />

        <b>Invest Amount</b>
        <input type="number" class="input"  name="invest_amount"/>

        <br /><hr />
            <div class="clear">
                <div style="float:right">
                    <input type="submit" class="invest_now_button" value="Yes"/>
                    <span class="invest_now_button" onclick="show_div('gold')"; style="background-color:red">No</span> 
                </div>
            </div>

            <input type="hidden" name="investor_id" value="<?=$data->user_id?>"/>
            <input type="hidden" name="request_type" value="Invest"/>
            <input type="hidden" name="invest"/>

    </div>
    </div>
    </form>





    <!--Diamond-->

    <form method="post" action="">

    <div style="width:100%">
    <div id="diamond" class="" style="position:fixed;display:none;padding:16px;font-size:18px;line-height:36px;background-color:#01123c;box-shadow:0px 0px 6px #2b8eeb;color:#fff;border-radius:6px;width:70%;top:3%;left:12%">
        <div class="clear" style="font-size:21px"><b style="float:left">Confirm Invest on Diamond</b> <label for="d_invest_now_button"><i class="fa fa-times" style="float:right"></i></label> </div> <hr />
        <div style="text-align:center">
            <b>Invest:</b>$20,000 - $unlimited<br />
            <b>Interest:</b> 5 %<br />
            per 24 hours, 30 times <br />
        </div>
        <b>Select Wallet</b><br />
        <select name="rq_from" style="background-color:#2b8eeb;color:#fff;width:80%;height:30px">
            <option value="deposit_wallet">Deposit Wallet: $<?=$deposit?></option>
            <option value="interest_wallet">Interest Wallet: $<?=$interest?></option>
        </select>

        <br />

        <b>Invest Amount</b>
        <input type="number" class="input"  name="invest_amount"/>

        <br /><hr />
            <div class="clear">
                <div style="float:right">
                    <input type="submit" class="invest_now_button" value="Yes"/>
                    <span class="invest_now_button" onclick="show_div('diamond')"; style="background-color:red">No</span>
                </div>
            </div>

            <input type="hidden" name="investor_id" value="<?=$data->user_id?>"/>
            <input type="hidden" name="request_type" value="Invest"/>
            <input type="hidden" name="invest"/>

    </div>
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