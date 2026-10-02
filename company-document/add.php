<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

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
                                        <h5 class="mb-0">Add Company Document</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-12">
                                                    <label class="form-label" for="name"> Name</label><span
                                                        style="color:red;"> *</span>
                                                    <!--<div class="input-group input-group-merge">-->
                                                    <!--    <span class="input-group-text"><i class="bx bx-user"></i></span>-->
                                                    <!--    <input type="text" class="form-control" name="name" id="name"-->
                                                    <!--        placeholder="Name" />-->
                                                    <!--</div>-->
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>
                                                        <select id="name" name="name" class="form-select">
                                                            <option value="">Select Company Name</option>
                                                            <?php
                                                                $query = "SELECT id, company_name FROM tbl_company_name ORDER BY company_name ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['company_name'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6 ">
                                                    <label class="form-label" for="document_name"> Document
                                                        Name</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-file"></i></span>
                                                        <input type="text" class="form-control" name="document_name"
                                                            id="document_name" placeholder="Document Name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="document_file" class="form-label">Document File
                                                    </label>
                                                    <input class="form-control" type="file" id="document_file"
                                                        name="document_file" accept="image/*,application/pdf">
                                                </div>
                                            </div>


                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                       


                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Company Document List</h5>
                                     <!-- Filter Form -->
                                     <div class="row" style="margin-top:-25px;">
                                        <form method="GET" class="p-3 mt-0">
                                            <div class="row" style="margin-left:0px;">
                                                <!-- Company Name Filter -->
                                                <div class="col-md-8">
                                                    <label for="name" class="form-label">Company Name</label>
                                                    <select name="name" class="form-select">
                                                        <option value="">Select Company Name</option>
                                                        <?php
                                                            $query = "SELECT id, company_name FROM tbl_company_name WHERE status=1 ORDER BY company_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['vendor_bank']) && $_GET['vendor_bank'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['company_name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Filter & Reset Buttons -->
                                                <div class="col-md-3 mt-6">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="list" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>

                                                    <th>Company Name</th>
                                                    <th>Document Name</th>
                                                    <th>Document File</th>
                                                    <th>Download</th>
                                                   <?php 
                                                     if($loggedInUserRank == 'superAdmin' ||  $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                                                        ?>
                                                    <th>ACTIONS</th>
                                                        <?php
                                                     }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                 $where = "WHERE b.status='1'"; 

                                                 if (!empty($_GET['name'])) {
                                                    $where .= " AND b.name='" . mysqli_real_escape_string($conn, $_GET['name']) . "'";
                                                }

                                                $sql = mysqli_query($conn, "SELECT b.* FROM tbl_company_document b 
                                                JOIN tbl_company_name v ON b.name = v.id 
                                                $where ORDER BY v.company_name ASC");
                                                
                                                // $sql = mysqli_query($conn, "SELECT * FROM tbl_company_document WHERE status='1' ORDER BY name");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>

                                                    <td><?= getCompanyName($conn, $row['name']); ?></td>
                                                    <td><?= $row['document_name']; ?></td>
                                                    <td><a href="../uploads/company-document/<?= $row['document_file']; ?>"
                                                            target="_blank">View File</a></td>
                                                    <td><a href="../uploads/company-document/<?= $row['document_file']; ?>"
                                                            download>Download Here</a></td>

                                                   
                                                        <?php 
                                                   if($loggedInUserRank == 'superAdmin'){
                                                    ?>
                                                     <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>"><i
                                                                            class="bx bx-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="delete?id=<?= $row['id']; ?>"
                                                                        onclick="return confirm('Are you sure?');"><i
                                                                            class="bx bx-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                         </td>
                                                        <?php
                                                   } else{
                                                    ?>
                                                        <?php
                        if( $loggedInUserDesignation == '17' || $loggedInUserDesignation == '34'){
                            ?>
 <td>
                                                        <a href="edit?id=<?= $row['id']; ?>">
                                                            <span class="badge bg-primary">Edit</span>
                                                        </a>
                                                        <?php
                        }
                                                   }
                                                    ?>
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

     $name = $_POST['name'];
     $document_name = $_POST['document_name'];
   
     $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($name) || empty($document_name)) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{


    // File Upload Handling
    $target_dir = "../uploads/company-document/";

    $document_file = "default.png"; // Default file
    if (!empty($_FILES["document_file"]["name"])) {
        $file_name = time() . "_" . basename($_FILES["document_file"]["name"]); // Generate unique filename
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = array("jpg", "jpeg", "png", "pdf");

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
                $document_file = $file_name; // Save only the file name
            } else {
                echo '<script>
                    iziToast.error({
                        title: "Error",
                        message: "File upload failed!",
                        position: "topRight"
                    });
                </script>';
                exit();
            }
        } else {
            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "Invalid file type. Only JPG, JPEG, PNG, PDF allowed.",
                    position: "topRight"
                });
            </script>';
            exit();
        }
    }

    // Insert into database
    $sql = "INSERT INTO `tbl_company_document`(`name`, `document_name`, `document_file`,`created_at`) 
            VALUES ('$name','$document_name','$document_file','$created_at')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Company Document Added Successfully",
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