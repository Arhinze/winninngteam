<?php

$countries = [
    "France", "Germany", "Sweden", "China", "Brazil",
    "England", "Norway", "Canada", "China", "Dubai",
    "Senegal","New York", "Denmark", "Portugal", "Spain",
    "Cameroon", "USA","Finland","Australia"
];

$status = [
    "is trading with", "just invested", "withdrew"
];

$amount = [
    500, 700, 1000, 1500, 1700, 1800, 2000, 2100, 2300,
    "3,000", "5,000", "1,200", "3,300", "4,100", "1,350", "2,430", "4,570", "1,620", "1,310", "30,000","32,000", "29,350","27,390", "15,051","16,000","6,000", "50,000", "60,000", "10,000", "17,210","21,110","14,770","44,280","36,000"
];

$i = 0;

    shuffle($countries);
    shuffle($status);
    shuffle($amount); 
?>

<div style='width:100%;position:relative;justify-content:center'>

<div class="invest" style="padding:3px 8px 3px 8px;margin:0px;top:50%;left:15%;right:15%;width:70%;font-size:15px;position:fixed">
    Someone from <b><?=$countries[0],"</b> <b>",$status[0],"</b> <b><span style='color:#2b8eeb'><br/>$", $amount[0], "</span></b>"?> 
</div>

</div>
