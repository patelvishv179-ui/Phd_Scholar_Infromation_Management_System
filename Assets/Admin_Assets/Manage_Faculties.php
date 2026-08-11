<?php


require_once "Assets/Helpers/flash_message.php";

if (isset($_POST['add_faculty'])) {

    $name = strtoupper(trim($_POST['faculty_name']));

    if ($name == "") {

                setMsg("danger","Faculty  Name Required!");
                header("Location: ?view=manage_faculties");
                exit;

    } else {

        // Duplicate check
        $check = $con->prepare("SELECT * FROM Faculty_Available WHERE FACULTY_NAME=?");
        $check->bind_param("s", $name);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {

                setMsg("danger", "Faculty Already Exists !");
                header("Location: ?view=manage_faculties");
                exit;

        } else {

            $stmt = $con->prepare("INSERT INTO Faculty_Available (FACULTY_NAME) VALUES (?)");
            $stmt->bind_param("s", $name);

            if ($stmt->execute()) {
                setMsg("success", "Faculty added successfully");
                header("Location: ?view=manage_faculties"); 
                exit;
            } else {
                setMsg("danger", "Error Adding Faculty !");
                header("Location: ?view=manage_faculties");
                exit;
            }
        }
    }
}

$editData = null;

if (isset($_POST['edit_btn'])) {

    $id = $_POST['edit_id'];

    $stmt = $con->prepare("SELECT * FROM Faculty_Available WHERE FACULTY_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editData = $stmt->get_result()->fetch_assoc();
}

if (isset($_POST['update_faculty'])) {

    $id = $_POST['faculty_id'];
    $name = strtoupper(trim($_POST['faculty_name']));

    if ($name == "") {

          setMsg("danger","Faculty  Name Required!");
                header("Location: ?view=manage_faculties");
                exit;

    } else {

    $check = $con->prepare("SELECT * FROM Faculty_Available WHERE FACULTY_NAME=? AND FACULTY_ID!=?");
        $check->bind_param("si", $name, $id);
    $check->execute();
    $res = $check->get_result();

if ($res->num_rows > 0) {
    setMsg("danger", "Faculty already exists");
    header("Location: ?view=manage_faculties");
    exit;
}

        $stmt = $con->prepare("UPDATE Faculty_Available SET FACULTY_NAME=? WHERE FACULTY_ID=?");
        $stmt->bind_param("si", $name, $id);

        if ($stmt->execute()) {
            setMsg("success", "Faculty Updated successfully");
            header("Location: ?view=manage_faculties"); 
            exit;
        } else {
             setMsg("danger", "Error Updating Faculty !");
                header("Location: ?view=manage_faculties");
                exit;
        }
    }
}

if (isset($_POST['delete_btn'])) {

    $id = intval($_POST['delete_id']);

if ($id <= 0) {
    exit;
}

    $stmt = $con->prepare("DELETE FROM Faculty_Available WHERE FACULTY_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

}

//Pagination
$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;


$total_records_query = "SELECT COUNT(*) AS total FROM Faculty_Available";
$total_records_result = $con->query($total_records_query);
$total_records_array = mysqli_fetch_array($total_records_result);
$total_records = $total_records_array['total'];

$total_pages = ceil($total_records / $limit);

if($page < 1){$page = 1;}

$offset = ($page - 1) * $limit;

?>

<style>
.pagination-glow {
    box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
}
    .modal-backdrop.show {
        backdrop-filter: blur(2px);
        background-color: rgba(0, 0, 0, 0.3);
    }
</style>

<!-- ADD FACULTY MODEL -->

<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add Faculty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST">

                <div class="modal-body">
                    <input type="text" name="faculty_name" class="form-control" placeholder="Enter Faculty" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="add_faculty" class="btn btn-success w-100">
                        Add Faculty
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- EDIT MODEL -->

<div class="modal fade <?php if($editData) echo 'show'; ?>" 
     style="<?php if($editData) echo 'display:block;'; ?>" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Faculty</h5>
                <a href="" class="btn-close"></a>
            </div>

            <form method="POST">

                <div class="modal-body">

                    <input type="hidden" name="faculty_id" value="<?php echo $editData['FACULTY_ID'] ?? ''; ?>">

                    <input type="text" name="faculty_name" class="form-control"
                        value="<?php echo $editData['FACULTY_NAME'] ?? ''; ?>" required>

                </div>

                <div class="modal-footer">
                    <button type="submit" name="update_faculty" class="btn btn-primary w-100">
                        Update Faculty
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php if($editData){ ?>
<div class="modal-backdrop show"></div>
<?php } ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center bg-light rounded">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-building fs-3 me-2 text-primary"></i>
            Manage Faculties
        </h5>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
    <i class="bi bi-plus-circle"></i> Add Faculty
</button>

    </div>
</div>


    <!-- Loader -->
    <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>

<?php
echo $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<table class="table table-bordered table-hover text-center align-middle" style="min-height: 300px;">

    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Faculty Name</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    <?php

   $stmt = $con->prepare("SELECT * FROM Faculty_Available ORDER BY FACULTY_ID ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {

        echo "<tr>

            <td>{$row['FACULTY_ID']}</td>
            <td>{$row['FACULTY_NAME']}</td>

            <td>

                <!-- EDIT -->
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='edit_id' value='{$row['FACULTY_ID']}'>
                    <button type='submit' name='edit_btn' class='btn btn-sm btn-primary' onclick='showLoader()'>
                        Edit
                    </button>
                </form>

                <!-- DELETE -->
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='delete_id' value='{$row['FACULTY_ID']}'>
                    <button type='submit' name='delete_btn' class='btn btn-sm btn-danger'
                        onclick='if(confirm('Are you sure?')){ showLoader(); return true;} else {return false;}'>
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
            <a class='page-link' href='?view=manage_faculties&page=".($page-1)."' onclick='showLoader()'>Previous</a>
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
                <a class='page-link' href='?view=manage_faculties&page=$i' onclick='showLoader()'>$i</a>
              </li>";
    }
}

// 🔹 Next
if ($page < $total_pages) {
    echo "<li class='page-item'>
            <a class='page-link' href='?view=manage_faculties&page=".($page+1)."' onclick='showLoader()'>Next</a>
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

<script src="Assets/Helpers/loader.js"></script>