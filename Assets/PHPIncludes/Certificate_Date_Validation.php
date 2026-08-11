<?php
$certDateErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $cert_date = $_POST['cert_date'] ?? "";

    if(empty($cert_date)){
        $certDateErr = "Certificate Date is required";
    }
    else if(strtotime($cert_date) > strtotime(date("Y-m-d"))){
        $certDateErr = "Future date is not allowed";
    }
}
?>
