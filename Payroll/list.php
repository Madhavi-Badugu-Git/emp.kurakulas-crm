<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Check if user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// ============================================================
// DELETE PAYROLL RECORD
// ============================================================
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    
    // Check if already finalized
    $check_status = "SELECT payment_status FROM tbl_payroll WHERE id='$delete_id' AND status='1'";
    $status_result = mysqli_query($conn, $check_status);
    $status_row = mysqli_fetch_assoc($status_result);
    
    if ($status_row && $status_row['payment_status'] === 'paid') {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Cannot delete finalized payroll!",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="list.php"; }, 1500);
        </script>';
    } else {
        $delete_query = "UPDATE tbl_payroll SET status='0' WHERE id='$delete_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Payroll deleted successfully!",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="list.php"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to delete payroll record!",
                    position: "topRight",
                });
            </script>';
        }
    }
}

// ============================================================
// FINALIZE PAYROLL RECORD - ADDED
// ============================================================
if (isset($_GET['finalize_id'])) {
    $finalize_id = (int) $_GET['finalize_id'];
    
    // Check if record exists and is in draft or pending status
    $check_query = "SELECT id, payment_status FROM tbl_payroll WHERE id='$finalize_id' AND status='1'";
    $check_result = mysqli_query($conn, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if ($check_row) {
        if ($check_row['payment_status'] === 'draft' || $check_row['payment_status'] === 'pending' || $check_row['payment_status'] === '') {
            // Update status to 'paid' (finalized)
            $update_query = "UPDATE tbl_payroll SET 
                            payment_status = 'paid', 
                            payment_date = NOW(),
                            updated_at = NOW() 
                            WHERE id='$finalize_id'";
            
            if (mysqli_query($conn, $update_query)) {
                echo '<script>
                    iziToast.success({
                        title: "Success",
                        message: "Payroll finalized successfully!",
                        position: "topRight",
                    });
                    setTimeout(() => { window.location.href="list.php"; }, 1000);
                </script>';
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "Failed to finalize payroll!",
                        position: "topRight",
                    });
                </script>';
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "This payroll is already finalized!",
                    position: "topRight",
                });
            </script>';
        }
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Payroll record not found!",
                position: "topRight",
            });
        </script>';
    }
}

// Get filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$month_filter = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build WHERE clause
$where = "p.status='1'";
if ($search) {
    $where .= " AND (u.firstName LIKE '%$search%' OR u.lastName LIKE '%$search%' OR u.username LIKE '%$search%')";
}
if ($month_filter) {
    $where .= " AND p.pay_month = '$month_filter'";
}
if ($status_filter) {
    $where .= " AND p.payment_status = '$status_filter'";
}

// Get total count
$count_query = "SELECT COUNT(p.id) as total FROM tbl_payroll p 
                 LEFT JOIN tbl_user u ON p.employee_id = u.id 
                 WHERE $where";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'] ?? 0;

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_pages = ceil($total_records / $limit);

// Fetch payroll records
$sql = "SELECT p.*, u.firstName, u.lastName, u.username 
        FROM tbl_payroll p 
        LEFT JOIN tbl_user u ON p.employee_id = u.id 
        WHERE $where 
        ORDER BY p.pay_month DESC, p.id DESC 
        LIMIT $offset, $limit";

$result = mysqli_query($conn, $sql);

// Get payroll summary
$summaryQuery = "SELECT 
                    COUNT(DISTINCT employee_id) as total_employees,
                    SUM(net_salary) as total_net,
                    SUM(pf_deduction) as total_pf,
                    SUM(esi_deduction) as total_esi
                 FROM tbl_payroll 
                 WHERE status = '1'";
if ($month_filter) {
    $summaryQuery .= " AND pay_month = '$month_filter'";
}
$summaryResult = $conn->query($summaryQuery);
$summary = $summaryResult->fetch_assoc();

// Get distinct months for month filter
$monthList = [];
$monthQuery = "SELECT DISTINCT pay_month FROM tbl_payroll WHERE status='1' ORDER BY pay_month DESC";
$monthResult = $conn->query($monthQuery);
while ($row = $monthResult->fetch_assoc()) {
    $monthList[] = $row['pay_month'];
}

// Get current statutory config
$statutoryQuery = "SELECT * FROM tbl_statutory_config WHERE status='1'";
$statutoryResult = $conn->query($statutoryQuery);
$statutoryConfig = [];
while ($row = $statutoryResult->fetch_assoc()) {
    $statutoryConfig[$row['config_name']] = $row;
}

$pageTitle = 'Payroll List';
include('../includes/header.php');
?>

