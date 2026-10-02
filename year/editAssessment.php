<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="addAssessment";</script>';
    exit();
}

$assessment_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_assessment_year WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Assessment Year not found!"); window.location.href="addAssessment";</script>';
    exit();
}

$assessment = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Assessment Year</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="assessment_id" value="<?= $assessment['id']; ?>">
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                                                    <select id="financial_year" name="financial_year" class="form-select">
                                                        <option value="">Select Financial Year</option>
                                                        <?php
                                                        $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $assessment['financial_year_id']) ? 'selected' : ''; // Preselect the department
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['financial_year'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                                    <input type="text" class="form-control" name="assessment_year"
                                                        id="assessment_year"
                                                        value="<?= $assessment['assessment_year']; ?>"
                                                        placeholder="Assessment Year (YYYY-YY)" maxlength="7" pattern="^\d{4}-\d{2}$"/>
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
    $assessment_id = $_POST['assessment_id'];
    $assessment_year = $_POST['assessment_year'];
    $financial_year = $_POST['financial_year'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($assessment_year) || empty($financial_year)) {
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
        $sql = "UPDATE tbl_assessment_year SET assessment_year = ?, financial_year_id = ?, updated_at ='$created_at'  WHERE id = ?";
       
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $assessment_year, $financial_year, $assessment_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Assessment Year Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="addAssessment"; }, 1000);
            </script>';
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Something Went Wrong, Please Try Again",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="addAssessment"; }, 1000);
            </script>';
        }
    }
}
?>
