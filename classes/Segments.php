<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

class Segments{
    public static function header(){
        echo <<<HTML
        <!doctype html>
        <html lang="en">
        <head>
          
            <link rel="stylesheet" href="/static/style.css"/>
            <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Trirong|Arimo"/>
    
            <link rel="stylesheet" href="/static/themify-icons.css"/>
    
    
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">-->
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <style>
                body{
                    padding:0px;
                }
                
                .footer_and_pre_footer{
                    background-color:#03010f;
                    width:120%;
                    margin:16% 0 -15% -10%;
                }

                .pre_footer, .footer{
                    padding:4% 8%;
                    margin:0;
                }

                .footer{
                    width:80%;
                }
            </style>
            <title>Future Finance</title>
        </head>
        <body>
    
        <div class="clear" 
        style="margin:0px;border:0px;padding:3%">
            <div class="headers" style="border-bottom: 1px solid #fff"> 
                <div style="font-size:18px;margin:-16px 19px 0px 14px"><a href="/" style="color:#2b8eeb"><h3 class="site_name">FUTURE FINANCE</h3></a></div>
            
                <span class="menu-icon"><label for = "menu-box"><i class="fa fa-bars"></i></label></span> 
            </div> 
        
        
            <a name="#top"></a>
            
            <!-- Google Translate divs -->    

            <div class="clear"><div id="google_translate_element" style="position:absolute;right:0;top:63px;background-color:#fff;border-radius:4px;padding:3px 6px"></div></div>
            
            <!-- End of Google Translate divs -->
        
            <div class="menu-list-div">  
                <input type="checkbox" id="menu-box" class="menu-box"/>
                <ul class="menu-list"> 
                    
                    <li class="x"><label for="menu-box"><i class="fa fa-times"></i></label></li>
                    
                    <li><a href="/">Home</a></li>
                    <li><a href="/invest">Invest</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/about-us">About us</a></li>
                    <li><a href="/faqs">Frequently Asked</a></li>
                </ul> 
            </div>
       
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
        </script>

    <noscript> 
        Texts won't display well. please enable Javascript.
    </noscript>
    
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

</body>  <!-- The end -->
</html>

HTML;
    }
}
?>