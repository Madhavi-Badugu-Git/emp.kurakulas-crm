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

// Get filter parameters (same as list page)
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

// Fetch all payroll records
$sql = "SELECT p.*, u.firstName, u.lastName, u.username 
        FROM tbl_payroll p 
        LEFT JOIN tbl_user u ON p.employee_id = u.id 
        WHERE $where 
        ORDER BY p.pay_month DESC, p.id DESC";

$result = mysqli_query($conn, $sql);

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="payroll_export_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Add column headers
fputcsv($output, [
    'ID',
    'Employee Name',
    'Employee ID',
    'Pay Month',
    'Basic Salary',
    'Gross Salary',
    'PF Deduction',
    'ESI Deduction',
    'PT Deduction',
    'Other Deduction',
    'Net Salary',
    'Payment Status',
    'Payment Date',
    'Created Date'
]);

// Add data rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['firstName'] . ' ' . $row['lastName'],
        $row['username'] ?? $row['employee_id'],
        $row['pay_month'],
        $row['basic_salary'],
        $row['gross_salary'],
        $row['pf_deduction'],
        $row['esi_deduction'],
        $row['pt_deduction'],
        $row['other_deduction'],
        $row['net_salary'],
        $row['payment_status'],
        $row['payment_date'],
        $row['created_at']
    ]);
}

fclose($output);
exit();
?>