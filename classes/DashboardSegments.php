<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

class Dashboard_Segments{
    public static function header(){

    $Hi_user = "";

    if(isset($_COOKIE["username"])){
        $Hi_user = $_COOKIE["username"];
    } 

    echo <<<HTML
    <!doctype html>
    <html lang="en">
    <head>
        <link rel="stylesheet" href="/static/style.css"/>
        <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>

        <link rel="stylesheet" href="/static/themify-icons.css"/>


        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
        <title>Voting System</title>


        <style>
            body{
                padding:0px;
            }
            
            
            .pre_footer, .footer{
                padding:4% 8%;
                margin:0;
            }

            .footer{
                width:90%;
            }

           .invest_now_div{
                border-radius:6px;
                box-shadow:0px 0px 9px 0px #2b8eeb;
                padding:5px 0px 16px 8px;
                margin:12px 0px 12px 0px;
                text-align:center;
                margin:24px 12px;
                padding:24px;
                font-size:18px;
                background-image:url('/static/images/invest_now_bg.png');
                background-repeat:no-repeat;
                background-size:cover
            }

            .invest_now_text{
                border-bottom:1px solid #2b8eeb;
                padding:12px;
            }

            .invest_now_capital{
                background-color:green;
                padding:2px 5px;
                border-radius:4px;
            }

            @media screen and (min-width:48em) {
                .invest_now_div{
                    float:left;
                    width:35%;
                }

                .invest_now_div_parent{
                    padding:16px 6%;
                }
            }
        </style>
    </head>
    <body>

    <div class="body">

        <div class="headers" style="height:36px;padding:16px 16px 8px 8px"> 
            <div style="font-size:18px;margin:-16px 19px 0px 14px;float:left">
                <h3 class="site_name">
                    <a href="/">FUTURE FINANCE</a>
                </h3>
            </div>

            <div class="menu_and_user_icon">

            <span class="other-menu-icon" style="">
                <label for = "menu-box"><i class="fa fa-bars"></i></label>
            </span>
            </div>
        </div> 

        <div style="position:fixed;width:100%;height:39px;top:59px;left:0;background-color:#fff;padding-bottom:6px;" class="clear"> 
        
            <!-- Google Translate div -->  
            
            <div class="clear"><div id="google_translate_element" style="position:fixed;float:left;left:13px;top:59px;background-color:#fff;border-radius:4px;padding:0px 3px"></div></div>
            
            <!-- Hi user --> 
            
            <span style="float:right;background-color:#01123c;border-radius:6px;margin:3px;font-size:12px;padding:1px 0 6px 9px">
                Hi $Hi_user

                <i style="background-color:#2b8eeb;color:#fff; border-radius:6px;padding:6px 8px;text-align:center;margin:6px 9px 0px 6px;" class="fa fa-user"></i> 
            </span>
        </div>
    
        <a name="#top"></a>

        <input type="checkbox" id="menu-box" class="menu-box"/>

        <ul class="menu-list"> 
            
            <li class="x"><label for="menu-box"><i class="fa fa-times"></i></label></li>
            
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/deposit">Deposit</a></li>
            <li><a href="/invest">Invest</a></li>
            <!-- <li><a href="/deposit">Deposit</a></li>-->
            <li><a href="/withdraw">Withdraw</a></li>
            <li><a href="/transactions">Transactions</a></li>
            <li><a href="/settings">Settings</a></li>

            <li class="clear" style="padding-bottom:16px">
                <label for="hidden-menu-item">
                    <span style="float:left">Referrals</span> <i class="fa fa-angle-down" style="float:right"></i> 
                </label>
            </li>

            <input type="checkbox" style="display:none" id="hidden-menu-item" class="hidden-menu-item"/>
            <div class="hidden-menu-div">
                <a href="/referred-users">Referred Users</a><br />
                <a href="/referred-commissions">Referred Commissions</a>
            </div>
            
            <li><a href="/reset-password">Reset Password</a></li>

            <li><a href="/logout" style="color:#fff;font-weight:bold;background-color:#2b8eeb;padding:6px;border-radius:12px">Log out</a></li>

        </ul>     

HTML;
    }

