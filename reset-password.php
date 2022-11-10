<?php

ini_set("session.use_only_cookies", 1);
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Segments.php");
Segments::header();

$check_email = '';
$c = false;

//Create Reset Password Code:
$code_array = [0,1,2,3,4,5,6,7,8,9];
shuffle($code_array);
$code = "";

$arr = [0,1,2,3,4];
shuffle($arr);

foreach($arr as $a){
    $code .= $code_array[$a]; 
}

$hashed_code = md5(md5("yuoujnxbuyg".$code."ipu9uhnbyy"));



if (isset($_POST["email"])) {
    $user_email = $_POST["email"];
    
    $stmt = $pdo->prepare("SELECT * FROM investors WHERE user_email = ?");
    $stmt->execute([$user_email]);
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
        $c = true;
        //Send Password Reset Link Mail:
        $message = <<<HTML
            <html>
            <head>
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                        <link rel="stylesheet" href="https://futurefinanceinvestment.com/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
        
                <style>
                    a {
                        color:#ff3c00
                    }
                </style>
            </head>
            <body style ="font-family:Trirong;">
                <center>
                    <img src="https://futurefinanceinvestment.com/static/images/logo.png" style="margin-left:36%;margin-right:36%;width:25%;"/>
                </center>
                <h2 style="color:#00008b;font-family:Arimo;text-align:center">Future Finance Investment</h2>

                Your Password reset code is <b>$code</b>. Enter it on the website to continue your password 
                reset process. Kindly disregard this mail if this request wasn't from you. 

                <p>Learn more about us on <b><a href="https://futurefinanceinvestment.com/about-us">
                    https://futurefinanceinvestment.com/about-us</a></b></p>
                
                <p>Contact admin on: <b><a href="mailto: admin@futurefinanceinvestment.com">
                    Admin@futurefinanceinvestment.com</a></b></p>
            </body>
            </html>
        
        HTML;

        $sender = "admin@futurefinanceinvestment.com";

        $headers = "From: $sender \r\n";
        $headers .="Reply-To: $sender \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html; charset=UTF-8\r\n";

        $mail = mail($_POST["email"],"Reset Password on Future Finance Investment",$message, $headers);

        if($mail){
        ?>

            <div id="message_success" style="background-color:#ff9100;color:#fff;
            border-radius:3px;padding:4px;margin:8px 8px;display:block;position:fixed;top:40%;width:80%;
            box-shadow:0px 0px 9px 0px #fff">
                    
                <div class="clear">
                    <span class="float:right"><b>A Mail has been sent to you</b></span>
                    &nbsp;&nbsp;&nbsp;

                    <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                </div>
            </div>

        <?php
        } else {
            echo "Sorry, an error occurred, Mail not sent";
        }



    } else {
        $check_email = "<h3> Sorry, you don't have an account. Kindly <a href='/sign-up' style=
        'font-weight:bold'>Sign Up</a></h3>";
    }
}



if (isset($_POST["code"])) {
    if(md5(md5("yuoujnxbuyg".$_POST["code"]."ipu9uhnbyy")) == $_POST["something"]){
    //if(password_hash((int)$_POST["code"], PASSWORD_BCRYPT, ["cost"=>15]) == $_POST["something"]){
        if($_POST["password1"] == $_POST["password2"]){
            //Update Password:
            $us = $pdo->prepare("UPDATE investors SET `password`=? WHERE user_email = ?");
            $us->execute([$_POST["password1"],$_POST["inputed_email"]]);

            //Log users out:
            $cookies = ["username","password","admin_name","admin_password"];
            foreach($cookies as $c){
                if(isset($_COOKIE["$c"])){
                    setcookie($c, $_COOKIE["$c"], time()-(24*3600), "/");
                }
            }

            //Send Password Reset Link Mail:
            $message = <<<HTML
                <html>
                <head>
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
                            <link rel="stylesheet" href="https://futurefinanceinvestment.com/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
    
                    <style>
                        a {
                            color:#ff3c00
                        }
                    </style>
                </head>
                <body style ="font-family:Trirong;">
                    <center>
                        <img src="https://futurefinanceinvestment.com/static/images/logo.png" style="margin-left:36%;margin-right:36%;width:25%;"/>
                    </center>
                    <h2 style="color:#00008b;font-family:Arimo;text-align:center">Future Finance Investment</h2>

                    Your Password has been reset on <b><a href="https://futurefinanceinvestment.com">
                    https://futurefinanceinvestment.com</a></b>. You can now proceed to <b>
                        <a href="https://futurefinanceinvestment.com/login">Login</a></b> with your new Password.
                    
                    <p>If this change wasn't requested from you, kindly contact our <b>Support Team</b> on 
                    <b><a href="mailto: support@futurefinanceinvestment.com">Support@futurefinanceinvestment.com</a></b> 
                    for immediate reversal of this change and tips on how to make your account more secure. </p>

                    <p>Learn more about us on <b><a href="https://futurefinanceinvestment.com/about-us">
                        https://futurefinanceinvestment.com/about-us</a></b></p>
                    
                    <p>Contact admin on: <b><a href="mailto: admin@futurefinanceinvestment.com">
                        Admin@futurefinanceinvestment.com</a></b></p>
                </body>
                </html>
            HTML;

            $sender = "admin@futurefinanceinvestment.com";

            $headers = "From: $sender \r\n";
            $headers .="Reply-To: $sender \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html; charset=UTF-8\r\n";

            $mail = mail($_POST["inputed_email"],"Reset Password on Future Finance Investment",$message, $headers);

            if(!$mail){
                echo "Sorry, an error occurred, Mail not sent";
            }    

            //Updated successfully Message:
?>
            <div id="message_success" class="message_success" style="display:block">     
                <div class="clear">
                    <span class="float:right"><b>Password Updated successfully.<br />
                       Proceed to <a href="/login">Login</a></b></span>
                    &nbsp;&nbsp;&nbsp;

                    <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                </div>
            </div>
<?php
        } else {
            //password mismatch:
?>
            <div id="message_success" class="message_success" style="display:block">     
                <div class="clear">
                    <span class="float:right"><b>Passwords do not match.</b></span>
                    &nbsp;&nbsp;&nbsp;

                    <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                </div>
            </div>
<?php
        }
    } else {
        //wrong code:
?>
        <div id="message_success" class="message_success" style="display:block">     
            <div class="clear">
                <span class="float:right"><b>Wrong Verification Code</b></span>
                &nbsp;&nbsp;&nbsp;

                <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
            </div>
        </div>
<?php
    }
}
?>


