<style>

    .btn-area {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
    }

    .btn-right {
        display: flex;
        gap: 10px;
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
    <form method="post" onsubmit="return validateForm(event)">

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
                    value="<?= htmlspecialchars($_POST['university_board'] ?? $editRow['UNIVERSITY_BOARD'] ?? '') ?>" >
                <small id="uniError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-3">
                <label>School / College</label>
                <input type="text" name="school_college" id="school_college" class="form-control"
                    value="<?= htmlspecialchars($_POST['school_college'] ?? $editRow['SCHOOL_COLLEGE'] ?? '') ?>" >
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
                    value="<?= htmlspecialchars($_POST['subject_offered'] ?? $editRow['SUBJECT_OFFERED'] ?? '') ?>" >
                <small id="subError" style="color:red; display:none;"></small>
            </div>

            <div class="col-md-2">
                <label>No. of Attempts</label>
                <input type="text" name="no_of_attempts" id="no_of_attempts" class="form-control"
                    value="<?= htmlspecialchars($_POST['no_of_attempts'] ?? $editRow['NO_OF_ATTEMPTS'] ?? '') ?>" >
                <small id="attemptError" style="color:red; display:none;"></small>
            </div>

        </div>

        <!-- BUTTONS -->
        <div class="btn-area">

            <button type="submit" formnovalidate class="btn btn-secondary" 
                name="edu_back_btn">
                Back
            </button>

            <div class="btn-right">

                <?php if ($editRow) { ?>

                    <!-- EDIT MODE -->
                    <button type="submit" name="edu_update_btn" class="btn btn-success">Update</button>

                    <button type="submit" name="edu_cancel_btn"
                    class="btn btn-danger"
                        formnovalidate>
                        Cancel
                        </button>

                <?php } else { ?>


                    <button type="submit" name="edu_save_btn" class="btn btn-success">Save</button>

                    <button type="submit"
                        name="edu_saveandnext_btn"
                        class="btn btn-outline-success"
                        onclick="return confirm('Save Details and go to next page?')">
                        Save & Next
                    </button>

                    <button type="submit" class="btn btn-primary"
                        name="edu_next_btn"
                        formnovalidate>
                        Next
                    </button>


                <?php } ?>

            </div>
        </div>

</div>

</form>

<hr>

<!-- ================= TABLE ================= -->
<?php
$res = mysqli_query(
    $con,
    "SELECT * FROM scholar_education_details WHERE SCHOLAR_ID='$scholar_id'"
);

if (mysqli_num_rows($res) > 0) {
?>

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

        <?php while ($edurow = mysqli_fetch_assoc($res)) { ?>
            <tr>
                <td><?= $edurow['EXAM_PASSED']; ?></td>
                <td><?= $edurow['UNIVERSITY_BOARD']; ?></td>
                <td><?= $edurow['SCHOOL_COLLEGE']; ?></td>
                <td><?= $edurow['DIVISION_PERCENTAGE']; ?></td>
                <td><?= $edurow['YEAR_OF_PASSING']; ?></td>
                <td><?= $edurow['SUBJECT_OFFERED']; ?></td>
                <td><?= $edurow['NO_OF_ATTEMPTS']; ?></td>

                <td class="text-center">
                    <a href="Complete_Profile.php?view=update&page=education&edit=<?= $edurow['EDUCATION_ID']; ?>"
                        class="btn btn-sm btn-warning">Edit</a>

                    <a href="Complete_Profile.php?view=update&page=education&del=<?= $edurow['EDUCATION_ID']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this record?')">Delete</a>
                </td>
            </tr>
        <?php } ?>

    </table>

<?php } ?>

</div>

    <!-- Exam Passed Validation -->
    <script src="Assets/JSIncludes/Exam_Passed_Validation.js?v=2"></script>
    <!-- University / Board Validation Script -->
    <script src="Assets/JSIncludes/University_Validation.js"></script>
    <!-- School / College Validation Script -->
    <script src="Assets/JSIncludes/School_Collge_Validation.js"></script>
    <!-- Division Validation Script -->
    <script src="Assets/JSIncludes/Divison_Validation.js"></script>
    <!-- Percentage Validation Script -->
    <script src="Assets/JSIncludes/Percentage_Validation.js"></script>
    <!-- Year of Passing Validation Script -->
    <script src="Assets/JSIncludes/Year_Validation.js"></script>
    <!-- Subejct Validation Script -->
    <script src="Assets/JSIncludes/Subject_Offered_Validation.js"></script>
    <!-- No of Attempts Validation Script -->
    <script src="Assets/JSIncludes/No_Of_Attempts_Validation.js"></script>


<script>
    function validateForm(event) {

    // Kayu button click thayu te melvo
    let btn = event.submitter ? event.submitter.name : "";

    // ❌ Validation skip karvi hoy aa buttons mate
    if (btn === "edu_back_btn" || btn === "edu_next_btn" || btn === "edu_cancel_btn" || btn === "edu_saveandnext_btn") {
        return true;
    }

    // ✅ Validation only SAVE / SAVE&NEXT / UPDATE mate
    let fields = [
        "exam_passed",
        "university_board",
        "school_college",
        "division",
        "percentage",
        "year_of_passing",
        "subject_offered",
        "no_of_attempts"
    ];

    let isValid = true;

    for (let i = 0; i <fields.length; i++) {
        let field = document.getElementById(fields[i]);

        if (!field || field.value.trim() === "") {
            isValid = false;
        }
    }

    if (!isValid) {
        alert("All fields are required");
    }

    return isValid;
}

</script> 