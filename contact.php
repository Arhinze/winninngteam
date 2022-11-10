<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Segments.php");
Segments::header();
?>

<div class="contact_us">
    <form method="post" action="">
        <h1 style="margin-bottom:-2px">Contact Us:</h1><hr/>
            We are just a click away :)
        <input type="text" placeholder="Name: Example: John Smith" class="input"/>    
        <input type = "text" placeholder = "Email: abc@example.com" name = "email" class="input"/>
        <textarea class="textarea" placeholder="Enter Text"></textarea><br/>
        <button type="submit" class="button">Send <i class="fa fa-telegram"></i> </button>
    </form>
            
    <br />
            
    <div>
        <i class="fa fa-telephone"></i> +447762734997 <br />
        <i class="fa fa-telephone"></i> +61396202220 <br /><br />
        
        <i class="fa fa-mail"></i> support@futurefinanceinvestment.com
    </div>

    <br />

    <div>
    <b style="border-bottom:2px solid #fff">WE ARE ACTIVE ON SOCIAL MEDIA:</b> <br /><br />
        <a href='https://instagram.com/brae_sokolski?igshid=YmMyMTA2M2Y=' style='color:#fff'><i class="fa fa-instagram"></i> instagram</a> <br /><br />   
        <a href='https://m.me/Brae.Sokolski' style='color:#fff'> <i class="fa fa-facebook"></i> facebook</a> <br /><br />  
        <a href='https://t.me/Futurefinancecom' style='color:#fff'> <i class="fa fa-telegram"></i> telegram</a> <br /><br />
        <a href='https://chat.whatsapp.com/BEclTWAc79S18paVW2Jt7B' style='color:#fff'> <i class="fa fa-whatsapp"></i> whatsApp</a> <br /><br /> 
    </div>
</div>


<?php
Segments::footer();
?>


<!--


-->