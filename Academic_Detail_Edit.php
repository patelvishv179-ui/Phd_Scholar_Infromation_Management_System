<?php


// ======================
// AJAX : Load Subjects
// ======================
if (isset($_GET['action']) && $_GET['action'] == "getSubjects") {

    $fid = $_GET['faculty_id'];

    $res = mysqli_query(
        $con,
        "SELECT SUBJECT_ID, SUBJECT_NAME 
         FROM subject_available 
         WHERE faculty_id='$fid'"
    );

    echo "<option value=''>-- Select Subject --</option>";

    while ($row = mysqli_fetch_assoc($res)) {
        echo "<option value='{$row['SUBJECT_ID']}'>
                {$row['SUBJECT_NAME']}
              </option>";
    }
    exit;
}


$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
$ps->bind_param("s", $_SESSION['email']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

// ================= FETCH DATA =================
$q = mysqli_query($con, "SELECT * FROM scholar_master WHERE SCHOLAR_ID='$scholar_id'");
$data = mysqli_fetch_assoc($q);

// ================= VALIDATIONS =================
include 'Assets/PHPIncludes/Entrance_Sheet_Number_Validation.php';
include 'Assets/PHPIncludes/Fee_Receipt_Number_Validation.php';
include 'Assets/PHPIncludes/Fee_Receipt_Date_Validation.php';
include 'Assets/PHPIncludes/Research_Title_Validation.php';
include 'Assets/PHPIncludes/Guide_Validation.php';

$regProtectErr = "";

// ================= SAVE =================
if (isset($_POST['save']) || isset($_POST['save_next'])) {

    // Protect Registration fields
    if ($_POST['regno'] != $data['SCHOLAR_REGISTRATION_NUMBER']) {
        $regProtectErr = "Please do not change Registration Number";
    }

    if ($_POST['regdate'] != $data['REGISTRATION_DATE']) {
        $regProtectErr = "Please do not change Registration Date";
    }

    if (
        empty($entranceErr) &&
        empty($feeErr) &&
        empty($feeDateErr) &&
        empty($titleErr) &&
        empty($facultyErr) &&
        empty($subjectErr) &&
        empty($guideErr) &&
        empty($regProtectErr)
    ) {

        $entrance = trim($_POST['entrance']);
        $fee_no   = trim($_POST['fee_no']);
        $fee_date = $_POST['fee_date'];
        $title    = trim($_POST['title']);
        $faculty  = $_POST['faculty'];
        $subject  = $_POST['subject'];
        $guide    = trim($_POST['guide']);

        $stmt = $con->prepare("
            UPDATE scholar_master SET
            ENTRANCE_SHEET_NO=?,
            FEE_RECEIPT_NO=?,
            FEE_RECEIPT_DATE=?,
            RESEARCH_TITLE=?,
            GUIDE_NAME_ADDRESS=?
            WHERE SCHOLAR_ID=?
        ");

        $stmt->bind_param(
            "sssssi",
            $entrance,
            $fee_no,
            $fee_date,
            $title,
            $guide,
            $scholar_id
        );

        if ($stmt->execute()) {

            // ===== WHEN DATA CHANGED =====
            if ($stmt->affected_rows > 0) {

                // Save button message
                if (isset($_POST['save'])) {
                    $successMsg = "Academic Details Saved Successfully";
                }

                // Save & Next button
                if (isset($_POST['save_next'])) {
                    echo "
<script>
    alert('Scholar Details Updated. Redirecting to next page.');
    window.location.href='Scholar_Dashboard.php?view=update&page=personal';
</script>
";
                    exit;
                }
            }
            // ===== WHEN NO DATA CHANGED =====
            else {
                $errorMsg = "Please first change data, then save.";
            }
        } else {
            $errorMsg = "Error in saving data. Please try again.";
        }


        $stmt->close();
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .main-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    }

    .section-box {
        background: #f8f9fb;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .heading-blue {
        color: #0d6efd;
        font-weight: 700;
    }

    .btn-area {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
    }

    .btn-right {
        display: flex;
        gap: 12px;
    }

    .btn-area button {
        min-width: 130px;
        height: 42px;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #28a745, #007bff);
        color: white;
    }
</style>

<div class="main-card p-3">

    <h4 class="heading-blue mb-3">Academic Details</h4>

    <?php if (!empty($successMsg)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $successMsg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($errorMsg)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $errorMsg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($regProtectErr)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $regProtectErr; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>


    <div class="section-box">

        <form method="post">

            <div class="row">

                <div class="col-md-4">
                    <label>Entrance Sheet Number</label>
                    <input type="text" name="entrance" class="form-control"
                        value="<?php echo $_POST['entrance'] ?? $data['ENTRANCE_SHEET_NO']; ?>">
                    <small class="text-danger"><?php echo $entranceErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Fee Receipt No</label>
                    <input type="text" name="fee_no" class="form-control"
                        value="<?php echo $_POST['fee_no'] ?? $data['FEE_RECEIPT_NO']; ?>">
                    <small class="text-danger"><?php echo $feeErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Fee Receipt Date</label>
                    <input type="date" name="fee_date" class="form-control"
                        value="<?php echo $_POST['fee_date'] ?? $data['FEE_RECEIPT_DATE']; ?>">
                    <small class="text-danger"><?php echo $feeDateErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Registration Number</label>
                    <input type="text" name="regno" class="form-control"
                        value="<?php echo $data['SCHOLAR_REGISTRATION_NUMBER']; ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>Registration Date</label>
                    <input type="date" name="regdate" class="form-control"
                        value="<?php echo $data['REGISTRATION_DATE']; ?>" readonly>
                </div>

                <div class="col-md-12">
                    <label>Full Title of Proposed Research</label>
                    <input type="text" name="title" class="form-control"
                        value="<?php echo $_POST['title'] ?? $data['RESEARCH_TITLE']; ?>">
                    <small class="text-danger"><?php echo $titleErr ?? ""; ?></small>
                </div>

                <?php
                $facultynamefecth = "Select FACULTY_NAME from faculty_available where FACULTY_ID= ?";
                $facstmt = $con->prepare($facultynamefecth);
                $facstmt->bind_param("i", $data['FACULTY_ID']);
                $facstmt->execute();
                $facultyname = $facstmt->get_result()->fetch_array()['FACULTY_NAME'];
                ?>

                <div class="col-md-6">
                    <label>Faculty</label>
                    <input type="text" class="form-control"
                        value="<?php echo $facultyname; ?> " readonly>
                </div>

                <?php
                $subjectnamefecth = "Select SUBJECT_NAME from subject_available where SUBJECT_ID= ?";
                $substmt = $con->prepare($subjectnamefecth);
                $substmt->bind_param("i", $data['SUBJECT_ID']);
                $substmt->execute();
                $subjectname = $substmt->get_result()->fetch_array()['SUBJECT_NAME'];

                ?>

                <div class="col-md-6">
                    <label>Subject</label>
                    <input type="text" class="form-control"
                        value="<?php echo $subjectname; ?>" readonly>
                </div>

                <div class="col-md-12">
                    <label>Name & Address of Guide</label>
                    <textarea name="guide" class="form-control"><?php
                                                                echo $_POST['guide'] ?? ($data['GUIDE_NAME_ADDRESS'] ?? '');
                                                                ?></textarea>
                    <small class="text-danger"><?php echo $guideErr ?? ""; ?></small>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="btn-area">

                <!-- BACK DISABLED -->
                <button type="button" class="btn btn-secondary" disabled>Back</button>

                <div class="btn-right">

                    <button type="submit" name="save" class="btn btn-success">
                        Save
                    </button>

                    <button type="submit" name="save_next" class="btn btn-outline-success">
                        Save & Next
                    </button>

                    <!-- NEXT WITHOUT SAVE -->
                    <button type="button"
                        class="btn btn-primary"
                        onclick="window.location='Scholar_Dashboard.php?view=update&page=personal'">
                        Next
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>