<?php
$permPinErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $perm_pin = trim($_POST['perm_pin'] ?? '');

    if(empty($perm_pin)){
        $permPinErr = "PIN code is required";
    }
    else if(!preg_match("/^[1-9][0-9]{5}$/", $perm_pin)){
        $permPinErr = "Enter valid 6 digit PIN code";
    }
}
?>
