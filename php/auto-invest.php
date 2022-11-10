<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Transactions.php");

$ss = $pdo->prepare("SELECT * FROM transactions WHERE tr_type = ?  AND tr_amount >= ? AND tr_time > ? LIMIT ?, ?");
$ss->execute(["Invest", 100, (time()-30*24*60*60), 0, 300]);
$all_data = $ss->fetchAll(PDO::FETCH_OBJ);

foreach($all_data as $d){
    //interest is added each day - so check if it's 24 hrs already since the last profit time    echo $d->user_id, " - ", $d->tr_amount,"<br />";
    
    if(((strtotime($d->tr_time) + ($transactions->num_of_days($d->tr_amount)*24*60*60)) > time()) && ((time() - (24*60*60)) > strtotime($d->last_profit_time))){
        $a = $transactions->rate($d->tr_amount);

        $up_stmt = $pdo->prepare("UPDATE transactions SET last_profit_time = ?, profit = ? WHERE tr_id = ?");
        $up_stmt->execute([date("Y-m-d H:i:s", time()), ($d->profit + ($a/100 * $d->tr_amount)), $d->tr_id]);
    }
}
/*
foreach($all_data as $d){
    //interest is added each day - so check if it's 24 hrs already since the last profit time    
    if(((strtotime($d->tr_time) + ($transactions->num_of_days($d->tr_amount)*24*60*60)) > time()) && ((time() - (24*60*60)) > strtotime($d->last_profit_time))){
        $a = $transactions->rate($d->tr_amount);

        $up_stmt = $pdo->prepare("UPDATE transactions SET last_profit_time = ? AND profit = ? WHERE tr_id = ?");
        $up_stmt->execute([date("Y-m-d h:i:s", time()), ($d->profit + ($a/100 * $d->tr_amount)), $d->tr_id]);
    
    }
}
*/
?>