<style>
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

    /* ============================================================
       LAYOUT STYLES
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

    .menu-inner {
        padding: 16px 0 30px;
        overflow: visible;
    }

    .menu-item {
        position: relative;
        margin: 2px 0;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        color: #a0aec0;
        text-decoration: none;
        font-size: 14px;
        border-radius: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-left: 3px solid transparent;
    }

    .menu-link:hover {
        background: #2d3748;
        color: #ffffff;
        border-left-color: #696cff;
    }

    .menu-link.active {
        background: #2d3748;
        color: #ffffff;
        border-left-color: #696cff;
    }

    .menu-link .menu-icon {
        margin-right: 12px;
        font-size: 20px;
        min-width: 24px;
        text-align: center;
        flex-shrink: 0;
    }

    .menu-link .menu-title {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .menu-link .menu-arrow {
        margin-left: auto;
        font-size: 12px;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .menu-item.open>.menu-link .menu-arrow {
        transform: rotate(180deg);
    }

    .submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        background: #141c28;
        display: none;
    }

    .menu-item.open>.submenu {
        display: block;
    }

    .submenu .menu-link {
        padding-left: 56px;
        font-size: 13px;
        color: #8896a8;
    }

    .submenu .menu-link:hover {
        background: #1e2a36;
        color: #ffffff;
    }

    .submenu .menu-link.active {
        background: #1e2a36;
        color: #ffffff;
    }

    .menu-header {
        padding: 20px 20px 16px;
        border-bottom: 1px solid #2d3748;
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .menu-header .brand-text {
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu-header .brand-text i {
        font-size: 24px;
        color: #696cff;
    }

    .layout-menu::after {
        content: '';
        position: fixed;
        bottom: 0;
        left: 0;
        width: 260px;
        height: 30px;
        background: linear-gradient(to top, #1a2332, transparent);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1001;
    }

    .layout-menu.scrolled::after {
        opacity: 1;
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

        .layout-menu::after {
            width: 280px;
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
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
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .menu-overlay.show {
            display: block;
        }
    }

    /* ============================================================
       PAYROLL LIST STYLES
    ============================================================ */
    .payroll-list-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 25px;
    }

    .payroll-list-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
        border-radius: 12px 12px 0 0;
    }

    .payroll-list-card .card-header h5 {
        font-weight: 700;
        color: #1a2332;
        font-size: 16px;
        letter-spacing: 0.3px;
    }

    .payroll-list-card .card-body {
        padding: 20px 24px;
    }

    .table-payroll th {
        white-space: nowrap;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
        background: #f8fafc;
        color: #4a5568;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-payroll td {
        vertical-align: middle;
        font-size: 13px;
        padding: 10px 12px;
        border-bottom: 1px solid #edf2f7;
    }

    .table-payroll tbody tr:hover td {
        background: #f7fafc;
    }

    .table-payroll tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-paid {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }

    .status-draft {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }

    .status-inactive {
        background: #f1f3f5;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }

    .net-salary {
        font-weight: 700;
        color: #28a745;
        font-size: 15px;
    }

    .payable-days-badge {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
    }

    /* ============================================================
       FINALIZE BUTTON STYLES - ADDED
    ============================================================ */
    .btn-finalize {
        background: #28a745;
        color: #fff;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-finalize:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: #fff;
    }

    .badge-finalized {
        background: #28a745;
        color: #fff;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-finalized i {
        font-size: 14px;
    }

    .action-buttons .btn {
        padding: 4px 8px;
        font-size: 13px;
        border-radius: 6px;
        margin: 0 2px;
    }

    .action-buttons .btn i {
        font-size: 16px;
    }

    .action-buttons .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .action-buttons .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
    }

    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        padding: 5px 0;
    }

    .dropdown-item {
        padding: 8px 20px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background: #f8fafc;
    }

    .dropdown-item i {
        margin-right: 8px;
        font-size: 16px;
    }

    .dropdown-item.text-danger:hover {
        background: #fee2e2;
    }

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 60px;
        color: #d1d5db;
        display: block;
        margin-bottom: 15px;
    }

    .empty-state h5 {
        color: #6b7a8f;
        font-weight: 500;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 14px;
    }

    .filter-section {
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .filter-section .form-label {
        font-weight: 600;
        font-size: 12px;
        color: #4a5568;
        margin-bottom: 4px;
    }

    .filter-section .form-control,
    .filter-section .form-select {
        font-size: 13px;
        border-color: #d2d6da;
        border-radius: 6px;
        padding: 8px 12px;
        height: 38px;
    }

    .filter-section .form-control:focus,
    .filter-section .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.1);
    }

    .filter-section .btn-filter {
        background: #696cff;
        color: #fff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        height: 38px;
    }

    .filter-section .btn-filter:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
    }

    .filter-section .btn-clear {
        background: #e2e8f0;
        color: #4a5568;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .filter-section .btn-clear:hover {
        background: #cbd5e0;
        color: #1a2332;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px 18px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .summary-card .number {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
    }

    .summary-card .number.green {
        color: #28a745;
    }

    .summary-card .number.blue {
        color: #696cff;
    }

    .summary-card .number.orange {
        color: #ed8936;
    }

    .summary-card .number.purple {
        color: #8b5cf6;
    }

    .summary-card .label {
        font-size: 12px;
        color: #6b7a8f;
        margin-top: 4px;
        font-weight: 500;
    }

    .summary-card .icon {
        font-size: 28px;
        display: block;
        margin-bottom: 4px;
    }

    .statutory-info {
        background: #f8fafc;
        padding: 10px 16px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .statutory-info strong {
        color: #1a2332;
        font-size: 13px;
    }

    .statutory-info .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .statutory-info .badge.bg-primary {
        background: #696cff;
        color: #fff;
    }

    .statutory-info .badge.bg-info {
        background: #17a2b8;
        color: #fff;
    }

    .statutory-info .badge.bg-warning {
        background: #ffc107;
        color: #000;
    }

    .statutory-info .badge.bg-success {
        background: #28a745;
        color: #fff;
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-container .info {
        color: #6b7a8f;
        font-size: 13px;
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #1a2332;
        border-color: #e9ecef;
        padding: 6px 14px;
        font-size: 13px;
        border-radius: 6px;
        margin: 0 2px;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: #f8fafc;
        border-color: #d1d5db;
    }

    .pagination .page-item.active .page-link {
        background: #696cff;
        border-color: #696cff;
        color: #ffffff;
    }

    .pagination .page-item.disabled .page-link {
        color: #a0aec0;
        pointer-events: none;
    }

    .total-badge {
        background: #696cff;
        color: #fff;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn-action-top {
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-action-top.btn-primary {
        background: #696cff;
        color: #fff;
        border: none;
    }

    .btn-action-top.btn-primary:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
        color: #fff;
    }

    .employee-name {
        font-weight: 600;
        color: #1a2332;
    }

    .employee-id {
        color: #6b7a8f;
        font-size: 12px;
    }

    /* ============================================================
       DELETE & FINALIZE CONFIRMATION POPUPS
    ============================================================ */
    .delete-popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .delete-popup-overlay.active {
        display: flex;
    }

    .delete-popup {
        background: #ffffff;
        border-radius: 16px;
        padding: 35px 40px 30px;
        max-width: 450px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease;
        position: relative;
    }

    .delete-popup .icon {
        font-size: 60px;
        margin-bottom: 10px;
        display: block;
    }

    .delete-popup .icon.danger {
        color: #dc3545;
    }

    .delete-popup .icon.success {
        color: #28a745;
    }

    .delete-popup .title {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 8px;
    }

    .delete-popup .title.danger {
        color: #dc3545;
    }

    .delete-popup .title.success {
        color: #28a745;
    }

    .delete-popup .message {
        font-size: 15px;
        color: #6b7a8f;
        margin-bottom: 5px;
        line-height: 1.6;
    }

    .delete-popup .message strong {
        color: #dc3545;
    }

    .delete-popup .warning-text {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        margin: 10px 0 18px;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .delete-popup .warning-text.danger {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }

    .delete-popup .warning-text.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #28a745;
    }

    .delete-popup .warning-text i {
        font-size: 18px;
    }

    .delete-popup .record-details {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 16px;
        text-align: left;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .delete-popup .record-details .row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .delete-popup .record-details .row:last-child {
        border-bottom: none;
    }

    .delete-popup .record-details .label {
        font-size: 12px;
        color: #6b7a8f;
        font-weight: 500;
    }

    .delete-popup .record-details .value {
        font-size: 13px;
        font-weight: 600;
        color: #1a2332;
    }

    .delete-popup .btn-group-popup {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .delete-popup .btn-confirm {
        color: #fff;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }

    .delete-popup .btn-confirm.danger {
        background: #dc3545;
    }

    .delete-popup .btn-confirm.danger:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .delete-popup .btn-confirm.success {
        background: #28a745;
    }

    .delete-popup .btn-confirm.success:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .delete-popup .btn-cancel-popup {
        background: #6c757d;
        color: #fff;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }

    .delete-popup .btn-cancel-popup:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .delete-popup .close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        font-size: 24px;
        color: #6b7a8f;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .delete-popup .close-btn:hover {
        color: #1a2332;
        transform: rotate(90deg);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .delete-popup {
            padding: 25px 20px;
            max-width: 95%;
        }
        .delete-popup .icon { font-size: 48px; }
        .delete-popup .title { font-size: 18px; }
        .delete-popup .message { font-size: 14px; }
        .delete-popup .btn-group-popup { flex-direction: column; }
        .delete-popup .btn-confirm,
        .delete-popup .btn-cancel-popup { width: 100%; }
        .delete-popup .record-details .row { flex-direction: column; padding: 6px 0; }
        
        .payroll-list-card .card-body { padding: 15px; }
        .table-payroll td { padding: 6px 8px; font-size: 11px; }
        .table-payroll th { font-size: 9px; padding: 6px 8px; }
        .action-buttons .btn { padding: 2px 5px; font-size: 10px; }
        .action-buttons .btn i { font-size: 12px; }
        .status-badge { padding: 2px 8px; font-size: 9px; }
        .pagination-container { flex-direction: column; align-items: center; }
        .filter-section .row { gap: 10px; }
        .net-salary { font-size: 12px; }
        .summary-cards { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .summary-card .number { font-size: 18px; }
        .btn-action-top { font-size: 12px; padding: 5px 12px; }
        .statutory-info { font-size: 12px; padding: 8px 12px; }
        .btn-finalize { font-size: 10px; padding: 4px 10px; }
    }

    @media (max-width: 480px) {
        .summary-cards { grid-template-columns: 1fr 1fr; gap: 8px; }
        .summary-card { padding: 10px 12px; }
        .summary-card .number { font-size: 16px; }
        .summary-card .label { font-size: 10px; }
        .filter-section { padding: 12px; }
        .filter-section .btn-filter { height: 34px; font-size: 12px; padding: 6px 14px; }
        .table-payroll td { padding: 4px 4px; font-size: 10px; }
        .table-payroll th { font-size: 8px; padding: 4px 4px; }
        .employee-name { font-size: 11px; }
        .statutory-info .badge { font-size: 10px; padding: 3px 8px; }
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

            <!-- Side Menu -->
            <?php include('../includes/sideMenu.php'); ?>

            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Breadcrumb Box -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="../payroll/add.php" class="breadcrumb-item">
                                <i class="bx bx-briefcase"></i> Payroll
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-list-ul"></i> Payroll List
                            </span>
                        </div>

                        <!-- Page Title -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">
                                    <span class="text-muted fw-light"></span> 
                                </h4>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="add.php" class="btn-action-top btn-primary">
                                    <i class="bx bx-plus"></i> Run Payroll
                                </a>
                            </div>
                        </div>

                        <!-- Statutory Config Info -->
                        <div class="statutory-info">
                            <strong>📋 Current Statutory Rates:</strong>
                            <?php if (isset($statutoryConfig['PF'])): ?>
                                <span class="badge bg-primary">EPF: <?= $statutoryConfig['PF']['employee_contribution_pct'] ?>%</span>
                            <?php endif; ?>
                            <?php if (isset($statutoryConfig['ESI'])): ?>
                                <span class="badge bg-info">ESI: <?= $statutoryConfig['ESI']['employee_contribution_pct'] ?>%</span>
                            <?php endif; ?>
                            <?php if (isset($statutoryConfig['Professional Tax'])): ?>
                                <span class="badge bg-warning">PT: ₹<?= $statutoryConfig['Professional Tax']['fixed_amount'] ?></span>
                            <?php endif; ?>
                            <?php if (isset($statutoryConfig['TDS'])): ?>
                                <span class="badge bg-success">TDS: <?= $statutoryConfig['TDS']['employee_contribution_pct'] ?>%</span>
                            <?php endif; ?>
                            <?php if (empty($statutoryConfig)): ?>
                                <span class="text-muted">No statutory config found. <a href="../Statutory_Config/add.php">Add Config</a></span>
                            <?php endif; ?>
                        </div>

                        <!-- Summary Cards -->
                        <div class="summary-cards">
                            <div class="summary-card">
                                <span class="icon">👥</span>
                                <div class="number blue"><?= $summary['total_employees'] ?? 0 ?></div>
                                <div class="label">Total Employees</div>
                            </div>
                            <div class="summary-card">
                                <span class="icon">💰</span>
                                <div class="number green">₹ <?= number_format($summary['total_net'] ?? 0, 2) ?></div>
                                <div class="label">Total Net Payout</div>
                            </div>
                            <div class="summary-card">
                                <span class="icon">🏦</span>
                                <div class="number orange">₹ <?= number_format($summary['total_pf'] ?? 0, 2) ?></div>
                                <div class="label">Total EPF</div>
                            </div>
                            <div class="summary-card">
                                <span class="icon">🏥</span>
                                <div class="number purple">₹ <?= number_format($summary['total_esi'] ?? 0, 2) ?></div>
                                <div class="label">Total ESI</div>
                            </div>
                        </div>

                        <!-- Payroll Table -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card payroll-list-card">
                                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                        <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i>Payroll Records</h5>
                                        <div>
                                            <span class="total-badge">Total: <?= $total_records ?></span>
                                        </div>
                                    </div>

                                    <!-- Filter Section -->
                                    <div class="card-body">
                                        <div class="filter-section">
                                            <form method="GET" action="">
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Search Employee</label>
                                                        <input type="text" class="form-control" name="search"
                                                            placeholder="Search by name..."
                                                            value="<?= htmlspecialchars($search) ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Month</label>
                                                        <select name="month" class="form-select">
                                                            <option value="">All Months</option>
                                                            <?php foreach ($monthList as $month): ?>
                                                                <option value="<?= $month ?>" <?= $month_filter == $month ? 'selected' : '' ?>>
                                                                    <?= date('F Y', strtotime($month . '-01')) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="">All Status</option>
                                                            <option value="draft" <?= $status_filter == 'draft' ? 'selected' : '' ?>>Draft</option>
                                                            <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                            <option value="paid" <?= $status_filter == 'paid' ? 'selected' : '' ?>>Paid</option>
                                                            <option value="inactive" <?= $status_filter == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn-filter w-100">
                                                            <i class="bx bx-search me-1"></i> Filter
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php if ($search || $month_filter || $status_filter): ?>
                                                    <div class="mt-3">
                                                        <a href="list.php" class="btn-clear">
                                                            <i class="bx bx-reset me-1"></i> Clear Filters
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive">
                                            <table class="table table-payroll">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Employee</th>
                                                        <th>Emp ID</th>
                                                        <th>Pay Period</th>
                                                        <th>Basic</th>
                                                        <th>Gross</th>
                                                        <th>PF</th>
                                                        <th>ESI</th>
                                                        <th>PT</th>
                                                        <th>Other</th>
                                                        <th>Net Salary</th>
                                                        <th>Payable Days</th>
                                                        <th>Status</th>
                                                        <th style="text-align:center;">Finalize</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($result && mysqli_num_rows($result) > 0):
                                                        $counter = $offset + 1;
                                                        while ($row = mysqli_fetch_assoc($result)):
                                                            
                                                            // Get payment status
                                                            $payment_status = strtolower(trim($row['payment_status'] ?? 'draft'));
                                                            
                                                            $statusClass = 'status-draft';
                                                            $statusText = 'Draft';
                                                            
                                                            if ($payment_status === 'paid') {
                                                                $statusClass = 'status-paid';
                                                                $statusText = 'Paid';
                                                            } elseif ($payment_status === 'pending') {
                                                                $statusClass = 'status-pending';
                                                                $statusText = 'Pending';
                                                            } elseif ($payment_status === 'draft') {
                                                                $statusClass = 'status-draft';
                                                                $statusText = 'Draft';
                                                            }

                                                            $paymentDate = $row['payment_date'] ? date('d M Y', strtotime($row['payment_date'])) : '-';

                                                            // Calculate payable days
                                                            $payableDays = 0;
                                                            $totalDays = 0;
                                                            $dateRangeText = '';
                                                            
                                                            $from_date = $row['from_date'] ?? null;
                                                            $to_date = $row['to_date'] ?? null;
                                                            
                                                            if ($from_date && $to_date && $from_date != '0000-00-00' && $to_date != '0000-00-00') {
                                                                $from = new DateTime($from_date);
                                                                $to = new DateTime($to_date);
                                                                $to->modify('+1 day');
                                                                $interval = $from->diff($to);
                                                                $totalDays = $interval->days;
                                                                $dateRangeText = date('d M', strtotime($from_date)) . ' - ' . date('d M Y', strtotime($to_date));
                                                                
                                                                $attQuery = "SELECT 
                                                                                SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                                                                                SUM(CASE WHEN attendance_type_id = 3 THEN 0.5 ELSE 0 END) as half_days,
                                                                                SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                                                                                SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                                                                                SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
                                                                             FROM tbl_user_attendance 
                                                                             WHERE employee_id = '{$row['employee_id']}' 
                                                                             AND date BETWEEN '$from_date' AND '$to_date'
                                                                             AND status = '1'";
                                                                $attResult = $conn->query($attQuery);
                                                                $attData = $attResult->fetch_assoc();
                                                                
                                                                if ($attData && $attData['present_days'] > 0) {
                                                                    $present_days = (float) ($attData['present_days'] ?? 0);
                                                                    $half_days = (float) ($attData['half_days'] ?? 0);
                                                                    $leave_days = (float) ($attData['leave_days'] ?? 0);
                                                                    $holidays = (float) ($attData['holidays'] ?? 0);
                                                                    $week_offs = (float) ($attData['week_offs'] ?? 0);
                                                                    $payableDays = $present_days + $leave_days + $holidays + $week_offs + ($half_days * 0.5);
                                                                } else {
                                                                    $payableDays = (float) ($row['payable_days'] ?? 0);
                                                                }
                                                            } else {
                                                                $pay_month = $row['pay_month'];
                                                                $year = (int) substr($pay_month, 0, 4);
                                                                $monthNum = (int) substr($pay_month, 5);
                                                                $totalDays = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
                                                                $dateRangeText = 'Full Month';
                                                                
                                                                $attQuery = "SELECT 
                                                                                SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                                                                                SUM(CASE WHEN attendance_type_id = 3 THEN 0.5 ELSE 0 END) as half_days,
                                                                                SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                                                                                SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                                                                                SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
                                                                             FROM tbl_user_attendance 
                                                                             WHERE employee_id = '{$row['employee_id']}' 
                                                                             AND DATE_FORMAT(date, '%Y-%m') = '{$row['pay_month']}'
                                                                             AND status = '1'";
                                                                $attResult = $conn->query($attQuery);
                                                                $attData = $attResult->fetch_assoc();
                                                                
                                                                if ($attData && $attData['present_days'] > 0) {
                                                                    $present_days = (float) ($attData['present_days'] ?? 0);
                                                                    $half_days = (float) ($attData['half_days'] ?? 0);
                                                                    $leave_days = (float) ($attData['leave_days'] ?? 0);
                                                                    $holidays = (float) ($attData['holidays'] ?? 0);
                                                                    $week_offs = (float) ($attData['week_offs'] ?? 0);
                                                                    $payableDays = $present_days + $leave_days + $holidays + $week_offs + ($half_days * 0.5);
                                                                } else {
                                                                    $payableDays = (float) ($row['payable_days'] ?? 0);
                                                                }
                                                            }
                                                            
                                                            $payableDaysFormatted = number_format($payableDays, 1);
                                                            ?>
                                                            <tr>
                                                                <td><?= $counter++ ?></td>
                                                                <td>
                                                                    <div class="employee-name">
                                                                        <?= htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) ?>
                                                                    </div>
                                                                    <div class="employee-id">
                                                                        <?= htmlspecialchars($row['username'] ?? '') ?>
                                                                    </div>
                                                                </td>
                                                                <td><?= htmlspecialchars($row['username'] ?? $row['employee_id']) ?></td>
                                                                <td>
                                                                    <?= date('M Y', strtotime($row['pay_month'] . '-01')) ?>
                                                                    <?php if ($dateRangeText && $dateRangeText != 'Full Month'): ?>
                                                                        <br><small class="text-muted"><?= $dateRangeText ?></small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>₹<?= number_format($row['basic_salary'], 2) ?></td>
                                                                <td>₹<?= number_format($row['gross_salary'], 2) ?></td>
                                                                <td>₹<?= number_format($row['pf_deduction'], 2) ?></td>
                                                                <td>₹<?= number_format($row['esi_deduction'], 2) ?></td>
                                                                <td>₹<?= number_format($row['pt_deduction'], 2) ?></td>
                                                                <td>₹<?= number_format($row['other_deduction'], 2) ?></td>
                                                                <td>
                                                                    <span class="net-salary">₹<?= number_format($row['net_salary'], 2) ?></span>
                                                                </td>
                                                                <td>
                                                                    <span class="payable-days-badge">
                                                                        <?= $payableDaysFormatted ?> / <?= $totalDays ?> days
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="status-badge <?= $statusClass ?>">
                                                                        <?= $statusText ?>
                                                                    </span>
                                                                    <?php if ($payment_status === 'paid'): ?>
                                                                        <br><small class="text-muted"><?= $paymentDate ?></small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <!-- ====== FINALIZE BUTTON COLUMN - ADDED ====== -->
                                                                <td style="text-align:center;">
                                                                    <?php if ($payment_status !== 'paid' && $payment_status !== 'finalized'): ?>
                                                                        <button type="button" class="btn-finalize" 
                                                                                onclick="showFinalizePopup(event, <?= $row['id'] ?>, '<?= addslashes($row['firstName'] . ' ' . $row['lastName']) ?>', '<?= date('M Y', strtotime($row['pay_month'] . '-01')) ?>', '<?= number_format($row['net_salary'], 2) ?>')">
                                                                            <i class="bx bx-check-circle"></i> Finalize
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <span class="badge-finalized">
                                                                            <i class="bx bx-check-circle"></i> Finalized
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <!-- ====== END FINALIZE COLUMN ====== -->
                                                                <td class="text-center">
                                                                    <div class="btn-group action-buttons" role="group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-secondary dropdown-toggle"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a class="dropdown-item" href="view.php?id=<?= $row['id'] ?>">
                                                                                    <i class="bx bx-show"></i> View
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item" href="edit.php?id=<?= $row['id'] ?>">
                                                                                    <i class="bx bx-edit"></i> Edit
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item" href="payslip.php?id=<?= $row['id'] ?>">
                                                                                    <i class="bx bx-receipt"></i> Payslip
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <hr class="dropdown-divider">
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                                                    onclick="showDeletePopup(event, <?= $row['id'] ?>, '<?= addslashes($row['firstName'] . ' ' . $row['lastName']) ?>', '<?= date('M Y', strtotime($row['pay_month'] . '-01')) ?>', '<?= number_format($row['net_salary'], 2) ?>')">
                                                                                    <i class="bx bx-trash"></i> Delete
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="16" class="text-center py-4">
                                                                <div class="empty-state">
                                                                    <i class="bx bx-receipt"></i>
                                                                    <h5>No Payroll Records Found</h5>
                                                                    <p>Click "Run Payroll" to create your first payroll record.</p>
                                                                    <a href="add.php" class="btn btn-primary btn-sm mt-2">
                                                                        <i class="bx bx-plus me-1"></i> Run Payroll
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <?php if ($total_records > 0): ?>
                                            <div class="pagination-container">
                                                <div class="info">
                                                    Showing <?= $offset + 1 ?> to
                                                    <?= min($offset + $limit, $total_records) ?> of <?= $total_records ?>
                                                    entries
                                                </div>
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination">
                                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                                            <a class="page-link" href="?page=<?= $page - 1 ?><?= $search ? '&search=' . $search : '' ?><?= $month_filter ? '&month=' . $month_filter : '' ?><?= $status_filter ? '&status=' . $status_filter : '' ?>">
                                                                Previous
                                                            </a>
                                                        </li>
                                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                                <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . $search : '' ?><?= $month_filter ? '&month=' . $month_filter : '' ?><?= $status_filter ? '&status=' . $status_filter : '' ?>">
                                                                    <?= $i ?>
                                                                </a>
                                                            </li>
                                                        <?php endfor; ?>
                                                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                                            <a class="page-link" href="?page=<?= $page + 1 ?><?= $search ? '&search=' . $search : '' ?><?= $month_filter ? '&month=' . $month_filter : '' ?><?= $status_filter ? '&status=' . $status_filter : '' ?>">
                                                                Next
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        <?php endif; ?>
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
    </div>

    <!-- ============================================================
         DELETE CONFIRMATION POPUP
    ============================================================ -->
    <div class="delete-popup-overlay" id="deletePopup">
        <div class="delete-popup">
            <button class="close-btn" onclick="closeDeletePopup()">&times;</button>

            <span class="icon danger">
                <i class="bx bx-trash"></i>
            </span>

            <div class="title danger">Delete Payroll Record</div>

            <div class="message">
                Are you sure you want to delete this payroll record?<br>
                This action <strong>cannot be undone</strong>.
            </div>

            <div class="warning-text danger">
                <i class="bx bx-error-circle"></i>
                <strong>Warning:</strong> This will permanently remove this payroll record.
            </div>

            <div class="record-details" id="recordDetails">
                <div class="row">
                    <span class="label">Employee</span>
                    <span class="value" id="popupEmployeeName">-</span>
                </div>
                <div class="row">
                    <span class="label">Pay Month</span>
                    <span class="value" id="popupPayMonth">-</span>
                </div>
                <div class="row">
                    <span class="label">Net Salary</span>
                    <span class="value" id="popupNetSalary">-</span>
                </div>
            </div>

            <div class="btn-group-popup">
                <button class="btn-confirm danger" id="confirmDeleteBtn">
                    <i class="bx bx-trash"></i> Yes, Delete
                </button>
                <button class="btn-cancel-popup" onclick="closeDeletePopup()">
                    <i class="bx bx-x"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================
         FINALIZE CONFIRMATION POPUP - ADDED
    ============================================================ -->
    <div class="delete-popup-overlay" id="finalizePopup">
        <div class="delete-popup">
            <button class="close-btn" onclick="closeFinalizePopup()">&times;</button>

            <span class="icon success">
                <i class="bx bx-check-circle"></i>
            </span>

            <div class="title success">Finalize Payroll</div>

            <div class="message">
                Are you sure you want to <strong>finalize</strong> this payroll record?<br>
                This will mark it as <strong>Paid</strong> and lock it from further edits.
            </div>

            <div class="warning-text success">
                <i class="bx bx-info-circle"></i>
                <strong>Note:</strong> Finalized payrolls cannot be edited or deleted.
            </div>

            <div class="record-details" id="finalizeRecordDetails">
                <div class="row">
                    <span class="label">Employee</span>
                    <span class="value" id="finalizeEmployeeName">-</span>
                </div>
                <div class="row">
                    <span class="label">Pay Month</span>
                    <span class="value" id="finalizePayMonth">-</span>
                </div>
                <div class="row">
                    <span class="label">Net Salary</span>
                    <span class="value" id="finalizeNetSalary">-</span>
                </div>
            </div>

            <div class="btn-group-popup">
                <button class="btn-confirm success" id="confirmFinalizeBtn">
                    <i class="bx bx-check-circle"></i> Yes, Finalize
                </button>
                <button class="btn-cancel-popup" onclick="closeFinalizePopup()">
                    <i class="bx bx-x"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
        // ============================================================
        // SIDE MENU SCROLLING & RESPONSIVE
        // ============================================================
        document.addEventListener('DOMContentLoaded', function () {
            const sideMenu = document.querySelector('.layout-menu');
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const menuOverlay = document.getElementById('menuOverlay');

            if (sideMenu) {
                sideMenu.addEventListener('scroll', function () {
                    if (this.scrollTop > 20) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            }

            if (menuToggleBtn) {
                menuToggleBtn.addEventListener('click', function (e) {
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
                menuOverlay.addEventListener('click', function () {
                    sideMenu.classList.remove('show');
                    this.classList.remove('show');
                    if (menuToggleBtn) {
                        menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sideMenu && sideMenu.classList.contains('show')) {
                    sideMenu.classList.remove('show');
                    if (menuOverlay) menuOverlay.classList.remove('show');
                    if (menuToggleBtn) menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                }
            });

            if (window.innerWidth <= 768) {
                const menuLinks = document.querySelectorAll('.menu-link');
                menuLinks.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        const parent = this.closest('.menu-item');
                        if (parent && parent.querySelector('.submenu')) {
                            e.preventDefault();
                            parent.classList.toggle('open');
                        }
                    });
                });
            }
        });

        // ============================================================
        // DELETE POPUP FUNCTIONS
        // ============================================================
        let deleteId = null;

        function showDeletePopup(event, id, name, month, salary) {
            event.preventDefault();
            event.stopPropagation();

            deleteId = id;
            document.getElementById('popupEmployeeName').textContent = name;
            document.getElementById('popupPayMonth').textContent = month;
            document.getElementById('popupNetSalary').textContent = '₹ ' + salary;
            document.getElementById('deletePopup').classList.add('active');

            document.getElementById('confirmDeleteBtn').onclick = function () {
                if (deleteId) {
                    window.location.href = 'list.php?delete_id=' + deleteId;
                }
            };
        }

        function closeDeletePopup() {
            document.getElementById('deletePopup').classList.remove('active');
            deleteId = null;
        }

        // ============================================================
        // FINALIZE POPUP FUNCTIONS - ADDED
        // ============================================================
        let finalizeId = null;

        function showFinalizePopup(event, id, name, month, salary) {
            event.preventDefault();
            event.stopPropagation();

            finalizeId = id;
            document.getElementById('finalizeEmployeeName').textContent = name;
            document.getElementById('finalizePayMonth').textContent = month;
            document.getElementById('finalizeNetSalary').textContent = '₹ ' + salary;
            document.getElementById('finalizePopup').classList.add('active');

            document.getElementById('confirmFinalizeBtn').onclick = function () {
                if (finalizeId) {
                    window.location.href = 'list.php?finalize_id=' + finalizeId;
                }
            };
        }

        function closeFinalizePopup() {
            document.getElementById('finalizePopup').classList.remove('active');
            finalizeId = null;
        }

        // Close popups on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDeletePopup();
                closeFinalizePopup();
            }
        });

        // Close popups when clicking outside
        document.getElementById('deletePopup').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDeletePopup();
            }
        });

        document.getElementById('finalizePopup').addEventListener('click', function (e) {
            if (e.target === this) {
                closeFinalizePopup();
            }
        });
    </script>

</body>

</html>