<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

if(!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

$payroll_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($payroll_id > 0) {
    $query = "SELECT p.*, u.firstName, u.lastName, u.mobile, u.email_id, u.username, u.rank, u.department
              FROM tbl_payroll p 
              LEFT JOIN tbl_user u ON p.employee_id = u.id 
              WHERE p.id = $payroll_id AND p.status='1'";
    $result = $conn->query($query);
    $payroll = $result->fetch_assoc();
    
    if (!$payroll) {
        echo '<script>alert("Payroll record not found!"); window.location.href="list.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid Payroll ID!"); window.location.href="list.php";</script>';
    exit();
}

// Calculate total working days for the month
$month = $payroll['pay_month'];
$year = date('Y', strtotime($month));
$monthNum = date('m', strtotime($month));
$totalWorkingDays = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

// Get attendance for payable days
$attQuery = "SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN attendance_type_id = 3 THEN 1 ELSE 0 END) as half_days,
                SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
             FROM tbl_user_attendance 
             WHERE employee_id = " . $payroll['employee_id'] . " 
             AND DATE_FORMAT(date, '%Y-%m') = '$month'
             AND status = '1'";
$attResult = $conn->query($attQuery);
$attData = $attResult->fetch_assoc();

if ($attData && ($attData['total_days'] ?? 0) > 0) {
    $present_days = $attData['present_days'] ?? 0;
    $half_days = $attData['half_days'] ?? 0;
    $leave_days = $attData['leave_days'] ?? 0;
    $holidays = $attData['holidays'] ?? 0;
    $week_offs = $attData['week_offs'] ?? 0;
    $payableDays = $present_days + $leave_days + $holidays + $week_offs + ($half_days * 0.5);
} else {
    $payableDays = $payroll['payable_days'] ?? 0;
}

$pageTitle = 'View Payroll';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       SIDEBAR SCROLLING & LAYOUT (ADD THIS)
    ============================================================ */
    .layout-menu {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        background: #1a2332;
        width: 260px;
        transition: all 0.3s ease;
        scrollbar-width: thin;
        scrollbar-color: #2d3748 transparent;
        
        /* REMOVE BLACK LINE */
        border-right: none !important;
        box-shadow: none !important;
    }
    
    .layout-menu::-webkit-scrollbar {
        width: 4px;
    }
    .layout-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    .layout-menu::-webkit-scrollbar-thumb {
        background: #2d3748;
        border-radius: 10px;
    }
    .layout-menu::-webkit-scrollbar-thumb:hover {
        background: #696cff;
    }
    
    @media (max-width: 768px) {
        .layout-menu {
            width: 100%;
            height: 100vh;
            max-width: 280px;
            transform: translateX(-100%);
        }
        .layout-menu.show {
            transform: translateX(0);
        }
        .layout-page {
            margin-left: 0 !important;
        }
    }
    
    @media (min-width: 769px) {
        .layout-page {
            margin-left: 260px;
        }
    }
    
    .menu-toggle-btn {
        display: none;
        position: fixed;
        top: 12px;
        left: 12px;
        z-index: 1002;
        background: #1a2332;
        border: none;
        color: #ffffff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 22px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .menu-toggle-btn:hover {
        background: #2d3748;
    }
    
    @media (max-width: 768px) {
        .menu-toggle-btn {
            display: block;
        }
        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .menu-overlay.show {
            display: block;
        }
    }

    /* ============================================================
       VIEW PAYROLL STYLES - NEW UI
    ============================================================ */
    .view-wrapper {
        max-width: 950px;
        margin: 0 auto;
    }
    
    .view-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    .view-card .card-body {
        padding: 28px 32px;
    }
    
    /* ============================================================
       EMPLOYEE PROFILE HEADER
    ============================================================ */
    .employee-profile {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #1a2332;
        margin-bottom: 20px;
    }
    .employee-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #696cff;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .employee-profile-info h2 {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }
    .employee-profile-info .employee-code {
        font-size: 14px;
        color: #6b7a8f;
        margin-top: 2px;
    }
    .employee-profile-info .employee-status {
        margin-top: 6px;
    }
    .employee-profile-info .status-badge {
        padding: 4px 18px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .employee-profile-info .status-badge.status-paid {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .employee-profile-info .status-badge.status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }
    .employee-profile-info .status-badge.status-draft {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }
    
    /* ============================================================
       DETAILS GRID
    ============================================================ */
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 15px 30px;
        background: #f8fafc;
        padding: 14px 20px;
        border-radius: 8px;
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
    }
    .details-grid .item {
        display: flex;
        flex-direction: column;
    }
    .details-grid .item .label {
        font-weight: 500;
        color: #6b7a8f;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .details-grid .item .value {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
        margin-top: 2px;
    }
    
    /* ============================================================
       SALARY SUMMARY BADGE
    ============================================================ */
    .salary-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #e8f0fe;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid #d2e3fc;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .salary-summary .item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .salary-summary .item .label {
        font-size: 13px;
        color: #4a5568;
        font-weight: 500;
    }
    .salary-summary .item .value {
        font-size: 16px;
        font-weight: 700;
        color: #1a2332;
    }
    .salary-summary .item .value .highlight {
        color: #696cff;
        font-size: 18px;
    }
    
    /* ============================================================
       SALARY BREAKDOWN TABLE
    ============================================================ */
    .salary-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 10px;
    }
    .salary-table thead th {
        background: #f8fafc;
        padding: 12px 18px;
        font-size: 13px;
        font-weight: 700;
        color: #1a2332;
        text-align: left;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .salary-table thead th:last-child {
        text-align: right;
    }
    .salary-table tbody td {
        padding: 12px 18px;
        font-size: 14px;
        border-bottom: 1px solid #edf2f7;
        color: #1a2332;
    }
    .salary-table tbody td:last-child {
        text-align: right;
        font-weight: 600;
    }
    .salary-table tbody tr:last-child td {
        border-bottom: none;
    }
    .salary-table tbody .total-row td {
        font-weight: 700;
        border-top: 2px solid #1a2332;
        padding-top: 14px;
        padding-bottom: 14px;
        background: #f8fafc;
    }
    .salary-table tbody .total-row td:last-child {
        color: #dc3545;
    }
    .salary-table tbody .net-row td {
        background: #d4edda;
        font-weight: 700;
        font-size: 16px;
        border-top: 2px solid #28a745;
        padding-top: 14px;
        padding-bottom: 14px;
    }
    .salary-table tbody .net-row td:last-child {
        font-weight: 800;
        font-size: 20px;
        color: #28a745;
    }
    .salary-table tbody .negative {
        color: #dc3545;
    }
    
    /* ============================================================
       ACTION BUTTONS
    ============================================================ */
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #e9ecef;
    }
    .action-buttons .btn {
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        cursor: pointer;
    }
    .action-buttons .btn-success {
        background: #28a745;
        color: #fff;
    }
    .action-buttons .btn-success:hover {
        background: #1e7e34;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40,167,69,0.3);
        color: #fff;
    }
    .action-buttons .btn-secondary {
        background: #6c757d;
        color: #fff;
    }
    .action-buttons .btn-secondary:hover {
        background: #5a6268;
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .view-card .card-body { padding: 18px; }
        .employee-profile { flex-direction: column; text-align: center; }
        .details-grid { grid-template-columns: 1fr 1fr; gap: 8px 15px; }
        .salary-summary { flex-direction: column; text-align: center; }
        .salary-table thead th { font-size: 11px; padding: 8px 12px; }
        .salary-table tbody td { font-size: 13px; padding: 8px 12px; }
        .salary-table tbody .net-row td { font-size: 14px; }
        .salary-table tbody .net-row td:last-child { font-size: 17px; }
        .action-buttons { flex-direction: column; }
        .action-buttons .btn { justify-content: center; }
    }
    
    @media (max-width: 480px) {
        .details-grid { grid-template-columns: 1fr; gap: 4px; }
        .employee-profile-info h2 { font-size: 18px; }
        .employee-avatar { width: 55px; height: 55px; font-size: 22px; }
        .salary-table thead th { font-size: 10px; padding: 6px 8px; }
        .salary-table tbody td { font-size: 12px; padding: 6px 8px; }
        .salary-summary .item .value { font-size: 14px; }
    }
