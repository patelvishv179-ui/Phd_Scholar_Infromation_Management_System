<?php

require_once "Assets/Helpers/sendApprovalMail.php";
require_once "Assets/Helpers/sendRejectMail.php";
require_once "Assets/Helpers/flash_message.php";

// APPROVE
if (isset($_POST['approve_btn'])) {

    $id = intval($_POST['approve_id']);

if ($id <= 0) {
    exit;
}

    // 🔹 Email & Name fetch 
    $stmt = $con->prepare("SELECT EMAIL, SCHOLAR_NAME FROM Scholar_Master WHERE SCHOLAR_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    $email = $data['EMAIL'];
    $name  = $data['SCHOLAR_NAME'];

    // 🔹 Approve update
    $stmt = $con->prepare("UPDATE Scholar_Master SET APPROVE = 1 WHERE SCHOLAR_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 🔹 Send Mail
    sendApprovalMail($email, $name);

    setMsg("success", "Scholar Approved Successfully");
    header("Location: ?view=manage_scholar_onboardings");
    exit;

}

// REJECT
// REJECT
if (isset($_POST['reject_btn'])) {

    $id = intval($_POST['reject_id']);

if ($id <= 0) {
    exit;
}

    // 🔹 Email & Name fetch કરો
    $stmt = $con->prepare("SELECT EMAIL, SCHOLAR_NAME FROM Scholar_Master WHERE SCHOLAR_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    $email = $data['EMAIL'];
    $name  = $data['SCHOLAR_NAME'];

    // 🔹 Update status
    $stmt = $con->prepare("UPDATE Scholar_Master SET APPROVE = 2 WHERE SCHOLAR_ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 🔹 Send Reject Mail
    sendRejectMail($email, $name);

    setMsg("danger", "Scholar Rejected Successfully");
    header("Location: ?view=manage_scholar_onboardings");
    exit;

}

// Paggination

$limit = 5;

$page = (isset($_GET['page'])) ? intval($_GET['page']) : 1;

if($page < 1){
    $page = 1;
}

$status = 0;
$trstmt = $con->prepare("
SELECT COUNT(*) AS TOTAL 
FROM Scholar_Master s
LEFT JOIN Faculty_Available f ON s.FACULTY_ID = f.FACULTY_ID
LEFT JOIN Subject_Available sub ON s.SUBJECT_ID = sub.SUBJECT_ID
WHERE s.APPROVE = ?
");
$trstmt->bind_param("i", $status);
$trstmt->execute();
$result = $trstmt->get_result();
$row = mysqli_fetch_array($result);
$totalrecords =  $row['TOTAL'];

$total_pages = ceil($totalrecords / $limit);

$offset = ($page - 1) * $limit;

?>




<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center bg-light rounded">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-check-fill fs-3 me-2 text-primary"></i>
            Scholar Onboarding
        </h5>

    </div>
</div>

<!-- loader.php -->
<?php include "Assets/Helpers/loader.php"; ?>

<?php
echo $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<table class="table table-bordered table-hover text-center align-middle" style="min-height: 300px;">

    <thead class="table-light">
        <tr>
            <th>Registration Number</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Faculty</th>
            <th>Subject</th>
            <th>Registration Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

    <?php

    // Only Pending Scholars (APPROVE = 0)
    $con = $GLOBALS['con'];
   $stmt = $con->prepare("
SELECT 
    s.SCHOLAR_ID,
    s.SCHOLAR_NAME,
    s.EMAIL,
    s.MOBILE,
    s.SCHOLAR_REGISTRATION_NUMBER,
    s.REGISTRATION_DATE,
    f.FACULTY_NAME,
    sub.SUBJECT_NAME
FROM Scholar_Master s
LEFT JOIN Faculty_Available f ON s.FACULTY_ID = f.FACULTY_ID
LEFT JOIN Subject_Available sub ON s.SUBJECT_ID = sub.SUBJECT_ID
WHERE s.APPROVE = 0
ORDER BY s.SCHOLAR_ID DESC
LIMIT ?, ?
");

$stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        echo "<tr>

            <td>{$row['SCHOLAR_REGISTRATION_NUMBER']}</td>
            <td>{$row['SCHOLAR_NAME']}</td>
            <td>{$row['EMAIL']}</td>
            <td>{$row['MOBILE']}</td>
            <td>{$row['FACULTY_NAME']}</td>
            <td>{$row['SUBJECT_NAME']}</td>
            <td>{$row['REGISTRATION_DATE']}</td>

            <td>

                <!-- APPROVE BUTTON -->
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='approve_id' value='{$row['SCHOLAR_ID']}'>
                    <button type='submit' onclick='showLoader()' name='approve_btn' class='btn btn-sm btn-success'>
                        <i class='bi bi-check-circle'></i> Approve
                    </button>
                </form>

                <!-- REJECT BUTTON -->
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='reject_id' value='{$row['SCHOLAR_ID']}'>
                    <button type='submit' onclick='showLoader()' name='reject_btn' class='btn btn-sm btn-danger'>
                        <i class='bi bi-x-circle'></i> Reject
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
            <a class='page-link' href='?view=manage_scholar_onboardings&page=".($page-1)."' onclick='showLoader()'>Previous</a>
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
                <a class='page-link' href='?view=manage_scholar_onboardings&page=$i' onclick='showLoader()'>$i</a>
              </li>";
    }
}

// 🔹 Next
if ($page < $total_pages) {
    echo "<li class='page-item'>
            <a class='page-link' href='?view=manage_scholar_onboardings&page=".($page+1)."' onclick='showLoader()'>Next</a>
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