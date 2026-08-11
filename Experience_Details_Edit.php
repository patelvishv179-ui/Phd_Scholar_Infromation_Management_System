<?php

$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
$ps->bind_param("s", $_SESSION['email']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

/* ================= DELETE ================= */
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    mysqli_query($con, "DELETE FROM scholar_experience_details WHERE EXPERIENCE_ID=$id");
    header("Location: Scholar_Dashboard.php?view=update&page=experience");

    exit;
}

/* ================= EDIT LOAD ================= */
$editData = [];
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $q = mysqli_query($con, "SELECT * FROM scholar_experience_details WHERE EXPERIENCE_ID=$id");
    $editData = mysqli_fetch_assoc($q);
}

/* ================= SAVE / UPDATE ================= */
if (isset($_POST['save']) || isset($_POST['save_next'])) {

    $emp_name   = $_POST['emp_name_address'];
    $post_held  = $_POST['post_held'];
    $pay_scale  = $_POST['pay_scale'];
    $emolument  = ($_POST['total_emolument'] == "") ? 0 : $_POST['total_emolument'];

    $nature_id   = intval($_POST['nature_id']);
    $level_id    = ($_POST['level_id'] == "") ? NULL : intval($_POST['level_id']);
    $category_id = ($_POST['category_id'] == "") ? NULL : intval($_POST['category_id']);

    $from  = $_POST['service_from'];
    $to    = $_POST['service_to'];
    $total = $_POST['total_experience'];

    /* ===== UPDATE ===== */
    if ($_POST['exp_id'] != "") {
        $id = intval($_POST['exp_id']);

        $stmt = $con->prepare("
        UPDATE scholar_experience_details SET
        EMPLOYEE_NAME_ADDRESS=?, POST_HELD=?, PAY_SCALE=?, TOTAL_EMOLUMENT=?,
        NATURE_ID=?, LEVEL_ID=?, CATEGORY_ID=?,
        SERVICE_FROM=?, SERVICE_TO=?, TOTAL_EXPERIENCE=?
        WHERE EXPERIENCE_ID=?");

        $stmt->bind_param(
            "sssdiiisssi",
            $emp_name,
            $post_held,
            $pay_scale,
            $emolument,
            $nature_id,
            $level_id,
            $category_id,
            $from,
            $to,
            $total,
            $id
        );
    }
    /* ===== INSERT ===== */ else {
        $stmt = $con->prepare("
        INSERT INTO scholar_experience_details
        (SCHOLAR_ID,EMPLOYEE_NAME_ADDRESS,POST_HELD,PAY_SCALE,TOTAL_EMOLUMENT,
         NATURE_ID,LEVEL_ID,CATEGORY_ID,SERVICE_FROM,SERVICE_TO,TOTAL_EXPERIENCE)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "isssdiiisss",
            $scholar_id,
            $emp_name,
            $post_held,
            $pay_scale,
            $emolument,
            $nature_id,
            $level_id,
            $category_id,
            $from,
            $to,
            $total
        );
    }

    if (!$stmt->execute()) {
        die("DB Error : " . $stmt->error);
    }

    if (isset($_POST['save_next'])) {
        header("Location: Scholar_Dashboard.php?view=update&page=summary");
        exit;
    }

    $msg = "Experience Saved Successfully";
}
?>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="card p-4 shadow">

    <h4 class="text-primary mb-3">Experience Details</h4>

    <?php if (!empty($msg)) { ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php } ?>

    <form method="post">

        <input type="hidden" name="exp_id" value="<?= $editData['EXPERIENCE_ID'] ?? '' ?>">

        <div class="row g-3">

            <div class="col-md-6">
                <label>Employee Name & Address</label>
                <input type="text" name="emp_name_address" class="form-control"
                    value="<?= $editData['EMPLOYEE_NAME_ADDRESS'] ?? '' ?>" required>
            </div>

            <div class="col-md-6">
                <label>Post Held</label>
                <input type="text" name="post_held" class="form-control"
                    value="<?= $editData['POST_HELD'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label>Pay Scale</label>
                <input type="text" name="pay_scale" class="form-control"
                    value="<?= $editData['PAY_SCALE'] ?? '' ?>">
            </div>

            <div class="col-md-4">
                <label>Total Emolument</label>
                <input type="number" step="0.01" name="total_emolument" class="form-control"
                    value="<?= $editData['TOTAL_EMOLUMENT'] ?? '' ?>">
            </div>

            <div class="col-md-4">
                <label>Nature of Work</label>
                <select name="nature_id" class="form-select" required>
                    <option value="">Select</option>
                    <?php
                    $q = mysqli_query($con, "SELECT * FROM nature_of_work_master");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = (($editData['NATURE_ID'] ?? '') == $r['NATURE_ID']) ? "selected" : "";
                        echo "<option value='{$r['NATURE_ID']}' $sel>{$r['NATURE_NAME']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>UG / PG</label>
                <select name="level_id" class="form-select">
                    <option value="">None</option>
                    <?php
                    $q = mysqli_query($con, "SELECT * FROM teaching_level_master");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = (($editData['LEVEL_ID'] ?? '') == $r['LEVEL_ID']) ? "selected" : "";
                        echo "<option value='{$r['LEVEL_ID']}' $sel>{$r['LEVEL_NAME']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>Research / Industry</label>
                <select name="category_id" class="form-select">
                    <option value="">None</option>
                    <?php
                    $q = mysqli_query($con, "SELECT * FROM experience_category_master");
                    while ($r = mysqli_fetch_assoc($q)) {
                        $sel = (($editData['CATEGORY_ID'] ?? '') == $r['CATEGORY_ID']) ? "selected" : "";
                        echo "<option value='{$r['CATEGORY_ID']}' $sel>{$r['CATEGORY_NAME']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>From</label>
                <input type="date" id="from" name="service_from" class="form-control"
                    value="<?= $editData['SERVICE_FROM'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label>To</label>
                <input type="date" id="to" name="service_to" class="form-control"
                    value="<?= $editData['SERVICE_TO'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label>Total Experience (Years.Months)</label>
                <input type="text" id="total_experience" name="total_experience"
                    value="<?= $editData['TOTAL_EXPERIENCE'] ?? '' ?>"
                    class="form-control" readonly>
            </div>

        </div>

        <div class="mt-3 d-flex justify-content-between">
            <button type="button" class="btn btn-secondary"
                onclick="location.href='Scholar_Dashboard.php?view=update&page=education'">Back</button>

            <div>
                <button type="submit" name="save" class="btn btn-success">Save</button>
                <button type="submit" name="save_next" class="btn btn-outline-success">Save & Next</button>
                            <button type="button" class="btn btn-primary"
                    onclick="window.location='Scholar_Dashboard.php?view=update&page=education'">
                    Next
                </button>   </div>
        </div>

    </form>

    <hr>

    <h5 class="text-primary mt-4">Added Experience</h5>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employee</th>
                <th>Post</th>
                <th>Nature</th>
                <th>UG/PG</th>
                <th>Research / Industry</th>
                <th>From</th>
                <th>To</th>
                <th>Years</th>
                <th>Months</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $res = mysqli_query($con, "
SELECT e.*,
n.NATURE_NAME,
l.LEVEL_NAME,
c.CATEGORY_NAME
FROM scholar_experience_details e
LEFT JOIN nature_of_work_master n ON e.NATURE_ID=n.NATURE_ID
LEFT JOIN teaching_level_master l ON e.LEVEL_ID=l.LEVEL_ID
LEFT JOIN experience_category_master c ON e.CATEGORY_ID=c.CATEGORY_ID
WHERE e.SCHOLAR_ID='$scholar_id'
ORDER BY e.EXPERIENCE_ID DESC
");

            while ($row = mysqli_fetch_assoc($res)) {

                // Split years & months
                $parts = explode(".", $row['TOTAL_EXPERIENCE']);
                $years  = $parts[0] ?? 0;
                $months = $parts[1] ?? 0;

                echo "<tr>
<td>{$row['EMPLOYEE_NAME_ADDRESS']}</td>
<td>{$row['POST_HELD']}</td>
<td>{$row['NATURE_NAME']}</td>
<td>{$row['LEVEL_NAME']}</td>
<td>{$row['CATEGORY_NAME']}</td>
<td>{$row['SERVICE_FROM']}</td>
<td>{$row['SERVICE_TO']}</td>
<td>$years</td>
<td>$months</td>
<td>
    <a href='Scholar_Dashboard.php?view=update&page=experience&edit_id={$row['EXPERIENCE_ID']}'
       class='btn btn-sm btn-warning mb-1'>
       Edit
    </a> 

    <a href='Scholar_Dashboard.php?view=update&page=experience&delete_id={$row['EXPERIENCE_ID']}'
       onclick=\"return confirm('Are you sure you want to delete this record?')\"
       class='btn btn-sm btn-danger'>
       Delete
    </a>
</td>
</tr>";
            }
            ?>
        </tbody>
    </table>


</div>

<!-- ================= PERFECT JS CALC ================= -->
<script>
    function calcExperience() {

        let f = document.getElementById("from").value;
        let t = document.getElementById("to").value;

        if (f && t) {

            let start = new Date(f);
            let end = new Date(t);

            if (end < start) {
                alert("Service To must be greater than Service From");
                document.getElementById("to").value = "";
                return;
            }

            let years = end.getFullYear() - start.getFullYear();
            let months = end.getMonth() - start.getMonth();

            if (end.getDate() < start.getDate()) {
                months--;
            }

            if (months < 0) {
                years--;
                months += 12;
            }

            document.getElementById("total_experience").value =
                years + "." + (months < 10 ? "0" + months : months);
        }
    }

    document.getElementById("from").addEventListener("change", calcExperience);
    document.getElementById("to").addEventListener("change", calcExperience);
</script>