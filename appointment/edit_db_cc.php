<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="view?id=' . $get_id . '";</script>';

    exit();
}

$cc_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_database_credit_card_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cc_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Credit Card Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$credit = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Credit Card</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="acc_id" value="<?= $credit['id']; ?>">

                                            <div class="mb-3">
                                                <label for="form-label">Bank Name</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-building"></i></span>
                                                    <!-- <input type="text" class="form-control" name="c_bank_name"
                                                        id="c_bank_name" value="<?= $credit['c_bank_name']; ?>" placeholder="Account Type" /> -->

                                                        <select id="c_bank_name" name="c_bank_name" class="form-select">
                                                        <option value="">Select Bank</option>
                                                        <?php
                                                        $query = "SELECT id, bank_name FROM tbl_credit_card_bank ORDER BY bank_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $credit['c_bank_name']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['bank_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="form-label">Limit</label>
                                                <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i
                                                        class="bx bx-block"></i></span>
                                                <input type="text" class="form-control" name="c_limit"
                                                    id="c_limit" value="<?= $credit['c_limit']; ?>" placeholder="Limit" />
                                                </div>
                                            </div>
                                            

                                            <input type="submit" name="update_form" class="btn btn-primary"
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
    $acc_id = $_POST['acc_id'];
    $c_bank_name = $_POST['c_bank_name'] ?? '';
    $c_limit = $_POST['c_limit'] ?? '';

    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($c_bank_name) || empty($c_limit)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Both Fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_database_credit_card_details SET c_bank_name = ?, c_limit = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $c_bank_name, $c_limit, $acc_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Credit Card Updated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="view?id=' . $get_id . '"; }, 1000);
        </script>';
    }
}
?>
