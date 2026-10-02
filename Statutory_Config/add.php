<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

if(!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// ============================================================
// HANDLE FORM SUBMISSION (Moved to top, before any HTML output)
// ============================================================
if (isset($_POST['form_submit'])) {
    // Get form data
    $epf_employee_pct = (float)$_POST['epf_employee_pct'];
    $epf_employer_pct = (float)$_POST['epf_employer_pct'];
    $epf_wage_ceiling = (float)$_POST['epf_wage_ceiling'];
    $esi_employee_pct = (float)$_POST['esi_employee_pct'];
    $esi_employer_pct = (float)$_POST['esi_employer_pct'];
    $esi_wage_ceiling = (float)$_POST['esi_wage_ceiling'];
    
    // Professional Tax fields
    $pt_employee_amount = (float)$_POST['pt_employee_amount'];
    $pt_employer_amount = (float)$_POST['pt_employer_amount'];
    $pt_wage_ceiling = (float)$_POST['pt_wage_ceiling'];
    
    $effective_from = mysqli_real_escape_string($conn, $_POST['effective_from']);
    $created_at = date('Y-m-d H:i:s');

    // Check if config already exists for this effective date
    $checkSql = "SELECT id FROM tbl_statutory_config 
                 WHERE effective_from = '$effective_from' 
                 AND config_name IN ('PF', 'ESI', 'PT')
                 AND status = '1'";
    $checkResult = mysqli_query($conn, $checkSql);
    
    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        // ============================================================
        // UPDATE EXISTING CONFIG
        // ============================================================
        
        // PF - percentage based on basic_salary
        $updateSql1 = "UPDATE tbl_statutory_config SET 
                        applicable_on = 'basic_salary',
                        employee_contribution_pct = '$epf_employee_pct',
                        employer_contribution_pct = '$epf_employer_pct',
                        fixed_amount = '$epf_wage_ceiling'
                       WHERE config_name = 'PF' AND effective_from = '$effective_from' AND status = '1'";
        
        // ESI - percentage based on gross_salary
        $updateSql2 = "UPDATE tbl_statutory_config SET 
                        applicable_on = 'gross_salary',
                        employee_contribution_pct = '$esi_employee_pct',
                        employer_contribution_pct = '$esi_employer_pct',
                        fixed_amount = '$esi_wage_ceiling'
                       WHERE config_name = 'ESI' AND effective_from = '$effective_from' AND status = '1'";

        // ============================================================
        // PT FIXED - Always use fixed_amount (never percentage)
        // ============================================================
        // PT is a FIXED amount deduction, not percentage-based.
        // So applicable_on must be 'fixed' and employee_contribution_pct = 0.
        // The actual PT amount goes into fixed_amount.
        // PT Wage Ceiling is stored separately (not used in calculation).
        // ============================================================
        $updateSql3 = "UPDATE tbl_statutory_config SET 
                        applicable_on = 'fixed',
                        employee_contribution_pct = '0',
                        employer_contribution_pct = '$pt_employer_amount',
                        fixed_amount = '$pt_employee_amount'
                       WHERE config_name = 'PT' AND effective_from = '$effective_from' AND status = '1'";
        
        if (mysqli_query($conn, $updateSql1) && mysqli_query($conn, $updateSql2) && mysqli_query($conn, $updateSql3)) {
            $_SESSION['toast_success'] = "Statutory Configuration Updated Successfully!";
            header("Location: list.php");
            exit;
        } else {
            $_SESSION['toast_error'] = "Something Went Wrong: " . mysqli_error($conn);
        }
    } else {
        // ============================================================
        // INSERT NEW CONFIG
        // ============================================================
        
        // PF - percentage based on basic_salary
        $sql1 = "INSERT INTO `tbl_statutory_config`
                (`config_name`, `applicable_on`, `employee_contribution_pct`, `employer_contribution_pct`, 
                 `fixed_amount`, `effective_from`, `status`, `created_at`)
                VALUES 
                ('PF', 'basic_salary', '$epf_employee_pct', '$epf_employer_pct', '$epf_wage_ceiling', '$effective_from', '1', '$created_at')";

        // ESI - percentage based on gross_salary
        $sql2 = "INSERT INTO `tbl_statutory_config`
                (`config_name`, `applicable_on`, `employee_contribution_pct`, `employer_contribution_pct`, 
                 `fixed_amount`, `effective_from`, `status`, `created_at`)
                VALUES 
                ('ESI', 'gross_salary', '$esi_employee_pct', '$esi_employer_pct', '$esi_wage_ceiling', '$effective_from', '1', '$created_at')";

        // ============================================================
        // PT FIXED - Always use fixed_amount (never percentage)
        // ============================================================
        // PT is a FIXED amount deduction. Save with applicable_on='fixed'
        // and employee_contribution_pct=0, so payroll uses fixed_amount directly.
        // ============================================================
        $sql3 = "INSERT INTO `tbl_statutory_config`
                (`config_name`, `applicable_on`, `employee_contribution_pct`, `employer_contribution_pct`, 
                 `fixed_amount`, `effective_from`, `status`, `created_at`)
                VALUES 
                ('PT', 'fixed', '0', '$pt_employer_amount', '$pt_employee_amount', '$effective_from', '1', '$created_at')";

        if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3)) {
            $_SESSION['toast_success'] = "Statutory Configuration Saved Successfully!";
            header("Location: list.php");
            exit;
        } else {
            $_SESSION['toast_error'] = "Something Went Wrong: " . mysqli_error($conn);
        }
    }
}

