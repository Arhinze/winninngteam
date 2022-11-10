<?php
    //CREATE POLL PARTICIPANTS
    if (isset($_GET["user"]) && isset($_GET["poll"])) {

        $user = htmlentities($_GET["user"]);
        $poll = htmlentities($_GET["poll"]);

    }
?>

<div class="input-div">
    <button class="dashboard-button" onclick="show_div('add-participants')">
        + Add Participants
    </button>
<div> 

<form method="post" action="">
<div class="input-div" id="add-participants" style="display:none">
    <input type="text" name="new-participant" class="input" placeholder="Enter Participant's Name"/>
</div>

<input type="hidden" name="add-participant" value="yes"/>

<button type="submit" class="dashboard-button">Submit</button>

</form>