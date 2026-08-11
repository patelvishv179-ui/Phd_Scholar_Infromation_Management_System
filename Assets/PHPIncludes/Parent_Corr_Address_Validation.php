<?php
$pcorrErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $pcorr = trim($_POST['parent_corr_address'] ?? '');

    if(empty($pcorr)){
        $pcorrErr = "Correspondence Address is required";
    }
    // Letters, numbers, space and comma only
    else if(!preg_match("/^[A-Za-z0-9 ,]+$/", $pcorr)){
        $pcorrErr = "Only letters, numbers and comma (,) allowed";
    }
}
?>
