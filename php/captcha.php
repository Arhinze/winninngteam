<div style="background-color:#57acfc;color:#fff;margin:14px 0px 12px 3px;padding:5px;width:190px;border-radius:4px;display:flex;">
            
<?php 
$code_array = [0,1,2,3,4,5,6,7,8,9];
shuffle($code_array);
$code = "";

$arr = [0,1,2,3,4,5];
shuffle($arr);

foreach($arr as $a){
    $code .= $code_array[$a];
?> 

<div class="code<?=$a?>"><?=$code_array[$a];?>
</div>&nbsp;
  
<?php } ?>    
</div>

<!--Hidden Captcha Code-->
<input type="hidden" name="code" value="<?=$code?>"/>

Enter Code:<br />
<input type = "number" placeholder = "The 6 Digit Code Above: example - 123456" name = "user_code" class="input"/><br />