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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list.php";</script>';
    exit();
}

$payroll_id = (int) $_GET['id'];

// Fetch payroll record with employee details
$query = "SELECT p.*, u.firstName, u.lastName, u.username 
          FROM tbl_payroll p 
          LEFT JOIN tbl_user u ON p.employee_id = u.id 
          WHERE p.id = ? AND p.status = '1'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payroll_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Payroll record not found!"); window.location.href="list.php";</script>';
    exit();
}

$payroll = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['form_submit'])) {
    $basic_salary = (float) $_POST['basic_salary'];
    $gross_salary = (float) $_POST['gross_salary'];
    $pf_deduction = (float) $_POST['pf_deduction'];
    $esi_deduction = (float) $_POST['esi_deduction'];
    $pt_deduction = (float) $_POST['pt_deduction'];
    $other_deduction = (float) $_POST['other_deduction'];
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $payment_date = $_POST['payment_date'] !== '' ? $_POST['payment_date'] : null;

    // Calculate net salary
    $net_salary = $gross_salary - $pf_deduction - $esi_deduction - $pt_deduction - $other_deduction;

    // Update query
    $update = "UPDATE tbl_payroll SET
        basic_salary = ?,
        gross_salary = ?,
        pf_deduction = ?,
        esi_deduction = ?,
        pt_deduction = ?,
        other_deduction = ?,
        net_salary = ?,
        payment_status = ?,
        payment_date = ?
        WHERE id = ?";

    $stmt2 = $conn->prepare($update);
    $stmt2->bind_param(
        "dddddddssi",
        $basic_salary,
        $gross_salary,
        $pf_deduction,
        $esi_deduction,
        $pt_deduction,
        $other_deduction,
        $net_salary,
        $payment_status,
        $payment_date,
        $payroll_id
    );

    if ($stmt2->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Payroll updated successfully!",
                position: "topRight",
            });
            setTimeout(() => { window.location.href = "list.php"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Update failed. Please try again.",
                position: "topRight",
            });
        </script>';
    }
}

