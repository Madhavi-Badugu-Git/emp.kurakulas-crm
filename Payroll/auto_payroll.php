<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Check if user is logged in
if(!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// Get parameters
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$employee_id = isset($_GET['employee_id']) ? (int)$_GET['employee_id'] : 0;

$pageTitle = 'Auto Generate Payroll';
include('../includes/header.php');
?>

<style>
    .auto-payroll-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .stats-card {
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #e9ecef;
        margin-bottom: 20px;
    }
    .stats-card .number {
        font-size: 28px;
        font-weight: 700;
        color: #696cff;
    }
    .stats-card .label {
        font-size: 14px;
        color: #6b7a8f;
    }
    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 15px 20px;
        border-radius: 8px;
    }
    .alert-success {
        background: #d4edda;
        border: 1px solid #28a745;
        color: #155724;
        padding: 15px 20px;
        border-radius: 8px;
    }
    .btn-generate {
        background: #696cff;
        color: #fff;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-generate:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105,108,255,0.3);
    }
    .btn-generate:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .month-selector {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .month-selector input[type="month"] {
        padding: 10px 14px;
        border: 1px solid #d2d6da;
        border-radius: 6px;
        font-size: 14px;
    }
    .month-selector label {
        font-weight: 600;
        color: #344767;
    }
    .loading {
        display: none;
        text-align: center;
        padding: 20px;
    }
    .loading .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #696cff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .result-table {
        margin-top: 20px;
        width: 100%;
        border-collapse: collapse;
    }
    .result-table th {
        background: #1a2332;
        color: #fff;
        padding: 10px 14px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
    }
    .result-table td {
        padding: 10px 14px;
        border-bottom: 1px solid #e9ecef;
        font-size: 13px;
    }
    .result-table tr:hover td {
        background: #f8fafc;
    }
    .badge-success {
        background: #28a745;
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
    }
    .badge-warning {
        background: #ffc107;
        color: #000;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
    }
    .badge-danger {
        background: #dc3545;
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
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
                        
                        <div class="auto-payroll-container">
                            
                            <!-- Page Title -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold py-3 mb-0">
                                    <span class="text-muted fw-light">Payroll /</span> Auto Generate
                                </h4>
                                <a href="list.php" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back to List
                                </a>
                            </div>

                            <!-- Info Cards -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="stats-card text-center">
                                        <div class="number" id="totalEmployees">0</div>
                                        <div class="label">Total Employees</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stats-card text-center">
                                        <div class="number" id="totalAttendance">0</div>
                                        <div class="label">Attendance Records</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stats-card text-center">
                                        <div class="number" id="totalPayroll">0</div>
                                        <div class="label">Payroll Generated</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Month Selector -->
                            <div class="stats-card">
                                <form method="GET" action="" id="generateForm">
                                    <div class="month-selector">
                                        <label for="month">Select Month:</label>
                                        <input type="month" name="month" id="month" 
                                               value="<?= htmlspecialchars($month) ?>">
                                        <button type="submit" name="generate" class="btn-generate" id="generateBtn">
                                            <i class="bx bx-play-circle me-1"></i> Generate Payroll
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Loading Indicator -->
                            <div class="loading" id="loadingIndicator">
                                <div class="spinner"></div>
                                <p class="mt-2 text-muted">Generating payroll, please wait...</p>
                            </div>

                            <?php
                            // Check if generate button was clicked
                            if (isset($_GET['generate'])) {
                                
                                // Get total employees
                                $totalEmpQuery = "SELECT COUNT(*) as total FROM tbl_user WHERE status = '1' AND rank != 'superadmin'";
                                $totalEmpResult = $conn->query($totalEmpQuery);
                                $totalEmployees = $totalEmpResult->fetch_assoc()['total'] ?? 0;
                                
                                // Get attendance count for this month
                                $attCountQuery = "SELECT COUNT(*) as total FROM tbl_user_attendance WHERE DATE_FORMAT(date, '%Y-%m') = '$month' AND status = '1'";
                                $attCountResult = $conn->query($attCountQuery);
                                $attendanceCount = $attCountResult->fetch_assoc()['total'] ?? 0;
                                
                                // Get all employees
                                if ($employee_id > 0) {
                                    $empQuery = "SELECT id, firstName, lastName, basic_salary, gross_salary, da, house_rent_allowance, other_allowances 
                                                 FROM tbl_user WHERE id = $employee_id AND status = '1'";
                                } else {
                                    $empQuery = "SELECT id, firstName, lastName, basic_salary, gross_salary, da, house_rent_allowance, other_allowances 
                                                 FROM tbl_user WHERE status = '1' AND rank != 'superadmin' ORDER BY firstName ASC";
                                }
                                $empResult = $conn->query($empQuery);
                                $processedCount = 0;
                                $failedCount = 0;
                                $payrollData = [];

                                while ($employee = $empResult->fetch_assoc()) {
                                    $empId = $employee['id'];
                                    $gross_salary = $employee['gross_salary'] ?? 0;
                                    $basic_salary = $employee['basic_salary'] ?? 0;
                                    
                                    // Skip if gross salary is 0
                                    if ($gross_salary <= 0) {
                                        $failedCount++;
                                        continue;
                                    }
                                    
                                    // Get attendance for this month
                                    $attQuery = "SELECT 
                                                    COUNT(*) as total_days,
                                                    SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                                                    SUM(CASE WHEN attendance_type_id = 2 THEN 1 ELSE 0 END) as absent_days,
                                                    SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                                                    SUM(CASE WHEN attendance_type_id = 3 THEN 1 ELSE 0 END) as half_days,
                                                    SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                                                    SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
                                                 FROM tbl_user_attendance 
                                                 WHERE employee_id = $empId 
                                                 AND DATE_FORMAT(date, '%Y-%m') = '$month'
                                                 AND status = '1'";
                                    
                                    $attResult = $conn->query($attQuery);
                                    $attData = $attResult->fetch_assoc();
                                    
                                    // If no attendance, skip
                                    if (!$attData || ($attData['total_days'] ?? 0) == 0) {
                                        $failedCount++;
                                        continue;
                                    }
                                    
                                    // Calculate working days in month
                                    $year = (int)substr($month, 0, 4);
                                    $monthNum = (int)substr($month, 5);
                                    $working_days_in_month = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
                                    
                                    $present_days = $attData['present_days'] ?? 0;
                                    $absent_days = $attData['absent_days'] ?? 0;
                                    $half_days = $attData['half_days'] ?? 0;
                                    
                                    // Calculate effective present days
                                    $effective_present = $present_days + ($half_days * 0.5);
                                    
                                    // Calculate daily salary
                                    $daily_salary = $gross_salary / $working_days_in_month;
                                    
                                    // Calculate net salary
                                    $net_salary = $effective_present * $daily_salary;
                                    if ($net_salary <= 0) {
                                        $net_salary = $gross_salary - ($absent_days * $daily_salary);
                                    }
                                    
                                    // Get deductions
                                    $pf = 0; $esi = 0; $pt = 0; $other_deduction = 0;
                                    $configQuery = mysqli_query($conn, "SELECT * FROM tbl_statutory_config WHERE status='1'");
                                    if ($configQuery) {
                                        while ($cfg = mysqli_fetch_assoc($configQuery)) {
                                            $base = $cfg['applicable_on'] === 'basic_salary' ? $basic_salary : $gross_salary;
                                            $amount = $cfg['applicable_on'] === 'fixed'
                                                ? (float)$cfg['fixed_amount']
                                                : $base * ((float)$cfg['employee_contribution_pct'] / 100);
                                            
                                            if (stripos($cfg['config_name'], 'PF') !== false) {
                                                $pf += $amount;
                                            } elseif (stripos($cfg['config_name'], 'ESI') !== false) {
                                                $esi += $amount;
                                            } elseif (stripos($cfg['config_name'], 'Professional Tax') !== false || stripos($cfg['config_name'], 'PT') !== false) {
                                                $pt += $amount;
                                            } else {
                                                $other_deduction += $amount;
                                            }
                                        }
                                    }
                                    
                                    $final_net = $net_salary - $pf - $esi - $pt - $other_deduction;
                                    if ($final_net < 0) $final_net = 0;
                                    
                                    // Check if payroll exists
                                    $checkPayroll = "SELECT id FROM tbl_payroll WHERE employee_id = $empId AND pay_month = '$month' AND status = '1'";
                                    $checkResult = $conn->query($checkPayroll);
                                    
                                    if ($checkResult->num_rows > 0) {
                                        // Update
                                        $updateSql = "UPDATE tbl_payroll SET 
                                                        basic_salary = '$basic_salary',
                                                        gross_salary = '$gross_salary',
                                                        pf_deduction = '$pf',
                                                        esi_deduction = '$esi',
                                                        pt_deduction = '$pt',
                                                        other_deduction = '$other_deduction',
                                                        net_salary = '$final_net'
                                                      WHERE employee_id = $empId AND pay_month = '$month'";
                                        $conn->query($updateSql);
                                    } else {
                                        // Insert
                                        $insertSql = "INSERT INTO tbl_payroll 
                                                      (employee_id, pay_month, basic_salary, gross_salary, pf_deduction, esi_deduction, pt_deduction, other_deduction, net_salary, payment_status, status, created_at)
                                                      VALUES 
                                                      ('$empId', '$month', '$basic_salary', '$gross_salary', '$pf', '$esi', '$pt', '$other_deduction', '$final_net', 'pending', '1', NOW())";
                                        $conn->query($insertSql);
                                    }
                                    $processedCount++;
                                    
                                    // Store for display
                                    $payrollData[] = [
                                        'name' => $employee['firstName'] . ' ' . $employee['lastName'],
                                        'gross' => $gross_salary,
                                        'net' => $final_net,
                                        'present' => $present_days,
                                        'status' => 'success'
                                    ];
                                }
                                
                                // Get total payroll generated
                                $payCountQuery = "SELECT COUNT(*) as total FROM tbl_payroll WHERE pay_month = '$month' AND status = '1'";
                                $payCountResult = $conn->query($payCountQuery);
                                $payrollGenerated = $payCountResult->fetch_assoc()['total'] ?? 0;
                            ?>
                            
                            <!-- Results -->
                            <div class="stats-card">
                                <?php if ($processedCount > 0): ?>
                                    <div class="alert-success" style="padding:15px 20px;border-radius:8px;margin-bottom:15px;">
                                        <i class="bx bx-check-circle me-1"></i>
                                        <strong>Success!</strong> Payroll generated for <strong><?= $processedCount ?></strong> employees.
                                        <?php if ($failedCount > 0): ?>
                                            <br><small><?= $failedCount ?> employees skipped (no attendance or no salary)</small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($payrollData)): ?>
                                    <div class="table-responsive">
                                        <table class="result-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Employee</th>
                                                    <th>Present Days</th>
                                                    <th>Gross Salary</th>
                                                    <th>Net Salary</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($payrollData as $index => $data): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($data['name']) ?></td>
                                                    <td><?= $data['present'] ?></td>
                                                    <td>₹<?= number_format($data['gross'], 2) ?></td>
                                                    <td><strong>₹<?= number_format($data['net'], 2) ?></strong></td>
                                                    <td><span class="badge-success">Generated</span></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3">
                                        <a href="list.php" class="btn btn-primary btn-sm">
                                            <i class="bx bx-list-ul me-1"></i> View All Payroll
                                        </a>
                                        <a href="?month=<?= $month ?>" class="btn btn-secondary btn-sm">
                                            <i class="bx bx-refresh me-1"></i> Refresh
                                        </a>
                                    </div>
                                    
                                <?php else: ?>
                                    <div class="alert-warning" style="padding:15px 20px;border-radius:8px;">
                                        <i class="bx bx-error-circle me-1"></i>
                                        <strong>No payroll generated.</strong><br>
                                        Possible reasons:
                                        <ul class="mt-2 mb-0">
                                            <li>No employees found</li>
                                            <li>No attendance records for this month</li>
                                            <li>Employees have zero salary</li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php } else { ?>
                            
                            <!-- Instructions -->
                            <div class="stats-card">
                                <h6 class="mb-3"><i class="bx bx-info-circle me-1"></i> Instructions</h6>
                                <ol class="mb-0">
                                    <li>Select the <strong>month</strong> for which you want to generate payroll</li>
                                    <li>Click <strong>"Generate Payroll"</strong> button</li>
                                    <li>System will calculate salary based on attendance</li>
                                    <li>Payroll will be automatically created in <strong>tbl_payroll</strong></li>
                                    <li>You can then view and generate payslips from payroll list</li>
                                </ol>
                                <div class="mt-3 alert-warning" style="padding:12px 16px;border-radius:6px;font-size:13px;">
                                    <i class="bx bx-info-circle me-1"></i>
                                    <strong>Note:</strong> Only employees with attendance records will be processed.
                                    Employees with zero salary or no attendance will be skipped.
                                </div>
                            </div>
                            
                            <?php } ?>
                            
                        </div>
                        
                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>
    <script>
        document.getElementById('generateBtn')?.addEventListener('click', function(e) {
            const month = document.getElementById('month').value;
            if (!month) {
                e.preventDefault();
                iziToast.warning({
                    title: 'Warning',
                    message: 'Please select a month first',
                    position: 'topRight'
                });
                return;
            }
            document.getElementById('loadingIndicator').style.display = 'block';
            this.disabled = true;
            this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Generating...';
        });
    </script>
</body>
</html>