<div class="input-div">
    <input type="password" class="input" placeholder="Enter a secret password" name="password" id = "password" onkeyup="check_password()" minlength="8"/>

    <small id="password-model" style="display:block">Password should be at least 8 character long and contain: A capital letter, a small letter and a character </small>
</div>

<div class="input-div">
    <input type="password" class="input"  placeholder="Enter password once more" name="password-confirm" id="password-confirm" onkeyup="password_confirm()"/>

    <small id="password-confirm-model" style="display:none">Password fields must match</small>
</div>

<div class="input-div">
    <input type="hidden" name = "new-commer" value="new-commer"/>
    <button type = "submit" class="button"> Continue &Gt; </button>
</div>