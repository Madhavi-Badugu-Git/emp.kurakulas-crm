<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

$loggedInUsername = $_SESSION['loggedInUser'];
$loggedInUserRank = $_SESSION['loggedInUserRank'];
$loggedInUserDesignation = $_SESSION['designation_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid Request!");
        window.location.href = "appointment";
    </script>';
    exit();
}
$get_id = $_GET['id'];
// echo $get_id;

$sql_appt = mysqli_query($conn, "SELECT * FROM tbl_appointment WHERE id ='$get_id'");
if(mysqli_num_rows($sql_appt)>0){
    while($row_appt =mysqli_fetch_assoc($sql_appt)){
        $appt_movedBy = $row_appt['createdBy'];
        $unique_id = $row_appt['unique_id'];
    }
}
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
                                <div class="card">
                                    <h5 class="card-header">Move Appt File</h5>

                                    <!-- Filter Form -->
                                    <form method="POST" class="p-3">
                                        <div class="row">
                                            <label  class="form-label" for="">Regional Business Head <span style="color:red"> *</span></label>
                                            <div class="col-md-6">
                                                <?php
                                                $query = "
                                                    SELECT u.id, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name
                                                    FROM tbl_user u
                                                    JOIN tbl_designation d ON u.designation_id = d.id
                                                    JOIN tbl_department dep ON u.department_id = dep.id
                                                    WHERE 
                                                        dep.department_name = 'Marketing' 
                                                        AND d.designation_name = 'Regional Business Head'
                                                ";
                                                $result = $conn->query($query);
                                                ?>
                                                <select name="rbh_user_id" id="rbh_user_id" class="form-select" 
                                                    onchange="loadBusinessHeads(this.value)">
                                                    <option value="">Select Regional Business Head</option>
                                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <option value="<?= $row['id']; ?>">
                                                        <?= $row['name']; ?> (<?= $row['designation_name']; ?>)
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>

                                        <!-- Business Heads Dropdown -->
                                        <div class="row mt-3">
                                            <label  class="form-label" for=""> Business Head <span style="color:red"> *</span></label>
                                            <div class="col-md-6">
                                                <select name="bh_user_id" id="bh_user_id" class="form-select" >
                                                    <option value="">Select Business Head</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3"> -->
                                            <input type="submit" name="moveFile" class="btn btn-primary mt-5"
                                                value="Move Appt">
                                        <!-- </div> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
        function loadBusinessHeads(rbhUserId) {
            if (rbhUserId) {
                fetch("../info/getBusinessHeads.php?rbh_user_id=" + rbhUserId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("bh_user_id").innerHTML = data;
                    })
                    .catch(error => console.error("Error loading Business Heads:", error));
            }
        }
    </script>
</body>

</html>

<?php
if(isset($_POST['moveFile'])){
    $rbh_user_id = $_POST['rbh_user_id'];
    $bh_user_id = $_POST['bh_user_id'];

    $created_at = date('Y-m-d H:i:s');
    if (empty($rbh_user_id) || empty($bh_user_id)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Both fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{
         $sql = "INSERT INTO `tbl_appointment_info`(`appointment_id`,`unique_id`,`appt_moved_by`,`appt_movedToRbh`,`appt_mobedToBh`, `appt_through`,`created_at`) VALUES ('$get_id', '$unique_id', '$appt_movedBy', '$rbh_user_id','$bh_user_id','File Move', '$created_at')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "File Moved Successfully",
                    position: "topRight",
                });
               
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
    }
}
?>