<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$data = authourized($pdo);

if ($data) {

    //CREATE POLL PARTICIPANTS
    $user = "";
    $poll = "";

    if (isset($_GET["user"]) && isset($_GET["poll"])) {
        $user = htmlentities($_GET["user"]);
        $poll = htmlentities($_GET["poll"]);
    }

    //Insert Poll Participant:
    $participant_added = "";

    if (isset($_POST["add-participant"])) {
        $istmt = $pdo->prepare("INSERT INTO poll_verification(`user_id`, poll_id, poll_participant) VALUES(?, ?, ?)");
        $istmt->execute([$user, $poll, htmlentities($_POST["new-participant"])]);

        $participant_added = "<span style='color:green;margin:4px'>You've successfully added participant: ".htmlentities($_POST["new-participant"])."</span>";
    }


    $mov_nin = isset($_POST["mov-nin"]) ? "nin" : "";
    $mov_int_pass = isset($_POST["mov-int-pass"]) ? "int-pass" : "";
    $mov_driver_license = isset($_POST["mov-driver-license"]) ? "driver-license" : "";
    $mov_voter_id = isset($_POST["mov-voter-id"]) ? "voter-id" : "";

    //CREATE POLL
    if (isset($_POST["create-poll"])) {
        $stmt = $pdo->prepare("INSERT INTO poll(poll_user_id, poll_name, poll_start_date, poll_end_date, mov_1, mov_2, mov_3, mov_4) VALUES(?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $data->id,
            htmlentities($_POST["poll-name"]), 
            htmlentities($_POST["poll-start-date"]),
            htmlentities($_POST["poll-end-date"]),
            $mov_nin,
            $mov_int_pass,
            $mov_driver_license,
            $mov_voter_id
        ]);   
?>
    <div class="invalid" style="background-color:#fff">Poll Created Successfully.</div>
<?php
    }

    //Fetch users created polls so as to list them in menu
    $poll_stmt = $pdo->prepare("SELECT * FROM poll WHERE poll_user_id = ? ORDER BY poll_id DESC LIMIT ?, ?");
    $poll_stmt->execute([$data->id, 0, 3]);
    
    $poll_data = $poll_stmt->fetchAll(PDO::FETCH_OBJ);
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
        <div class="dashboard">
            <div class="dashboard-menu">
                <ul class="dashboard-menu-list">
                    <li class="clear"><button class="dashboard-button" style="float:right" onclick="ajax_dashboard('new-poll')"> + New Poll </button></li>
                    
                    <li><a href="/">HOME</a></li>

                    <li onclick="show_div('view_polls')" class="clear">VIEW POLLS <i class="fa fa-angle-down"></i></li>
                        <div id="view_polls" style="display:none">
                            <?php
                                if (count($poll_data) > 0) { 
                                    foreach ($poll_data as $pd) {
                            ?>
                                        <small class="view_polls"><a href="/poll-participants.php?user=<?=$data->id?>&poll=<?=$pd->poll_id?>"><?=$pd->poll_name?></a></small><br />
                            <?php
                                    }
                                } else {
                            ?>
                                    <small class="view_polls">You don't have any active polls yet</small>
                            <?php
                                }
                            ?>
                        </div>
                    <li onclick="show_div('view_archives')" class="clear">VIEW ARCHIVES <i class="fa fa-angle-down"></i></li>
                        <small class="view_archives" id="view_archives" style="display:none">You don't have any polls here yet. Ended polls would automatically appear here after 30 days.</small>

                    <li><a href="/logout">Log out <i class="fa fa-circle"></i></a></li>
                </ul> 
            </div>

            <div class="dashboard-main">
                <div class="clear">
                    <div class="dashboard-menu-icon" onclick="show_div('dashboard-menu-list')"><i class="fa fa-bars"></i></div> 
                </div>

                <div class="inner-dashboard-main" id="inner-dashboard-main">

                    <!--Main Content Begins -->
                    <?=$participant_added?>
                    <form method="post" action="" style="padding:8px 12px">
                        <h3>Manage Your Poll </h3><hr/>
                    
                        <h4>Enter Participant's Name:</h4>
                        <small>
                            Fill in names of eligible participants for this poll. Our system would automatically verify their identities before they can vote.
                        </small>

                        <div class="input-div">
                            <input type="text" name="new-participant" class="input" placeholder="Enter Participant's Name"/>
                        </div>

                        <h4>Enter Options:</h4>
                        <small>
                            Click this Button to enter/edit the options of this poll
                        </small>

                        <a href="/poll-options.php?user=<?=$user?>&poll=<?=$poll?>" class="dashboard-button">Options</a>
                    
                        <input type="hidden" name="add-participant" value="yes"/>
                        
                        <div class="input-div">
                            <button type="submit" class="dashboard-button">Submit</button> 
                        </div>
                    </form>
                    <!--Main Content ends-->

                </div>

            </div>
        </div>

        <!-- Load Javascript code: -->
        <script src="/static/javascript/all-scripts.js"></script>
    </body>  
    </html>

<?php
} else {
    //redirect to home page
    header("location:/");
}