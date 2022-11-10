<?php


include_once("views/Dashboard_Segments.php");

if ((isset($_COOKIE["username"])) && ((isset($_COOKIE["password"])))){
    $username = $_COOKIE["username"];
    $password = $_COOKIE["password"];

    $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
    $stmt->execute([$username, $password]);
    
    $data = $stmt->fetch(PDO::FETCH_OBJ);

    if($data){
        // that means user is logged in:
        
        //display header
        Dashboard_Segments::header();

        if (isset($_POST["edit_account_data"])){
            //Update Data:
            $us = $pdo->prepare("UPDATE investors SET real_name = ?, btc_wallet_address = ?, eth_wallet_address = ?, btc_cash_wallet_address = ?, usdt_trc20_wallet_address = ?, usdt_erc20_wallet_address = ?, bnb_wallet_address = ?, bnb_p20_wallet_address = ?, bsc_wallet_address = ? WHERE user_id = ?");

            $us->execute([htmlentities($_POST["full_name"]),htmlentities($_POST["bitcoin_wallet_address"]), htmlentities($_POST["ethereum_wallet_address"]), htmlentities($_POST["bitcoin_cash_wallet_address"]), htmlentities($_POST["usdt_trc20_wallet_address"]), htmlentities($_POST["usdt_erc20_wallet_address"]), htmlentities($_POST["bnb_wallet_address"]), htmlentities($_POST["bnb_p20_wallet_address"]), htmlentities($_POST["bsc_wallet_address"]), $data->user_id]);

            $stmt = $pdo->prepare("SELECT * FROM investors WHERE username = ? AND `password` = ?");
            $stmt->execute([$username, $password]);
            
            $data = $stmt->fetch(PDO::FETCH_OBJ);
        }
?>

<form method="post" action="">

<div class="dashboard_div">

<div class="sign-in-welcome">
    <h3 style="color:#fff"><i class="fa fa-gear"></i>&nbsp; Settings</h3>
    <a href="/"><i class="fa fa-home"> Home</i></a> - Profile
</div>

<!-- Account Data: -->

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Username:</div>
    <div style="width:60%;float:right"><?=$data->username?></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Registration Date:</div>
    <div style="width:60%;float:right"><input type="text" name="" value="<?=date("D M jS Y - h:i a", strtotime($data->entry_date))?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your Full name:</div>
    <div style="width:60%;float:right"><input type="text" name="full_name" value="<?=$data->real_name?>" class="input"/></div>
</div>

<div class="clear" style="padding:18px 8px 10px 8px;border-top:1px solid #888;margin:12px" >
&nbsp; <a class = "button" href="/reset-password">Reset Password <i class="fa fa-pencil"></i></a>
</div>

<!--

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">New Password:</div>
    <div style="width:60%;float:right"><input type="password" name="password1" value="" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Retype Password:</div>
    <div style="width:60%;float:right"><input type="password" name="password2" value="" class="input"/></div>
</div>

-->

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your Bitcoin Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="bitcoin_wallet_address" value="<?=$data->btc_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your Ethereum Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="ethereum_wallet_address" value="<?=$data->eth_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your Bitcoin Cash Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="bitcoin_cash_wallet_address" value="<?=$data->btc_cash_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your USDT(TRC20) Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="usdt_trc20_wallet_address" value="<?=$data->usdt_trc20_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your USDT(ERC20) Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="usdt_erc20_wallet_address" value="<?=$data->usdt_erc20_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your BNB Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="bnb_wallet_address" value="<?=$data->bnb_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your BNB P20 Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="bnb_p20_wallet_address" value="<?=$data->bnb_p20_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your Binance Smart Chain Wallet Address:</div>
    <div style="width:60%;float:right"><input type="text" name="bsc_wallet_address" value="<?=$data->bsc_wallet_address?>" class="input"/></div>
</div>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">
    <div style="width:30%;float:left">Your E-mail Address:</div>
    <div style="width:60%;float:right"><input type="text" name="email" value="<?=$data->user_email?>" class="input"/></div>
</div>

<input type="hidden" name="edit_account_data"/>

<div class="clear" style="padding:6px 8px;border-top:1px solid #888;margin:12px">

<div style="float:right"><button type="submit" class="button"><i class="fa fa-gear"></i> Change Account Data</button></div>

</div>

<!-- Account Data Ends here... -->
</div> 

</form>

<?php
Dashboard_Segments::footer();
    } /*end of count($data) for cookie name and pass*/ else {
        header("location:/login");
    } 
} else {
    header("location:/login");
}
?>