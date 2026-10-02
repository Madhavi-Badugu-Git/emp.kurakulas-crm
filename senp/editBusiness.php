<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="type_business";</script>';
    exit();
}

$business_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_senp_business_type WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $business_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Business not found!"); window.location.href="type_business";</script>';
    exit();
}

$business = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Business</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="business_id" value="<?= $business['id']; ?>">
                                            <div class="mb-6">
                                            <label class="form-label" for="industry_type">Industry Type</label><span
                                            style="color:red;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-store-alt"></i></span>
                                                    <select id="industry_type" name="industry_type" class="form-select">
                                                        <option value="">Select Industry Type</option>
                                                        <?php
                                                        $query = "SELECT id, industry_name FROM tbl_senp_industry_type ORDER BY industry_name ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $business['industry_id']) ? 'selected' : ''; 
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['industry_name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <label class="form-label" for="business_name"> Business Name</label><span
                                                style="color:red;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i
                                                            class="bx bx-briefcase"></i></span>
                                                    <input type="text" class="form-control" name="business_name"
                                                        id="business_name" value="<?= $business['business_name']; ?>" placeholder="Business Name" />
                                                </div>
                                            </div>
                                            <input type="submit" name="update_form" class="btn btn-primary" value="Update">
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
    $business_id = $_POST['business_id'];
    $industry_type = $_POST['industry_type'];
    $business_name = $_POST['business_name'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($industry_type) || empty($business_name)) {
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
        $sql = "UPDATE tbl_senp_business_type SET business_name = ?, industry_id = ?, updated_at ='$created_at'  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $business_name, $industry_type, $business_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Business Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_business"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="type_business"; }, 1000);
            </script>';
        }
    }
}
?>
