<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="database";</script>';
    exit();
}

$get_id = $_GET['id'];

// echo $get_id;
// exit();

$query = "SELECT * FROM tbl_database WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $get_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("File Status not found!"); window.location.href="database";</script>';
    exit();
}

$database = $result->fetch_assoc();
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

                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="get_id" value="<?= $database['id']; ?>">

                                            <div class="row mt-5">
                                                <h5>Add Property Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Type Of Property
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Area
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Land In Sq. Yards
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        SFT
                                                                    </th>
                                                                    <th style="width: 20%; min-width: 200px;">
                                                                        Market Value
                                                                    </th>

                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="propertyTable">
                                                                <tr>
                                                                    <td>
                                                                        <select name="p_property_type[]"
                                                                            class="form-select">
                                                                            <option value="">Select Property Type
                                                                            </option>
                                                                            <?php
                                                                        $query = "SELECT id, property_type FROM tbl_bank_property_type ORDER BY property_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['property_type'].'</option>';
                                                                        }
                                                                    ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="p_area[]" placeholder="Area">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="p_lands[]"
                                                                            placeholder=" Land In Sq. Yards">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="p_sft[]" placeholder="SFT">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            name="p_market_value[]"
                                                                            placeholder="Market Value">
                                                                    </td>

                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn3 addRow3">Add</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <input type="submit" name="submit_form" class="btn btn-primary mt-10"
                                                    value="Save">
                                            </div>
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
    $(document).on("click", ".action-btn3", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow3")) {
            let newRow = `<tr>
                           <td>
                                                                        <select name="p_property_type[]"
                                                                                    class="form-select">
                                                                                    <option value="">Select Property Type
                                                                                    </option>
                                                                                    <?php
                                                                        $query = "SELECT id, property_type FROM tbl_bank_property_type ORDER BY property_type ASC";
                                                                        $result = $conn->query($query);
                                                                        while ($row = $result->fetch_assoc()) {
                                                                            echo '<option value="'.$row['id'].'">'.$row['property_type'].'</option>';
                                                                        }
                                                                    ?>
                                                                                </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_area[]" 
                                                                                placeholder="Area">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_lands[]" 
                                                                                placeholder=" Land In Sq. Yards">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_sft[]" 
                                                                                placeholder="SFT">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control"
                                                                                name="p_market_value[]"
                                                                                placeholder="Market Value" >
                                                                        </td>
                            <td>
                                <button type="button" class="btn btn-success action-btn3 addRow3">Add</button>
                            </td>
                        </tr>`;

            $("#propertyTable").append(newRow);
            btn.removeClass("btn-primary addRow3").addClass("btn-danger removeRow3").text("Delete");
        } else if (btn.hasClass("removeRow3")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#propertyTable tr:last");
        lastRow.find(".action-btn3").removeClass("btn-danger removeRow3").addClass("btn-primary addRow3").text(
            "Add");
    }
    </script>
</body>

</html>

<?php
// Handle update
if (!empty($_POST['p_property_type'])) {
    $get_id = $_POST['get_id'];
    $created_at = date('Y-m-d H:i:s');
    $all_success = true;

    foreach ($_POST['p_property_type'] as $key => $p_property_type) {
        $p_property_type = mysqli_real_escape_string($conn, $p_property_type);
        $p_area = mysqli_real_escape_string($conn, $_POST['p_area'][$key]);
        $p_lands = mysqli_real_escape_string($conn, $_POST['p_lands'][$key]);
        $p_sft = mysqli_real_escape_string($conn, $_POST['p_sft'][$key]);
        $p_market_value = mysqli_real_escape_string($conn, $_POST['p_market_value'][$key]);  

        $property_sql = "INSERT INTO `tbl_database_property_details`(`database_id`, `p_property_type`, `p_area`, `p_lands`, `p_sft`, `p_market_value`, `created_at`) 
                         VALUES ('$get_id', '$p_property_type', '$p_area', '$p_lands', '$p_sft', '$p_market_value', '$created_at')";

        if (!mysqli_query($conn, $property_sql)) {
            $all_success = false;
        }
    }

    // Show single success or error message after loop
    if ($all_success) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Property Details Added Successfully",
                position: "topRight"
            });
            setTimeout(() => { window.location.href="database"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Some rows failed to insert. Please try again.",
                position: "topRight"
            });
            setTimeout(() => { window.location.href="database"; }, 1000);
        </script>';
    }
}


?>