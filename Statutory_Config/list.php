<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<style>
    /* Breadcrumb Box Styles */
    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .breadcrumb-box .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        font-weight: 500;
        color: #198754;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breadcrumb-box .breadcrumb-item i {
        font-size: 18px;
        color: #198754;
    }

    .breadcrumb-box .breadcrumb-item:hover {
        color: #0f5132;
        text-decoration: underline;
    }

    .breadcrumb-box .breadcrumb-item.active {
        color: #1a2332;
        font-weight: 600;
        pointer-events: none;
    }

    .breadcrumb-box .breadcrumb-item.active i {
        color: #1a2332;
    }

    .breadcrumb-box .separator {
        color: #9ca3af;
        font-size: 16px;
        margin: 0 4px;
        font-weight: 600;
    }
</style>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>

            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb Box -->
                        <div class="breadcrumb-box">
                            <!-- Dashboard -->
                            <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>

                            <span class="separator">›</span>

                            <!-- Payroll -->
                            <a href="../payroll/list.php" class="breadcrumb-item">
                                <i class="bx bx-briefcase"></i> Payroll
                            </a>

                            <span class="separator">›</span>

                            <!-- Statutory Config List (Active - Not clickable) -->
                            <span class="breadcrumb-item active">
                                <i class="bx bx-list-ul"></i> Statutory Config List
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Statutory Config List</h5>
                                        <a href="add" class="btn btn-primary btn-sm">+ Add Statutory Config</a>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Config Name</th>
                                                    <th>Applicable On</th>
                                                    <th>Employee %</th>
                                                    <th>Employer %</th>
                                                    <th>Fixed Amount</th>
                                                    <th>Effective From</th>
                                                    <th>Status</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_statutory_config WHERE status='1' ORDER BY config_name ASC");

                                                if ($sql && mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['config_name']); ?></td>
                                                    <td><?= htmlspecialchars($row['applicable_on']); ?></td>
                                                    <td><?= htmlspecialchars($row['employee_contribution_pct']); ?>%</td>
                                                    <td><?= htmlspecialchars($row['employer_contribution_pct']); ?>%</td>
                                                    <td><?= htmlspecialchars($row['fixed_amount']); ?></td>
                                                    <td><?= htmlspecialchars($row['effective_from']); ?></td>
                                                    <td><span class="badge bg-label-success">Active</span></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit.php?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete.php?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr><td colspan="8">No statutory config added yet.</td></tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include('../includes/footer.php'); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <?php include('../includes/script.php'); ?>
</body>

</html>