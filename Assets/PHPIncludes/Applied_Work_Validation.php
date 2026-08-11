<?php
$appliedWorkErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $applied_work = trim($_POST['applied_work'] ?? "");

    if(empty($applied_work)){
        $appliedWorkErr = "Applied for work is required";
    }
    else if(!preg_match("/^[A-Za-z.\-\/\s]+$/", $applied_work)){
        $appliedWorkErr = "Only letters, space, dot (.), hyphen (-) and slash (/) allowed";
    }
}
?>
