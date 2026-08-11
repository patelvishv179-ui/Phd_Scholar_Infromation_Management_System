<?php

$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
$ps->bind_param("s", $_SESSION['email']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

$_SESSION['id'] = $scholar_id;

$errors = [];

/* ================= VALIDATIONS ================= */
include "Assets/PHPIncludes/Exam_Passed_Validation.php";
include "Assets/PHPIncludes/University_Validation.php";
include "Assets/PHPIncludes/School_Collge_Validation.php";
include "Assets/PHPIncludes/Divison_Validation.php";
include "Assets/PHPIncludes/Percentage_Validation.php";
include "Assets/PHPIncludes/Year_Validation.php";
include "Assets/PHPIncludes/Subject_Offered_Validation.php";
include "Assets/PHPIncludes/No_Of_Attempts_Validation.php";
/* =============================================== */


/* ================= SAVE / UPDATE ================= */
if (isset($_POST['add']) || isset($_POST['update']) || isset($_POST['save_next'])) {

    $exam   = trim($_POST['exam_passed'] ?? '');
    $board  = trim($_POST['university_board'] ?? '');
    $school = trim($_POST['school_college'] ?? '');

    $division = trim($_POST['division'] ?? '');
    $percent  = trim($_POST['percentage'] ?? '');
    $div_percent = $division . " - " . $percent . "%";

    $year   = trim($_POST['year_of_passing'] ?? '');
    $sub    = trim($_POST['subject_offered'] ?? '');
    $att    = trim($_POST['no_of_attempts'] ?? '');

    // ---------- UPDATE ----------
    if (isset($_POST['update']) && empty($errors)) {

        $eid = $_POST['eid'];

        $stmt = $con->prepare("
            UPDATE scholar_education_details SET
            EXAM_PASSED=?,
            UNIVERSITY_BOARD=?,
            SCHOOL_COLLEGE=?,
            DIVISION_PERCENTAGE=?,
            YEAR_OF_PASSING=?,
            SUBJECT_OFFERED=?,
            NO_OF_ATTEMPTS=?
            WHERE EDUCATION_ID=?
        ");

        $stmt->bind_param(
            "ssssissi",
            $exam,
            $board,
            $school,
            $div_percent,
            $year,
            $sub,
            $att,
            $eid
        );

        if ($stmt->execute()) {
            header("Location: Scholar_Dashboard.php?view=update&page=education&updated=1");
            exit;
        }
    }

    // ---------- INSERT ----------
    if ((isset($_POST['add']) || isset($_POST['save_next'])) && empty($errors)) {

        $stmt = $con->prepare("
            INSERT INTO scholar_education_details
            (SCHOLAR_ID, EXAM_PASSED, UNIVERSITY_BOARD, SCHOOL_COLLEGE,
             DIVISION_PERCENTAGE, YEAR_OF_PASSING,
             SUBJECT_OFFERED, NO_OF_ATTEMPTS)
            VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "issssisi",
            $scholar_id,
            $exam,
            $board,
            $school,
            $div_percent,
            $year,
            $sub,
            $att
        );

        if ($stmt->execute()) {

            if (isset($_POST['save_next'])) {
                header("Location: Scholar_Dashboard.php?view=update&page=experience");
            } else {
                header("Location: Scholar_Dashboard.php?view=update&page=education&added=1");
            }
            exit;
        }
    }
}

/* ================= DELETE ================= */
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($con, "DELETE FROM scholar_education_details WHERE EDUCATION_ID='$id'");
    header("Location: Scholar_Dashboard.php?view=update&page=education&deleted=1");
    exit;
}

/* ================= EDIT FETCH ================= */
$editRow = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($con, "SELECT * FROM scholar_education_details WHERE EDUCATION_ID='$id'");
    $editRow = mysqli_fetch_assoc($res);
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .btn-gradient {
        background: linear-gradient(90deg, #28a745, #007bff);
        color: white;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #10ee40, #076fde);
        color: white;
    }
</style>

<div class="main-card p-3">

    <h5 class="text-primary mb-3">Education Details</h5>

    <!-- ALERTS WITH CLOSE -->
    <?php if (isset($_GET['added'])) { ?>
        <div class="alert alert-success alert-dismissible fade show">
            Record Added Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (isset($_GET['updated'])) { ?>
        <div class="alert alert-success alert-dismissible fade show">
            Record Updated Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (isset($_GET['deleted'])) { ?>
        <div class="alert alert-success alert-dismissible fade show">
            Record Deleted Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err) { ?>
                <div><?= htmlspecialchars($err); ?></div>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- ================= FORM ================= -->
    <form method="post">

        <input type="hidden" name="eid"
            value="<?= $editRow['EDUCATION_ID'] ?? '' ?>">

        <div class="row g-2">

            <div class="col-md-3">
                <label>Exam Passed</label>
                <input type="text" name="exam_passed" id="exam_passed" class="form-control"
                    value="<?= htmlspecialchars($_POST['exam_passed'] ?? $editRow['EXAM_PASSED'] ?? '') ?>">
                <small id="examError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-3">
                <label>University / Board</label>
                <input type="text" name="university_board" id="university_board" class="form-control"
                    value="<?= htmlspecialchars($_POST['university_board'] ?? $editRow['UNIVERSITY_BOARD'] ?? '') ?>">
                <small id="uniError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-3">
                <label>School / College</label>
                <input type="text" name="school_college" id="school_college" class="form-control"
                    value="<?= htmlspecialchars($_POST['school_college'] ?? $editRow['SCHOOL_COLLEGE'] ?? '') ?>">
                <small id="schoolError" style="color:red; display:none;"></small>
            </div>

            <?php
            $div = "";
            $per = "";
            if (!empty($editRow['DIVISION_PERCENTAGE'])) {
                list($div, $per) = explode("-", $editRow['DIVISION_PERCENTAGE']);
                $per = str_replace("%", "", $per);
            }
            ?>

            <div class="col-md-2">
                <label>Division</label>
                <input type="text" name="division" id="division" class="form-control"
                    value="<?= htmlspecialchars($_POST['division'] ?? trim($div) ?? '') ?>">
                <small id="divError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-2">
                <label>Percentage</label>
                <input type="text" name="percentage" id="percentage" class="form-control"
                    value="<?= htmlspecialchars($_POST['percentage'] ?? trim($per) ?? '') ?>">
                <small id="perError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-2">
                <label>Year</label>
                <input type="text" name="year_of_passing" id="year_of_passing" class="form-control"
                    value="<?= htmlspecialchars($_POST['year_of_passing'] ?? $editRow['YEAR_OF_PASSING'] ?? '') ?>">
                <small id="yearError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-3">
                <label>Subject Offered</label>
                <input type="text" name="subject_offered" id="subject_offered" class="form-control"
                    value="<?= htmlspecialchars($_POST['subject_offered'] ?? $editRow['SUBJECT_OFFERED'] ?? '') ?>">
                <small id="subError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-2">
                <label>No. of Attempts</label>
                <input type="text" name="no_of_attempts" id="no_of_attempts" class="form-control"
                    value="<?= htmlspecialchars($_POST['no_of_attempts'] ?? $editRow['NO_OF_ATTEMPTS'] ?? '') ?>">
                <small id="attemptError" style="color:red; display:none;"></small>
            </div>

        </div>

        <hr>

        <div class="d-flex justify-content-between">

            <!-- BACK -->
            <button type="button" class="btn btn-secondary"
                onclick="window.location='Scholar_Dashboard.php?view=update&page=personal'">
                Back
            </button>


            <div>

                <?php if ($editRow) { ?>

                    <!-- EDIT MODE -->
                    <button type="submit" name="update" class="btn btn-success">Update</button>

                    <a href="Scholar_Dashboard.php?view=update&page=education"
                        class="btn btn-danger">Cancel</a>

                <?php } else { ?>

                    <!-- NORMAL MODE -->
                    <button type="submit" name="add" class="btn btn-success">Save</button>

                    <button type="submit"
                        name="save_next"
                        class="btn btn-outline-success"
                        onclick="return confirmSaveNext();">
                        Save & Next
                    </button>


                    <button type="button" class="btn btn-primary"
                        onclick="window.location='Scholar_Dashboard.php?view=update&page=experience'">
                        Next
                    </button>


                <?php } ?>

            </div>

        </div>

    </form>

    <hr>

    <!-- ================= TABLE ================= -->

    <table class="table table-bordered table-striped">

        <tr class="table-dark">
            <th>Exam</th>
            <th>Board</th>
            <th>School</th>
            <th>Division & %</th>
            <th>Year</th>
            <th>Subject</th>
            <th>Attempts</th>
            <th width="140">Action</th>
        </tr>

        <?php
        $res = mysqli_query(
            $con,
            "SELECT * FROM scholar_education_details WHERE SCHOLAR_ID='$scholar_id'"
        );
        while ($row = mysqli_fetch_assoc($res)) {
        ?>

            <tr>
                <td><?= $row['EXAM_PASSED']; ?></td>
                <td><?= $row['UNIVERSITY_BOARD']; ?></td>
                <td><?= $row['SCHOOL_COLLEGE']; ?></td>
                <td><?= $row['DIVISION_PERCENTAGE']; ?></td>
                <td><?= $row['YEAR_OF_PASSING']; ?></td>
                <td><?= $row['SUBJECT_OFFERED']; ?></td>
                <td><?= $row['NO_OF_ATTEMPTS']; ?></td>

                <td class="text-center">

                    <a href="Scholar_Dashboard.php?view=update&page=education&edit=<?= $row['EDUCATION_ID']; ?>"
                        class="btn btn-sm btn-warning">Edit</a>

                    <a href="Scholar_Dashboard.php?view=update&page=education&del=<?= $row['EDUCATION_ID']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this record?')">Delete</a>

                </td>
            </tr>

        <?php } ?>

    </table>

</div>

<script>
function confirmSaveNext(){

    return confirm(
        "Education Detail Saved Successfully.\nRedirecting to next page."
    );

}
</script>
