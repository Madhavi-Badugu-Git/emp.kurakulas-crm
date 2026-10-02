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

$icon_id = $_GET['id'];
// echo $icon_id;

// Fetch department details
$query = "SELECT * FROM tbl_data_icon WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $icon_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo '<script>alert("Data Icon not found!"); window.location.href="add";</script>';
    exit();
}

$icon = $result->fetch_assoc();
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
                                        <h5 class="mb-0">Edit Data Icon</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <input type="hidden" name="icon_id" value="<?= $icon['id']; ?>">


                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-cog"></i></span>
                                                        <input type="text" class="form-control" name="icon_name"
                                                            id="icon_name" value="<?= $icon['icon_name']; ?>"
                                                            placeholder="Icon Name" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <input class="form-control" type="file" id="formFile"
                                                            name="file">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="mb-6">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-link"></i></span>
                                                        <input type="url" class="form-control" name="url_name"
                                                            id="url_name" value="<?= $icon['icon_url']; ?>"
                                                            placeholder="Url Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row " style="margin-top: -10px;">
                                                <div class="mb-6">
                                                    <div class="input-group input-group-merge">
                                                        <span id="birth_date2" class="input-group-text">
                                                            <i class="bx bx-message-square"></i>
                                                        </span>
                                                        <textarea name="description" id="description"
                                                            class="form-control"
                                                            placeholder="Description"><?= $icon['icon_description']; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>


                                            <input type="submit" name="update_icon" class="btn btn-primary"
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
if (isset($_POST['update_icon'])) {
    $icon_id = $_POST['icon_id'];
    $icon_name = $_POST['icon_name'];
    $url_name = $_POST['url_name'];
    $description = $_POST['description'];
    $updated_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($icon_name) || empty($url_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Icon Name and URL Name are required",
                position: "topRight",
            });
        </script>';
        exit();
    }

    // Fetch current image (if no new file is uploaded, we keep this)
    $stmt = $conn->prepare("SELECT icon_image FROM tbl_data_icon WHERE id=?");
    $stmt->bind_param("i", $icon_id);
    $stmt->execute();
    $stmt->bind_result($existing_image);
    $stmt->fetch();
    $stmt->close();

    $brocher = $existing_image; // Default to existing image

    // ✅ If a new image is selected, update it
    if (!empty($_FILES['file']['name'])) {
        $filename = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $path = '../uploads/data-icons/';
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png');

        if (in_array($ext, $valid_extensions)) {
            $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
            if (move_uploaded_file($tmp, $path . $final_image)) {
                $brocher = $final_image; // Update with new image
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Error",
                    message: "Invalid file format. Only JPEG, JPG, and PNG allowed.",
                    position: "topRight",
                });
            </script>';
            exit();
        }
    }

    // ✅ Update the database with the new values
    $stmt = $conn->prepare("UPDATE `tbl_data_icon` SET `icon_name`=?, `icon_url`=?, `icon_description`=?, `icon_image`=?, `updated_at`=? WHERE id=?");
    $stmt->bind_param("sssssi", $icon_name, $url_name, $description, $brocher, $updated_at, $icon_id);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Data Icon Updated Successfully",
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

    $stmt->close();
    $conn->close();
}
?>
