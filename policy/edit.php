<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="add";</script>';
    exit();
}

$policy_id = $_GET['id'];
// Fetch department details
$query = "SELECT * FROM tbl_policy WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $policy_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Policy not found!"); window.location.href="add";</script>';
    exit();
}

$policy = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Policy</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="policy_id" value="<?= $policy['id']; ?>">


                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="loan_type">Loan Type</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="loan_type" name="loan_type" class="form-select">
                                                            <option value="">Select Loan Type</option>
                                                            <?php
                                                        $query = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $policy['loan_type_id']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="vendor_bank"> Vendor
                                                        Bank</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-briefcase"></i></span>
                                                        <select id="vendor_bank" name="vendor_bank" class="form-select">
                                                            <option value="">Select Vendor Bank</option>
                                                            <?php
                                                        $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $policy['vendor_bank_id']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vendor_bank_name'].'</option>';
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-mb-3 ">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <input class="form-control" type="file" id="file"
                                                            name="file" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-mb-3 ">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-message"></i></span>
                                                            <textarea name="content" id="content" class="form-control" placeholder="Enter Content........"><?= $policy['content'] ?></textarea>
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
    $policy_id = $_POST['policy_id'];
    $vendor_bank = $_POST['vendor_bank'];
    $loan_type = $_POST['loan_type'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $updated_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($document_name)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "Document Name Field is required",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    // Fetch current image (if no new file is uploaded, we keep this)
    $stmt = $conn->prepare("SELECT image FROM tbl_policy WHERE id=?");
    $stmt->bind_param("i", $policy_id);
    $stmt->execute();
    $stmt->bind_result($existing_image);
    $stmt->fetch();
    $stmt->close();

    $brocher = $existing_image; // Default to existing image

    // ✅ If a new file is selected, update it
    if (!empty($_FILES['file']['name'])) {
        $filename = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $path = '../uploads/policy/';
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $valid_extensions = ['jpeg', 'jpg', 'png']; // Ensure 'pdf' is lowercase

        if (in_array($ext, $valid_extensions)) {
            $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
            if (move_uploaded_file($tmp, $path . $final_image)) {
                $brocher = $final_image; // Update with new image
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Invalid file format. Only JPEG, JPG, PNG are allowed.",
                    position: "topRight",
                });
            </script>';
            exit();
        }
    }

    // ✅ Update the database with the new values
    $stmt = $conn->prepare("UPDATE `tbl_policy` SET `vendor_bank_id`=?, `loan_type_id`=?, `image`=?, `content`=?,`updated_at`=? WHERE id=?");
    $stmt->bind_param("sssssi", $vendor_bank, $loan_type, $brocher, $content, $updated_at, $policy_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Policy Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="add"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="add"; }, 1000);
        </script>';
    }

    $stmt->close();
    $conn->close();
}
?>
