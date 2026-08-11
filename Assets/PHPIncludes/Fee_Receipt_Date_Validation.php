<?php
$feeDateErr = "";

if (isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])) {

    $fee_date = $_POST['fee_date'] ?? '';

    // Required check
    if (empty($fee_date)) {
        $feeDateErr = "Fee Receipt Date is required";
    }
    // Future date allow nathi
    else if ($fee_date > date("Y-m-d")) {
        $feeDateErr = "Future date not allowed";
    }
}
?>