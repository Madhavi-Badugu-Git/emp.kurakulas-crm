<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="bank";</script>';
    exit();
}

$payout_id = $_GET['id'];

// Fetch Payout details
$query = "SELECT * FROM tbl_bank_payout WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payout_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Bank Payout not found!"); window.location.href="bank";</script>';
    exit();
}

$payout1 = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>
            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Edit Bank Payout</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="payout_id" value="<?= $payout1['id']; ?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="loan_type">Loan Type</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="loan_type" name="loan_type" class="form-select">
                                                            <option value="">Select Loan Type</option>
                                                            <?php
                                                        $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $payout1['loan_type']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="vendor_bank_name"> Vendor
                                                        Bank</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="vendor_bank_name" name="vendor_bank_name"
                                                            class="form-select">
                                                            <option value="">Select Vendor Bank</option>
                                                            <?php
                                                        $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $payout1['vendor_bank_name']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vendor_bank_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                            <div class="col-md-6">
                                                    <label class="form-label" for="category_name"> Category</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="category_name" name="category_name" class="form-select">
                                                            <option value="">Select Category</option>
                                                            <?php
                                                        $query = "SELECT id, category_name FROM tbl_payout_category ORDER BY category_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $payout1['category_name']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['category_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="document_file" class="form-label">Payout Sheet
                                                    </label>
                                                    <input class="form-control" type="file" id="document_file"
                                                        name="document_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="mb-6">
                                                    <label class="form-label" for="document_name"> Document
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-wallet"></i></span>
                                                        <input type="text" class="form-control" name="document_name"
                                                            id="document_name" value="<?= $payout1['document_name']; ?>"
                                                            placeholder="Document Name" />
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="submit" name="update_form" class="btn btn-primary mt-3"
                                                value="Update">
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
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <?php include('../includes/script.php'); ?>
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $payout_id = $_POST['payout_id'];
    $loan_type = $_POST['loan_type'];
    $vendor_bank_name = $_POST['vendor_bank_name'];
    $category_name = $_POST['category_name'];
    $document_name = $_POST['document_name'];
    $created_at = date('Y-m-d H:i:s');

    // Fetch existing document file
    $stmt = $conn->prepare("SELECT document_file FROM tbl_bank_payout WHERE id = ?");
    $stmt->bind_param("i", $payout_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $document_file = $row['document_file']; // Keep previous file

    // File Upload Handling (Replace only if a new file is uploaded)
    if (!empty($_FILES["document_file"]["name"])) { 
        $target_dir = "../uploads/bank-payout/";
        $file_name = time() . "_" . basename($_FILES["document_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
                $document_file = $file_name; // Update only if a new file is uploaded
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "Invalid file type. Only JPG, JPEG, PNG, PDF allowed.",
                    position: "topRight"
                });
            </script>';
            exit();
        }
    }

    // Update query
    $sql = "UPDATE tbl_bank_payout SET document_name = ?, document_file = ?, loan_type = ?, vendor_bank_name = ?, category_name = ?,updated_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiisi", $document_name, $document_file, $loan_type, $vendor_bank_name, $category_name, $created_at, $payout_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Bank Payout Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="bank"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="bank"; }, 1000);
        </script>';
    }
}
?>
