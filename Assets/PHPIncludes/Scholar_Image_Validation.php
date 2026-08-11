<?php
$imageErr = "";

if(isset($_FILES['scholar_image']) && $_FILES['scholar_image']['name']!=""){

    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['scholar_image']['name'], PATHINFO_EXTENSION));

    if(!in_array($ext,$allowed)){
        $imageErr = "Only JPG and PNG image allowed";
    }
    elseif($_FILES['scholar_image']['size'] > 2*1024*1024){
        $imageErr = "Image size must be less than 2MB";
    }
}
?>
