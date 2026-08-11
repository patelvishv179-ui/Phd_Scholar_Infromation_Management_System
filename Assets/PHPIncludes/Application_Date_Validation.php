<?php
$appDateErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $app_date = $_POST['app_date'] ?? "";

    if(empty($app_date)){
        $appDateErr = "Application date is required";
    }
    else{
        $today = date("Y-m-d");

        if($app_date > $today){
            $appDateErr = "Future date is not allowed";
        }
    }
}
?>
