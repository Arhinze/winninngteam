<?php

//admin_segments would include connections
include_once($_SERVER["DOCUMENT_ROOT"]."/views/admin_Segments.php");
if(isset($_COOKIE["admin_name"]) && isset($_COOKIE["admin_password"])){

    $stmt = $pdo->prepare("SELECT * FROM `admin` LIMIT ?, ?");
    $stmt->execute([0, 2]);

    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($data)>0){
    //that means admin is logged in:
        
        $r = '';
        foreach($data as $d){
            if(($d->admin_name == $_COOKIE["admin_name"]) && ($d->admin_password == $_COOKIE["admin_password"])){
                $r .= "y";
            } else {
                $r .= "";  
            }
        }

        if($r == "y"){
            echo "";
        } else {
            header("location:/admin");
        }
          
       //that means user is an admin of admins
        admin_Segments::header();

        //if remove admin was requested:
        if(isset($_POST["remove_admin"])){
            $delete_check_stmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_id=?");
            $delete_check_stmt->execute([$_POST["remove_admin"]]);
            $dcd = $delete_check_stmt->fetch(PDO::FETCH_OBJ);

            if($dcd){
                $delete_admin_stmt = $pdo->prepare("DELETE FROM `admin` WHERE admin_id=?");
                $delete_admin_stmt->execute([$_POST["remove_admin"]]);
            }
        }

        //to insert dummy admin:
        if(isset($_POST["dummy_name"]) && isset($_POST["dummy_password"])){
            $istmt = $pdo->prepare("SELECT * FROM `admin` WHERE admin_name = ? AND admin_password = ?");
            $istmt->execute([$_POST["dummy_name"],$_POST["dummy_password"]]);
            $idata = $istmt->fetch(PDO::FETCH_OBJ);

            if($idata){
                echo "<h2>Sorry, an Admin with same username and password already exists.</h2>
                 Kindly create a new one";
            } else {
                $ins = $pdo->prepare("INSERT INTO `admin`(admin_name, admin_password) VALUES(?, ?)");
                $ins->execute([$_POST["dummy_name"],$_POST["dummy_password"]]);

                echo "<h3>temporary admin created successfully. </h3>
                   kindly remind the new admin to reset password to a stronger one once he logs in.";
            }
        }

        //update rate
        $arr = [1,2,3,4];
        $is_updated = "";
        foreach($arr as $a){
            if(isset($_POST["rate$a"])) {
                $update_rate_stmt = $pdo->prepare("UPDATE btc_rate SET br = ? WHERE br_id = ?");
                $update_rate_stmt->execute([$_POST["rate$a"],$a]);

                $is_updated = "<h3 style='color:#ff9100'>Rates updated successfully</h3>";  
            }  
        }

        echo $is_updated;

        /*
           Tip:
               rate1 = btc;
               rate2 = eth;
               rate3 = usdt;
               rate4 = pm;
        */ 
        
        //To be used in 1.) withdraw.php 2.) invest.php 3.) admin_of_admins.php
        //Select the rates from database to use as 'value' for the input elements
        $rates = [];
        $br_stmt = $pdo->prepare("SELECT * FROM btc_rate LIMIT ?, ?");
        $br_stmt->execute([0, 6]);
        $br_data = $br_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($br_data as $b){
            $rates[] = $b->br;
        }
 
        //Admins:
        $sa_stmt = $pdo->prepare("SELECT * FROM `admin` LIMIT ?, ?");
        $sa_stmt->execute([1,100]);
        $sa_data = $sa_stmt->fetchAll(PDO::FETCH_OBJ);
?>
        <div class="main">
            <div class= "calculator">
                <h2>Admins </h2><hr />
                <?php 
                $i = 0;
                foreach($sa_data as $sd){
                    $i += 1;
                    echo $i, ". ", $sd->admin_name;
                ?>
                    <button style="background-color:red;padding:3px;border-radius:6px;color:#fff;text-align:center;
                    height:24px;border:none;margin-left:12px" onclick="show_div('remove<?=$i?>')">
                        Remove
                    </button>  <br /><br />

                    <div id ="remove<?=$i?>" style="display:none;background-color:#000;position:fixed;top:21%;padding:10px;box-shadow:0px 0px 9px 0px #ff9100;
                    border-radius:6px;margin:16px;text-align:center">
                        <div class="clear">
                            <i class="fa fa-times" style="float:right" onclick="show_div('remove<?=$i?>')"></i>
                        </div>

                        <form method="post" action ="" id="<?=$i?>">
                            Are you sure you want to delete Admin: <?=$sd->admin_name?>? <b style="color:red"><i class="fa fa-warning"></i> This can't be undone!</b>
                            <br /><br />

                            <input type="hidden" name="remove_admin" value="<?=$sd->admin_id?>"/>
                            <input type="submit" value = "Remove" style="background-color:red;
                            padding:3px;border-radius:6px;color:#fff;
                            text-align:center;height:24px;border:none"/>
                        </form> <br />

                        <button style="background-color:#ff9100;padding:3px;border-radius:6px;color:#fff;text-align:center;
                        height:24px;border:none;margin-left:12px" onclick="show_div('remove<?=$i?>')">
                        Cancel
                        </button>
                    </div>


                <?php
                } 
                ?>
            </div>

            <div class="calculator">
                <h2>Add Admin: </h2><hr />

                <p style="text-align:center;color:#ff9100">after creating this admin username and password, inform the new admin who logs in with it to quickly reset password. 
                to a stronger one</p>

                <form method="post" action="">
                <b style="font-size:16px">Input a dummy name: E.g: "admin3"</b><br />
                <input type="text" name="dummy_name" class="input" placeholder="name: e.g 'abc'"/> <br /><br />

                <b style="font-size:16px">Input a dummy password: E.g: "123"</b><br />
                <input type="text" name="dummy_password" class="input" placeholder="password: e.g '123'"/><br />
                <br />
                <input type="submit"  value="Submit" style="border:none;color:#fff;background-color:#ff9100;border-radius:6px;padding:5px"/>    
                </form>
            </div>

            <div class="calculator">
                <h2>Set Rates </h2><hr />
                <h3>How many dollars?</h3>
                    You can change the <b>crypto-usd</b> exchange rates below.  
                      <b style="font-size:16px;color:#ff9100">Note: This would affect in-built calculators on this site.</b>

                <h3>Current Rates </h3>
                <form method="post" action="">
                <b style="font-size:19px">1 BTC = ?</b> <br />
                <input type="text" name="rate1" class="investor_input" value="<?=$rates[0]?>"/><span style="margin-left:-37px">USD</span><br /><br />

                <b style="font-size:19px">1 ETH = ?</b> <br />
                <input type="text" name="rate2" class="investor_input" value="<?=$rates[1]?>"/><span style="margin-left:-37px">USD</span><br /><br />

                <b style="font-size:19px">1 USDT = ?</b> <br />
                <input type="text" name="rate3" class="investor_input" value="<?=$rates[2]?>"/><span style="margin-left:-37px">USD</span><br /><br />

                <b style="font-size:19px">1 PM = ?</b> <br />
                <input type="text" name="rate4" class="investor_input" value="<?=$rates[3]?>"/><span style="margin-left:-37px">USD</span><br />

                <br /><br />

                <input type="submit"  value="Submit" style="border:none;color:#fff;background-color:#ff9100;border-radius:6px;padding:5px"/>
                </form>
            </div>
        </div>


<?php
        admin_Segments::footer();
    } else {
        header("location:/admin");
    } 
} else {
    header("location:/admin");
} 
?>

<script>
function show_div(vari) {
    if (document.getElementById(vari).style.display == "none") {
        document.getElementById(vari).style.display = "block";
    } else if (document.getElementById(vari).style.display == "block") {
        document.getElementById(vari).style.display = "none";
    }
}
</script>