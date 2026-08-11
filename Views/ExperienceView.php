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


<div class="card p-4 shadow">

    <h4 class="text-primary mb-3">Experience Details</h4>

    <!-- Needd for session redirect -->

    <?php if (!empty($_SESSION['success'])) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php unset($_SESSION['success']); } ?>

<?php if (!empty($_SESSION['error'])) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php unset($_SESSION['error']); } ?>

<!-- Alag chhe nicho -->

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
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
                    value="<?= $editData['PAY_SCALE'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <label>Total Emolument</label>
                <input type="number" step="0.01" name="total_emolument" class="form-control"
                    value="<?= $editData['TOTAL_EMOLUMENT'] ?? '' ?>" required>
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
                <select name="level_id" class="form-select" required>
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
                <select name="category_id" class="form-select" required>
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
        <div class="btn-area">

            <!-- Back -->
            <button type="submit" name="exp_back_btn" class="btn btn-secondary">
                Back
            </button>

            <div class="btn-right">

                <?php if (!empty($editData)) { ?>

                    <!-- EDIT MODE -->
                    <button type="submit" name="exp_update" class="btn btn-success">
                        Update
                    </button>

                    <button type="submit" name="exp_cancel" class="btn btn-danger">
                        Cancel
                    </button>

                <?php } else { ?>

                    <!-- NORMAL MODE -->
                    <button type="submit" name="exp_save" class="btn btn-success">
                        Save
                    </button>

                    <button type="submit" name="exp_save_next"
                        class="btn btn-outline-success"
                        formnovalidate>
                        Save & Preview
                    </button>

                    <button type="submit" name="exp_next" class="btn btn-primary"
                        onclick="return confirm('Go without saving?')">
                        Next
                    </button>

                <?php } ?>

            </div>

        </div>

    </form>

    <hr>


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

    if (mysqli_num_rows($res) > 0) {
    ?>

        <h5 class="text-primary mt-4">Added Experience</h5>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Employee</th>
                    <th>Post</th>
                    <th>Pay Scale</th>
                    <th>Total Emolument</th>
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
                while ($row = mysqli_fetch_assoc($res)) {

                    $total_exp = !empty($row['TOTAL_EXPERIENCE']) ? $row['TOTAL_EXPERIENCE'] : "0.0";
                    $parts = explode(".", $total_exp);

                    $years  = $parts[0] ?? 0;
                    $months = $parts[1] ?? 0;

                    echo "<tr>
<td>{$row['EMPLOYEE_NAME_ADDRESS']}</td>
<td>{$row['POST_HELD']}</td>
<td>{$row['PAY_SCALE']}</td>
<td>₹{$row['TOTAL_EMOLUMENT']}</td>
<td>{$row['NATURE_NAME']}</td>
<td>{$row['LEVEL_NAME']}</td>
<td>{$row['CATEGORY_NAME']}</td>
<td>{$row['SERVICE_FROM']}</td>
<td>{$row['SERVICE_TO']}</td>
<td>$years</td>
<td>$months</td>
<td>
    <a href='Complete_Profile.php?view=update&page=experience&edit_id={$row['EXPERIENCE_ID']}' class='btn btn-sm btn-warning mb-1'>Edit</a>
    <a href='Complete_Profile.php?view=update&page=experience&delete_id={$row['EXPERIENCE_ID']}' onclick=\"return confirm('Are you sure?')\" class='btn btn-sm btn-danger'>Delete</a>
</td>
</tr>";
                }
                ?>
            </tbody>
        </table>

    <?php
    } // end if
    ?>


</div>



<!-- ================= PERFECT JS CALC ================= -->
<script>
    function calcExp() {

        let f = document.getElementById("from").value;
        let t = document.getElementById("to").value;

        if (f && t) {

            let start = new Date(f);
            let end = new Date(t);

            if (end < start) {
                alert("Invalid dates end date cannot be before start date");
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

    document.getElementById("from").addEventListener("change", calcExp);
    document.getElementById("to").addEventListener("change", calcExp);
</script>