</style>

<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="menu-toggle-btn" id="menuToggleBtn" aria-label="Toggle menu">
        <i class="bx bx-menu"></i>
    </button>
    
    <!-- Mobile Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>
    
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>
            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="view-wrapper">

                            <!-- Page Title -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-1">
                                        <span class="text-muted fw-light">Payroll /</span> View Payroll
                                    </h4>
                                    <p class="text-muted small mb-0">View payroll details for <?= date('F Y', strtotime($payroll['pay_month'] . '-01')) ?></p>
                                </div>
                                <div>
                                    <a href="list.php" class="btn btn-secondary btn-sm">
                                        <i class="bx bx-arrow-back me-1"></i> Back to List
                                    </a>
                                </div>
                            </div>

                            <!-- ============================================================
                                 MAIN CARD
                            ============================================================ -->
                            <div class="view-card">
                                <div class="card-body">

                                    <!-- ============================================================
                                         EMPLOYEE PROFILE
                                    ============================================================ -->
                                    <div class="employee-profile">
                                        <div class="employee-avatar">
                                            <?= strtoupper(substr($payroll['firstName'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div class="employee-profile-info">
                                            <h2><?= htmlspecialchars($payroll['firstName'] . ' ' . $payroll['lastName']) ?></h2>
                                            <div class="employee-code"><?= htmlspecialchars($payroll['username'] ?? $payroll['employee_id']) ?></div>
                                            <div class="employee-status">
                                                <span class="status-badge status-<?= $payroll['payment_status'] ?>">
                                                    <?= ucfirst($payroll['payment_status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ============================================================
                                         EMPLOYEE DETAILS
                                    ============================================================ -->
                                    <div class="details-grid">
                                        <div class="item">
                                            <span class="label">Pay Month</span>
                                            <span class="value"><?= date('F Y', strtotime($payroll['pay_month'] . '-01')) ?></span>
                                        </div>
                                        <div class="item">
                                            <span class="label">Designation</span>
                                            <span class="value"><?= htmlspecialchars($payroll['rank'] ?? 'Unknown') ?></span>
                                        </div>
                                        <div class="item">
                                            <span class="label">Department</span>
                                            <span class="value"><?= htmlspecialchars($payroll['department'] ?? 'Unknown') ?></span>
                                        </div>
                                        <div class="item">
                                            <span class="label">Mobile</span>
                                            <span class="value"><?= htmlspecialchars($payroll['mobile'] ?? '-') ?></span>
                                        </div>
                                    </div>

                                    <!-- ============================================================
                                         SALARY SUMMARY
                                    ============================================================ -->
                                    <div class="salary-summary">
                                        <div class="item">
                                            <span class="label">📅 Payable Days</span>
                                            <span class="value"><span class="highlight"><?= number_format($payableDays, 1) ?></span> / <?= $totalWorkingDays ?> days</span>
                                        </div>
                                        <div class="item">
                                            <span class="label">💰 Gross Salary</span>
                                            <span class="value">₹ <?= number_format($payroll['gross_salary'], 2) ?></span>
                                        </div>
                                        <div class="item">
                                            <span class="label">💵 Net Pay</span>
                                            <span class="value">₹ <?= number_format($payroll['net_salary'], 2) ?></span>
                                        </div>
                                    </div>

                                    <!-- ============================================================
                                         SALARY BREAKDOWN TABLE
                                    ============================================================ -->
                                    <table class="salary-table">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Amount (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Basic Salary</td>
                                                <td><?= number_format($payroll['basic_salary'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Gross Salary</td>
                                                <td><?= number_format($payroll['gross_salary'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="negative">PF Deduction</td>
                                                <td class="negative">- <?= number_format($payroll['pf_deduction'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="negative">ESI Deduction</td>
                                                <td class="negative">- <?= number_format($payroll['esi_deduction'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="negative">Professional Tax</td>
                                                <td class="negative">- <?= number_format($payroll['pt_deduction'], 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="negative">Other Deduction</td>
                                                <td class="negative">- <?= number_format($payroll['other_deduction'], 2) ?></td>
                                            </tr>
                                            <!-- Total Deductions -->
                                            <tr class="total-row">
                                                <td><strong>Total Deductions</strong></td>
                                                <td><strong>- <?= number_format($payroll['pf_deduction'] + $payroll['esi_deduction'] + $payroll['pt_deduction'] + $payroll['other_deduction'], 2) ?></strong></td>
                                            </tr>
                                            <!-- Net Salary -->
                                            <tr class="net-row">
                                                <td><strong>Net Salary</strong></td>
                                                <td><strong>₹ <?= number_format($payroll['net_salary'], 2) ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- Payment Date -->
                                    <?php if ($payroll['payment_status'] == 'paid' && !empty($payroll['payment_date'])): ?>
                                    <div style="background: #f8fafc; padding: 8px 16px; border-radius: 6px; margin-top: 12px; border: 1px solid #edf2f7;">
                                        <span style="font-weight: 600; color: #4a5568;">Payment Date:</span>
                                        <span style="font-weight: 600; color: #1a2332; margin-left: 8px;"><?= date('d M Y', strtotime($payroll['payment_date'])) ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <!-- ============================================================
                                         ACTION BUTTONS
                                    ============================================================ -->
                                    <div class="action-buttons">
                                        <a href="payslip.php?id=<?= $payroll['id'] ?>" class="btn btn-success" target="_blank">
                                            <i class="bx bx-receipt me-1"></i> View Payslip
                                        </a>
                                        <a href="list.php" class="btn btn-secondary">
                                            <i class="bx bx-list-ul me-1"></i> Back to List
                                        </a>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>

    <?php include('../includes/script.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const menuOverlay = document.getElementById('menuOverlay');
            const sideMenu = document.querySelector('.layout-menu');
            
            if (menuToggleBtn) {
                menuToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = sideMenu.classList.contains('show');
                    if (isOpen) {
                        sideMenu.classList.remove('show');
                        menuOverlay.classList.remove('show');
                        this.innerHTML = '<i class="bx bx-menu"></i>';
                    } else {
                        sideMenu.classList.add('show');
                        menuOverlay.classList.add('show');
                        this.innerHTML = '<i class="bx bx-x"></i>';
                    }
                });
            }
            
            if (menuOverlay) {
                menuOverlay.addEventListener('click', function() {
                    sideMenu.classList.remove('show');
                    this.classList.remove('show');
                    if (menuToggleBtn) menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sideMenu && sideMenu.classList.contains('show')) {
                    sideMenu.classList.remove('show');
                    if (menuOverlay) menuOverlay.classList.remove('show');
                    if (menuToggleBtn) menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                }
            });
        });
    </script>

</body>
</html>