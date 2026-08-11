<?php include "Assets/Helpers/loader.php"; ?>


<div class="container-fluid">

    <div class="row ps-3  pt-2 gx-3">
        <div class="card border-primary p-3 mb-4 d-flex flex-row justify-content-between align-items-center">
            <h5 class="mb-0">Welcome <?php echo $name; ?> 👋</h5>
            <a href="Assets/Config/Logout.php" class="btn  btn-outline-danger" onclick="showLoader()">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>

        <div class="col-md-6 col-lg-3 mb-3 ps-0">
            <a href="?view=manage_scholar_onboardings" class="text-decoration-none" onclick="showLoader()">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="bi bi-mortarboard-fill fs-1 text-primary"></i>
                        <h6 class="mt-2">Manage Scholar Onboardings</h6>
                        <h3><?php echo $total_onboarding; ?></h3>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <a href="?view=manage_subjects" class="text-decoration-none" onclick="showLoader()">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="bi bi-person-badge-fill fs-1 text-success"></i>
                        <h6 class="mt-2">Manage Subjects</h6>
                        <h3><?php echo $total_subject ?></h3>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3 mb-3">
            <a href="?view=manage_faculties" class="text-decoration-none" onclick="showLoader()">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <i class="bi bi-person-lines-fill fs-1 text-info"></i>
                        <h6 class="mt-2">Manage Faculties</h6>
                        <h3><?php echo $total_faculty ?></h3>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>


    <!-- Loader -->
    <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>