    public static function footer(){
        echo <<<HTML
        
        <!-- Footer -->

        <!--Move to top with this-->

        <div style="position:fixed;bottom:125px;right:42px;z-index:5;text-align:center;background-color:#2b8eeb;padding:6px 2px 6px 2px;border-radius:4px;line-height:7px;width:30px;">
            <a href="#top" style="font-size:18px;color:#fff">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>

        <!--Footer Proper-->

        <div class="footer_and_pre_footer">

        <!-- Pre-Footer -->

        <div class="pre_footer">
            <div class="pre_footer_text_top" style="text-align:center">
    
                <span style="font-size:21px;font-weight:bold;text-align:center;color:#2b8eeb">FUTURE FINANCE</span> 
                <br /><br />

                <span>Our leadership team reflects our belief in strong governance, with diverse backgrounds, expertise and ways of thinking. Together, this group enables us to build a multidimensional Investment strategy.</span>
            </div>

            <div class="clear" style="width:100%">   
                <ul style="list-style-type:none;float:left;width:48%;padding-left:3px">
                <h4>USEFUL LINKS</h4>
                    <li>Home</li>
                    <li>About Us</li>
                    <li>Question Guide</li>
                    <li>Log in</li>
                    <li>Sign up</li>
                    <li>Contact us</li>
                </ul>

                <ul style="list-style-type:none;float:right;width:48%;padding-left:3px">
                <h4>GET IN TOUCH</h4>
                    <li>22 Dundonald Street, Eden Terrace, Auckland 1021, New Zealand.</li>
                </ul>
            </div>
        </div>

        <!-- Pre-Footer Ends -->

        <!-- Footer -->

        <div class="footer" style="background-color:#03010f;">
            <div style="float:left;padding:10px 10px 10px 4%">
                &copy; 2022 CB Finance. All rights reserved.
            </div>

            <div style="float:right;padding:10px 4% 10px 10px;">
                Contact Us &nbsp; &nbsp; Terms & Conditions
            </div>
        </div>
        
        <!-- Footer Ends -->
        
        </div>
   
        <!-- End of footer_and_pre_footer -->
        
        
        <script>
            function show_div(vari) {
                if (document.getElementById(vari).style.display == "none") {
                    document.getElementById(vari).style.display = "block";
                } else if (document.getElementById(vari).style.display == "block") {
                    document.getElementById(vari).style.display = "none";
                }
            }

            const collection = document.getElementsByClassName("invalid");

            for (let i=0; i < collection.length; i++){
                collection[i].style = "display:block";
                //collection[i].style = "color:pink";
                
                var innerHT = collection[i].innerHTML;

                var newInnerHT = innerHT + "<span style='float:right;margin:4px 18px'><i class='fa fa-times' onclick='show_class_div()'></i></span>";

                collection[i].innerHTML = newInnerHT;
            }

            function show_class_div() {
                const collection = document.getElementsByClassName("invalid");
                i = 0;

                for (i=0; i<collection.length; i++){
                    collection[i].style.display = "none";
                }
                

                /*
                for (i=0; i<collection.length; i++){
                    if (collection[i].style.display == "none") {
                        collection[i].style.display = "block";
                    } else if (collection[i].style.display == "block") {
                        collection[i].style.display = "none";
                    }
                } 
                */     
            }

            function copyText(linkText){
                x = document.getElementById(linkText);
        
                x.select();
                x.setSelectionRange(0, 99999);
        
                //navigator.clipboard.writeText(x.value);
                document.execCommand('copy');
                alert("copied text: " + x.value);
            }
        </script>

    
    
    <!-- Google Translate scripts -->

    <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.VERTICAL}, 'google_translate_element');
    }
    </script>
        
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    <!-- End of Google Translate scripts -->
    
    <!-- Tidio chat -->

    <script src="//code.tidio.co/pe3y6xzoe3gnjngpxsol4rpxfbzza5g2.js" async>
    </script>

    <!-- End of Tidio chat -->

    <noscript> 
        Texts won't display well. please enable Javascript.
    </noscript>

</body>  <!-- The end -->
</html>

HTML;
    }
}