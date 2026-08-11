<?php

$entranceErr = "";

if(isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])){

    $entrance = str_replace(" ","", $_POST['entrance']);

    // Required check
    if(empty($entrance)){
        $entranceErr = "Entrance Sheet Number is required";
    }
    // Allow only letters and numbers
    else if(!preg_match("/^[A-Za-z0-9]+$/", $entrance)){
        $entranceErr = "Only letters and numbers allowed";
    }

    // If no error then continue saving later
    if($entranceErr == ""){
        // valid
    }
}

?>