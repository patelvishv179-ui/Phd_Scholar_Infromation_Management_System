<?php
$con = mysqli_connect("localhost","root","","PHD_HNGU");
if(!$con){
    die("Database Connection Failed");
}

/* ===== GET REG NO FROM QR ===== */
if(!isset($_GET['reg'])){
    die("Invalid QR Code");
}
$reg = $_GET['reg'];

$stmt = $con->prepare("

SELECT 
s.*,
p.*,
f.FACULTY_NAME,
sub.SUBJECT_NAME

FROM Scholar_Master s

LEFT JOIN Scholar_Personal_Details p
ON s.SCHOLAR_ID = p.SCHOLAR_ID

LEFT JOIN Faculty_Available f
ON s.FACULTY_ID = f.FACULTY_ID

LEFT JOIN Subject_Available sub
ON s.SUBJECT_ID = sub.SUBJECT_ID

WHERE s.SCHOLAR_REGISTRATION_NUMBER = ?

");

$stmt->bind_param("s",$reg);
$stmt->execute();
$personal = $stmt->get_result()->fetch_assoc();

if(!$personal){
    die("Scholar Not Found");
}
$scholar_id = $personal['SCHOLAR_ID'];

/* ===== EDUCATION ===== */
$edu = $con->prepare("SELECT * FROM Scholar_Education_Details WHERE SCHOLAR_ID=?");
$edu->bind_param("i",$scholar_id);
$edu->execute();
$eduRes = $edu->get_result();

/* ===== EXPERIENCE ===== */
$exp = $con->prepare("
SELECT e.*,n.NATURE_NAME,l.LEVEL_NAME,c.CATEGORY_NAME
FROM Scholar_Experience_Details e
LEFT JOIN Nature_Of_Work_Master n 
ON e.NATURE_ID=n.NATURE_ID
LEFT JOIN Teaching_Level_Master l 
ON e.LEVEL_ID=l.LEVEL_ID
LEFT JOIN Experience_Category_Master c 
ON e.CATEGORY_ID=c.CATEGORY_ID
WHERE e.SCHOLAR_ID=?
"); 
$exp->bind_param("i",$scholar_id);
$exp->execute();
$expRes = $exp->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scholar Profile</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f2f4f8;
    margin:0;
    padding:0;
}
.wrapper{
    max-width:1000px;
    margin:40px auto;
    padding:20px;
}

/* HEADER */
.header{
    background:#2563eb;
    color:white;
    padding:20px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}
.photo{
    width:120px;
    height:120px;
    border-radius:50%;
    overflow:hidden;
    background:white;
}
.photo img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* CARD */
.card-box{
    background:white;
    margin-top:30px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
.card-title{
    padding:12px 18px;
    font-weight:600;
    border-bottom:1px solid #ddd;
    background:#f8f9fa;
}
.card-body{
    padding:18px;
}

/* TABLE */
.table{
    margin-bottom:0;
}
th{
    width:30%;
    background:#f8f9fa;
}

/* MOBILE */
@media(max-width:768px){
    .header{
        flex-direction:column;
        text-align:center;
    }
    th{
        width:45%;
    }
}
</style>
</head>

<body>

<div class="wrapper">

<!-- HEADER -->
<div class="header">

<div class="photo">
<?php
$img=$personal['SCHOLAR_IMAGEURL']??"";
if($img!="" && file_exists($img)){
    echo "<img src='$img'>";
}else{
    echo "<img src='https://cdn-icons-png.flaticon.com/512/847/847969.png'>";
}
?>
</div>

<div>
<h3><?= $personal['SCHOLAR_NAME'] ?></h3>
<p><i class="bi bi-envelope"></i> <?= $personal['EMAIL'] ?></p>
<p><i class="bi bi-phone"></i> <?= $personal['MOBILE'] ?></p>
</div>

</div>

<!-- REGISTRATION -->
<div class="card-box">
<div class="card-title">Registration Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr><th>Registration No</th><td><?= $personal['SCHOLAR_REGISTRATION_NUMBER'] ?></td></tr>
<tr><th>Registration Date</th><td><?= $personal['REGISTRATION_DATE'] ?></td></tr>
<tr><th>Faculty</th><td><?= $personal['FACULTY_NAME'] ?></td></tr>
<tr><th>Subject</th><td><?= $personal['SUBJECT_NAME'] ?></td></tr>
</table>
</div>
</div>
</div>

<!-- PERSONAL -->
<div class="card-box">
<div class="card-title">Personal Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr><th>Gender</th><td><?= $personal['GENDER'] ?: '-' ?></td></tr>
<tr><th>DOB</th><td><?= $personal['DOB'] ?: '-' ?></td></tr>
<tr><th>Nationality</th><td><?= $personal['NATIONALITY'] ?: '-' ?></td></tr>
<tr><th>Aadhar</th><td><?= $personal['ADHAR_NUMBER'] ?: '-' ?></td></tr>
<tr><th>Permanent Address</th><td><?= $personal['SCHOLAR_PERM_ADDRESS'] ?: '-' ?></td></tr>
<tr><th>Admission Category</th><td><?= $personal['ADMISSION_CATEGORY'] ?: '-' ?></td></tr>
</table>
</div>
</div>
</div>

<!-- PARENT -->
<?php if(!empty($personal['PARENT_NAME'])){ ?>
<div class="card-box">
<div class="card-title">Parent Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr><th>Name</th><td><?= $personal['PARENT_NAME'] ?></td></tr>
<tr><th>Relation</th><td><?= $personal['PARENT_RELATIONSHIP'] ?></td></tr>
<tr><th>Mobile</th><td><?= $personal['PARENT_MOBILE'] ?></td></tr>
<tr><th>Address</th><td><?= $personal['PARENT_CORR_ADDRESS'] ?></td></tr>
</table>
</div>
</div>
</div>
<?php } ?>

<!-- ADMISSION & RESEARCH -->
<div class="card-box">
<div class="card-title">Admission & Research Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr><th>Entrance Sheet No</th><td><?= $personal['ENTRANCE_SHEET_NO'] ?: '-' ?></td></tr>
<tr><th>Fee Receipt No</th><td><?= $personal['FEE_RECEIPT_NO'] ?: '-' ?></td></tr>
<tr><th>Fee Receipt Date</th><td><?= $personal['FEE_RECEIPT_DATE'] ?: '-' ?></td></tr>
<tr><th>Research Title</th><td><?= $personal['RESEARCH_TITLE'] ?: '-' ?></td></tr>
<tr><th>Guide Name & Address</th><td><?= $personal['GUIDE_NAME_ADDRESS'] ?: '-' ?></td></tr>
<tr><th>Institute</th><td><?= $personal['INSTITUTE_WHERE'] ?: '-' ?></td></tr>
<tr><th>Applied For Work</th><td><?= $personal['APPLIED_FOR_WORK'] ?: '-' ?></td></tr>
<tr><th>Eligibility Certificate No</th><td><?= $personal['ELIGIBILITY_CERT_NO'] ?: '-' ?></td></tr>
<tr><th>Eligibility Certificate Date</th><td><?= $personal['ELIGIBILITY_CERT_DATE'] ?: '-' ?></td></tr>
</table>
</div>
</div>
</div>

<!-- EDUCATION -->
<?php if($eduRes->num_rows>0){ ?>
<div class="card-box">
<div class="card-title">Education Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr class="table-secondary">
<th>Exam</th><th>Board</th><th>College</th><th>Division</th><th>Year</th>
</tr>
<?php while($e=$eduRes->fetch_assoc()){ ?>
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
</div>
<?php } ?>

<!-- EXPERIENCE -->
<?php if($expRes->num_rows>0){ ?>
<div class="card-box">
<div class="card-title">Experience Details</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr class="table-secondary">
<th>Employee</th><th>Post</th><th>Nature</th><th>Level</th>
<th>Category</th><th>From</th><th>To</th><th>Years</th><th>Months</th>
</tr>

<?php while($x=$expRes->fetch_assoc()){
$p=explode(".",$x['TOTAL_EXPERIENCE']);
$y=$p[0]??0; 
$m=$p[1]??0;
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
</div>
<?php } ?>

</div>
</body>
</html>
