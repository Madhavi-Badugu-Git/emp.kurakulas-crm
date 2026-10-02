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

$senp_id = $_GET['id'];
$get_id = $_GET['get_id'];
// echo $get_id;

$query = "SELECT * FROM tbl_senp_fy_details WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $senp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("SENP ID Id not found!"); window.location.href="view?id=' . $get_id . '";</script>';
    exit();
}

$senp = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit SENP Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="senp_id" value="<?= $senp['id']; ?>">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="form-label">Financial Year</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-calendar"></i>
                                                        </span>

                                                        <select name="financial_year" class="form-select" onchange="getAssessmentYear(this)">
                                                            <option value="">Select Financial Year</option>
                                                            <?php
                                                            $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = ($row['id'] == $senp['financial_year']) ? 'selected' : '';
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['financial_year'].'</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <label class="form-label" for="assessment_year">Assessment Year</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-calendar"></i>
                                                    </span>
                                                    <select name="assessment_year" id="assessment_year" class="form-select assessment_year">
                                                        <option value="">Select Assessment Year</option>
                                                        <?php
                                                        $query = "SELECT id, assessment_year FROM tbl_assessment_year WHERE financial_year_id = '".$senp['financial_year']."' ORDER BY assessment_year ASC";
                                                        $result = $conn->query($query);
                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $senp['assessment_year']) ? 'selected' : '';
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['assessment_year'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">Turnover</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-transfer-alt"></i></span>
                                                        <input type="text" class="form-control" name="turnover" id="turnover"
                                                            value="<?= $senp['turnover']; ?>" placeholder="Turnover" />
                                                            
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">Depreciation</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-trending-down"></i></span>
                                                        <input type="text" class="form-control" name="depreciation" id="depreciation"
                                                            value="<?= $senp['depreciation']; ?>" placeholder="Depreciation" />
                                                            
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label for="form-label">PBT</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-line-chart"></i></span>
                                                        <input type="text" class="form-control" name="pbt" id="pbt"
                                                            value="<?= $senp['pbt']; ?>" placeholder="PBT" />
                                                            
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="form-label">PAT</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-bar-chart-alt-2"></i></span>
                                                        <input type="text" class="form-control" name="pat" id="pat"
                                                            value="<?= $senp['pat']; ?>" placeholder="PAT" />
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
    <script>
        function getAssessmentYear(selectElement) {
            const financial_year_id = selectElement.value;  // Get selected financial year ID
            const assessmentSelect = document.querySelector('.assessment_year');  // Get the assessment year select dropdown

            if (financial_year_id) {
                // Check if we are getting the correct value
                console.log("Selected Financial Year ID: " + financial_year_id);
                
                // Fetch the assessment years
                fetch("../info/get_assessment_year.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "financial_year_id=" + financial_year_id
                })
                .then(response => response.text())
                .then(data => {
                    // Output the fetched data to debug
                    console.log("Fetched Data: " + data);

                    // Populate the assessment year select dropdown
                    assessmentSelect.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                assessmentSelect.innerHTML = '<option value="">Select Assessment Year</option>';
            }
        }

    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $senp_id = $_POST['senp_id'];
    $financial_year = $_POST['financial_year'] ?? '';
    $assessment_year = $_POST['assessment_year'] ?? '';
    $turnover = $_POST['turnover'] ?? '';
    $depreciation = $_POST['depreciation'] ?? '';
    $pbt = $_POST['pbt'] ?? '';
    $pat = $_POST['pat'] ?? '';


    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    // if (empty($financial_year) || empty($assessment_year)) {
    //     echo '<script>
    //         iziToast.warning({
    //             title: "Error",
    //             message: "Both Fields are required",
    //             position: "topRight",
    //         });
    //     </script>';
    //     exit();
    // }

    // Update query
    $sql = "UPDATE tbl_senp_fy_details SET financial_year = ?, assessment_year = ?, turnover = ?, depreciation = ?, pbt = ?, pat = ?, updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $financial_year, $assessment_year, $turnover, $depreciation, $pbt, $pat, $senp_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "SENP Details Updated Successfully",
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