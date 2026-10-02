<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// CHECK LOGIN & AUTHORIZATION
// ============================================================

// Ensure user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// Only HR/Admin/Superadmin can access
$loggedInUserRank = $_SESSION['user_rank'] ?? '';
if ($loggedInUserRank != 'admin' && $loggedInUserRank != 'hr' && $loggedInUserRank != 'superadmin') {
    header('Location: ../dashboard/employee-dashboard.php');
    exit();
}

// Get batch ID from URL
$batch_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($batch_id == 0) {
    echo '<script>alert("Invalid Batch ID!"); window.location.href="list.php";</script>';
    exit();
}

// ============================================================
// GET BATCH DETAILS
// ============================================================
$batchQuery = "SELECT * FROM tbl_payroll_batches WHERE id = '$batch_id' AND status != 'deleted'";
$batchResult = mysqli_query($conn, $batchQuery);
$batch = mysqli_fetch_assoc($batchResult);

if (!$batch) {
    echo '<script>alert("Payroll batch not found!"); window.location.href="list.php";</script>';
    exit();
}

// ============================================================
// HANDLE FINALIZE ACTION
// ============================================================
if (isset($_POST['action']) && $_POST['action'] == 'finalize') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
    
    // Update batch status to 'finalized'
    $updateQuery = "UPDATE tbl_payroll_batches SET status = 'finalized', finalized_at = NOW() WHERE id = '$batch_id'";
    if (mysqli_query($conn, $updateQuery)) {
        // Update all payslips in this batch to 'finalized' status
        $updatePayslips = "UPDATE tbl_payroll SET payment_status = 'finalized' WHERE payroll_batch_id = '$batch_id' AND status = '1'";
        mysqli_query($conn, $updatePayslips);
        
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Payroll batch finalized successfully! Payslips are now visible to employees.",
                position: "topRight"
            });
            setTimeout(() => { window.location.href = "payroll_batch_view.php?id=' . $batch_id . '"; }, 1500);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Failed to finalize payroll batch!",
                position: "topRight"
            });
        </script>';
    }
}

// ============================================================
// GET PAYSLIPS IN THIS BATCH
// ============================================================
$payslipQuery = "SELECT p.*, u.firstName, u.lastName, u.username 
                FROM tbl_payroll p
                LEFT JOIN tbl_user u ON p.employee_id = u.id
                WHERE p.payroll_batch_id = '$batch_id' AND p.status = '1'
                ORDER BY u.firstName ASC";
$payslipResult = mysqli_query($conn, $payslipQuery);
$payslips = [];
while ($row = mysqli_fetch_assoc($payslipResult)) {
    $payslips[] = $row;
}

// Calculate total net payout
$totalNet = array_sum(array_column($payslips, 'net_salary'));

// Get employee count
$employeeCount = count($payslips);

$pageTitle = 'Payroll Batch - ' . date('F Y', strtotime($batch['period_month'] . '-01'));
include('../includes/header.php');
?>

