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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        iziToast.error({
            title: "Error",
            message: "Invalid request!",
            position: "topRight",
        });
        setTimeout(() => { window.location.href = "list.php"; }, 1500);
    </script>';
    exit();
}

$payroll_id = (int)$_GET['id'];

// Check if record exists
$check_query = "SELECT p.*, u.firstName, u.lastName, u.username 
                FROM tbl_payroll p 
                LEFT JOIN tbl_user u ON p.employee_id = u.id 
                WHERE p.id = ? AND p.status = '1'";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $payroll_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>
        iziToast.warning({
            title: "Not Found",
            message: "Payroll record not found or already deleted!",
            position: "topRight",
        });
        setTimeout(() => { window.location.href = "list.php"; }, 1500);
    </script>';
    exit();
}

$payroll = $result->fetch_assoc();

// Handle delete confirmation
if (isset($_POST['confirm_delete'])) {
    $delete_query = "UPDATE tbl_payroll SET status = 0, updated_at = NOW() WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $payroll_id);
    
    if ($delete_stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Payroll record deleted successfully!",
                position: "topRight",
            });
            setTimeout(() => { window.location.href = "list.php"; }, 1500);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Unable to delete payroll record. Please try again.",
                position: "topRight",
            });
        </script>';
    }
    $delete_stmt->close();
}

$pageTitle = 'Delete Payroll';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       DELETE PAGE STYLES
    ============================================================ */
    .delete-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .delete-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 35px 30px;
        text-align: center;
        border-top: 4px solid #dc3545;
    }
    
    .delete-icon {
        font-size: 64px;
        color: #dc3545;
        margin-bottom: 15px;
    }
    
    .delete-title {
        font-size: 22px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 10px;
    }
    
    .delete-message {
        color: #6b7a8f;
        font-size: 15px;
        margin-bottom: 25px;
    }
    
    .delete-message strong {
        color: #dc3545;
    }
    
    .delete-details {
        background: #f8fafc;
        border-radius: 8px;
        padding: 18px 20px;
        text-align: left;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    
    .delete-details .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .delete-details .detail-row:last-child {
        border-bottom: none;
    }
    
    .delete-details .label {
        font-size: 13px;
        color: #6b7a8f;
        font-weight: 500;
    }
    
    .delete-details .value {
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
    }
    
    .delete-details .value.highlight {
        color: #28a745;
        font-size: 16px;
    }
    
    .btn-group {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-danger {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220,53,69,0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        color: #fff;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-danger i, .btn-secondary i {
        font-size: 18px;
    }
    
    .warning-text {
        color: #856404;
        background: #fff3cd;
        padding: 10px 15px;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 20px;
        border: 1px solid #ffc107;
        text-align: left;
    }
    
    @media (max-width: 768px) {
        .delete-card {
            padding: 20px;
        }
        .delete-title {
            font-size: 18px;
        }
        .delete-icon {
            font-size: 48px;
        }
        .btn-group {
            flex-direction: column;
        }
        .btn-group .btn {
            width: 100%;
            justify-content: center;
        }
        .delete-details .detail-row {
            flex-direction: column;
            padding: 8px 0;
        }
        .delete-details .value {
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
                                <span class="text-muted fw-light">Payroll /</span> Delete Payroll
                            </h4>
                            <a href="list.php" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i> Back to List
                            </a>
                        </div>
                        
                        <div class="delete-container">
                            <div class="delete-card">
                                
                                <!-- Icon -->
                                <div class="delete-icon">
                                    <i class="bx bx-trash"></i>
                                </div>
                                
                                <!-- Title -->
                                <div class="delete-title">Delete Payroll Record</div>
                                
                                <!-- Message -->
                                <div class="delete-message">
                                    Are you sure you want to delete this payroll record? 
                                    This action <strong>cannot be undone</strong>.
                                </div>
                                
                                <!-- Warning -->
                                <div class="warning-text">
                                    <i class="bx bx-error-circle me-1"></i>
                                    <strong>Warning:</strong> This will permanently remove this payroll record from the system.
                                </div>
                                
                                <!-- Record Details -->
                                <div class="delete-details">
                                    <div class="detail-row">
                                        <span class="label">Employee Name</span>
                                        <span class="value"><?= htmlspecialchars($payroll['firstName'] . ' ' . $payroll['lastName']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Employee ID</span>
                                        <span class="value"><?= htmlspecialchars($payroll['username'] ?? $payroll['employee_id']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Pay Month</span>
                                        <span class="value"><?= date('F Y', strtotime($payroll['pay_month'] . '-01')) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Gross Salary</span>
                                        <span class="value">₹ <?= number_format($payroll['gross_salary'], 2) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Net Salary</span>
                                        <span class="value highlight">₹ <?= number_format($payroll['net_salary'], 2) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Status</span>
                                        <span class="value">
                                            <span class="badge <?= $payroll['payment_status'] == 'paid' ? 'bg-success' : 'bg-warning' ?>">
                                                <?= ucfirst($payroll['payment_status']) ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="btn-group">
                                    <form action="" method="POST" style="display:inline;">
                                        <button type="submit" name="confirm_delete" class="btn-danger" 
                                                onclick="return confirm('Are you absolutely sure you want to delete this payroll record?');">
                                            <i class="bx bx-trash"></i> Yes, Delete
                                        </button>
                                    </form>
                                    <a href="list.php" class="btn-secondary">
                                        <i class="bx bx-arrow-back"></i> Cancel
                                    </a>
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
        // Additional confirmation before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Are you absolutely sure you want to delete this payroll record? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    </script>

</body>
</html>