<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="list";</script>';
    exit();
}

$user_id = $_GET['id'];
// echo "SELECT * FROM tbl_user WHERE status='0' AND id='$user_id'";
$sql = mysqli_query($conn, "SELECT * FROM tbl_user WHERE status='0' AND id='$user_id'");
if(mysqli_num_rows($sql)>0){
    if($user = mysqli_fetch_assoc($sql)){
        $id = $user['id'];
        $username = $user['username'];
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
                                <div class="card mb-6">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Active User</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="user_id" value="<?= $id; ?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="username">UserName</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" name="username"
                                                            id="username" value="<?= $username; ?>"
                                                            placeholder="" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="re_joining_date">Re Joining 
                                                        Date</label><span style="color:red;">*</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-calender"></i></span>
                                                        <input type="text" class="form-control" name="re_joining_date"
                                                            id="re_joining_date" placeholder="DD/MM/YYYY" />
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
    // date of birth 
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.getElementById("re_joining_date");

        // Function to format date as DD/MM/YYYY
        function formatDate(date) {
            let d = new Date(date);
            let day = ("0" + d.getDate()).slice(-2);
            let month = ("0" + (d.getMonth() + 1)).slice(-2);
            let year = d.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // ✅ If there's a prefilled value (e.g., from database), format it properly
        if (dateInput.value) {
            dateInput.value = formatDate(new Date(dateInput.value));
        }

        // ✅ Restrict input to only valid date format (DD/MM/YYYY)
        dateInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9/]/g, "").substring(0, 10);
        });

        // ✅ Convert DD/MM/YYYY to YYYY-MM-DD before form submission
        dateInput.form.addEventListener("submit", function() {
            let parts = dateInput.value.split("/");
            if (parts.length === 3) {
                let formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert to YYYY-MM-DD
                dateInput.value = formattedDate;
            }
        });
    });
    </script>
</body>

</html>

<?php
// Handle update
if (isset($_POST['update_form'])) {
    $user_id = $_POST['user_id'];
    $re_joining_date = $_POST['re_joining_date'] ?? '';
// echo $re_joining_date;
// exit();
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($re_joining_date)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Re Joining Date is required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Update query
    $sql = "UPDATE tbl_user SET re_joining_date = ?, status='1', updated_at='$created_at' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $re_joining_date, $user_id);


    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "User Activated Successfully",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="list"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="list"; }, 1000);
        </script>';
    }
}
?>