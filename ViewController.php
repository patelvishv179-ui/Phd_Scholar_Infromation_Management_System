<?php

if (!isset($_SESSION['user_id']) || !isset($_SESSION['SCHOLAR_EMAIL'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['SCHOLAR_EMAIL'];
$stmt = $con->prepare("SELECT SCHOLAR_NAME, SCHOLAR_REGISTRATION_NUMBER FROM scholar_master WHERE EMAIL=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

?>

<!-- ================= TOP HEADER ================= -->
<div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-3 mb-3 rounded border border-primary">

    <!-- LEFT: USER INFO -->
    <div class="d-flex align-items-center gap-3">
        <img src="Assets/logo.png" alt="User Avatar"  width="45" height="50">
        <div>
        <h6 class="mb-0 fw-semibold">
            Welcome, <?= htmlspecialchars($data['SCHOLAR_NAME']); ?>
        </h6>
        <small class="text-muted">
            Reg No: <?= htmlspecialchars($data['SCHOLAR_REGISTRATION_NUMBER']); ?>
        </small>
        </div>
    </div>

    <!-- RIGHT: LOGOUT -->
    <a href="Assets/Config/Logout.php" class="btn btn-outline-danger btn-md">
        Logout
    </a>

</div>


<!-- ================= TAB MENU ================= -->
<div class="tabs-wrapper">

    <a href="Complete_Profile.php?page=academic"
       class="tab-btn <?php echo (!isset($_GET['page']) || $_GET['page']=="academic") ? "active" : ""; ?>">
       Academic Details
    </a>

    <a href="Complete_Profile.php?page=personal"
       class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="personal") ? "active" : ""; ?>">
       Personal Details
    </a>

    <a href="Complete_Profile.php?page=education"
       class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="education") ? "active" : ""; ?>">
       Education Details
    </a>

    <a href="Complete_Profile.php?page=experience"
       class="tab-btn <?php echo (isset($_GET['page']) && $_GET['page']=="experience") ? "active" : ""; ?>">
       Experience Details
    </a>

</div>


<!-- ================= CONTENT ================= -->
<div class="tab-content-box ">

<?php
if(isset($_GET['page']) && $_GET['page']=="personal"){
    include("Views/PersonalView.php");
}
elseif(isset($_GET['page']) && $_GET['page']=="education"){
    include("Views/EducationView.php");
}
elseif(isset($_GET['page']) && $_GET['page']=="experience"){
    include("Views/ExperienceView.php"); 
}
else{
    include("Views/AcademicView.php");   // default
}
?>

</div>