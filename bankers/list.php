<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

$loggedInUser = $_SESSION['loggedInUser'];
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
                                    <h5 class="card-header">Bankers List</h5>
                                    <!-- Filter Form -->
                                    <div class="row" style="margin-top:-25px;">
                                        <form method="GET" class="p-3 mt-0">
                                            <div class="row" style="margin-left:0px;">
                                                <!-- Vendor Bank Filter -->
                                                <div class="col-md-3">
                                                    <select name="vendor_bank" class="form-select">
                                                        <option value="">Select Vendor Bank</option>
                                                        <?php
                                                            $query = "SELECT id, vendor_bank_name FROM tbl_vendor_bank WHERE status=1 ORDER BY vendor_bank_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['vendor_bank']) && $_GET['vendor_bank'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['vendor_bank_name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Loan Type Filter -->
                                                <div class="col-md-3">
                                                    <select name="loan_type" class="form-select">
                                                        <option value="">Select Loan Type</option>
                                                        <?php
                                                            $query = "SELECT id, loan_type FROM tbl_loan_type WHERE status=1 ORDER BY loan_type ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['loan_type']) && $_GET['loan_type'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['loan_type'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- State Filter -->
                                                <div class="col-md-3">
                                                    <select name="state" class="form-select"
                                                        onchange="loadBranchLocation(this.value)">
                                                        <option value="">Select State</option>
                                                        <?php
                                                            $query = "SELECT id, branch_state_name FROM tbl_branch_state WHERE status=1 ORDER BY branch_state_name ASC";
                                                            $result = $conn->query($query);
                                                            while ($row = $result->fetch_assoc()) {
                                                                $selected = (isset($_GET['state']) && $_GET['state'] == $row['id']) ? "selected" : "";
                                                                echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['branch_state_name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Location Filter -->
                                                <div class="col-md-3">
                                                    <select name="location" id="location" class="form-select">
                                                        <option value="">Select Location</option>
                                                       
                                                    </select>
                                                </div>

                                                <!-- Filter & Reset Buttons -->
                                                <div class="col-md-3 mt-3">
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
                                                    <th>Vendor Bank</th>
                                                    <th>Banker Name</th>
                                                    <th>Banker Designation</th>
                                                    <th>Mobile No</th>
                                                    <th>Email</th>
                                                    <th>Loan Type</th>
                                                    <th>State</th>
                                                    <th>Location</th>
                                                    <th>Visiting Card</th>
                                                    <th>Address</th>
                                                    <th>Created By</th>
                                                    <?php 
                                                    if($loggedInUserRank == 'superAdmin' || $loggedInUserDepartment == '14'){
                                                        ?>
                                                    <th>Actions</th>

                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $where = "WHERE b.status='1'"; // Ensure status belongs to tbl_bankers

                                                //   if ($loggedInUserRank != 'superAdmin') {
                                                //       // Ensure at least one filter is applied for non-superAdmin users
                                                //       if (empty($_GET['vendor_bank']) && empty($_GET['loan_type']) && empty($_GET['state']) && empty($_GET['location'])) {
                                                //           $where .= " AND 1=0"; // Block results if no filters are selected
                                                //       }
                                                //   }
                                              
                                                if (!empty($_GET['vendor_bank'])) {
                                                    $where .= " AND b.vendor_bank='" . mysqli_real_escape_string($conn, $_GET['vendor_bank']) . "'";
                                                }
                                                if (!empty($_GET['loan_type'])) {
                                                    $where .= " AND b.loan_type='" . mysqli_real_escape_string($conn, $_GET['loan_type']) . "'";
                                                }
                                                if (!empty($_GET['state'])) {
                                                    $where .= " AND b.state='" . mysqli_real_escape_string($conn, $_GET['state']) . "'";
                                                }
                                                if (!empty($_GET['location'])) {
                                                    $where .= " AND b.location='" . mysqli_real_escape_string($conn, $_GET['location']) . "'";
                                                }
                                              
                                                
                                                // Pagination starts
                                                $limit = 50; // Records per page
                                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                if ($page < 1) $page = 1;
                                                $offset = ($page - 1) * $limit;

                                                // Count total records
                                                $countQuery = "SELECT COUNT(*) as total FROM tbl_bankers b 
                                                            JOIN tbl_vendor_bank v ON b.vendor_bank = v.id 
                                                            $where";
                                                $countResult = mysqli_query($conn, $countQuery);
                                                $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                                $totalPages = ceil($totalRows / $limit);
                                                // Pagination ends
                                                
                                                $sql = mysqli_query($conn, "SELECT b.* FROM tbl_bankers b 
                                                JOIN tbl_vendor_bank v ON b.vendor_bank = v.id 
                                                $where ORDER BY v.vendor_bank_name ASC LIMIT $offset, $limit");
                                                
                                                
                                                if (mysqli_num_rows($sql) > 0) {
                                                    while ($row = mysqli_fetch_assoc($sql)) {
                                                ?>
                                                <tr>
                                                    <td><?= getVendorBank($conn, $row['vendor_bank']); ?></td>
                                                    <td><?= $row['banker_name']; ?></td>
                                                    <td><?= getBankerDesignation($conn, $row['banker_designation']); ?></td>

                                                    <td><?= $row['Phone_number']; ?></td>
                                                    <td><?= $row['email_id']; ?></td>

                                                    <td><?= getLoanType($conn, $row['loan_type']); ?></td>
                                                    <td><?= getBranchState($conn, $row['state']); ?></td>
                                                    <td><?= getBranchLocation($conn, $row['location']); ?></td>
                                                    <td><a href="../uploads/bankers/<?= $row['visiting_card'] ?>" target="_blank">View Visiting Card</a></td>
                                                    <td><?= $row['address']; ?></td>
                                                    <td><?= getUserFullName($conn, $row['createdBy']); ?></td>
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
                                                   }else if($loggedInUserDepartment == '14'){
                                                    ?>
                                                     <td>
                                                        <div class="dropdown">
                                                           
                                                             <span class="badge bg-primary"><a class="dropdown-item"
                                                                        href="edit?id=<?= $row['id']; ?>">Edit</a></span>
                                                        </div>
                                                    </td>
                                                    <?php
                                                   }
                                                    ?>
                                                  
                                                </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                        <?php include('../includes/pagination.php'); ?>
                                    </div>
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
    function loadBranchLocation(stateId) {
        if (stateId) {
            fetch("../info/get_branch_location", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "branch_state_id=" + stateId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("location").innerHTML = data;
                });
        } else {
            document.getElementById("location").innerHTML = '<option value="">Select Branch Location</option>';
        }
    }
    </script>
</body>

</html>