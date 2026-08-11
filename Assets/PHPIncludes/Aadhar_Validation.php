<?php
$aadharErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $aadhar = trim($_POST['adhar'] ?? '');

    if(empty($aadhar)){
        $aadharErr = "Aadhar number is required";
    }
    else if(!preg_match("/^[0-9]{12}$/", $aadhar)){
        $aadharErr = "Enter valid 12 digit Aadhar number";
    }
}
?>