// ============================================================
// GET CURRENT CONFIG VALUES
// ============================================================
$currentConfig = [];
$configQuery = "SELECT * FROM tbl_statutory_config WHERE status='1'";
$configResult = mysqli_query($conn, $configQuery);
while ($row = mysqli_fetch_assoc($configResult)) {
    $currentConfig[$row['config_name']] = $row;
}

// Set default values
$epf_employee = isset($currentConfig['PF']['employee_contribution_pct']) ? $currentConfig['PF']['employee_contribution_pct'] : 12.00;
$epf_employer = isset($currentConfig['PF']['employer_contribution_pct']) ? $currentConfig['PF']['employer_contribution_pct'] : 12.00;
$epf_ceiling = isset($currentConfig['PF']['fixed_amount']) ? $currentConfig['PF']['fixed_amount'] : 15000.00;
$esi_employee = isset($currentConfig['ESI']['employee_contribution_pct']) ? $currentConfig['ESI']['employee_contribution_pct'] : 0.75;
$esi_employer = isset($currentConfig['ESI']['employer_contribution_pct']) ? $currentConfig['ESI']['employer_contribution_pct'] : 3.25;
$esi_ceiling = isset($currentConfig['ESI']['fixed_amount']) ? $currentConfig['ESI']['fixed_amount'] : 21000.00;

// Professional Tax defaults
$pt_employee_amount = isset($currentConfig['PT']['fixed_amount']) ? $currentConfig['PT']['fixed_amount'] : 150.00;
$pt_employer_amount = isset($currentConfig['PT']['employer_contribution_pct']) ? $currentConfig['PT']['employer_contribution_pct'] : 0.00;
$pt_wage_ceiling = isset($currentConfig['PT']['employee_contribution_pct']) && $currentConfig['PT']['employee_contribution_pct'] > 0 
    ? $currentConfig['PT']['employee_contribution_pct'] 
    : 15000.00;

$effective_date = isset($currentConfig['PF']['effective_from']) ? $currentConfig['PF']['effective_from'] : date('Y-m-d');

$pageTitle = 'Statutory Configuration';

// ============================================================
// NOW INCLUDE HEADER & HTML OUTPUT
// ============================================================
include('../includes/header.php');
?>

