<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>alert("Invalid Request!"); window.location.href="add";</script>';
    exit();
}

$rating_id = $_GET['id'];

// Fetch designation details
$query = "SELECT * FROM tbl_rating WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rating_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Rating not found!"); window.location.href="add";</script>';
    exit();
}

$rating = $result->fetch_assoc();
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
                                        <h4 class="mb-0">Edit Rating</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="rating_id" value="<?= $rating['id']; ?>">
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-star"></i></span>
                                                    <select id="rating_type" name="rating_type" class="form-select">
                                                        <option value="">Select Type of Rating</option>
                                                        <?php
                                                        $query = "SELECT id, rating_type FROM tbl_type_rating ORDER BY rating_type ASC";
                                                        $result = $conn->query($query);

                                                        while ($row = $result->fetch_assoc()) {
                                                            $selected = ($row['id'] == $rating['rating_type_id']) ? 'selected' : ''; // Preselect the department
                                                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['rating_type'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-6">
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bxs-star"></i></span>
                                                    <input type="text" class="form-control" name="rating_name"
                                                        id="rating_name"
                                                        value="<?= $rating['rating_name']; ?>"
                                                        placeholder="Rating Name"/>
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
    $rating_id = $_POST['rating_id'];
    $rating_name = $_POST['rating_name'];
    $rating_type = $_POST['rating_type'];
    $created_at = date('Y-m-d H:i:s');


    // Validate required fields
    if (empty($rating_name) || empty($rating_type)) {
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
        $sql = "UPDATE tbl_rating SET rating_name = ?, rating_type_id = ?, updated_at ='$created_at'  WHERE id = ?";
       
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $rating_name, $rating_type, $rating_id);

        if ($stmt->execute()) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Rating Updated Successfully",
                    position: "topRight",
                });
                setTimeout(() => { window.location.href="add"; }, 1000);
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