<!--HTML:-->

<div class="dashboard_div" style="padding:2% 3% 5% 3%">

<!--

<h1 style="margin:-3px 0px -2px 0px"><br />Reset Password</h1>
<hr/>

-->

<h2><?=$check_email?></h2> 

<div class="sign-in-box">
    <div class="sign-in-welcome">
        <span style="color:#fff;font-size:30px">Reset Login Password on</span><br />
        <b style="color:#2b8eeb">Future Finance Investment</b>
    </div>

    <form method="post" action="" id="email"> 
        <input type="text" name="email" placeholder="Enter Your Email Address: " class="input" 
        <?php if (isset($_POST["email"])) { ?> value="<?=htmlentities($_POST["email"])?>" <?php } if(!$c || isset($_POST["password1"])) { ?>/>    

        <button type="submit" class="button">Submit <i class="fa fa-telegram"></i> </button> <br />

        <a href="/login" style="font-weight:bold;font-size:18px">Login</a>. 
        Don't have an account? <a href="/sign-up" style="font-weight:bold;font-size:18px">Sign Up</a>
        <?php } ?>
    </form>

        <?php if ($c) { ?>
            <?php if (isset($_POST["email"])) { ?>
                <?php if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) { ?>

                    
                    <?php 
                        $em = $_POST["email"];
                        $em ??= $_POST["inputed_email"];  
                    ?>
    
                    <form method="post" action="" id="code">
                        <h3 style="margin-bottom:-5px"> Enter the 5 digit Code Sent to Your Email: <?= $em ?></h3>
                        <input type = "text" placeholder = "Enter Secret Code:" name="code" class="input" required/><br />
                        
                        <h3 style="margin-bottom:-5px"> Enter New Password </h3>
                        <input type = "text" placeholder = "Enter Password:" name = "password1" class="input" 
                        id = 'password1' required/><br />
                        <input type = "text" placeholder = "Repeat Password:" name = "password2" class="input" 
                        id = 'password2' onkeyup="check_password('password1','password2')" required/><br />
                        
                        <input type="hidden" name="something" value="<?=$hashed_code?>"/>
                        <input type="hidden" name="inputed_email" value="<?=$_POST["email"]?>"/>
    
                        <div id="status" style="margin:12px 2px 12px 2px"></div>

                        <button type="submit" class="button">Submit <i class="fa fa-telegram"></i> </button>
                    </form>
    
                <?php } else { ?>
                    
                    <div id="message_success" class="message_success">
                        
                        <div class="clear">
                            <span class="float:right"><b>Sorry, Invalid Email Adress</b></span>
                            &nbsp;&nbsp;&nbsp;
    
                            <i class="fa fa-times" style="float:right" onclick="show_div('message_success')"></i>
                        </div>
                    </div>
    
                <?php } ?>
            <?php } ?>
        <?php } ?>

</div>
                
</div> <!--Reset Password Page ends-->

<script>
    function check_password(smtn1, smtn2){
        if(document.getElementById(smtn1).value == document.getElementById(smtn2).value){
            document.getElementById("status").innerHTML = "<b style='color:green'>Nice. Passwords Match <i class='fa fa-check'></i></b>";
        } else {
            document.getElementById("status").innerHTML = "<p style='color:red'><b>Passwords Do Not Match</b>.</p> <small><i class='fa fa-warning' style='color:red'></i> Make sure both password fields match to avoid starting the entire password reset Process afresh.<br /></small>";
        }
    }
</script>

<?php Segments::footer(); ?>