<style>
    /* ============================================================
       SIDEBAR SCROLLING & LAYOUT (ADDED TO FIX SCROLLING)
    ============================================================ */
    .layout-container {
        display: flex;
        min-height: 100vh;
        position: relative;
    }

    .layout-sidebar, .layout-menu {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        flex-shrink: 0;
        width: 260px;
        background: #1a2332;
        color: #ffffff;
        z-index: 1000;
        transition: all 0.3s ease;
        
        /* REMOVED BLACK LINE */
        border-right: none !important;
        box-shadow: none !important;
    }

    .layout-sidebar::-webkit-scrollbar, .layout-menu::-webkit-scrollbar {
        width: 4px;
    }
    .layout-sidebar::-webkit-scrollbar-track, .layout-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    .layout-sidebar::-webkit-scrollbar-thumb, .layout-menu::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 10px;
    }
    .layout-sidebar::-webkit-scrollbar-thumb:hover, .layout-menu::-webkit-scrollbar-thumb:hover {
        background: #696cff;
    }

    .layout-page {
        flex: 1;
        min-height: 100vh;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .layout-sidebar, .layout-menu {
            position: fixed;
            left: -280px;
            width: 280px;
            transition: left 0.3s ease;
            z-index: 9999;
            height: 100vh;
            overflow-y: auto;
        }
        .layout-sidebar.open, .layout-menu.show {
            left: 0;
        }
    }

    /* ============================================================
       BREADCRUMB BOX STYLES (EXACT MATCH TO IMAGE)
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
       NEW MODERN STATUTORY CONFIG - UI
    ============================================================ */
    .statutory-wrapper {
        max-width: 750px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header-modern {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-header-modern .header-left h4 {
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }
    .page-header-modern .header-left p {
        font-size: 13px;
        color: #6b7a8f;
        margin: 4px 0 0 0;
    }
    .btn-back-modern {
        background: #f8f9fc;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .btn-back-modern:hover {
        background: #e2e8f0;
        color: #1a2332;
    }

    /* Card */
    .statutory-card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    .statutory-card-modern .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .statutory-card-modern .card-header h5 {
        font-weight: 700;
        color: #1a2332;
        font-size: 16px;
        margin: 0;
    }
    .statutory-card-modern .card-header i {
        color: #696cff;
        font-size: 20px;
    }
    .statutory-card-modern .card-body {
        padding: 24px 28px;
    }

    /* Info Box */
    .info-box-modern {
        background: #f8f9fc;
        border-left: 4px solid #696cff;
        border-radius: 8px;
        padding: 15px 20px;
        color: #4a5568;
        font-size: 13px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
        line-height: 1.7;
    }
    .info-box-modern i {
        color: #696cff;
        margin-right: 6px;
    }
    .info-box-modern .highlight {
        color: #696cff;
        font-weight: 700;
    }

    /* Section Title */
    .section-title-modern {
        font-size: 15px;
        font-weight: 700;
        color: #1a2332;
        margin: 20px 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title-modern:first-of-type {
        margin-top: 0;
    }
    .section-title-modern i {
        color: #696cff;
        font-size: 18px;
    }

    /* Config Display */
    .config-display-modern {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 15px 20px;
        border: 1px solid #e9ecef;
        margin-bottom: 5px;
    }
    .config-display-modern .config-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .config-display-modern .config-item:last-child {
        border-bottom: none;
    }
    .config-display-modern .config-item .label {
        font-weight: 600;
        color: #4a5568;
        font-size: 14px;
    }
    .config-display-modern .config-item .value {
        font-weight: 600;
        color: #1a2332;
        font-size: 14px;
    }
    .config-display-modern .config-item .value .input-group {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .config-display-modern .config-item .value .input-group .form-control {
        width: 160px;
        text-align: right;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
        background: #ffffff;
        height: 36px;
        transition: all 0.2s ease;
    }
    .config-display-modern .config-item .value .input-group .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        outline: none;
    }
    .config-display-modern .config-item .value .input-group .input-group-text {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 4px 10px;
        font-size: 13px;
        color: #696cff;
        min-width: 30px;
        justify-content: center;
        height: 36px;
    }
    .config-display-modern .config-item .value .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    /* Effective From */
    .effective-row-modern {
        margin-top: 10px;
        padding-top: 16px;
        border-top: 2px solid #e9ecef;
    }
    .effective-row-modern .config-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }
    .effective-row-modern .config-item .label {
        font-weight: 600;
        color: #4a5568;
        font-size: 14px;
    }
    .effective-row-modern .config-item .value .input-group .form-control {
        width: 180px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 14px;
        color: #1a2332;
        background: #ffffff;
        height: 36px;
    }
    .effective-row-modern .config-item .value .input-group .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
        outline: none;
    }
    .effective-row-modern .config-item .value .input-group .input-group-text {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 4px 10px;
        font-size: 13px;
        color: #696cff;
        height: 36px;
    }
    .effective-row-modern .config-item .value .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    /* Save Button */
    .btn-save-modern {
        background: #696cff;
        color: #ffffff;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        letter-spacing: 0.3px;
        margin-top: 20px;
    }
    .btn-save-modern:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(105, 108, 255, 0.3);
        color: #ffffff;
    }
    .btn-save-modern i {
        margin-right: 8px;
    }

    /* ============================================================
       ACTIONS BUTTONS (ADDED)
    ============================================================ */
    .action-buttons-modern {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #e9ecef;
        flex-wrap: wrap;
    }

    .btn-action-modern {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action-modern.btn-view {
        background: #0ea5e9;
        color: #fff;
    }
    .btn-action-modern.btn-view:hover {
        background: #0284c7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }

    .btn-action-modern.btn-edit {
        background: #696cff;
        color: #fff;
    }
    .btn-action-modern.btn-edit:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
    }

    .btn-action-modern.btn-delete {
        background: #ef4444;
        color: #fff;
    }
    .btn-action-modern.btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-action-modern.btn-back {
        background: #6b7280;
        color: #fff;
    }
    .btn-action-modern.btn-back:hover {
        background: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .statutory-card-modern .card-body { padding: 16px 18px; }
        .config-display-modern .config-item { flex-direction: column; align-items: flex-start; gap: 4px; padding: 8px 0; }
        .config-display-modern .config-item .label { font-size: 13px; }
        .config-display-modern .config-item .value { width: 100%; }
        .config-display-modern .config-item .value .input-group { justify-content: flex-start; }
        .config-display-modern .config-item .value .input-group .form-control { width: 140px; font-size: 13px; height: 32px; }
        .effective-row-modern .config-item { flex-direction: column; align-items: flex-start; gap: 4px; }
        .effective-row-modern .config-item .value .input-group .form-control { width: 160px; font-size: 13px; height: 32px; }
        .info-box-modern { font-size: 12px; padding: 12px 14px; }
        .btn-save-modern { padding: 10px 20px; font-size: 13px; }
        .section-title-modern { font-size: 14px; }
        .page-header-modern { flex-direction: column; align-items: flex-start; gap: 10px; }
        .action-buttons-modern { justify-content: center; }
        .btn-action-modern { width: 100%; justify-content: center; }
    }

    @media (max-width: 480px) {
        .config-display-modern .config-item .value .input-group .form-control { width: 120px; }
        .effective-row-modern .config-item .value .input-group .form-control { width: 140px; }
        .config-display-modern .config-item .label { font-size: 12px; }
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
                        <div class="statutory-wrapper">

                            <!-- Breadcrumb Box -->
                            <div class="breadcrumb-box">
                                <!-- Dashboard -->
                                <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                    <i class="bx bx-home"></i> Dashboard
                                </a>

                                <span class="separator">›</span>

                                <!-- Payroll -->
                                <a href="#" class="breadcrumb-item">
                                    <i class="bx bx-briefcase"></i> Payroll
                                </a>

                                <span class="separator">›</span>

                                <!-- Statutory Configuration (Active - Not clickable) -->
                                <span class="breadcrumb-item active">
                                    <i class="bx bx-cog"></i> Statutory Configuration
                                </span>
                            </div>

                            <!-- Page Header -->
                            <div class="page-header-modern">
                                <div class="header-left">
                                    <h4>Statutory Configuration</h4>
                                    <p>EPF / ESI / PT Configuration</p>
                                </div>
                                <div>
                                    <a href="list.php" class="btn-back-modern">
                                        <i class="bx bx-arrow-back me-1"></i> Back
                                    </a>
                                </div>
                            </div>

                            <!-- Main Card -->
                            <div class="card statutory-card-modern">
                                <div class="card-header">
                                    <i class="bx bx-cog"></i>
                                    <h5>Statutory Configuration (EPF / ESI / PT)</h5>
                                </div>
                                <div class="card-body">

                                    <!-- Info Box -->
                                    <div class="info-box-modern">
                                        <i class="bx bx-info-circle"></i>
                                        Standard rates as of 2026: EPF <span class="highlight">12%</span> employee + <span class="highlight">12%</span> employer on wages up to ₹15,000/month; ESI <span class="highlight">0.75%</span> employee + <span class="highlight">3.25%</span> employer, applicable only if gross monthly wage is ₹21,000 or below. Professional Tax (PT) is a <span class="highlight">fixed amount</span> deducted from employees' salaries (not percentage-based). Note the EPF wage ceiling is under judicial review and may change — verify against current EPFO/ESIC circulars before relying on this for compliance filing.
                                    </div>

                                    <form action="" method="POST" id="statutoryForm">

                                        <!-- EPF Section -->
                                        <div class="section-title-modern">
                                            <i class="bx bx-briefcase"></i> EPF (Employees' Provident Fund)
                                        </div>

                                        <div class="config-display-modern">
                                            <div class="config-item">
                                                <span class="label">EPF Employee %</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-percentage"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="epf_employee_pct" id="epf_employee_pct" 
                                                               value="<?= $epf_employee ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">EPF Employer %</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-percentage"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="epf_employer_pct" id="epf_employer_pct" 
                                                               value="<?= $epf_employer ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">EPF Wage Ceiling (₹)</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="epf_wage_ceiling" id="epf_wage_ceiling" 
                                                               value="<?= $epf_ceiling ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- ESI Section -->
                                        <div class="section-title-modern">
                                            <i class="bx bx-shield"></i> ESI (Employees' State Insurance)
                                        </div>

                                        <div class="config-display-modern">
                                            <div class="config-item">
                                                <span class="label">ESI Employee %</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-percentage"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="esi_employee_pct" id="esi_employee_pct" 
                                                               value="<?= $esi_employee ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">ESI Employer %</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-percentage"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="esi_employer_pct" id="esi_employer_pct" 
                                                               value="<?= $esi_employer ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">ESI Wage Ceiling (₹)</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="esi_wage_ceiling" id="esi_wage_ceiling" 
                                                               value="<?= $esi_ceiling ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Professional Tax Section -->
                                        <div class="section-title-modern">
                                            <i class="bx bx-receipt"></i> PT (Professional Tax)
                                        </div>

                                        <div class="config-display-modern">
                                            <div class="config-item">
                                                <span class="label">PT Employee Amount (₹)</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="pt_employee_amount" id="pt_employee_amount" 
                                                               value="<?= $pt_employee_amount ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">PT Employer Amount (₹)</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="pt_employer_amount" id="pt_employer_amount" 
                                                               value="<?= $pt_employer_amount ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="config-item">
                                                <span class="label">PT Wage Ceiling (₹)</span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-rupee"></i></span>
                                                        <input type="number" step="0.01" class="form-control" 
                                                               name="pt_wage_ceiling" id="pt_wage_ceiling" 
                                                               value="<?= $pt_wage_ceiling ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Effective From -->
                                        <div class="effective-row-modern">
                                            <div class="config-item">
                                                <span class="label">Effective From <span style="color:red;">*</span></span>
                                                <span class="value">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                                        <input type="date" class="form-control" 
                                                               name="effective_from" id="effective_from" 
                                                               value="<?= $effective_date ?>" required />
                                                    </div>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Save Button -->
                                        <button type="submit" name="form_submit" class="btn-save-modern">
                                            <i class="bx bx-save"></i> Save Configuration
                                        </button>

                                    </form>

                                    <!-- ============================================================
                                         ACTIONS BUTTONS (ADDED)
                                    ============================================================ -->
                                    <div class="action-buttons-modern">
                                        <a href="list.php" class="btn-action-modern btn-view">
                                            <i class="bx bx-show"></i> List
                                        </a>
                                       
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
    <div class="layout-overlay layout-menu-toggle"></div>

    <?php include('../includes/script.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show toast messages from session (if any)
            <?php if (isset($_SESSION['toast_success'])): ?>
                iziToast.success({
                    title: 'Success',
                    message: '<?= addslashes($_SESSION['toast_success']) ?>',
                    position: 'topRight'
                });
                <?php unset($_SESSION['toast_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['toast_error'])): ?>
                iziToast.error({
                    title: 'Error',
                    message: '<?= addslashes($_SESSION['toast_error']) ?>',
                    position: 'topRight'
                });
                <?php unset($_SESSION['toast_error']); ?>
            <?php endif; ?>

            const statutoryForm = document.getElementById('statutoryForm');
            
            if (statutoryForm) {
                statutoryForm.addEventListener('submit', function(event) {
                    const epfEmployee = parseFloat(document.getElementById('epf_employee_pct').value) || 0;
                    const epfEmployer = parseFloat(document.getElementById('epf_employer_pct').value) || 0;
                    const esiEmployee = parseFloat(document.getElementById('esi_employee_pct').value) || 0;
                    const esiEmployer = parseFloat(document.getElementById('esi_employer_pct').value) || 0;
                    const effectiveFrom = document.getElementById('effective_from').value;
                    
                    if (!effectiveFrom) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Error',
                            message: 'Please select Effective From date',
                            position: 'topRight'
                        });
                        return false;
                    }
                    
                    if (epfEmployee < 0 || epfEmployer < 0 || esiEmployee < 0 || esiEmployer < 0) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Error',
                            message: 'Percentage values cannot be negative',
                            position: 'topRight'
                        });
                        return false;
                    }
                });
            }
            
            // Set default date to today if not set
            const effectiveFromInput = document.getElementById('effective_from');
            if (effectiveFromInput && !effectiveFromInput.value) {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                effectiveFromInput.value = year + '-' + month + '-' + day;
            }
        });
    </script>

</body>
</html>