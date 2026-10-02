<?php
include('../includes/dbConfig.php');

if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    $query = "SELECT id, designation_name FROM tbl_designation WHERE department_id='$department_id'";
    $result = mysqli_query($conn, $query);

    echo '<option value="">Select Designation</option>'; // Default option
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.$row['designation_name'].'</option>';
    }
}
?>
