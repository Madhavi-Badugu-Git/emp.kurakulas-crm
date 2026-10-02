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

                                    <h5 class="card-header">Agent List</h5>

                                    <!-- Filter Form -->

                                    <div class="row" style="margin-top:-25px;">

                                        <form method="GET" class="p-3 mt-0">

                                            <div class="row" style="margin-left:0px;">



                                                <!-- Loan Type Filter -->

                                                <div class="col-md-3">

                                                    <label class="form-label" for="">Agent Type</label>

                                                    <select name="partner_type" class="form-select">

                                                        <option value="">Select Agent Type</option>

                                                        <?php

                                                    $query = "SELECT id, partner_type FROM tbl_partner_type WHERE status=1 ORDER BY partner_type ASC";

                                                    $result = $conn->query($query);

                                                    while ($row = $result->fetch_assoc()) {

                                                        $selected = (isset($_GET['partnerType']) && $_GET['partnerType'] == $row['id']) ? "selected" : "";

                                                        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['partner_type'].'</option>';

                                                    }

                                                ?>

                                                    </select>

                                                </div>



                                                <!-- State Filter -->

                                                <div class="col-md-3">

                                                    <label class="form-label" for="">Branch State</label>

                                                    <select name="state" class="form-select"

                                                        onchange="loadBranchLocation(this.value)">

                                                        <option value="">Select Branch State</option>

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

                                                    <label class="form-label" for="">Branch Location</label>

                                                    <select name="location" id="location" class="form-select">

                                                        <option value="">Select Branch Location</option>



                                                    </select>

                                                </div>



                                                <!-- Filter & Reset Buttons -->

                                                <div class="col-md-3 mt-5">

                                                    <button type="submit" class="btn btn-primary">Filter</button>

                                                    <a href="list" class="btn btn-secondary">Reset</a>

                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                    <div class="table-responsive text-nowrap mt-2">

                                        <table class="table">

                                            <thead class="table-dark">

                                                <tr class="head">

                                                    <th>Full Name</th>

                                                    <th>Company Name</th>

                                                    <th>Mobile</th>

                                                    <th>Agent Type</th>

                                                    <th>Branch State</th>

                                                    <th>Branch Location</th>

                                                    <th>Created By</th>



                                                    <th>Actions</th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <?php

                                                $where = "WHERE status='1'";



                                                // Apply filters based on GET parameters

                                                if (!empty($_GET['partner_type'])) {

                                                    $where .= " AND partnerType='" . mysqli_real_escape_string($conn, $_GET['partner_type']) . "'";

                                                }

                                                if (!empty($_GET['state'])) {

                                                    $where .= " AND state='" . mysqli_real_escape_string($conn, $_GET['state']) . "'";

                                                }

                                                if (!empty($_GET['location'])) {

                                                    $where .= " AND location='" . mysqli_real_escape_string($conn, $_GET['location']) . "'";

                                                }

                                                // Pagination starts
                                                $limit = 50; // Records per page
                                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                                if ($page < 1) $page = 1;
                                                $offset = ($page - 1) * $limit;

                                                // Count total records
                                                // $countQuery = "SELECT COUNT(*) as total FROM tbl_agent_data $where";
                                                $countQuery = "SELECT COUNT(DISTINCT Phone_number) AS total FROM tbl_agent_data $where";
                                                $countResult = mysqli_query($conn, $countQuery);
                                                $totalRows = mysqli_fetch_assoc($countResult)['total'];
                                                $totalPages = ceil($totalRows / $limit);
                                                // Pagination ends

                                                // Adjust query based on user rank/designation

                                               if ($loggedInUserRank == 'superAdmin' || $loggedInUserDesignation == '13') {

                                                   $sql = "SELECT * FROM tbl_agent_data $where GROUP BY Phone_number ORDER BY full_name ASC LIMIT $offset, $limit";

                                               } else {

                                                   $sql = "SELECT * FROM tbl_agent_data $where AND createdBy='$loggedInUser' ORDER BY full_name ASC LIMIT $offset, $limit";

                                               }



                                               // Execute the query

                                               $result = mysqli_query($conn, $sql);



                                                if (mysqli_num_rows($result) > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                ?>

                                                <tr>

                                                    <td><?= $row['full_name']; ?></td>

                                                    <td><?= $row['company_name']; ?></td>

                                                    <td><?= $row['Phone_number']; ?></td>

                                                    <td><?= getPartnerType($conn, $row['partnerType']); ?></td>

                                                    <td><?= getBranchState($conn, $row['state']); ?></td>

                                                    <td><?= getBranchLocation($conn, $row['location']); ?></td>

                                                    <td><?= $row['createdBy']; ?></td>

                                                    <td>

                                                        <?php

                                                        if($loggedInUserRank == 'superAdmin'){

                                                        ?>

                                                        <div class="dropdown">

                                                            <button class="btn btn-sm btn-secondary dropdown-toggle"

                                                                type="button" data-bs-toggle="dropdown">

                                                                <i class="bx bx-dots-vertical-rounded"></i>

                                                            </button>

                                                            <ul class="dropdown-menu">

                                                                <li><a class="dropdown-item text-primary"

                                                                        href="view?id=<?= $row['id']; ?>"><i

                                                                            class="bx bx-user"></i> View</a></li>

                                                                <li><a class="dropdown-item"

                                                                        href="edit?id=<?= $row['id']; ?>"><i

                                                                            class="bx bx-edit"></i> Edit</a></li>

                                                                <li><a class="dropdown-item text-danger"

                                                                        href="delete?id=<?= $row['id']; ?>"

                                                                        onclick="return confirm('Are you sure?');"><i

                                                                            class="bx bx-trash"></i> Delete</a></li>

                                                            </ul>

                                                        </div>

                                                        <?php

                                                        } else{

                                                            ?>

                                                        <a href="view?id=<?= $row['id']; ?>">

                                                            <span class="badge bg-primary">View</span>

                                                        </a>

                                                        <?php

                                                        }

                                                        ?>

                                                    </td>

                                                </tr>

                                                <?php

                                                    }

                                                } else {

                                                    echo "<tr><td colspan='7' class='text-center'>No Agents Found</td></tr>";

                                                }

                                                ?>

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

        <div class="layout-overlay layout-menu-toggle"></div>

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