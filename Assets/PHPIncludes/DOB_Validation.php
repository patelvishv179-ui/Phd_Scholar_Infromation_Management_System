<?php
$dobErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $dob = $_POST['dob'] ?? '';

    if(empty($dob)){
        $dobErr = "Date of Birth is required";
    }
    else{
        $birth = strtotime($dob);
        $today = strtotime(date("Y-m-d"));
        $age = floor( ($today - $birth) / (365*24*60*60) );

        if($age < 14){
            $dobErr = "Minimum age must be 14 years";
        }
    }
}
?>
