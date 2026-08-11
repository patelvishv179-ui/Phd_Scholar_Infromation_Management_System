<?php
$permAddrErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $perm_address = trim($_POST['perm_address'] ?? '');

    if(empty($perm_address)){
        $permAddrErr = "Permanent Address is required";
    }
    // Letters, numbers, space and comma only
    else if(!preg_match("/^[A-Za-z0-9 ,]+$/", $perm_address)){
        $permAddrErr = "Only letters, numbers and comma (,) allowed";
    }
}
?>
