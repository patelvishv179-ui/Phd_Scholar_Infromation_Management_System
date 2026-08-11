<?php
$certNoErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $cert_no = trim($_POST['cert_no'] ?? "");

    if(empty($cert_no)){
        $certNoErr = "Eligibility Certificate Number is required";
    }
    else if(preg_match("/\s/", $cert_no)){
        $certNoErr = "Spaces are not allowed";
    }
    else if(!preg_match("/^[A-Za-z0-9]+$/", $cert_no)){
        $certNoErr = "Only letters and numbers allowed";
    }
}
?>
