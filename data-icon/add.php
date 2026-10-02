<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

$loggedInUser = $_SESSION['loggedInUser'];
// echo $loggedInUser;

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
                                        <h5 class="mb-0">Add Data Icons</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-cog"></i></span>
                                                        <input type="text" class="form-control" name="icon_name"
                                                            id="icon_name" placeholder="Icon Name" />
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
                                                            id="url_name" placeholder="Url Name" />
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
                                                            class="form-control" placeholder="Description"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <input type="submit" name="form_submit" Value="Submit"
                                                class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Data Icon List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Image</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch department data
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_data_icon WHERE status='1' ORDER BY icon_name ASC");
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['icon_name']; ?></td>
                                                    <td><?= $row['icon_description']; ?></td>

                                                    <td><img src="../uploads/data-icons/<?= $row['icon_image'] ?>"
                                                            alt="Image" style="height:50px;"></td>
                                                    <!-- <td>
                                                        <?php if ($status == 1) { ?>
                                                        <span class="badge bg-primary">Active</span>
                                                        <?php } else { ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td> -->
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit.php?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete.php?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
        }
    }
    ?>
                                            </tbody>
                                        </table>
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


if (isset($_POST['form_submit'])) {
    $icon_name = $_POST['icon_name'];
    $url_name = $_POST['url_name'];
    $description = $_POST['description'];
    $created_at = date('Y-m-d H:i:s');

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

    $brocher = ''; // Default value if no file is uploaded

    // File Upload Handling
    if (!empty($_FILES['file']['name'])) {
        $filename = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];
        $path = '../uploads/data-icons/';
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png');

        if (in_array($ext, $valid_extensions)) {
            $final_image = date("dHis") . '-' . strtolower(str_replace(' ', '-', $filename));
            if (move_uploaded_file($tmp, $path . $final_image)) {
                $brocher = $final_image;
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

    // Database Insert using Prepared Statement
    $stmt = $conn->prepare("INSERT INTO `tbl_data_icon` (`icon_name`, `icon_image`, `icon_url`, `icon_description`, `created_at`) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $icon_name, $brocher, $url_name, $description, $created_at);

    if ($stmt->execute()) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Data Icons Added Successfully",
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