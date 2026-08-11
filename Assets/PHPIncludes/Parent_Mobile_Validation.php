<?php
$parentMobileErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $parent_mobile = trim($_POST['parent_mobile'] ?? '');

    if(empty($parent_mobile)){
        $parentMobileErr = "Mobile number is required";
    }
    else if(!preg_match("/^[6-9][0-9]{9}$/", $parent_mobile)){
        $parentMobileErr = "Enter valid 10 digit mobile number";
    }
}
?>
