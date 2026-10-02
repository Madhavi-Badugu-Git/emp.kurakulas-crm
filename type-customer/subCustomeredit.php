<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="subCustomer";</script>';
    exit();
}

$sub_customer_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_sub_customer WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sub_customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("subcustomer not found!"); window.location.href="subCustomer";</script>';
    exit();
}

$subcustomer = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Sub Customer</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="sub_customer_id" value="<?= $subcustomer['id']; ?>">
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                                                    <select id="customer" name="customer" class="form-select">
                                                        <option value="">Select customer</option>
                                                        <?php
                                                        $query = "SELECT id, customer_name FROM tbl_type_of_customer ORDER BY customer_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $subcustomer['customer_id']) ? 'selected' : ''; // Preselect the department
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['customer_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                                    <input type="text" class="form-control" name="subcustomer_name"
                                                        id="subcustomer_name"
                                                        value="<?= $subcustomer['subcustomer_name']; ?>"
                                                        placeholder="Sub Customer Name" />
                                                </div>
                                            </div>
                                            <input type="submit" name="update_customer" class="btn btn-primary" value="Update">
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
if (isset($_POST['update_customer'])) {
    $sub_customer_id = $_POST['sub_customer_id'];
    $subcustomer_name = $_POST['subcustomer_name'];
    $customer_id = $_POST['customer'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($subcustomer_name) || empty($customer_id)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else {
        // Update query
        $sql = "UPDATE tbl_sub_customer SET subcustomer_name = ?, customer_id = ?, updated_at ='$created_at'  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $subcustomer_name, $customer_id, $sub_customer_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "subcustomer Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="subCustomer"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="subCustomer"; }, 1000);
            </script>';
        }
    }
}
?>