<style>
    /* ============================================================
       LAYOUT - STICKY SIDEBAR
    ============================================================ */
    .layout-container {
        display: flex;
        min-height: 100vh;
        position: relative;
    }

    .layout-sidebar,
    .layout-menu {
        position: sticky;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        flex-shrink: 0;
        width: 260px;
        background: #1a2332;
        color: #ffffff;
        z-index: 1000;
        transition: all 0.3s ease;
        border: none !important;
        border-right: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .layout-sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .layout-sidebar::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 4px;
    }

    .layout-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .layout-page {
        flex: 1;
        min-height: 100vh;
        overflow-y: auto;
        margin-left: 0;
    }

    /* ============================================================
       BREADCRUMB STYLES
    ============================================================ */
    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .breadcrumb-box .breadcrumb-item {
        display: inline-flex;
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

    .breadcrumb-box .separator {
        color: #9ca3af;
        font-size: 16px;
        margin: 0 4px;
        font-weight: 600;
    }

    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .page-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .page-header .batch-status {
        padding: 6px 18px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .page-header .batch-status.draft {
        background: #f1f3f5;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    .page-header .batch-status.finalized {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .page-header .batch-status.paid {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    /* ============================================================
       FINALIZE BUTTON
    ============================================================ */
    .finalize-section {
        background: #fef9c3;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .finalize-section .info-text {
        color: #713f12;
        font-size: 14px;
    }

    .finalize-section .info-text i {
        font-size: 20px;
        margin-right: 8px;
    }

    .btn-finalize {
        background: #dc3545;
        color: #fff;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-finalize:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-finalize:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ============================================================
       STATS CARDS
    ============================================================ */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
    }

    .stat-card .number.green { color: #28a745; }
    .stat-card .number.blue { color: #696cff; }
    .stat-card .number.orange { color: #ed8936; }
    .stat-card .number.purple { color: #8b5cf6; }

    .stat-card .label {
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 4px;
        font-weight: 500;
    }

    /* ============================================================
       PAYSLIP TABLE
    ============================================================ */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        overflow: hidden;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .card-header h5 {
        font-weight: 700;
        color: #1a2332;
        font-size: 16px;
        margin: 0;
    }

    .card-body {
        padding: 0;
        overflow-x: auto;
    }

    .table-payslip {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-payslip thead th {
        background: #f8fafc;
        color: #1a2332;
        font-weight: 600;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table-payslip thead th:last-child {
        text-align: center;
    }

    .table-payslip tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f5;
        color: #1a2332;
        vertical-align: middle;
    }

    .table-payslip tbody td:last-child {
        text-align: center;
    }

    .table-payslip tbody tr:hover td {
        background: #f8fafc;
    }

    .table-payslip tbody tr:last-child td {
        border-bottom: none;
    }

    .table-payslip .salary-amount {
        font-weight: 700;
        color: #28a745;
    }

    .table-payslip .net-amount {
        font-weight: 700;
        color: #28a745;
        font-size: 16px;
    }

    /* ============================================================
       TOTAL NET SECTION
    ============================================================ */
    .total-net-section {
        display: flex;
        justify-content: flex-end;
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 2px solid #696cff;
    }

    .total-net-box {
        text-align: right;
    }

    .total-net-box .label {
        font-size: 14px;
        color: #6b7a8f;
        font-weight: 500;
    }

    .total-net-box .amount {
        font-size: 24px;
        font-weight: 800;
        color: #28a745;
        margin-top: 2px;
    }

    /* ============================================================
       VIEW PAYSLIP BUTTON
    ============================================================ */
    .btn-view-payslip {
        background: #696cff;
        color: #fff;
        border: none;
        padding: 5px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view-payslip:hover {
        background: #5a5de0;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 48px;
        color: #d1d5db;
        display: block;
        margin-bottom: 15px;
    }

    .empty-state h6 {
        color: #6b7a8f;
        font-weight: 500;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 14px;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .layout-sidebar {
            position: fixed;
            left: -280px;
            width: 280px;
            transition: left 0.3s ease;
            z-index: 9999;
        }

        .layout-sidebar.open {
            left: 0;
        }

        .layout-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }

        .layout-sidebar-overlay.active {
            display: block;
        }

        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card .number {
            font-size: 20px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header h2 {
            font-size: 20px;
        }

        .finalize-section {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-finalize {
            justify-content: center;
        }

        .table-payslip thead th,
        .table-payslip tbody td {
            padding: 8px 10px;
            font-size: 12px;
        }

        .total-net-box .amount {
            font-size: 20px;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .stats-cards {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .stat-card {
            padding: 12px 14px;
        }

        .stat-card .number {
            font-size: 18px;
        }

        .table-payslip thead th,
        .table-payslip tbody td {
            padding: 6px 6px;
            font-size: 11px;
        }

        .btn-view-payslip {
            font-size: 10px;
            padding: 3px 10px;
        }

        .total-net-box .amount {
            font-size: 18px;
        }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu -->
            <div class="layout-sidebar" id="sidebar">
                <?php include('../includes/sideMenu.php'); ?>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="list.php" class="breadcrumb-item">
                                <i class="bx bx-briefcase"></i> Payroll
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-file"></i> Batch View
                            </span>
                        </div>

                        <!-- Page Header -->
                        <div class="page-header">
                            <div>
                                <h2>
                                    <?= date('F Y', strtotime($batch['period_month'] . '-01')) ?> Payroll
                                </h2>
                            </div>
                            <div>
                                <span class="batch-status <?= $batch['status'] ?>">
                                    <?= ucfirst($batch['status']) ?>
                                </span>
                                <?php if ($batch['finalized_at']): ?>
                                    <small class="text-muted ms-2">
                                        Finalized on: <?= date('d M Y H:i', strtotime($batch['finalized_at'])) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Finalize Button (Only for Draft Status) -->
                        <?php if ($batch['status'] == 'draft'): ?>
                            <div class="finalize-section">
                                <div class="info-text">
                                    <i class="bx bx-info-circle"></i>
                                    <strong>Draft Status:</strong> This payroll batch is currently in draft mode.
                                    Click "Finalize" to make payslips visible to employees.
                                </div>
                                <form method="POST" action="" onsubmit="return confirmFinalize();">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="action" value="finalize">
                                    <button type="submit" class="btn-finalize">
                                        <i class="bx bx-check-circle"></i> Finalize This Batch
                                    </button>
                                </form>
                            </div>
                        <?php elseif ($batch['status'] == 'finalized'): ?>
                            <div class="finalize-section" style="background: #dcfce7; border-color: #bbf7d0;">
                                <div class="info-text" style="color: #166534;">
                                    <i class="bx bx-check-circle" style="color: #22c55e;"></i>
                                    <strong>Finalized:</strong> This payroll batch has been finalized.
                                    Payslips are now visible to all employees.
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Stats Cards -->
                        <div class="stats-cards">
                            <div class="stat-card">
                                <div class="number blue"><?= $employeeCount ?></div>
                                <div class="label">Total Employees</div>
                            </div>
                            <div class="stat-card">
                                <div class="number green">₹<?= number_format($totalNet, 2) ?></div>
                                <div class="label">Total Net Payout</div>
                            </div>
                            <div class="stat-card">
                                <div class="number orange"><?= count($payslips) ?></div>
                                <div class="label">Total Payslips</div>
                            </div>
                            <div class="stat-card">
                                <div class="number purple"><?= $batch['status'] ?></div>
                                <div class="label">Status</div>
                            </div>
                        </div>

                        <!-- Payslip Table -->
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="bx bx-receipt me-2"></i>Payslip Details</h5>
                                <span class="badge bg-primary"><?= count($payslips) ?> Records</span>
                            </div>
                            <div class="card-body">
                                <table class="table-payslip">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Payable Days</th>
                                            <th>Gross</th>
                                            <th>EPF</th>
                                            <th>ESI</th>
                                            <th>Net Pay</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($payslips)): ?>
                                            <?php foreach ($payslips as $payslip): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($payslip['username'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars(($payslip['firstName'] ?? '') . ' ' . ($payslip['lastName'] ?? '')) ?></td>
                                                    <td><?= number_format($payslip['payable_days'] ?? 0, 1) ?> / 31</td>
                                                    <td>₹<?= number_format($payslip['gross_salary'] ?? 0, 2) ?></td>
                                                    <td>₹<?= number_format($payslip['pf_deduction'] ?? 0, 2) ?></td>
                                                    <td>₹<?= number_format($payslip['esi_deduction'] ?? 0, 2) ?></td>
                                                    <td class="net-amount">₹<?= number_format($payslip['net_salary'] ?? 0, 2) ?></td>
                                                    <td>
                                                        <a href="view.php?id=<?= $payslip['id'] ?>" class="btn-view-payslip" target="_blank">
                                                            <i class="bx bx-show"></i> View Payslip
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8">
                                                    <div class="empty-state">
                                                        <i class="bx bx-receipt"></i>
                                                        <h6>No Payslips Found</h6>
                                                        <p>This payroll batch has no payslips.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="total-net-section">
                                <div class="total-net-box">
                                    <div class="label">Total Net Payout</div>
                                    <div class="amount">₹ <?= number_format($totalNet, 2) ?></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../includes/script.php'); ?>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuToggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle?.contains(event.target)) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                }
            }
        });

        // Confirm Finalize
        function confirmFinalize() {
            return confirm(
                '⚠️ Are you sure you want to finalize this payroll batch?\n\n' +
                'This action will:\n' +
                '✅ Make payslips visible to all employees\n' +
                '✅ Lock the payroll from further edits\n' +
                '⚠️ This action cannot be undone!\n\n' +
                'Do you want to continue?'
            );
        }
    </script>

</body>

</html>