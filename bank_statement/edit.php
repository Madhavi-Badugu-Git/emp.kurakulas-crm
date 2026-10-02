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

$bank_id = $_GET['id'];

// Fetch bank details
$query = "SELECT * FROM tbl_bank_statement WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bank_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Bank not found!"); window.location.href="add";</script>';
    exit();
}

$bank = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Bank Statement</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="bank_id" value="<?= $bank['id']; ?>">

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="dsa_name">Account DSA Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user-voice"></i></span>
                                                        <select id="dsa_name" name="dsa_name" class="form-select"
                                                            >
                                                            <option value="">Select Account DSA Name</option>
                                                            <?php
                                                                $query = "SELECT id, dsa_name FROM tbl_account_dsa ORDER BY dsa_name ASC";
                                                                $result = $conn->query($query);
                                                               
                                                                while ($dsa = $result->fetch_assoc()) {
                                                                    $selected = ($dsa['id'] == $bank['dsa_name']) ? 'selected' : '';
                                                                    echo '<option value="'.$dsa['id'].'" '.$selected.'>'.$dsa['dsa_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_name">Account Bank Name</label><span
                                                        style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-building"></i></span>
                                                        <select id="bank_name" name="bank_name" class="form-select"
                                                            >
                                                            <option value="">Select Account Bank Name</option>
                                                            <?php
                                                                $query = "SELECT id, bank_name FROM tbl_account_bank ORDER BY bank_name ASC";
                                                                $result = $conn->query($query);
                                                               
                                                                while ($bank_row = $result->fetch_assoc()) {
                                                                    $selected = ($bank_row['id'] == $bank['bank_name']) ? 'selected' : '';
                                                                    echo '<option value="'.$bank_row['id'].'" '.$selected.'>'.$bank_row['bank_name'].'</option>';
                                                                }
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="Phone_number">Month / Year</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calendar"></i></span>
                                                        <input type="month" id="month_year" name="month_year" class="form-control" value="<?= $bank['month_year']; ?>" placeholder="MM-YYYY">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="file" class="form-label">Upload File
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-file"></i></span>
                                                        <input class="form-control" type="file" id="file"
                                                        name="file" accept="image/*,application/pdf">
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="password">Passwprd
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                    class="bx bx-lock"></i></span>
                                                       <input type="password" name="password" id="password" class="form-control" value="<?= $bank['password']; ?>" placeholder="Enter password">

                                                        <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
                                                            <i class="bx bx-show" id="toggleIcon"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="submit" name="update_bank_statement" class="btn btn-primary mt-3" value="Update">
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
    <script>
    function togglePassword() {
        const password = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");

        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("bx-show");
            icon.classList.add("bx-hide");
        } else {
            password.type = "password";
            icon.classList.remove("bx-hide");
            icon.classList.add("bx-show");
        }
    }
</script>

</body>
</html>

<?php
// Handle update
if (isset($_POST['update_bank_statement'])) {
    $bank_id = $_POST['bank_id'];
    $dsa_name = mysqli_real_escape_string($conn, $_POST['dsa_name']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $month_year = mysqli_real_escape_string($conn, $_POST['month_year']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $created_at = date('Y-m-d H:i:s');

    // ---------- Validation ----------
    if (empty($dsa_name) || empty($bank_name) || empty($month_year)) {
        echo '<script>
            iziToast.warning({
                title: "Warning",
                message: "Please fill all required fields.",
                position: "topRight"
            });
        </script>';
    } else {

        // Fetch existing file
        $getOldFile = mysqli_query(
            $conn,
            "SELECT uploaded_file FROM tbl_bank_statement WHERE id = '$bank_id'"
        );
        $oldData = mysqli_fetch_assoc($getOldFile);
        $oldFile = $oldData['uploaded_file'] ?? '';

        // ---------- File Handling ----------
        $file = $oldFile; // default → keep old file
        $target_dir = "../uploads/bank_statement/";

        if (!empty($_FILES['file']['name'])) {

            $file_name = time() . "_" . basename($_FILES["file"]["name"]);
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = array("jpg", "jpeg", "png", "pdf");

            if (!in_array($file_type, $allowed_types)) {
                echo '<script>
                    iziToast.warning({
                        title: "Warning",
                        message: "Invalid file type. Only JPG, PNG, PDF allowed.",
                        position: "topRight"
                    });
                </script>';
                exit();
            }

            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }

            // Delete old file (optional but recommended)
            if (!empty($oldFile) && file_exists($target_dir . $oldFile)) {
                unlink($target_dir . $oldFile);
            }

            $file = $file_name;
        }

        // ---------- UPDATE QUERY ----------
        $sql = "UPDATE `tbl_bank_statement` SET
            `dsa_name`     = '$dsa_name',
            `bank_name`    = '$bank_name',
            `month_year`   = '$month_year',
            `uploaded_file`= '$file',
            `password`     = '$password',
            `updated_at`   = '$created_at'
        WHERE `id` = '$bank_id'";

        if (mysqli_query($conn, $sql)) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Bank statement updated successfully!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "add"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.error({
                    title: "Error",
                    message: "Failed to update Bank statement. Please try again.",
                    position: "topRight"
                });
            </script>';
        }
    }
}
?>
