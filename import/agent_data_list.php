<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 
// echo $loggedInUser;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agent Data</title>
</head>
<body>
    <h2>Agent Data Export</h2>

    <!-- Download Excel Button -->
    <form method="post" action="download_agent_data.php">
        <button type="submit" name="download_excel">Download All Agent Data</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Company Name</th>
            <th>Phone</th>
            <th>Alternative Phone Number</th>
            <th>Email</th>
            <th>Partner Type</th>
            <th>State</th>
            <th>Location</th>
            <th>Address</th>
          </tr>
        </thead>
        <?php
         $query = "SELECT * FROM tbl_agent_data WHERE location = '10'";
    $result = mysqli_query($conn, $query);
$i = 1; // Initialize before the loop
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $i . "</td>"; // Display serial number
    echo "<td>" . $row['full_name'] . "</td>";
    echo "<td>" . $row['company_name'] . "</td>";
    echo "<td>" . $row['Phone_number'] . "</td>";
    echo "<td>" . $row['alternative_Phone_number'] . "</td>";
    echo "<td>" . $row['email_id'] . "</td>";
    echo "<td>" . fetchColumnValue($conn, 'tbl_partner_type', 'id', $row['partnerType'], 'partner_type') . "</td>";
    echo "<td>" . fetchColumnValue($conn, 'tbl_branch_state', 'id', $row['state'], 'branch_state_name') . "</td>";
    echo "<td>" . fetchColumnValue($conn, 'tbl_branch_location', 'id', $row['location'], 'branch_location') . "</td>";
    echo "<td>" . $row['address'] . "</td>";
    echo "</tr>";
    $i++; // Increment at the end of each loop
}
    ?>
        </tbody>
    </table>
</body>
</html>
