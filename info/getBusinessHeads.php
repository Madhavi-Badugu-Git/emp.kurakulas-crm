<?php
include('../includes/dbConfig.php');

if (isset($_GET['rbh_user_id'])) {
    $rbhUserId = intval($_GET['rbh_user_id']);

    // Fetch Business Heads reporting to the selected RBH
    $query = "
        SELECT u.id, u.username, CONCAT(u.firstName, ' ', u.lastName) AS name, d.designation_name
        FROM tbl_user u
        JOIN tbl_designation d ON u.designation_id = d.id
        JOIN tbl_department dep ON u.department_id = dep.id
        WHERE 
            dep.department_name = 'Marketing' 
            AND d.designation_name = 'Business Head'
            AND u.reportingTo = $rbhUserId
    ";
    
    $result = $conn->query($query);

    echo '<option value="">Select Business Head</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['username'] . '">' . $row['name'] . ' (' . $row['designation_name'] . ')</option>';
    }
}
?>
