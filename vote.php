<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");


$user = "";
$poll = "";

if (isset($_GET["user"]) && isset($_GET["poll"])) {
    $user = (int) htmlentities($_GET["user"]);
    $poll = (int) htmlentities($_GET["poll"]);
}

//Fetch options
$poll_stmt = $pdo->prepare("SELECT * FROM poll_option WHERE poll_id = ? AND `user_id` = ?  ORDER BY poll_id DESC LIMIT ?, ?");
$poll_stmt->execute([$poll, $user, 0, 6]);

$poll_data = $poll_stmt->fetchAll(PDO::FETCH_OBJ);

//Get Poll's Name:
$poll_name_stmt = $pdo->prepare("SELECT * FROM poll WHERE poll_id = ? AND poll_user_id = ?  ORDER BY poll_id DESC LIMIT ?, ?");
$poll_name_stmt->execute([$poll, $user, 0, 6]);

$poll_name_data = $poll_name_stmt->fetch(PDO::FETCH_OBJ);


//Verify User's NIN using verify.africa api

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
    
    $is_verified = "";

    if ($response->getBody()) {
        $istmt = $pdo->prepare("INSERT INTO poll_verification(`user_id`, poll_id, poll_participant) VALUES(?, ?, ?)");
        $istmt->execute([$user, $poll, htmlentities($_POST["new-participant"])]);
    } else {
        $is_verified = "Sorry, Your NIN could not be verified. Ensure you're inputting the correct Figures";
    }

}

?>

<!DOCTYPE HTML/>
<html lang="en">

<head>
    <link rel="stylesheet" href="/static/css/style.css"/>
    <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/static/themify-icons.css"/>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <title>Voting System</title>
</head>

<body>
    <form method="post" action="" class="inner-dashboard-main">
    <div style="padding:3%">

    <?php
        if ($poll_name_data) {
            echo "<h3>", $poll_name_data->poll_name, "</h3> <hr /> <br />";
        }

        if (count($poll_data) > 0) {
            foreach ($poll_data as $pd) {
    ?>
                <label>
                    <input type="radio" name="poll-option" value="<?=$pd->poll_option_value?>" required/> 
                    <?=$pd->poll_option_value?>
                </label>
                
                <br /><br />
        <?php
            }
        ?>

        <b>Kindly enter your NIN to proceed in taking this poll</b><br />

        <div class="input-div">
            <input type="text" name="nin" class="input" style="border-radius:6px;height:28px;width:80%" placeholder="Please enter your NIN" required/>
        </div>
        
        <br />
           
        <input type="submit" class="dashboard-button" style = "background-color:#5a3e8d;color:#fff;border-radius:6px;padding:8px 12px" value="Submit"/> 
        
        <?php
            }
        ?> 
    </div>
    </form>

<!-- Load Javascript code: -->
<script src="/static/javascript/all-scripts.js"></script>

</body>  
</html>