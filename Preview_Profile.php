<?php
session_start();

// 1. Session Protection
if (!isset($_SESSION['SCHOLAR_EMAIL']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "Assets/Config/Connection.php";

$email = $_SESSION['SCHOLAR_EMAIL'];
$user_session_id = $_SESSION['user_id']; // Use the session ID to fetch data initially

/* ================= 2. FETCH BASIC DETAILS FIRST ================= */
// We use the session user_id to get the full profile. 
// This fixes the "Undefined variable $personal" error.
$stmt = $con->prepare("
    SELECT s.*, p.*, f.FACULTY_NAME, sub.SUBJECT_NAME
    FROM scholar_master s
    LEFT JOIN Scholar_Personal_Details p ON s.SCHOLAR_ID = p.SCHOLAR_ID
    LEFT JOIN faculty_available f ON s.FACULTY_ID = f.FACULTY_ID
    LEFT JOIN subject_available sub ON s.SUBJECT_ID = sub.SUBJECT_ID
    WHERE s.SCHOLAR_ID = ?
");

$stmt->bind_param("i", $user_session_id);
$stmt->execute();
$personal = $stmt->get_result()->fetch_assoc();

// Check if profile exists
if (!$personal) {
    echo "Profile not found.";
    exit;
}

$scholar_id = $personal['SCHOLAR_ID'];
$regno = $personal['SCHOLAR_REGISTRATION_NUMBER'];

/* ================= 3. HANDLE POST ACTIONS ================= */
if (isset($_POST['confirm_lock'])) {

    // Update profile complete status
    $stmt_upd = $con->prepare("UPDATE Scholar_Master SET PROFILE_COMPLETE = 1 WHERE SCHOLAR_ID = ?");
    $stmt_upd->bind_param("i", $scholar_id);
    $stmt_upd->execute();

    // QR CODE GENERATION
    require_once "Assets/phpqrcode/qrlib.php";

   $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/BACKUP/";

   $qrData  = $baseUrl . "View_Scholar_Profile.php?reg=" . $regno;
 
   $qrFolder = "Assets/ScholarQr/";

    if (!is_dir($qrFolder)) {
        mkdir($qrFolder, 0777, true);
    }

    $qrFile = $qrFolder . $regno . ".png";

    if (!file_exists($qrFile)) {
        QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 5);
    }

    header("Location: Scholar_Dashboard.php");
    exit;
}

/* ================= 4. FETCH REMAINING DETAILS ================= */
// Education - Prepared Statement to prevent SQL injection and syntax errors
$stmt_edu = $con->prepare("SELECT * FROM scholar_education_details WHERE SCHOLAR_ID = ?");
$stmt_edu->bind_param("i", $scholar_id);
$stmt_edu->execute();
$eduRes = $stmt_edu->get_result();

// Experience - Prepared Statement
$stmt_exp = $con->prepare("
    SELECT e.*, n.NATURE_NAME, l.LEVEL_NAME, c.CATEGORY_NAME
    FROM scholar_experience_details e
    LEFT JOIN nature_of_work_master n ON e.NATURE_ID = n.NATURE_ID
    LEFT JOIN teaching_level_master l ON e.LEVEL_ID = l.LEVEL_ID
    LEFT JOIN experience_category_master c ON e.CATEGORY_ID = c.CATEGORY_ID
    WHERE e.SCHOLAR_ID = ?
");
$stmt_exp->bind_param("i", $scholar_id);
$stmt_exp->execute();
$expRes = $stmt_exp->get_result();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: #f2f4f8;
    }

    .wrapper {
        max-width: 1000px;
        margin: auto;
        padding: 30px;
    }

    .profile-header {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        color: white;
        border-radius: 15px;
        padding: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .left-box {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .photo-box {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        overflow: hidden;
        border: 5px solid #fff;
    }

    .photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qr-box {
        background: #fff;
        padding: 10px;
        border-radius: 10px;
    }

    .card-box {
        background: white;
        border-radius: 12px;
        margin-top: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        padding: 15px 20px;
        font-size: 18px;
        font-weight: 600;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 20px;
    }

    th {
        background: #f8f9fa;
        width: 30%;
    }

    @media print {
        .none-d-class {
            display: none !important;
        }
    }
</style>


<div class="wrapper border rounded border-dark mt-5 mb-5">

    <!-- ================= HEADER ================= -->
    <div class="profile-header">

        <div class="left-box">
            <div class="photo-box">
                <?php
                $img = $personal['SCHOLAR_IMAGEURL'] ?? "";
                if ($img != "" && file_exists($img)) { ?>
                    <img src="<?= $img ?>">
                <?php } else { ?>
                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png">
                <?php } ?>
            </div>

            <div>
                <h3><?= $personal['SCHOLAR_NAME'] ?></h3>
                <p><i class="bi bi-envelope"></i> <?= $personal['EMAIL'] ?></p>
                <p><i class="bi bi-phone"></i> <?= $personal['MOBILE'] ?></p>
            </div>
        </div>


    </div>

    <!-- ================= REGISTRATION DETAILS ================= -->
    <div class="card-box">
        <div class="card-title"><i class="bi bi-person-circle"></i> Registration Details</div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Registration No</th>
                    <td><?= $personal['SCHOLAR_REGISTRATION_NUMBER'] ?></td>
                </tr>
                <tr>
                    <th>Registration Date</th>
                    <td><?= $personal['REGISTRATION_DATE'] ?></td>
                </tr>
                <tr>
                    <th>Faculty</th>
                    <td><?= $personal['FACULTY_NAME'] ?></td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td><?= $personal['SUBJECT_NAME'] ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ================= PERSON DETAILS ================= -->
    <?php
    if (
        !empty($personal['GENDER']) ||
        !empty($personal['DOB']) ||
        !empty($personal['NATIONALITY']) ||
        !empty($personal['ADHAR_NUMBER']) ||
        !empty($personal['SCHOLAR_PERM_ADDRESS']) ||
        !empty($personal['ADMISSION_CATEGORY'])
    ) {
    ?>
        <div class="card-box">
            <div class="card-title">
                <i class="bi bi-person-badge-fill"></i> Person Details
            </div>

            <div class="card-body">
                <table class="table table-bordered">

                    <tr>
                        <th>Scholar Name</th>
                        <td><?= $personal['SCHOLAR_NAME'] ?></td>
                    </tr>

                    <tr>
                        <th>Gender</th>
                        <td><?= $personal['GENDER'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Date of Birth</th>
                        <td><?= $personal['DOB'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Nationality</th>
                        <td><?= $personal['NATIONALITY'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Aadhar Number</th>
                        <td><?= htmlspecialchars($personal['ADHAR_NUMBER'] ?? '-') ?></td>
                    </tr>

                    <tr>
                        <th>Permanent Address</th>
                        <td><?= $personal['SCHOLAR_PERM_ADDRESS'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Admission Category</th>
                        <td><?= $personal['ADMISSION_CATEGORY'] ?: '-' ?></td>
                    </tr>

                </table>
            </div>
        </div>
    <?php } ?>


    <!-- ================= PARENT DETAILS ================= -->
    <?php if (!empty($personal['PARENT_NAME'])) { ?>
        <div class="card-box">
            <div class="card-title"><i class="bi bi-people-fill"></i> Parent Details</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td><?= $personal['PARENT_NAME'] ?></td>
                    </tr>
                    <tr>
                        <th>Relation</th>
                        <td><?= $personal['PARENT_RELATIONSHIP'] ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><?= $personal['PARENT_MOBILE'] ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?= $personal['PARENT_CORR_ADDRESS'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php } ?>

    <!-- ================= ACADEMIC DETAILS (DOCUMENTS) ================= -->
    <?php
    if (
        !empty($personal['ENTRANCE_SHEET_NO']) ||
        !empty($personal['FEE_RECEIPT_NO']) ||
        !empty($personal['FEE_RECEIPT_DATE']) ||
        !empty($personal['RESEARCH_TITLE']) ||
        !empty($personal['GUIDE_NAME_ADDRESS']) ||
        !empty($personal['INSTITUTE_WHERE']) ||
        !empty($personal['APPLIED_FOR_WORK']) ||
        !empty($personal['ELIGIBILITY_CERT_NO']) ||
        !empty($personal['ELIGIBILITY_CERT_DATE'])
    ) {
    ?>
        <div class="card-box">
            <div class="card-title">
                <i class="bi bi-file-earmark-text-fill"></i> Admission & Research Details
            </div>

            <div class="card-body">
                <table class="table table-bordered">

                    <tr>
                        <th>Entrance Sheet No</th>
                        <td><?= $personal['ENTRANCE_SHEET_NO'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Fee Receipt No</th>
                        <td><?= $personal['FEE_RECEIPT_NO'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Fee Receipt Date</th>
                        <td><?= $personal['FEE_RECEIPT_DATE'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Research Title</th>
                        <td><?= $personal['RESEARCH_TITLE'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Guide Name & Address</th>
                        <td><?= $personal['GUIDE_NAME_ADDRESS'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Institute </th>
                        <td><?= $personal['INSTITUTE_WHERE'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Applied For Work</th>
                        <td><?= $personal['APPLIED_FOR_WORK'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Eligibility Certificate No</th>
                        <td><?= $personal['ELIGIBILITY_CERT_NO'] ?: '-' ?></td>
                    </tr>

                    <tr>
                        <th>Eligibility Certificate Date</th>
                        <td><?= $personal['ELIGIBILITY_CERT_DATE'] ?: '-' ?></td>
                    </tr>

                </table>
            </div>
        </div>
    <?php } ?>


    <!-- ================= EDUCATION DETAILS ================= -->
    <?php if (mysqli_num_rows($eduRes) > 0) { ?>
        <div class="card-box">
            <div class="card-title"><i class="bi bi-mortarboard-fill"></i> Education Details</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr class="table-secondary">
                        <th>Exam</th>
                        <th>Board</th>
                        <th>College</th>
                        <th>Division</th>
                        <th>Year</th>
                    </tr>
                    <?php while ($e = mysqli_fetch_assoc($eduRes)) { ?>
                        <tr>
                            <td><?= $e['EXAM_PASSED'] ?></td>
                            <td><?= $e['UNIVERSITY_BOARD'] ?></td>
                            <td><?= $e['SCHOOL_COLLEGE'] ?></td>
                            <td><?= $e['DIVISION_PERCENTAGE'] ?></td>
                            <td><?= $e['YEAR_OF_PASSING'] ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php } ?>

    <!-- ================= EXPERIENCE DETAILS ================= -->
    <?php if (mysqli_num_rows($expRes) > 0) { ?>
        <div class="card-box">
            <div class="card-title"><i class="bi bi-briefcase-fill"></i> Experience Details</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr class="table-secondary">
                        <th>Employee</th>
                        <th>Post</th>
                        <th>Nature</th>
                        <th>Level</th>
                        <th>Category</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Years</th>
                        <th>Months</th>
                    </tr>
                    <?php while ($x = mysqli_fetch_assoc($expRes)) {
                        $p = explode(".", $x['TOTAL_EXPERIENCE']);
                        $y = $p[0] ?? 0;
                        $m = $p[1] ?? 0;
                    ?>
                        <tr>
                            <td><?= $x['EMPLOYEE_NAME_ADDRESS'] ?></td>
                            <td><?= $x['POST_HELD'] ?></td>
                            <td><?= $x['NATURE_NAME'] ?></td>
                            <td><?= $x['LEVEL_NAME'] ?></td>
                            <td><?= $x['CATEGORY_NAME'] ?></td>
                            <td><?= $x['SERVICE_FROM'] ?></td>
                            <td><?= $x['SERVICE_TO'] ?></td>
                            <td><?= $y ?></td>
                            <td><?= $m ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    <?php } ?>

    <div class="card-box none-d-class">

        <div class="card-body d-flex justify-content-between ">

            <button onclick="window.print()" class="btn btn-secondary btn-lg">
                Download PDF
            </button>
       <div class="d-flex gap-3 align-items-center">

    <a href="Complete_Profile.php?view=update&page=academic"
        class="btn btn-primary btn-lg">
        Edit
    </a>

    <form method="POST" class="m-0">
        <button type="submit" name="confirm_lock"
            class="btn btn-success btn-lg">
            Confirm & Lock
        </button>
    </form>

</div>

        </div>

    </div>

</div>