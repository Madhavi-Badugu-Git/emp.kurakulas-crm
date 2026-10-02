<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Set session variables if not set (for demo purposes)
if (!isset($_SESSION['user_rank'])) {
    $_SESSION['user_rank'] = 'admin';
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

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

    .layout-sidebar {
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
       EMPLOYEE LIST STYLES
    ============================================================ */
    .employee-list-card {
        margin-top: 25px;
    }

    .employee-list-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .employee-list-wrapper::-webkit-scrollbar {
        height: 10px;
    }
    .employee-list-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .employee-list-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }
    .employee-list-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a0a7ae;
    }

    .employee-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .employee-table thead th {
        height: 42px;
        background: #f8f9fc;
        color: #1a2332;
        font-weight: 600;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
        padding: 8px 16px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .employee-table thead th:last-child {
        text-align: center;
    }

    .employee-table tbody td {
        height: 52px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        background: #ffffff;
        padding: 8px 16px;
        transition: background 0.15s ease;
    }

    .employee-table tbody tr:hover td {
        background: #f8fafc;
    }

    .employee-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Employee Avatar */
    .employee-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #696cff;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }

    .employee-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-name-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .employee-name {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
    }

    .employee-username {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }

    .employee-username .username-label {
        color: #9ca3af;
    }

    /* Badge Styles */
    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 60px;
    }

    .badge-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .badge-pending {
        background: #fef9c3;
        color: #713f12;
        border: 1px solid #fde68a;
    }

    .badge-suspended {
        background: #fef3e8;
        color: #b37400;
        border: 1px solid #fde8d0;
    }

    /* Rank Badges */
    .badge-rank {
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-rank-admin {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .badge-rank-superadmin {
        background: #ede9fe;
        color: #5b21b6;
        border: 1px solid #ddd6fe;
    }

    .badge-rank-user {
        background: #f1f3f5;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    .badge-rank-manager {
        background: #fef9c3;
        color: #713f12;
        border: 1px solid #fde68a;
    }

    /* Department Tag */
    .dept-tag {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        background: #f1f3f5;
        color: #495057;
        border: 1px solid #e9ecef;
    }

    .dept-tag-engineering {
        background: #dbeafe;
        color: #1e40af;
        border-color: #bfdbfe;
    }

    .dept-tag-hr {
        background: #fce4ec;
        color: #b71c1c;
        border-color: #f8bbd0;
    }

    .dept-tag-sales {
        background: #e8f5e9;
        color: #1b5e20;
        border-color: #c8e6c9;
    }

    .dept-tag-marketing {
        background: #fef9c3;
        color: #713f12;
        border-color: #fde68a;
    }

    .dept-tag-finance {
        background: #e0f7fa;
        color: #006064;
        border-color: #b2ebf2;
    }

    .dept-tag-it {
        background: #ede9fe;
        color: #5b21b6;
        border-color: #ddd6fe;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    /* REMOVED: action-btn-view */

    .action-btn-salary {
        background: #dcfce7;
        color: #166534;
    }
    .action-btn-salary:hover {
        background: #bbf7d0;
        color: #14532d;
        transform: scale(1.1);
    }

    .action-btn i {
        font-size: 16px;
    }

    /* ============================================================
       FILTER & SEARCH
    ============================================================ */
    .employee-filter {
        background: #f8fafc;
        padding: 16px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        border: 1px solid #e9ecef;
    }

    .employee-filter .form-select,
    .employee-filter .form-control {
        border-color: #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        padding: 8px 14px;
        background-color: #ffffff;
    }

    .employee-filter .form-select:focus,
    .employee-filter .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.1);
    }

    .employee-filter .btn-filter {
        background: #696cff;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .employee-filter .btn-filter:hover {
        background: #5a5de0;
        color: #ffffff;
    }

    .employee-filter .btn-reset-filter {
        background: #e9ecef;
        color: #495057;
        border: none;
        border-radius: 8px;
        padding: 8px 24px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .employee-filter .btn-reset-filter:hover {
        background: #dde1e6;
        color: #1a2332;
    }

    /* ============================================================
       BREADCRUMB
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
       PAGINATION
    ============================================================ */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
    }

    .pagination-info {
        font-size: 14px;
        color: #6c757d;
    }

    .pagination-info strong {
        color: #1a2332;
    }

    .pagination-buttons {
        display: flex;
        gap: 4px;
    }

    .pagination-buttons .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #ffffff;
        color: #1a2332;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .pagination-buttons .page-btn:hover {
        background: #f1f3f5;
        border-color: #dde1e6;
    }

    .pagination-buttons .page-btn.active {
        background: #696cff;
        color: #ffffff;
        border-color: #696cff;
    }

    .pagination-buttons .page-btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 992px) {
        .employee-table {
            font-size: 13px;
        }
        .employee-table thead th,
        .employee-table tbody td {
            padding: 6px 12px;
        }
        .action-btn {
            width: 30px;
            height: 30px;
            font-size: 13px;
        }
        .action-btn i {
            font-size: 14px;
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

        .employee-table thead th,
        .employee-table tbody td {
            padding: 6px 8px;
            font-size: 12px;
        }

        .employee-avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .employee-name {
            font-size: 12px;
        }
        .employee-username {
            font-size: 10px;
        }

        .badge-status {
            font-size: 10px;
            padding: 3px 10px;
            min-width: 50px;
        }

        .action-btn {
            width: 26px;
            height: 26px;
            font-size: 11px;
        }
        .action-btn i {
            font-size: 12px;
        }

        .employee-filter .row {
            gap: 10px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }
        .pagination-info {
            text-align: center;
        }
        .pagination-buttons {
            justify-content: center;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .employee-table thead th,
        .employee-table tbody td {
            padding: 4px 6px;
            font-size: 11px;
        }
        .employee-avatar {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
        .employee-name {
            font-size: 11px;
        }
        .employee-username {
            font-size: 9px;
        }
        .badge-status {
            font-size: 9px;
            padding: 2px 8px;
            min-width: 40px;
        }
        .action-btn {
            width: 22px;
            height: 22px;
            font-size: 10px;
        }
        .action-btn i {
            font-size: 10px;
        }
        .dept-tag {
            font-size: 10px;
            padding: 2px 8px;
        }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu - Sticky -->
            <div class="layout-sidebar" id="sidebar">
                <?php include('../includes/sideMenu.php'); ?>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <!-- Page Content -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <?php
                // Get logged in user info
                $loggedInUserRank = $_SESSION['user_rank'] ?? 'admin';
                $loggedInUserId = $_SESSION['user_id'] ?? 0;

                // Pagination variables
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
                $offset = ($page - 1) * $limit;

                // Filter variables
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                $departmentFilter = isset($_GET['department']) ? trim($_GET['department']) : '';
                $rankFilter = isset($_GET['rank']) ? trim($_GET['rank']) : '';

                // Check if columns exist in tbl_user
                $columns = [];
                $columnQuery = "SHOW COLUMNS FROM tbl_user";
                $columnResult = mysqli_query($conn, $columnQuery);
                if ($columnResult) {
                    while ($col = mysqli_fetch_assoc($columnResult)) {
                        $columns[] = $col['Field'];
                    }
                }

                // Check for column existence
                $hasEmail = in_array('email', $columns);
                $hasMobile = in_array('mobile', $columns);
                $hasDepartment = in_array('department', $columns);
                $hasDesignation = in_array('designation', $columns);
                $hasDepartmentId = in_array('department_id', $columns);
                $hasDesignationId = in_array('designation_id', $columns);
                $hasRank = in_array('rank', $columns);
                $hasStatus = in_array('status', $columns);
                $hasCreatedAt = in_array('created_at', $columns);
                $hasFirstName = in_array('firstName', $columns);
                $hasLastName = in_array('lastName', $columns);
                $hasUsername = in_array('username', $columns);
                $hasId = in_array('id', $columns);

                // Check if department and designation tables exist
                $deptTableExists = false;
                $designationTableExists = false;
                
                $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_department'");
                if ($tableCheck && mysqli_num_rows($tableCheck) > 0) {
                    $deptTableExists = true;
                }
                
                $tableCheck2 = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_designation'");
                if ($tableCheck2 && mysqli_num_rows($tableCheck2) > 0) {
                    $designationTableExists = true;
                }

                // Build the base query dynamically
                $selectFields = [];
                if ($hasId) $selectFields[] = "u.id";
                if ($hasUsername) $selectFields[] = "u.username";
                if ($hasFirstName) $selectFields[] = "u.firstName";
                if ($hasLastName) $selectFields[] = "u.lastName";
                if ($hasEmail) $selectFields[] = "u.email";
                if ($hasMobile) $selectFields[] = "u.mobile";
                if ($hasStatus) $selectFields[] = "u.status";
                if ($hasCreatedAt) $selectFields[] = "u.created_at";
                if ($hasRank) $selectFields[] = "u.rank";

                // Get department and designation from foreign key tables
                $fromClause = "FROM tbl_user u";
                $joinClause = "";

                // Check if department_id exists and department table exists
                if ($hasDepartmentId && $deptTableExists) {
                    $joinClause .= " LEFT JOIN tbl_department d ON u.department_id = d.id";
                    $selectFields[] = "d.department_name AS department";
                } elseif ($hasDepartment) {
                    $selectFields[] = "u.department";
                } else {
                    $selectFields[] = "'' as department";
                }

                // Check if designation_id exists and designation table exists
                if ($hasDesignationId && $designationTableExists) {
                    $joinClause .= " LEFT JOIN tbl_designation des ON u.designation_id = des.id";
                    $selectFields[] = "des.designation_name AS designation";
                } elseif ($hasDesignation) {
                    $selectFields[] = "u.designation";
                } else {
                    $selectFields[] = "'' as designation";
                }

                $selectFieldsStr = implode(", ", $selectFields);

                // ONLY ACTIVE EMPLOYEES (status = '1')
                $whereClause = "WHERE u.status = '1'";
                
                // Exclude superadmin
                if ($hasRank) {
                    $whereClause .= " AND u.rank != 'superadmin'";
                }

                // Apply filters
                if (!empty($search)) {
                    $searchEscaped = mysqli_real_escape_string($conn, $search);
                    $searchConditions = [];
                    if ($hasUsername) $searchConditions[] = "u.username LIKE '%$searchEscaped%'";
                    if ($hasFirstName) $searchConditions[] = "u.firstName LIKE '%$searchEscaped%'";
                    if ($hasLastName) $searchConditions[] = "u.lastName LIKE '%$searchEscaped%'";
                    if ($hasEmail) $searchConditions[] = "u.email LIKE '%$searchEscaped%'";
                    if ($hasMobile) $searchConditions[] = "u.mobile LIKE '%$searchEscaped%'";
                    if (!empty($searchConditions)) {
                        $whereClause .= " AND (" . implode(" OR ", $searchConditions) . ")";
                    }
                }

                if (!empty($departmentFilter)) {
                    $deptEscaped = mysqli_real_escape_string($conn, $departmentFilter);
                    if ($hasDepartmentId && $deptTableExists) {
                        $whereClause .= " AND d.department_name = '$deptEscaped'";
                    } elseif ($hasDepartment) {
                        $whereClause .= " AND u.department = '$deptEscaped'";
                    }
                }

                if (!empty($rankFilter) && $hasRank) {
                    $rankEscaped = mysqli_real_escape_string($conn, $rankFilter);
                    $whereClause .= " AND u.rank = '$rankEscaped'";
                }

                // Count total records
                $countQuery = "SELECT COUNT(*) as total $fromClause $joinClause $whereClause";
                $countResult = mysqli_query($conn, $countQuery);
                $totalRecords = 0;
                if ($countResult && mysqli_num_rows($countResult) > 0) {
                    $totalRecords = (int)mysqli_fetch_assoc($countResult)['total'];
                }
                $totalPages = ceil($totalRecords / $limit);

                // Get employees with pagination
                $employeeQuery = "SELECT $selectFieldsStr $fromClause $joinClause $whereClause ORDER BY u.firstName ASC, u.lastName ASC LIMIT $offset, $limit";
                $employeeResult = mysqli_query($conn, $employeeQuery);
                $employees = [];
                if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
                    while ($row = mysqli_fetch_assoc($employeeResult)) {
                        $employees[] = $row;
                    }
                }

                // Get distinct departments for filter dropdown from department table
                $departments = [];
                if ($hasDepartmentId && $deptTableExists) {
                    $deptQuery = "SELECT DISTINCT d.department_name FROM tbl_department d 
                                  INNER JOIN tbl_user u ON u.department_id = d.id 
                                  WHERE u.status = '1'";
                    if ($hasRank) {
                        $deptQuery .= " AND u.rank != 'superadmin'";
                    }
                    $deptResult = mysqli_query($conn, $deptQuery);
                    if ($deptResult) {
                        while ($row = mysqli_fetch_assoc($deptResult)) {
                            if (!empty($row['department_name'])) {
                                $departments[] = $row['department_name'];
                            }
                        }
                    }
                } elseif ($hasDepartment) {
                    $deptQuery = "SELECT DISTINCT u.department FROM tbl_user u WHERE u.department IS NOT NULL AND u.department != '' AND u.status = '1'";
                    if ($hasRank) {
                        $deptQuery .= " AND u.rank != 'superadmin'";
                    }
                    $deptResult = mysqli_query($conn, $deptQuery);
                    if ($deptResult) {
                        while ($row = mysqli_fetch_assoc($deptResult)) {
                            if (!empty($row['department'])) {
                                $departments[] = $row['department'];
                            }
                        }
                    }
                }

                // Get distinct ranks for filter dropdown
                $ranks = [];
                if ($hasRank) {
                    $rankQuery = "SELECT DISTINCT u.rank FROM tbl_user u WHERE u.rank IS NOT NULL AND u.rank != '' AND u.rank != 'superadmin' AND u.status = '1'";
                    $rankResult = mysqli_query($conn, $rankQuery);
                    if ($rankResult) {
                        while ($row = mysqli_fetch_assoc($rankResult)) {
                            if (!empty($row['rank'])) {
                                $ranks[] = $row['rank'];
                            }
                        }
                    }
                }

                $pageTitle = 'Active Employee List';

                // Get current page name for reset button
                $currentPage = basename($_SERVER['PHP_SELF']);
                ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="#" class="breadcrumb-item">
                                <i class="bx bx-user"></i> Payroll Master
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-group"></i>salary
                            </span>
                        </div>

                        <!-- Page Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                            <h4 class="fw-bold py-3 mb-0">
                                <i class="bx bx-group me-2"></i> Active Employee List
                                <span class="badge bg-success ms-2"><?= $totalRecords ?></span>
                            </h4>
                        </div>

                        <!-- Employee List Card -->
                        <div class="row employee-list-card">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="bx bx-list-ul me-2"></i>
                                            Active Employees
                                        </h5>
                                    </div>
                                    <div class="card-body">

                                        <!-- Filter Section -->
                                        <div class="employee-filter">
                                            <form method="GET" action="" id="filterForm">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-md-3 col-sm-6">
                                                        <label class="form-label fw-semibold mb-1" style="font-size: 13px; color: #495057;">
                                                            <i class="bx bx-search me-1"></i> Search
                                                        </label>
                                                        <input type="text" name="search" class="form-control" 
                                                               placeholder="Name, ID, Phone..." 
                                                               value="<?= htmlspecialchars($search) ?>">
                                                    </div>
                                                    <div class="col-md-3 col-sm-6">
                                                        <label class="form-label fw-semibold mb-1" style="font-size: 13px; color: #495057;">
                                                            <i class="bx bx-building me-1"></i> Department
                                                        </label>
                                                        <select name="department" class="form-select">
                                                            <option value="">All Departments</option>
                                                            <?php foreach ($departments as $dept): ?>
                                                                <option value="<?= htmlspecialchars($dept) ?>" <?= ($departmentFilter == $dept) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($dept) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <?php if ($hasRank): ?>
                                                    <div class="col-md-3 col-sm-6">
                                                        <label class="form-label fw-semibold mb-1" style="font-size: 13px; color: #495057;">
                                                            <i class="bx bx-shield me-1"></i> Rank
                                                        </label>
                                                        <select name="rank" class="form-select">
                                                            <option value="">All Ranks</option>
                                                            <?php foreach ($ranks as $rank): ?>
                                                                <option value="<?= htmlspecialchars($rank) ?>" <?= ($rankFilter == $rank) ? 'selected' : '' ?>>
                                                                    <?= ucfirst(htmlspecialchars($rank)) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="col-md-3 col-sm-12">
                                                        <div class="d-flex gap-2">
                                                            <button type="submit" class="btn btn-filter w-100">
                                                                <i class="bx bx-search me-1"></i> Filter
                                                            </button>
                                                            <a href="<?= $currentPage ?>" class="btn btn-reset-filter w-100">
                                                                <i class="bx bx-refresh me-1"></i> Reset
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Employee Table -->
                                        <div class="employee-list-wrapper">
                                            <table class="employee-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>Employee</th>
                                                        <?php if ($hasMobile): ?>
                                                        <th>Phone</th>
                                                        <?php endif; ?>
                                                        <th>Department</th>
                                                        <th>Designation</th>
                                                        <?php if ($hasRank): ?>
                                                        <th>Rank</th>
                                                        <?php endif; ?>
                                                        <th style="width: 80px; text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($employees)): ?>
                                                        <?php 
                                                        $counter = $offset + 1;
                                                        foreach ($employees as $employee): 
                                                            $employeeId = (int) ($employee['id'] ?? 0);
                                                            
                                                            // Build full name
                                                            $fullName = '';
                                                            if (isset($employee['firstName']) && isset($employee['lastName'])) {
                                                                $fullName = trim($employee['firstName'] . ' ' . $employee['lastName']);
                                                            }
                                                            if (empty($fullName)) {
                                                                $fullName = $employee['username'] ?? 'Unknown';
                                                            }
                                                            
                                                            $username = $employee['username'] ?? 'N/A';
                                                            $mobile = $employee['mobile'] ?? 'N/A';
                                                            
                                                            $department = isset($employee['department']) && !empty($employee['department']) ? $employee['department'] : 'N/A';
                                                            $designation = isset($employee['designation']) && !empty($employee['designation']) ? $employee['designation'] : 'N/A';
                                                            
                                                            $rank = isset($employee['rank']) && !empty($employee['rank']) ? $employee['rank'] : 'user';
                                                            
                                                            // Get initials for avatar
                                                            $initials = '';
                                                            $nameParts = explode(' ', $fullName);
                                                            foreach ($nameParts as $part) {
                                                                if (!empty($part)) {
                                                                    $initials .= strtoupper($part[0]);
                                                                }
                                                            }
                                                            if (empty($initials)) {
                                                                $initials = strtoupper(substr($username, 0, 1));
                                                            }
                                                            $initials = substr($initials, 0, 2);
                                                            
                                                            // Rank badge
                                                            $rankBadge = '';
                                                            switch (strtolower($rank)) {
                                                                case 'admin':
                                                                    $rankBadge = '<span class="badge-rank badge-rank-admin">Admin</span>';
                                                                    break;
                                                                case 'superadmin':
                                                                    $rankBadge = '<span class="badge-rank badge-rank-superadmin">Super Admin</span>';
                                                                    break;
                                                                case 'manager':
                                                                    $rankBadge = '<span class="badge-rank badge-rank-manager">Manager</span>';
                                                                    break;
                                                                default:
                                                                    $rankBadge = '<span class="badge-rank badge-rank-user">User</span>';
                                                                    break;
                                                            }
                                                            
                                                            // Department tag color
                                                            $deptClass = 'dept-tag';
                                                            $deptLower = strtolower($department);
                                                            if (strpos($deptLower, 'engineering') !== false || strpos($deptLower, 'it') !== false || strpos($deptLower, 'technology') !== false) {
                                                                $deptClass .= ' dept-tag-it';
                                                            } elseif (strpos($deptLower, 'hr') !== false || strpos($deptLower, 'human') !== false) {
                                                                $deptClass .= ' dept-tag-hr';
                                                            } elseif (strpos($deptLower, 'sales') !== false) {
                                                                $deptClass .= ' dept-tag-sales';
                                                            } elseif (strpos($deptLower, 'marketing') !== false) {
                                                                $deptClass .= ' dept-tag-marketing';
                                                            } elseif (strpos($deptLower, 'finance') !== false || strpos($deptLower, 'account') !== false) {
                                                                $deptClass .= ' dept-tag-finance';
                                                            } elseif (strpos($deptLower, 'engineering') !== false) {
                                                                $deptClass .= ' dept-tag-engineering';
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td><?= $counter ?></td>
                                                                <td>
                                                                    <div class="employee-info-cell">
                                                                        <div class="employee-avatar">
                                                                            <?= htmlspecialchars($initials) ?>
                                                                        </div>
                                                                        <div class="employee-name-details">
                                                                            <div class="employee-name"><?= htmlspecialchars($fullName) ?></div>
                                                                            <div class="employee-username">
                                                                                <span class="username-label">ID:</span> <?= htmlspecialchars($username) ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <?php if ($hasMobile): ?>
                                                                <td><?= htmlspecialchars($mobile) ?></td>
                                                                <?php endif; ?>
                                                                <td><span class="<?= $deptClass ?>"><?= htmlspecialchars($department) ?></span></td>
                                                                <td><?= htmlspecialchars($designation) ?></td>
                                                                <?php if ($hasRank): ?>
                                                                <td><?= $rankBadge ?></td>
                                                                <?php endif; ?>
                                                                <td style="text-align: center;">
                                                                    <div class="action-buttons">
                                                                        <!-- REMOVED: View Employee Button -->
                                                                        <a href="../attendance/salary.php?employee_id=<?= $employeeId ?>" 
                                                                           class="action-btn action-btn-salary" 
                                                                           title="View Salary">
                                                                            <i class="bx bx-dollar-circle"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                            $counter++;
                                                        endforeach; 
                                                        ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="10" class="text-center py-4">
                                                                <span class="text-muted">No active employees found.</span>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <?php if ($totalPages > 1): ?>
                                        <div class="pagination-wrapper">
                                            <div class="pagination-info">
                                                Showing <strong><?= $offset + 1 ?></strong> to 
                                                <strong><?= min($offset + $limit, $totalRecords) ?></strong> 
                                                of <strong><?= $totalRecords ?></strong> active employees
                                            </div>
                                            <div class="pagination-buttons">
                                                <?php if ($page > 1): ?>
                                                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($departmentFilter) ?>&rank=<?= urlencode($rankFilter) ?>" 
                                                       class="page-btn">
                                                        <i class="bx bx-chevron-left"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="page-btn disabled"><i class="bx bx-chevron-left"></i></span>
                                                <?php endif; ?>

                                                <?php
                                                $startPage = max(1, $page - 2);
                                                $endPage = min($totalPages, $page + 2);
                                                if ($startPage > 1) {
                                                    echo '<a href="?page=1&search=' . urlencode($search) . '&department=' . urlencode($departmentFilter) . '&rank=' . urlencode($rankFilter) . '" class="page-btn">1</a>';
                                                    if ($startPage > 2) {
                                                        echo '<span class="page-btn disabled">...</span>';
                                                    }
                                                }
                                                for ($i = $startPage; $i <= $endPage; $i++):
                                                ?>
                                                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($departmentFilter) ?>&rank=<?= urlencode($rankFilter) ?>" 
                                                       class="page-btn <?= ($i == $page) ? 'active' : '' ?>">
                                                        <?= $i ?>
                                                    </a>
                                                <?php endfor; ?>
                                                <?php if ($endPage < $totalPages): ?>
                                                    <?php if ($endPage < $totalPages - 1): ?>
                                                        <span class="page-btn disabled">...</span>
                                                    <?php endif; ?>
                                                    <a href="?page=<?= $totalPages ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($departmentFilter) ?>&rank=<?= urlencode($rankFilter) ?>" 
                                                       class="page-btn">
                                                        <?= $totalPages ?>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($page < $totalPages): ?>
                                                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($departmentFilter) ?>&rank=<?= urlencode($rankFilter) ?>" 
                                                       class="page-btn">
                                                        <i class="bx bx-chevron-right"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="page-btn disabled"><i class="bx bx-chevron-right"></i></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                    </div>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

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

        // Auto-submit filter on Enter key
        document.addEventListener('DOMContentLoaded', function() {
            const filterInputs = document.querySelectorAll('#filterForm input, #filterForm select');
            filterInputs.forEach(function(input) {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            });
        });
    </script>

</body>

</html>