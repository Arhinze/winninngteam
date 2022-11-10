<?php
$dbhost = "localhost";
$dbname = "u370269237_voting_system";
$dbuser = "u370269237_francis";
$dbpass = "Winninglife1!";

$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

//SIGN UP
$old_name = "";
$old_email = "";

if (isset($_POST["signup-user"])) {
    $old_name = htmlentities($_POST["new-name"]);
    $old_email = htmlentities($_POST["new-user-email"]);

    $stmt = $pdo->prepare("SELECT * FROM `user` WHERE user_email = ?");
    $stmt->execute([$_POST["new-user-email"]]);
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$data) { //that means user is unique
        //insert user:

        $istmt = $pdo->prepare("INSERT INTO user(`name`, user_email, user_password) VALUES(?, ?, ?)");
        $istmt->execute([htmlentities($_POST["new-name"]), htmlentities($_POST["new-user-email"]), md5(htmlentities($_POST["password"]))]);

        //redirect to dashboard:
        header("location:/dashboard");
    } else {
?>
        <div class="invalid">Sorry, Email Address is already taken</div>
<?php
    }
}


//LOGIN
$old_email = "";

if (isset($_POST["login-user"])) {

    $old_email = htmlentities($_POST["login-email"]);

    $stmt = $pdo->prepare("SELECT * FROM `user` WHERE user_email = ? AND user_password = ?");
    $stmt->execute([htmlentities($_POST["login-email"]), md5(htmlentities($_POST["login-password"]))]);
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if ($data) { //that means user is already registered
        //SET COOKIE:
        setcookie("user_email", htmlentities($_POST["login-email"]), time()+(24*3600), "/");
        setcookie("password", md5(htmlentities($_POST["login-password"])), time()+(24*3600), "/");

        //redirect to dashboard:
        header("location:/dashboard");
    } else {
?>
        <div class="invalid">Invalid email/password combination</div>
<?php
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="/static/css/style.css"/>
        <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
        <link rel="stylesheet" href="/static/css/themify-icons.css"/>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Voting System</title>
    </head>

    <body>
        <!-- header -->     
        <div class="clear">  
            <div class="headers">  
                <div style="font-size:18px;margin:-16px 19px 0px 14px"><a href="/" style="color:#2b8eeb"><h3 class="site_name">WinningT Voting System</h3></a></div>
            
                <div class="menu-icon"><label for = "menu-box"><i class="fa fa-bars"></i></label></div> 
            </div> 
        </div> 
        <!-- End of header -->

        <!-- menu list -->
        <div class="menu-list-div">
            <!-- using css checkbox hack, just to ensure this works whether javascript is turned on or off in user browser-->
            <input type="checkbox" id="menu-box" class="menu-box"/>

            <ul class="menu-list"> 
                <li class="x"><label for="menu-box"><i class="fa fa-times"></i></label></li>
                <li><a href="/">Home</a></li>
                <li><a href="https://winningteam.myinstu.online/dashboard">Dashboard</a></li>
                <li><a href="/home#contact">Contact</a></li>
                <li><a href="/about-us">About us</a></li>
                <li><a href="/faqs">Frequently Asked</a></li>
            </ul> 
        </div>  
        <!-- end of menu list -->

        <!-- Upper Body of the landing page -->
        <div class="clear">
            <div class="main-page-img">
                <img src="/static/images/voting_img.png"/>
            </div>

            <div class="main-page-text-side">
                <h2>Welcome !</h2>
                <small>We're revolutionalizing the traditional voting system by leveraging on cutting edge identity verification on voters. To join us in this, let's start by getting to know you.</small>

                <div id="form">
                    <form method="post" action="">
                        <div class="input-div">
                            <input type="email" class="input"  placeholder="Please Enter Your Email" value="<?=$old_email?>" name="login-email" required/>
                        </div>
                        
                        <div class="input-div">
                            <input type="password" class="input" placeholder="Enter Your password" name="login-password" id = "password" minlength="8" required/>
                        </div>

                        <input type="hidden" name = "login-user" value="new-commer"/>
                        <button type = "submit" class="button"> Continue &Gt; </button>

                        <div>
                            <b>Don't have an account? <span onclick="sign_up()" style="color:#5a3e8d">Sign up</span></b>
                        </div>
                    </form>
                </div>

            </div>

        </div>
        <!-- End of Upper Body of the landing page -->

        <!-- Lowerv Part of the landing page -->
            <h2 style="text-align:center">
                Let's help you simplify any voting Process you might like to conduct in your Organization
            </h2>
        <!-- End of Lower Part of the landing page -->

    

        <!-- Load Javascript code: -->
        <script src="/static/javascript/all-scripts.js"></script>
            
        <!-- Tidio chat -->
        <script src="//code.tidio.co/" async></script>  
        <!-- End of Tidio chat -->
        
    </body>
</html>