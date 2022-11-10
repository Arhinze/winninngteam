<?php

//admin_Segments

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

Class admin_Segments{
    public static function inject($obj){
        admin_Segments::$pdo = $obj;
    }

    private static $pdo;

    public static function main_admin_access(){
        $admin_stmt = admin_Segments::$pdo->prepare("SELECT * FROM `admin` LIMIT ?, ?");
        $admin_stmt->execute([0,2]);
        $admin_data = $admin_stmt->fetchAll(PDO::FETCH_OBJ);
        
        if(count($admin_data)>0) {
            $r = '';
            foreach($admin_data as $d){
                if(($d->admin_name == $_COOKIE["admin_name"]) && ($d->admin_password == $_COOKIE["admin_password"])){
                    $r .= "y";
                } else {
                    $r .= "";  
                }
            }
        } else {
            $r = "<span style='color:red'><li><i class='fa fa-lock'></i> admins</li></span>"; 
        }

        if($r == "y"){
            return "<li><i class='fa fa-lock'></i> <a href='/admin-of-admins'>admins</a></li>";
        } else {
            return "<span style='color:red'><li><i class='fa fa-lock'></i> admins</li></span>";
        }
        
    }    


    public static function header(){
        $main_admin_access = admin_Segments::main_admin_access();

        $Hi_admin = "";

        if(isset($_COOKIE["admin_name"])){
            $Hi_admin = $_COOKIE["admin_name"];
        }

        echo <<<HTML
            <!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/static/style.css"/>
    <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/static/themify-icons.css"/>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

    <style>
        .admin_invest_now_button{
            padding:4px 8px;
            color:#fff;
            border-radius:6px;
            background-color:#ff9100;
            font-size:15px;
            border:none;
        }

        .show_hidden_divs_button{
            background-color:#888;
            padding:3px;
            border-radius:6px;
            color:#fff;
            margin-left:6px;
            text-align:center;
            height:24px;
            border:none
        }

        /*
        .goog-logo-link {
            display:none !important;
        } 
            
        .goog-te-gadget {
            color: transparent !important;
        }
        
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }        

        */
    </style>
    <title>Admin - FUTURE FINANCE</title>
</head>
<body>
    <div class="headers" style="height:36px;padding:16px 16px 8px 8px"> 
        <div style="font-size:18px;margin:-16px 19px 0px 14px"><h3 class="site_name"><a href="/">FUTURE FINANCE</a></h3></div>
        
        <div class="menu-icon">
            <label for = "menu-box"><i class="fa fa-bars"></i></label>
        </div>
    </div> 

    <hr />

    <div style="position:fixed;width:100%;height:39px;top:59px;left:0;background-color:#fff;padding-bottom:6px;" class="clear"> 
            
            <!-- Google Translate div -->  
            
            <div class="clear"><div id="google_translate_element" style="position:fixed;float:left;left:13px;top:59px;background-color:#fff;border-radius:4px;padding:0px 3px"></div></div>
            
            <!-- Hi admin -->  
            
            <span style="float:right;background-color:#01123c;border-radius:6px;margin:3px;font-size:12px;padding:1px 0 6px 9px">

                <i class="fa fa-lock"></i> Hi $Hi_admin

                <i style="background-color:#2b8eeb;color:#fff; border-radius:6px;padding:6px 8px;text-align:center;margin:6px 9px 0px 6px;" class="fa fa-user"></i> 
            </span>
    </div>

    <input type="checkbox" id="menu-box" class="menu-box" style="display:none"/>
    
    <ul class="menu-list"> 
        <div class="clear">
            <li style="float:right;">
                <label for="menu-box"><i class="fa fa-times" style="font-weight:bold"></i></label> 
            </li>
        </div> 
        
        
        <li><i class="fa fa-home"></i> <a href="/admin">Home</a></li>

        <li><i class="fa fa-users"></i> <a href="/site-users">Users</a></li>

        <!-- <li><i class="fa fa-lock"></i> <a href="/">admins</a></li> -->
        
        $main_admin_access

        <li><i class="fa fa-eye"></i> <a href="/user-requests">View User Requests</a></li>


        <!--
        <li><i class="fa fa-envelope"></i> <label for="hidden-menu-item">Mail</label></li>
            <input type="checkbox" style="display:none" id="hidden-menu-item" class="hidden-menu-item"/>
            <div class="hidden-menu-div">
                <a href="">Send Mail</a><br />
                <a href="">View Sent Mails</a>
            </div> -->

        <li><i class="fa fa-key"></i> <a href="/admin-reset-password">Reset Password</a></li>

        <li><a href="/logout" style="color:#fff;font-weight:bold;background-color:#2b8eeb;padding:6px;border-radius:12px">Log out</a></li>

    </ul>

    <!-- Google Translate Scripts -->

    <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.VERTICAL}, 'google_translate_element');
    }
    </script>
    
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
HTML;
}



public static function body(){
    echo <<<HTML
        <div class ="main" style ="text-align:center;margin-top:160px">
        <h2>CONTROL PANEL</h2><hr />
            Welcome to the admnin Panel of <b><a href="/" style="font-size:18px">Future Finance Investment</a></b>. 
            You have the privilegde to edit user's details, Send Mails and Confirm Deposits.
        </div>
HTML;
}

public static function footer(){
    echo <<<HTML
        <hr />
    <div style="text-align:center">
        <div style="display:flex;text-align:center;margin:0 20% 0 20%;">
            <!--
                <img src="/static/images/logo.png" style="width:80px;height:80px"/>
            --> 
            &nbsp; <h3>Future Finance Investment</h3>
        </div>
        &copy;2022 - All Rights Reserved
    </div>

    <script>
        function copyText(linkText){
        x = document.getElementById(linkText);

        x.select();
        x.setSelectionRange(0, 99999);

        //navigator.clipboard.writeText(x.value);
        document.execCommand('copy');
        alert("copied text: " + x.value);
    }
    </script>
</body>
</html>

HTML;
    }
    //Class admin_Segments ends..
}

//$admin_Segments = new admin_Segments;
admin_Segments::inject($pdo);
?>