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