$pageTitle = 'Edit Payroll';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       GLOBAL FIX FOR SIDE MENU SCROLLING
    ============================================================ */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .layout-wrapper {
        height: 100vh;
        overflow: hidden;
    }

    .layout-container {
        height: 100%;
        display: flex;
        overflow: hidden;
    }

    /* FIX: Make side menu scrollable */
    .layout-menu {
        height: 100vh !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        position: sticky !important;
        top: 0;
        flex-shrink: 0;
        background: #1a2332;
    }

    /* FIX: Main content area */
    .layout-page {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        background: #f8f9fa;
    }

    /* FIX: Content wrapper - scrollable */
    .content-wrapper {
        flex: 1;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding-bottom: 20px;
        height: calc(100vh - 70px);
    }

    /* FIX: Navbar stays fixed */
    .layout-navbar {
        flex-shrink: 0;
        position: sticky !important;
        top: 0;
        z-index: 1000;
        background: #ffffff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    /* FIX: Container inside content */
    .container-xxl {
        max-height: 100%;
        overflow-y: visible;
    }

    /* FIX: Overlay */
    .layout-overlay {
        display: none !important;
    }

    /* ============================================================
       SIDE MENU SCROLLBAR STYLING
    ============================================================ */
    .layout-menu::-webkit-scrollbar {
        width: 5px;
    }

    .layout-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .layout-menu::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 10px;
    }

    .layout-menu::-webkit-scrollbar-thumb:hover {
        background: #5a6578;
    }

    /* Firefox scrollbar */
    .layout-menu {
        scrollbar-width: thin;
        scrollbar-color: #4a5568 transparent;
    }

    /* ============================================================
       MOBILE RESPONSIVE FIX
    ============================================================ */
    @media (max-width: 992px) {
        .layout-menu {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh !important;
            width: 280px;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto !important;
        }

        .layout-menu.show {
            transform: translateX(0);
        }

        .layout-overlay {
            display: block !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .layout-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .content-wrapper {
            height: calc(100vh - 60px);
        }
    }

    /* ============================================================
       EDIT PAYROLL STYLES
    ============================================================ */
    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #344767;
    }

    .input-group-text {
        background: #f8f9fa;
        border-color: #d2d6da;
    }

    .form-control,
    .form-select {
        border-color: #d2d6da;
        font-size: 14px;
        border-radius: 6px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.15);
    }

    .form-control[readonly] {
        background: #f8f9fa;
        cursor: not-allowed;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1a2332;
    }

    .employee-info {
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 8px;
        border-left: 4px solid #696cff;
        margin-bottom: 20px;
    }

    .employee-info .label {
        font-size: 12px;
        color: #6b7a8f;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .employee-info .value {
        font-size: 15px;
        font-weight: 600;
        color: #1a2332;
    }

    .btn-primary {
        background: #696cff;
        border-color: #696cff;
    }

    .btn-primary:hover {
        background: #5a5de0;
        border-color: #5a5de0;
    }

    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
    }

    .btn-success {
        background: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background: #1e7e34;
        border-color: #1e7e34;
    }

    .net-salary-display {
        background: #d4edda;
        border: 2px solid #28a745;
        border-radius: 8px;
        padding: 15px 20px;
        text-align: center;
    }

    .net-salary-display .label {
        font-size: 14px;
        color: #155724;
        font-weight: 600;
    }

    .net-salary-display .amount {
        font-size: 28px;
        font-weight: 700;
        color: #28a745;
    }

    .info-box {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 12px 16px;
        color: #856404;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .net-salary-display .amount {
            font-size: 22px;
        }

        .employee-info .value {
            font-size: 13px;
        }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu -->
            <?php include('../includes/sideMenu.php'); ?>

            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Page Title -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold py-3 mb-0">
                                <span class="text-muted fw-light">Payroll /</span> Edit Payroll
                            </h4>
                            <div>
                                <a href="payslip.php?id=<?= $payroll_id ?>" class="btn btn-success btn-sm">
                                    <i class="bx bx-receipt me-1"></i> Payslip
                                </a>
                                <a href="list.php" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="bx bx-edit me-2"></i>Edit Payroll</h5>
                                        <small class="text-muted">ID:
                                            #<?= str_pad($payroll_id, 6, '0', STR_PAD_LEFT) ?></small>
                                    </div>
                                    <div class="card-body">

                                        <!-- Employee Info -->
                                        <div class="employee-info">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="label">Employee Name</div>
                                                    <div class="value">
                                                        <?= htmlspecialchars($payroll['firstName'] . ' ' . $payroll['lastName']) ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="label">Employee ID</div>
                                                    <div class="value">
                                                        <?= htmlspecialchars($payroll['username'] ?? $payroll['employee_id']) ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="label">Pay Month</div>
                                                    <div class="value">
                                                        <?= date('F Y', strtotime($payroll['pay_month'] . '-01')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="" method="POST" id="editForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basic_salary">Basic
                                                            Salary</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="basic_salary" id="basic_salary"
                                                                value="<?= htmlspecialchars($payroll['basic_salary']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="gross_salary">Gross
                                                            Salary</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="gross_salary" id="gross_salary"
                                                                value="<?= htmlspecialchars($payroll['gross_salary']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="pf_deduction">PF
                                                            Deduction</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-minus-circle"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="pf_deduction" id="pf_deduction"
                                                                value="<?= htmlspecialchars($payroll['pf_deduction']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="esi_deduction">ESI
                                                            Deduction</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-minus-circle"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="esi_deduction" id="esi_deduction"
                                                                value="<?= htmlspecialchars($payroll['esi_deduction']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="pt_deduction">PT
                                                            Deduction</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-minus-circle"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="pt_deduction" id="pt_deduction"
                                                                value="<?= htmlspecialchars($payroll['pt_deduction']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="other_deduction">Other
                                                            Deduction</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-minus-circle"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="other_deduction" id="other_deduction"
                                                                value="<?= htmlspecialchars($payroll['other_deduction']) ?>"
                                                                onkeyup="calculateNetSalary()">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Net Salary Display -->
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="net-salary-display">
                                                        <div class="label">Calculated Net Salary</div>
                                                        <div class="amount" id="netSalaryDisplay">
                                                            ₹ <?= number_format($payroll['net_salary'], 2) ?>
                                                        </div>
                                                        <input type="hidden" name="net_salary" id="netSalaryInput"
                                                            value="<?= $payroll['net_salary'] ?>">
                                                        <small class="text-muted">Auto-calculated: Gross - (PF + ESI +
                                                            PT + Other)</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="payment_status">Payment
                                                            Status</label>
                                                        <select name="payment_status" id="payment_status"
                                                            class="form-select">
                                                            <option value="pending"
                                                                <?= $payroll['payment_status'] === 'pending' ? 'selected' : '' ?>>
                                                                ⏳ Pending
                                                            </option>
                                                            <option value="paid" <?= $payroll['payment_status'] === 'paid' ? 'selected' : '' ?>>
                                                                ✅ Paid
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="payment_date">Payment
                                                            Date</label>
                                                        <input type="date" class="form-control" name="payment_date"
                                                            id="payment_date"
                                                            value="<?= htmlspecialchars($payroll['payment_date'] ?? '') ?>">
                                                        <small class="text-muted">Leave empty if not paid yet</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Info Box -->
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="info-box">
                                                        <i class="bx bx-info-circle me-1"></i>
                                                        <strong>Note:</strong> Changing salary values will
                                                        auto-calculate the net salary.
                                                        PF, ESI, and PT values can be manually adjusted.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" name="form_submit" class="btn btn-primary">
                                                    <i class="bx bx-save me-1"></i> Update Payroll
                                                </button>
                                                <a href="list.php" class="btn btn-secondary ms-2">
                                                    <i class="bx bx-arrow-back me-1"></i> Cancel
                                                </a>
                                            </div>
                                        </form>
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
        // Function to calculate net salary
        function calculateNetSalary() {
            const gross = parseFloat(document.getElementById('gross_salary').value) || 0;
            const pf = parseFloat(document.getElementById('pf_deduction').value) || 0;
            const esi = parseFloat(document.getElementById('esi_deduction').value) || 0;
            const pt = parseFloat(document.getElementById('pt_deduction').value) || 0;
            const other = parseFloat(document.getElementById('other_deduction').value) || 0;

            const net = gross - pf - esi - pt - other;

            document.getElementById('netSalaryDisplay').textContent = '₹ ' + net.toFixed(2);
            document.getElementById('netSalaryInput').value = net.toFixed(2);
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('editForm');

            form.addEventListener('submit', function (event) {
                const gross = parseFloat(document.getElementById('gross_salary').value) || 0;

                if (gross <= 0) {
                    event.preventDefault();
                    iziToast.warning({
                        title: 'Error',
                        message: 'Gross Salary must be greater than 0',
                        position: 'topRight'
                    });
                    return false;
                }
            });

            // ============================================================
            // FIX: Ensure side menu scrolling works
            // ============================================================
            function fixSideMenuScrolling() {
                const layoutMenu = document.querySelector('.layout-menu');
                const layoutPage = document.querySelector('.layout-page');
                const contentWrapper = document.querySelector('.content-wrapper');

                if (layoutMenu) {
                    // Set fixed height for side menu
                    const viewportHeight = window.innerHeight;
                    layoutMenu.style.height = viewportHeight + 'px';
                    layoutMenu.style.overflowY = 'auto';
                    layoutMenu.style.overflowX = 'hidden';
                }

                if (contentWrapper) {
                    // Ensure content wrapper is scrollable
                    contentWrapper.style.overflowY = 'auto';
                    contentWrapper.style.overflowX = 'hidden';
                }
            }

            // Run on load and resize
            fixSideMenuScrolling();
            window.addEventListener('resize', fixSideMenuScrolling);

            // ============================================================
            // FIX: Mobile menu toggle
            // ============================================================
            const menuToggle = document.querySelector('.menu-toggle');
            const layoutMenu = document.querySelector('.layout-menu');
            const overlay = document.querySelector('.layout-overlay');

            if (menuToggle && layoutMenu) {
                menuToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    layoutMenu.classList.toggle('show');
                    if (overlay) {
                        overlay.classList.toggle('show');
                    }
                    // Fix height when menu opens on mobile
                    if (window.innerWidth <= 992) {
                        layoutMenu.style.height = window.innerHeight + 'px';
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function () {
                    layoutMenu.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Fix for window resize on mobile
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992 && layoutMenu) {
                    layoutMenu.classList.remove('show');
                    if (overlay) overlay.classList.remove('show');
                }
            });
        });

        // Additional fix: Ensure scrolling works after page load
        window.addEventListener('load', function () {
            const layoutMenu = document.querySelector('.layout-menu');
            if (layoutMenu) {
                layoutMenu.style.overflowY = 'auto';
                layoutMenu.style.overflowX = 'hidden';
            }
        });
    </script>

</body>

</html>