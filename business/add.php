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
                                        <h5 class="mb-0">Add Type of Business</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="customer_type">Customer
                                                        Type</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <select id="customer_type" name="customer_type"
                                                            class="form-select" onchange="loadCustomerType(this.value)">
                                                            <option value="">Select Customer Type</option>
                                                            <?php
                                                                $query = "SELECT id, customer_type FROM tbl_customer_type ORDER BY customer_type ASC";
                                                                $result = $conn->query($query);
                                                                while ($row = $result->fetch_assoc()) {
                                                                    echo '<option value="'.$row['id'].'">'.$row['customer_type'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="industry_type"> Industry
                                                        Type</label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>
                                                        <select id="industry_type" name="industry_type"
                                                            class="form-select">
                                                            <option value="">Select Industry Type</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="business_name"> Business Name
                                                    </label><span style="color:red;"> *</span>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>
                                                        <input type="text" class="form-control" name="business_name"
                                                            id="business_name" placeholder="Business Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="submit" name="form_submit" class="btn btn-primary mt-3"
                                                value="Submit">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xl">
                                <div class="card">
                                    <h5 class="card-header">Business List</h5>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>Customer Type </th>
                                                    <th>Industry Type </th>

                                                    <th>STATUS</th>
                                                    <th>ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch department data
                                                $sql = mysqli_query($conn, "SELECT * FROM tbl_business_type WHERE status='1' ORDER BY business_name ASC");

                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                        $status = $row['status'];
                                                ?>
                                                <tr>
                                                    <td><?= $row['business_name']; ?></td>
                                                    <td><?= getCustomerType($conn, $row['customer_id']); ?></td>
                                                    <td><?= getIndustryType($conn, $row['industry_id']); ?></td>

                                                    <td>
                                                        <?php if ($status == 1) { ?>
                                                        <span class="badge bg-primary">Active</span>
                                                        <?php } else { ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                        <?php } ?>
                                                    </td>
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
    <script>
    function loadCustomerType(customerId) {
        if (customerId) {
            fetch("../info/get_industry_type", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "customer_id=" + customerId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("industry_type").innerHTML = data;
                });
        } else {
            document.getElementById("industry_type").innerHTML = '<option value="">Select Industry Type</option>';
        }
    }
    </script>
</body>

</html>
<?php

if (isset($_POST['form_submit'])) {
    $business_name = $_POST['business_name'];
    $customer_type = $_POST['customer_type'];
    $industry_type = $_POST['industry_type'];
   
    $created_at = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($business_name) || empty($customer_type) || empty($industry_type) ) {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "All fields are required",
                position: "topRight",
            });
        </script>';
        exit();
    } else{

    // Insert into databas
    $sql = "INSERT INTO `tbl_business_type`(`business_name`, `customer_id`, `industry_id`,`created_at`) VALUES ('$business_name','$customer_type','$industry_type','$created_at')";
    if (mysqli_query($conn, $sql)) {
        echo '<script>
            iziToast.success({
                title: "Success",
                message: "Business Added Successfully",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="add.php"; }, 1000);
        </script>';
    } else {
        echo '<script>
            iziToast.warning({
                title: "Error",
                message: "Something Went Wrong, Please Try Again",
                position: "topRight",
            });
              setTimeout(() => { window.location.href="add.php"; }, 1000);
        </script>';
    }

    }

}
?>