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
    <title>Database Data</title>
</head>
<body>
    <h2>Database Data Export</h2>

    <!-- Download Excel Button -->
    <form method="post" action="download_database_data.php">
        <button type="submit" name="download_excel">Download All Agent Data</button>
    </form>

    <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Mobile Number</th>
            <th>Lead Name</th>
            <th>Email Id</th>
          </tr>
        </thead>
        <?php
         $query = "SELECT * FROM tbl_database WHERE location = '35'";
    $result = mysqli_query($conn, $query);
$i = 1; // Initialize before the loop
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $i . "</td>"; // Display serial number
    echo "<td>" . $row['mobile_number'] . "</td>";
    echo "<td>" . $row['lead_name'] . "</td>";
    echo "<td>" . $row['email_id'] . "</td>";
   
    echo "</tr>";
    $i++; // Increment at the end of each loop
}
    ?>
        </tbody>
    </table>
</body>
</html>
