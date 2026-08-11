<?php
$feeErr = "";

if(isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])){

    $fee_no = str_replace(" ","", $_POST['fee_no']);

    // Required check
    if(empty($fee_no)){
        $feeErr = "Fee Receipt Number is required";
    }
    // Only letters and numbers allowed
    else if(!preg_match("/^[A-Za-z0-9]+$/", $fee_no)){
        $feeErr = "Only letters and numbers allowed";
    }
}
?>
