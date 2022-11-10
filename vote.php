<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

    $nin = "";
    if (isset($_POST["nin"])) {
        $nin = $_POST["nin"];
    }
    
    $user = "";
    $poll = "";
    
    if (isset($_GET["user"]) && isset($_GET["poll"])) {
        $user = htmlentities($_GET["user"]);
        $poll = htmlentities($_GET["poll"]);
    
        //Fetch options
        $poll_stmt = $pdo->prepare("SELECT * FROM poll_option WHERE `user_id` = ? ORDER BY poll_id DESC LIMIT ?, ?");
        $poll_stmt->execute([$user, 0, 3]);
        
        $poll_data = $poll_stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    
if (isset($_POST["poll-option"])) {



    //require_once('vendor/autoload.php');
    
    $client = new \GuzzleHttp\Client();
    
    $response = $client->request('POST', 'https://api.verified.africa/sfx-verify/v3/id-service/', [
      'body' => '{"searchParameter":"$nin","verificationType":"NIN-SEARCH"}',
      'headers' => [
        'accept' => 'application/json',
        'apiKey' => 'H9tk3GRuuxyaFEN4KcTu',
        'content-type' => 'application/json',
        'userid' => '1667834368034',
      ],
    ]);
    
 //   echo $response->getBody();
    


    $is_verified = "";

    if ($response->getBody()) {
        $istmt = $pdo->prepare("INSERT INTO poll_verification(`user_id`, poll_id, poll_participant) VALUES(?, ?, ?)");
        $istmt->execute([$user, $poll, htmlentities($_POST["new-participant"])]);
    } else {
        $is_verified = "Sorry, Your NIN could not be verified. Ensure you're inputting the correct Figures";
    }

}

?>

<form method="post" action="" class="inner-dashboard-main">
    <b style="color:green"><?=$is_verified?></b>
<?php
    if (count($poll_data) > 0) {
        foreach ($polldata as $pd) {
?>
           <label><input type="radio" name="poll-option" value="<?=$pd->poll_option_value?>"/> <?=$pd->poll_option_value?></label>   
<?php
        }
?>

<div style="border:2px solid #888;box-shadow:3px 3px 7px 3px #888; border-radius:6px;padding:8px 12px;margin:6% 8%">
    <b>Kindly enter your NIN to proceed in taking this poll</b><br /><br />



    <div class="input-div">
        <input type="text" name="nin" class="input" style="border-radius:6px;height:39px;width:80%" placeholder="Please enter your NIN"/>
    </div>

    <br />
   
    <input type="submit" class="dashboard-button" style = "background-color:#5a3e8d;color:#fff;border-radius:6px;padding:8px 12px" value="Submit"/>
</div>    

<?php
    }
?> 
</form>