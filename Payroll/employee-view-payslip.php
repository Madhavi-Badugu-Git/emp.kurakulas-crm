<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// CHECK LOGIN & GET USER DETAILS
// ============================================================

// Ensure user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// Get logged in user details from session
$loggedInUserId = $_SESSION['user_id'] ?? 0;
$loggedInUsername = $_SESSION['loggedInUser'] ?? '';
$loggedInUserRank = $_SESSION['user_rank'] ?? 'employee';

// If user_id is not set in session, try to get it from database
if ($loggedInUserId == 0 && !empty($loggedInUsername)) {
    $userCheck = "SELECT id FROM tbl_user WHERE username = '" . mysqli_real_escape_string($conn, $loggedInUsername) . "' AND status = '1'";
    $userResult = mysqli_query($conn, $userCheck);
    if ($userRow = mysqli_fetch_assoc($userResult)) {
        $loggedInUserId = $userRow['id'];
        $_SESSION['user_id'] = $loggedInUserId;
    }
}

// If still no user ID, show error
if ($loggedInUserId == 0) {
    echo '<div class="alert alert-danger">Unable to identify user. Please <a href="../login.php">login again</a>.</div>';
    include('../includes/footer.php');
    exit();
}

// If user is admin/hr/superadmin, redirect to admin payroll list
if ($loggedInUserRank == 'admin' || $loggedInUserRank == 'hr' || $loggedInUserRank == 'superadmin') {
    header('Location: list.php');
    exit();
}

// Get payslip ID from URL
$payslip_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($payslip_id == 0) {
    echo '<div class="alert alert-danger">Invalid Payslip ID!</div>';
    include('../includes/footer.php');
    exit();
}

// Get payslip details
$payslipQuery = "SELECT p.*, u.firstName, u.lastName, u.username, u.rank 
                FROM tbl_payroll p
                LEFT JOIN tbl_user u ON p.employee_id = u.id
                WHERE p.id = '$payslip_id' AND p.employee_id = '$loggedInUserId' AND p.status = '1'";
$payslipResult = mysqli_query($conn, $payslipQuery);
$payslip = mysqli_fetch_assoc($payslipResult);

if (!$payslip) {
    echo '<div class="alert alert-danger">Payslip not found or you don\'t have permission to view it!</div>';
    include('../includes/footer.php');
    exit();
}

// Get employee details
$empQuery = "SELECT id, username, firstName, lastName, rank, basic_salary, gross_salary 
            FROM tbl_user 
            WHERE id = '" . (int)$loggedInUserId . "' AND status = '1'";
$empResult = mysqli_query($conn, $empQuery);
$employee = mysqli_fetch_assoc($empResult);

$pageTitle = 'My Payslip';
include('../includes/header.php');
?>

