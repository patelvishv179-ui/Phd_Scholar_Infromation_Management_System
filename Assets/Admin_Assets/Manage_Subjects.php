<?php

require_once "Assets/Helpers/flash_message.php";

$editData = null;

if (isset($_POST['edit_btn'])) {

    $id = $_POST['edit_id'];

    $stmt = $con->prepare("SELECT * FROM Subject_Available WHERE SUBJECT_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editData = $stmt->get_result()->fetch_assoc();
}

if (isset($_POST['update_subject'])) {

    $id = $_POST['subject_id'];
    $name = strtoupper(trim($_POST['subject_name']));
    $faculty = intval($_POST['faculty_id']);

    if ($name == "" || $faculty == 0) {

        setMsg("danger", "All Fields Required");
        header("Location: ?view=manage_subjects");
        exit;
    } else {

        // Duplicate check
        $check = $con->prepare("
            SELECT * FROM Subject_Available 
            WHERE SUBJECT_NAME=? AND FACULTY_ID=? AND SUBJECT_ID!=?
        ");
        $check->bind_param("sii", $name, $faculty, $id);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {

            setMsg("danger", "Subject Already Exists");
            header("Location: ?view=manage_subjects");
            exit;
        } else {

            $stmt = $con->prepare("
                UPDATE Subject_Available 
                SET SUBJECT_NAME=?, FACULTY_ID=? 
                WHERE SUBJECT_ID=?
            ");
            $stmt->bind_param("sii", $name, $faculty, $id);

            if ($stmt->execute()) {
                setMsg("success", "Subject Updated successfully");
                header("Location: ?view=manage_subjects");
                exit;
            } else {
                setMsg("danger", "Error Updating Subject");
                header("Location: ?view=manage_subjects");
                exit;
            }
        }
    }
}

// Add Subject

if (isset($_POST['add_subject'])) {

    $name = strtoupper(trim($_POST['subject_name']));
    $faculty = intval($_POST['faculty_id']);

    if ($name == "" || $faculty == 0) {
        setMsg("danger", "All Fields Required");
        header("Location: ?view=manage_subjects");
        exit;
    } else {

        // 🔍 Check duplicate (same subject + same faculty)
        $check = $con->prepare("SELECT * FROM Subject_Available WHERE SUBJECT_NAME = ? AND FACULTY_ID = ?");
        $check->bind_param("si", $name, $faculty);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            setMsg("danger", "Subject Already Exists Try New !");
            header("Location: ?view=manage_subjects");
            exit;
        } else {

            // ✅ Insert
            $stmt = $con->prepare("INSERT INTO Subject_Available (SUBJECT_NAME, FACULTY_ID) VALUES (?,?)");
            $stmt->bind_param("si", $name, $faculty);

            if ($stmt->execute()) {
                setMsg("success", "Subject added successfully");
                header("Location: ?view=manage_subjects");
                exit;
            } else {
                setMsg("danger", "Error Adding Subject");
                header("Location: ?view=manage_subjects");
                exit;
            }
        }
    }
}


// Delete Subject
if (isset($_POST['delete_btn'])) {

    $id = intval($_POST['delete_id']);

    if ($id <= 0) {
        exit;
    }

    $stmt = $con->prepare("DELETE FROM Subject_Available WHERE SUBJECT_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    setMsg("success", "Subject Deleted successfully");
    header("Location: ?view=manage_subjects");
    exit;
}

// Paggination

$limit = 5;

$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;

if ($page < 1) {
    $page = 1;
}

$total_records_query = "
    SELECT COUNT(*) AS TOTAL 
    FROM Subject_Available s
    JOIN Faculty_Available f ON s.FACULTY_ID = f.FACULTY_ID
";
$total_records_result = $con->query($total_records_query);
$row = mysqli_fetch_assoc($total_records_result);
$totalrecords = $row['TOTAL'];

$total_pages = ceil($totalrecords / $limit);

$offset = ($page - 1) * $limit;


?>

<!-- Style of Model -->

<style>
    .modal-backdrop.show {
        backdrop-filter: blur(2px);
        background-color: rgba(0, 0, 0, 0.3);
    }
</style>


<!-- Top Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center bg-light rounded">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-journal-bookmark-fill fs-3 me-2 text-primary"></i>
            Manage Subjects
        </h5>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="bi bi-plus-circle"></i> Add Subject
        </button>

    </div>
</div>

<!-- Edit Subject Model -->

<div class="modal fade <?php if ($editData) echo 'show'; ?>"
    id="editModal"
    style="<?php if ($editData) echo 'display:block;'; ?>" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Subject</h5>
                <a href="" class="btn-close"></a>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <input type="hidden" name="subject_id" value="<?php echo $editData['SUBJECT_ID'] ?? ''; ?>">

                    <div class="mb-3">
                        <label>Subject Name</label>
                        <input type="text" name="subject_name" class="form-control"
                            value="<?php echo $editData['SUBJECT_NAME'] ?? ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Select Faculty</label>
                        <select name="faculty_id" class="form-select" required>

                            <?php
                            $res = $con->query("SELECT * FROM Faculty_Available");
                            while ($f = $res->fetch_assoc()) {

                                $selected = (isset($editData['FACULTY_ID']) && $editData['FACULTY_ID'] == $f['FACULTY_ID']) ? "selected" : "";

                                echo "<option value='{$f['FACULTY_ID']}' $selected>{$f['FACULTY_NAME']}</option>";
                            }
                            ?>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" name="update_subject" class="btn btn-primary w-100">
                        Update Subject
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!--Add Subject Model -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-journal-plus text-primary"></i> Add Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- FORM -->
            <form method="POST">
                <!-- BODY -->
                <div class="modal-body">
                    <!-- Subject Name -->
                    <div class="mb-3">
                        <label class="form-label">Enter Subject</label>
                        <input type="text" name="subject_name" class="form-control" required style="text-transform: uppercase;">
                    </div>
                    <!-- Faculty Dropdown -->
                    <div class="mb-3">
                        <label class="form-label">Select Faculty</label>
                        <select name="faculty_id" class="form-select" required>
                            <option value="">Select Faculty</option>
                            <?php
                            $res = $con->query("SELECT * FROM Faculty_Available");
                            while ($f = $res->fetch_assoc()) {
                                echo "<option value='{$f['FACULTY_ID']}'>{$f['FACULTY_NAME']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="submit" name="add_subject" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($editData) { ?>
    <div class="modal-backdrop show"></div>
<?php } ?>
<!-- Search Bar
<div class="col-md-3">
    <form method="POST" class="mb-3">
        <div class="input-group border border-primary rounded">
            <input type="text" id="searchInput" name="search_query"
                placeholder="Search Subject..."
                class="form-control  border-0 shadow-none py-2">
            <button type="submit" name="search_btn" class="btn btn-primary border-0">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div> -->

<?php include "Assets/Helpers/loader.php"; ?>

<?php
echo $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<table class="table table-bordered table-hover text-center align-middle" style="min-height: 300px;">

    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Subject Name</th>
            <th>Faculty</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        <?php

        // Join Subject + Faculty (Better UI)
        $con = $GLOBALS['con'];

        $stmt = $con->prepare("
SELECT s.SUBJECT_ID, s.SUBJECT_NAME, f.FACULTY_NAME 
FROM Subject_Available s
JOIN Faculty_Available f ON s.FACULTY_ID = f.FACULTY_ID
ORDER BY s.SUBJECT_ID LIMIT ?, ?
");

        $stmt->bind_param("ii", $offset, $limit);

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {

            echo "<tr>

            <td>{$row['SUBJECT_ID']}</td>
            <td>{$row['SUBJECT_NAME']}</td>
            <td>{$row['FACULTY_NAME']}</td>

            <td>

                <!-- EDIT -->
                <form method='post' style='display:inline;'>
    <input type='hidden' name='edit_id' value='{$row['SUBJECT_ID']}'>
    <button type='submit' name='edit_btn' class='btn btn-sm btn-primary'>
        Edit
    </button>
</form>

                <!-- DELETE -->
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='delete_id' value='{$row['SUBJECT_ID']}'>
                    <button type='submit' name='delete_btn' class='btn btn-sm btn-danger'
                        onclick='if(confirm('Delete this subject?')){ showLoader(); return true;} else {return false;}'>
                        Delete
                    </button>
                </form>

            </td>

        </tr>";
        }

        ?>

    </tbody>

</table>

<?php

echo "<div class='d-flex justify-content-center mt-4'>
        <nav>
        <ul class='pagination border border-primary pagination-glow rounded p-2 gap-2 mb-0' style='width:fit-content;'>";

// 🔹 Previous
if ($page > 1) {
    echo "<li class='page-item'>
            <a class='page-link' href='?view=manage_subjects&page=" . ($page - 1) . "' onclick='showLoader()'>Previous</a>
          </li>";
} else {
    echo "<li class='page-item disabled'>
            <span class='page-link'>Previous</span>
          </li>";
}

// 🔹 Page Numbers
for ($i = 1; $i <= $total_pages; $i++) {

    if ($i == $page) {
        echo "<li class='page-item active'>
                <span class='page-link'>$i</span>
              </li>";
    } else {
        echo "<li class='page-item'>
                <a class='page-link' href='?view=manage_subjects&page=$i' onclick='showLoader()'>$i</a>
              </li>";
    }
}

// 🔹 Next
if ($page < $total_pages) {
    echo "<li class='page-item'>
            <a class='page-link' href='?view=manage_subjects&page=" . ($page + 1) . "' onclick='showLoader()'>Next</a>
          </li>";
} else {
    echo "<li class='page-item disabled'>
            <span class='page-link'>Next</span>
          </li>";
}

echo "  </ul>
        </nav>
      </div>";

?>


<!-- Loader -->
<script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>