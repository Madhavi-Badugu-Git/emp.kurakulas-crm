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
    echo '<script>alert("Database Id not found!"); window.location.href="database";</script>';
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

                                            <div class="row mt-3">
                                                <h5> SENP Details</h5>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        Financial Year
                                                                    </th>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        Assessment Year
                                                                    </th>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        Turnover
                                                                    </th>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        Depreciation</th>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        PBT</th>
                                                                    <th style="width: 21%; min-width: 210px;">
                                                                        PAT
                                                                    </th>

                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="assessmentTable">
                                                                <tr>
                                                                    <td>
                                                                        <select name="financial_year[]"
                                                                            class="form-select"
                                                                            onchange="getAssessmentYear(this)">
                                                                            <option value="">Select Financial
                                                                                Year</option>
                                                                            <?php
                                                                                        $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                                                                                        $result = $conn->query($query);
                                                                                        while ($row = $result->fetch_assoc()) {
                                                                                            echo '<option value="'.$row['id'].'" data-text="'.$row['financial_year'].'">'.$row['financial_year'].'</option>';
                                                                                        }
                                                                                    ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select name="assessment_year[]"
                                                                            class="form-select assessment_year">
                                                                            <option value="">Select Assessment
                                                                                Year</option>
                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <input name="turnover[]" type="text"
                                                                            class="form-control"
                                                                            placeholder="Turnover ">
                                                                    </td>

                                                                    <td><input name="depreciation[]" type="text"
                                                                            class="form-control"
                                                                            placeholder=" Depreciation">
                                                                    </td>
                                                                    <td><input name="pbt[]" type="text"
                                                                            class="form-control" placeholder="PBT">
                                                                    </td>
                                                                    <td><input name="pat[]" type="text"
                                                                            class="form-control" placeholder="PAT">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-success action-btn-2 addRow-2">Add</button>
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
         // assessment year
    
    function getAssessmentYear(selectElement) {
        const financial_year_id = selectElement.value;
        const row = selectElement.closest('tr');
        const assessmentSelect = row.querySelector('.assessment_year');

        if (financial_year_id) {
            fetch("../info/get_assessment_year", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "financial_year_id=" + financial_year_id
                })
                .then(response => response.text())
                .then(data => {
                    assessmentSelect.innerHTML = data;
                });
        } else {
            assessmentSelect.innerHTML = '<option value="">Select Assessment Year</option>';
        }
    }



    // rating
    function getSenpRating(ratingId) {
        if (ratingId) {
            fetch("../info/get_senp_rating", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "rating_type_id=" + ratingId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("senp_rating_name").innerHTML = data;
                });
        } else {
            document.getElementById("senp_rating_name").innerHTML = '<option value="">Select Rating</option>';
        }
    }
     $(document).on("click", ".action-btn-2", function() {
        let btn = $(this);
        let row = btn.closest("tr");

        if (btn.hasClass("addRow-2")) {
            let newRow = `<tr>
            <td>
                <select name="financial_year[]"
                        class="form-select"
                        onchange="getAssessmentYear(this)">
                    <option value="">Select Financial Year</option>
                    <?php
                        $query = "SELECT id, financial_year FROM tbl_financial_year ORDER BY financial_year ASC";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['id'].'" data-text="'.$row['financial_year'].'">'.$row['financial_year'].'</option>';
                        }
                    ?>
                </select>
            </td>
            <td>
                <select name="assessment_year[]"
                        class="form-select assessment_year">
                    <option value="">Select Assessment Year</option>
                </select>
            </td>
            <td>
                <input name="turnover[]" type="text" class="form-control"
                    
                    placeholder="Turnover ">
            </td>

            <td><input  name="depreciation[]" type="text" class="form-control"
                    placeholder=" Depreciation">
            </td>
            <td><input  name="pbt[]" type="text" class="form-control"
                    placeholder="PBT">
            </td>
            <td><input name="pat[]" type="text" class="form-control"
                    placeholder="PAT">
            </td>
            <td>
                <button type="button" class="btn btn-success action-btn-2 addRow-2">Add</button>
            </td>
            </tr>`;

            $("#assessmentTable").append(newRow);
            btn.removeClass("btn-primary addRow-2").addClass("btn-danger removeRow-2").text("Delete");
        } else if (btn.hasClass("removeRow-2")) {
            row.remove();
            updateLastRow();
        }
    });

    function updateLastRow() {
        let lastRow = $("#assessmentTable tr:last");
        lastRow.find(".action-btn-2").removeClass("btn-danger removeRow-2").addClass("btn-primary addRow-2").text(
            "Add");
    }
    </script>
</body>

</html>

<?php
// Handle update
if (!empty($_POST['financial_year'])) {
    $get_id = $_POST['get_id'];
    $created_at = date('Y-m-d H:i:s');
    $all_success = true;

     // credit card details
     foreach ($_POST['financial_year'] as $key => $financial_year) {
        $financial_year = mysqli_real_escape_string($conn, $financial_year);
        $assessment_year = mysqli_real_escape_string($conn, $_POST['assessment_year'][$key]);
            $turnover = mysqli_real_escape_string($conn, $_POST['turnover'][$key]);
            $depreciation = mysqli_real_escape_string($conn, $_POST['depreciation'][$key]);
            $pbt = mysqli_real_escape_string($conn, $_POST['pbt'][$key]);
            $pat = mysqli_real_escape_string($conn, $_POST['pat'][$key]);


            $senp_fy_sql = "INSERT INTO `tbl_senp_fy_details`(`database_id`, `financial_year`, `assessment_year`, `turnover`, `depreciation`, `pbt`, `pat`, `created_at`) 
                         VALUES ('$get_id', '$financial_year', '$assessment_year', '$turnover', '$depreciation', '$pbt', '$pat', '$created_at')";


        if (!mysqli_query($conn, $senp_fy_sql)) {
            $all_success = false;
        }
    }

    

    // Show single success or error message after loop
    if ($all_success) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Financial Year Details Added Successfully",
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