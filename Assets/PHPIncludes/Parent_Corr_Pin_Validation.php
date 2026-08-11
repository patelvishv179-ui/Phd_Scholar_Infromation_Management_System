<?php
$pcpinErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $pcpin = trim($_POST['parent_corr_pin'] ?? '');

    if(empty($pcpin)){
        $pcpinErr = "PIN code is required";
    }
    else if(!preg_match("/^[1-9][0-9]{5}$/", $pcpin)){
        $pcpinErr = "Enter valid 6 digit PIN code";
    }
}
?>
