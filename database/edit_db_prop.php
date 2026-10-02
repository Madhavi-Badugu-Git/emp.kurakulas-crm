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

$prop_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_database_property_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Credit Card Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$property = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Property</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="prop_id" value="<?= $property['id']; ?>">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="form-label">Type Of Property</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-home"></i></span>
                                                        <select name="p_property_type" class="form-select">
                                                            <option value="">Select Property Type
                                                            </option>
                                                            <?php
                                                            $query = "SELECT id, property_type FROM tbl_bank_property_type ORDER BY property_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $property['p_property_type']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['property_type'].'</option>';
                                                            }
                                                            
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">Area</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-crop"></i></span>
                                                        <input type="text" class="form-control" name="p_area"
                                                            value="<?= $property['p_area']; ?>" placeholder="Area">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Land In Sq. Yards</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-expand"></i></span>
                                                        <input type="text" class="form-control"
                                                                            name="p_lands"  value="<?= $property['p_lands']; ?>"
                                                                            placeholder=" Land In Sq. Yards">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">SFT</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-ruler"></i></span>
                                                                <input type="text" class="form-control"
                                                                                    name="p_sft"  value="<?= $property['p_sft']; ?>"
                                                                                    placeholder="SFT">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Market Value</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-wallet"></i></span>
                                                        <input type="text" class="form-control"
                                                                                    name="p_market_value"  value="<?= $property['p_market_value']; ?>"
                                                                                    placeholder="Market Value" >
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
    $prop_id = $_POST['prop_id'];
    $p_property_type = $_POST['p_property_type'] ?? '';
    $p_area = $_POST['p_area'] ?? '';
    $p_lands = $_POST['p_lands'] ?? '';
    $p_sft = $_POST['p_sft'] ?? '';
    $p_market_value = $_POST['p_market_value'] ?? '';


    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($p_property_type) || empty($p_area) || empty($p_lands) || empty($p_sft) || empty($p_market_value)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "All Fields are required",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    // Update query
    $sql = "UPDATE tbl_database_property_details SET p_property_type = ?, p_area = ?, p_lands = ?, p_sft = ?, p_market_value = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $p_property_type, $p_area, $p_lands, $p_sft, $p_market_value, $prop_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Property Updated Successfully",
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