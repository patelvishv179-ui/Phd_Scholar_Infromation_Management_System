

<!-- ================= TAB MENU ================= -->
<div class="tabs-wrapper">

    <a href="Scholar_Dashboard.php?view=update&page=academic"
       class="tab-btn <?php echo (!isset($_GET['page']) || $_GET['page']=="academic") ? "active" : ""; ?>">
       Academic Details
    </a>

    <a href="Scholar_Dashboard.php?view=update&page=personal"
       class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="personal") ? "active" : ""; ?>">
       Personal Details
    </a>

    <a href="Scholar_Dashboard.php?view=update&page=education"
        class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="education") ? "active" : ""; ?>">
        Education Details
    </a>

    <a href="Scholar_Dashboard.php?view=update&page=experience"
       class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="experience") ? "active" : ""; ?>">
       Experience Details
    </a>

</div>

<!-- ================= CONTENT ================= -->
<div class="tab-content-box">

<?php
if(isset($_GET['page']) && $_GET['page']=="personal"){
    include("Personal_Details_Edit.php");
}
elseif(isset($_GET['page']) && $_GET['page']=="education"){
    include("Education_Details_Edit.php");
}
elseif(isset($_GET['page']) && $_GET['page']=="experience"){
    include("Experience_Details_Edit.php"); 
}
else{
    include("Academic_Detail_Edit.php");   // default
}
?>

</div>
