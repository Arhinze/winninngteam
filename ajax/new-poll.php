<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$data = authourized($pdo);

if ($data) {

?>

<form method="post" action="" style="padding:8px 12px">
    <div class="input-div">
        <input type="text" name="poll-name" placeholder="Name of Poll" class="input" required/>
        <br/><small>This is unique per user.</small>
    </div>
   
    <!--
    <div class="input-div" id="add-participants" style="display:none">
        <input type="text" name="new-participant" class="input" placeholder="Enter Participant's Name"/>
    </div>
    -->
    
    <div class="input-div">
        Poll Start Date: <input type="date" name="poll-start-date" style="width:120px;height:30px;border-radius:6px;border:2px solid #5a3e8d"/>
    </div>

    <div class="input-div">
        Poll End Date: <input type="date" name="poll-end-date" style="width:120px;height:30px;border-radius:6px;border:2px solid #5a3e8d"/>
    </div>

    <h3>Means of verification</h3>
    
    <i class="fa fa-circle"></i> How do you wish to verify participants?

    <div style="margin-top:12px">
        <input type="checkbox" name="mov-nin" value="nin"/> NIN 
        <input type="checkbox" name="mov-int-pass" value="int-pass"/> International Passport 
        <input type="checkbox" name="mov-driver-license" value="driver-license"/> Driver's License
        <input type="checkbox" name="mov-voter-id" value="voter-id"/> Voter's ID
    </div>

    <!--

    <br /><i class="fa fa-circle"></i> How many of these MOVs must they pass?

    <div style="margin-top:12px">
        <input type="radio" name="no-of-mov" value="1"/> 1
        <input type="radio" name="no-of-mov" value="2"/> 2
        <input type="radio" name="no-of-mov" value="3"/> 3
        <input type="radio" name="no-of-mov" value="4"/> 4
    </div>

    -->

    <!--
    <div class="input-div">
        <button class="dashboard-button" onclick="show_div('add-participants')">
            + Add Participants
        </button>
    <div> 
    -->

    <input type="hidden" name="create-poll" value="create-poll"/>

    <div class="clear">
        <button type="submit" class="dashboard-button" style="float:right">Create Poll</button>
    </div>
</form>

<?php
} else {
    //redirect to home
    header("location:/faqs");
}
?>