<style>
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

    .payslip-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .payslip-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        padding: 40px 45px;
        border: 1px solid #e9ecef;
    }

    .company-header {
        text-align: center;
        border-bottom: 2px solid #696cff;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .company-header .company-name {
        font-size: 28px;
        font-weight: 700;
        color: #1a2332;
        letter-spacing: 1px;
    }

    .company-header .company-tagline {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 4px;
    }

    .company-header .company-address {
        font-size: 13px;
        color: #6b7a8f;
        margin-top: 2px;
    }

    .company-header .payslip-title {
        font-size: 20px;
        font-weight: 700;
        color: #696cff;
        margin-top: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .employee-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 30px;
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }

    .employee-info-grid .info-item {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .employee-info-grid .info-item:last-child {
        border-bottom: none;
    }

    .employee-info-grid .info-item .label {
        font-size: 13px;
        color: #6b7a8f;
        font-weight: 500;
    }

    .employee-info-grid .info-item .value {
        font-size: 14px;
        color: #1a2332;
        font-weight: 600;
    }

    .employee-info-grid .info-item .value.highlight {
        color: #696cff;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
        margin: 20px 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 2px solid #696cff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .payslip-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .payslip-table thead th {
        background: #f8fafc;
        color: #1a2332;
        font-weight: 700;
        padding: 10px 15px;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .payslip-table thead th:last-child {
        text-align: right;
    }

    .payslip-table tbody td {
        padding: 10px 15px;
        border-bottom: 1px solid #f1f3f5;
        color: #1a2332;
    }

    .payslip-table tbody td:last-child {
        text-align: right;
        font-weight: 600;
    }

    .payslip-table tbody tr:hover td {
        background: #f8fafc;
    }

    .payslip-table .total-row td {
        background: #f8fafc;
        font-weight: 700;
        border-top: 2px solid #696cff;
        padding: 12px 15px;
    }

    .payslip-table .total-row td:last-child {
        font-size: 18px;
        color: #28a745;
    }

    .net-pay-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
    }

    .net-pay-box {
        background: #dcfce7;
        padding: 15px 30px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid #bbf7d0;
        min-width: 200px;
    }

    .net-pay-box .label {
        font-size: 14px;
        color: #166534;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .net-pay-box .amount {
        font-size: 28px;
        font-weight: 700;
        color: #166534;
        margin-top: 4px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 25px;
        flex-wrap: wrap;
    }

    .action-buttons .btn-action {
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }

    .action-buttons .btn-action.btn-primary {
        background: #696cff;
        color: #fff;
    }

    .action-buttons .btn-action.btn-primary:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
    }

    .action-buttons .btn-action.btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    .action-buttons .btn-action.btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-badge.paid {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-badge.pending {
        background: #fef9c3;
        color: #713f12;
        border: 1px solid #fde68a;
    }

    .status-badge.draft {
        background: #f1f3f5;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

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

    @media print {
        .layout-sidebar, .layout-sidebar-overlay, .navbar, .breadcrumb-box, .action-buttons, .menu-toggle-btn, .menu-overlay {
            display: none !important;
        }
        .layout-page {
            margin-left: 0 !important;
        }
        .payslip-wrapper {
            box-shadow: none;
            border: none;
            padding: 20px;
        }
        .payslip-container {
            padding: 0;
        }
        .company-header .payslip-title {
            color: #1a2332;
        }
        .section-title {
            border-bottom-color: #1a2332;
        }
        .payslip-table .total-row td {
            border-top-color: #1a2332;
        }
        .net-pay-box {
            background: #f8fafc;
            border-color: #d1d5db;
        }
        .net-pay-box .amount {
            color: #1a2332;
        }
        .action-buttons {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .layout-sidebar {
            position: fixed;
            left: -280px;
            width: 280px;
            transition: left 0.3s ease;
            z-index: 9999;
        }
        .layout-sidebar.open { left: 0; }
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
        .layout-sidebar-overlay.active { display: block; }

        .payslip-wrapper { padding: 20px 15px; }
        .company-header .company-name { font-size: 22px; }
        .employee-info-grid { grid-template-columns: 1fr; gap: 5px; padding: 12px 15px; }
        .payslip-table thead th, .payslip-table tbody td { padding: 8px 10px; font-size: 12px; }
        .net-pay-box { padding: 12px 20px; min-width: 150px; }
        .net-pay-box .amount { font-size: 22px; }
        .action-buttons .btn-action { padding: 8px 18px; font-size: 12px; }
        .company-header .payslip-title { font-size: 16px; }
    }

    @media (max-width: 480px) {
        .payslip-wrapper { padding: 15px 10px; }
        .company-header .company-name { font-size: 18px; }
        .payslip-table thead th, .payslip-table tbody td { padding: 6px 6px; font-size: 11px; }
        .net-pay-box .amount { font-size: 18px; }
        .employee-info-grid .info-item .label { font-size: 12px; }
        .employee-info-grid .info-item .value { font-size: 12px; }
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
                            <a href="../dashboard/employee-dashboard.php" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="employee-payslip.php" class="breadcrumb-item">
                                <i class="bx bx-receipt"></i> My Payslips
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-file"></i> Payslip
                            </span>
                        </div>

                        <?php
                        $basic = $payslip['basic_salary'] ?? 0;
                        $da = $payslip['da'] ?? 0;
                        $hra = $payslip['hra'] ?? 0;
                        $other_allowance = $payslip['other_allowance'] ?? 0;
                        $gross_earnings = $basic + $da + $hra + $other_allowance;

                        $pf = $payslip['pf_deduction'] ?? 0;
                        $esi = $payslip['esi_deduction'] ?? 0;
                        $pt = $payslip['pt_deduction'] ?? 0;
                        $other_deduction = $payslip['other_deduction'] ?? 0;
                        $total_deductions = $pf + $esi + $pt + $other_deduction;

                        $net_salary = $payslip['net_salary'] ?? 0;

                        $payment_status = strtolower(trim($payslip['payment_status'] ?? 'draft'));
                        $statusClass = 'draft';
                        $statusText = 'Draft';
                        if ($payment_status == 'paid') {
                            $statusClass = 'paid';
                            $statusText = 'Paid';
                        } elseif ($payment_status == 'pending') {
                            $statusClass = 'pending';
                            $statusText = 'Pending';
                        }
                        ?>

                        <div class="payslip-container">

                            <div class="payslip-wrapper" id="payslipWrapper">

                                <!-- Company Header -->
                                <div class="company-header">
                                    <div class="company-name">KURAKULA'S</div>
                                    <div class="company-tagline">The Complete Solution for all your services</div>
                                    <div class="company-address">HYDERABAD</div>
                                    <div class="payslip-title">Payslip — <?= date('F Y', strtotime($payslip['pay_month'] . '-01')) ?></div>
                                </div>

                                <!-- Employee Info -->
                                <div class="employee-info-grid">
                                    <div class="info-item">
                                        <span class="label">EMPLOYEE CODE</span>
                                        <span class="value"><?= htmlspecialchars($employee['username'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">NAME</span>
                                        <span class="value highlight"><?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">UAN NO</span>
                                        <span class="value"><?= htmlspecialchars($payslip['uan_no'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">PAYABLE DAYS</span>
                                        <span class="value"><?= number_format($payslip['payable_days'] ?? 0, 1) ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">DESIGNATION</span>
                                        <span class="value"><?= htmlspecialchars($employee['rank'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">DEPARTMENT</span>
                                        <span class="value"><?= htmlspecialchars($payslip['department'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">ESI NO</span>
                                        <span class="value"><?= htmlspecialchars($payslip['esi_no'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">TOTAL DAYS</span>
                                        <span class="value"><?= $payslip['total_days'] ?? 31 ?></span>
                                    </div>
                                    <div class="info-item" style="grid-column: 1 / -1; border-bottom: none; justify-content: flex-end;">
                                        <span class="label">Status:</span>
                                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </div>
                                </div>

                                <!-- Earnings -->
                                <div class="section-title">
                                    <i class="bx bx-up-arrow-circle" style="color: #28a745;"></i> EARNINGS
                                </div>
                                <table class="payslip-table">
                                    <thead><tr><th>Particulars</th><th>Amount (₹)</th></tr></thead>
                                    <tbody>
                                        <tr><td>Basic</td><td>₹ <?= number_format($basic, 2) ?></td></tr>
                                        <tr><td>DA</td><td>₹ <?= number_format($da, 2) ?></td></tr>
                                        <tr><td>HRA</td><td>₹ <?= number_format($hra, 2) ?></td></tr>
                                        <tr><td>Other Allowance</td><td>₹ <?= number_format($other_allowance, 2) ?></td></tr>
                                        <tr class="total-row">
                                            <td><strong>Gross Earnings</strong></td>
                                            <td><strong>₹ <?= number_format($gross_earnings, 2) ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Deductions -->
                                <div class="section-title">
                                    <i class="bx bx-down-arrow-circle" style="color: #dc3545;"></i> DEDUCTIONS
                                </div>
                                <table class="payslip-table">
                                    <thead><tr><th>Particulars</th><th>Amount (₹)</th></tr></thead>
                                    <tbody>
                                        <tr><td>EPF (Employee)</td><td>₹ <?= number_format($pf, 2) ?></td></tr>
                                        <tr><td>ESI (Employee)</td><td>₹ <?= number_format($esi, 2) ?></td></tr>
                                        <tr><td>Professional Tax</td><td>₹ <?= number_format($pt, 2) ?></td></tr>
                                        <tr><td>Other Deductions</td><td>₹ <?= number_format($other_deduction, 2) ?></td></tr>
                                        <tr class="total-row">
                                            <td><strong>Total Deductions</strong></td>
                                            <td><strong>₹ <?= number_format($total_deductions, 2) ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Net Pay -->
                                <div class="net-pay-section">
                                    <div class="net-pay-box">
                                        <div class="label">Net Pay</div>
                                        <div class="amount">₹ <?= number_format($net_salary, 2) ?></div>
                                    </div>
                                </div>

                                <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #e9ecef; font-size: 12px; color: #9ca3af;">
                                    <p>This is a computer-generated payslip. No signature required.</p>
                                    <p>© <?= date('Y') ?> KURAKULA'S. All Rights Reserved.</p>
                                </div>

                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button onclick="window.print()" class="btn-action btn-primary">
                                    <i class="bx bx-printer"></i> Print Payslip
                                </button>
                                <button onclick="window.location.href='employee-payslip.php'" class="btn-action btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Back to Payslips
                                </button>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

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
    </script>

</body>

</html>