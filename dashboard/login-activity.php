<?php session_start(); 
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
// $loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

if ($loggedInUser == 'Admin') {
    header("Location: admin");
} else if($loggedInUser == 'User'){
    header("Location: user");
}


$limit = 50; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Count total records
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_login l INNER JOIN tbl_user u ON l.username = u.username");
$total_row = mysqli_fetch_assoc($total_query);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch paginated records
$sql = mysqli_query($conn, "SELECT l.username, u.firstName, u.lastName, l.logged_in_at 
                            FROM tbl_login l 
                            INNER JOIN tbl_user u ON l.username = u.username 
                            ORDER BY l.id DESC 
                            LIMIT $start, $limit");


?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>
<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination .page-item {
    display: inline-block;
    margin: 5px;
}

.pagination .page-link {
    padding: 8px 12px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #007bff;
    border-radius: 5px;
}

.pagination .active .page-link {
    background-color: #007bff;
    color: #fff;
}
</style>

<body>

    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- side menu -->
            <?php include('../includes/sideMenu.php'); ?>
            <!-- side menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">


                            <div class="col-lg-12 col-md-12 order-1">
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-6 mb-6">
                                        <div class="card h-100">
                                            <h5 class="card-header">User Login</h5>
                                            <div class="table-responsive text-nowrap">
                                                <table class="table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Full Name</th>
                                                            <th>UserName</th>
                                                            <th>Login At</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (mysqli_num_rows($sql) > 0) {
                                                            while ($row = mysqli_fetch_assoc($sql)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $row['firstName'] . ' ' . $row['lastName']; ?></td>
                                                            <td><?= $row['username']; ?></td>
                                                            <td><?= $row['logged_in_at']; ?></td>
                                                        </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <div class="pagination">
                                                    <nav>
                                                        <ul class="pagination">
                                                            <?php if ($page > 1): ?>
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="?page=<?= $page - 1; ?>">Previous</a>
                                                            </li>
                                                            <?php endif; ?>

                                                            <?php
                                                            $maxPagesToShow = 5; // Show up to 5 pages
                                                            $startPage = max(1, $page - floor($maxPagesToShow / 2)); // Adjust start page dynamically
                                                            $endPage = min($total_pages, $startPage + $maxPagesToShow - 1); // Ensure it doesn't exceed total pages

                                                            // Adjust start page if we are near the end
                                                            if ($endPage - $startPage + 1 < $maxPagesToShow) {
                                                                $startPage = max(1, $endPage - $maxPagesToShow + 1);
                                                            }

                                                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                                                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                                                <a class="page-link"
                                                                    href="?page=<?= $i; ?>"><?= $i; ?></a>
                                                            </li>
                                                            <?php endfor; ?>

                                                            <?php if ($page < $total_pages): ?>
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="?page=<?= $page + 1; ?>">Next</a>
                                                            </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </nav>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>
                    <!-- / Footer -->


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>



        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>


    </div>
    <!-- / Layout wrapper -->

    <?php include('../includes/script.php'); ?>

</body>


</html>