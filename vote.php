<?php

if (isset($_POST["poll-option"])) {

    $nin = "";
    if (isset($_POST["nin"])) {
        $nin = $_POST["nin"];
    }

    require_once('vendor/autoload.php');
    
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
    
    echo $response->getBody();

    $istmt = $pdo->prepare("INSERT INTO poll_verification(`user_id`, poll_id, poll_participant) VALUES(?, ?, ?)");
    $istmt->execute([$user, $poll, htmlentities($_POST["new-participant"])]);
}

$user = "";
$poll = "";

if (isset($_GET["user"]) && isset($_GET["poll"])) {
    $user = htmlentities($_GET["user"]);
    $poll = htmlentities($_GET["poll"]);

    //Fetch options
    $poll_stmt = $pdo->prepare("SELECT * FROM poll_option WHERE poll_id = ? ORDER BY poll_id DESC LIMIT ?, ?");
    $poll_stmt->execute([$poll, 0, 3]);
    
    $poll_data = $poll_stmt->fetchAll(PDO::FETCH_OBJ);
}
?>

<form method="post" action="" class="inner-dashboard-main" class ="inner-dashboard-main">
<?php
    if (count($poll_data) > 0) {
        foreach ($polldata as $pd) {
?>
           <label><input type="radio" name="poll-option" value="<?=$pd->poll_option_value?>"/> <?=$pd->poll_option_value?></label>   
<?php
        }
?>

    <b>Kindly enter your NIN to proceed in taking this poll</b>

    <div class="input-div">
        <input type="text" name="nin" class="input" placeholder="Please enter your NIN"/>
    </div>

    <input type="submit" class="dashboard-button" value="Submit"/>
<?php
    }
?> 
</form>