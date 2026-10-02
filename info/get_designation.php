<!-- <?php
include('../includes/dbConfig.php');

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    $query = "SELECT id, designation_name FROM tbl_designation WHERE department_id = ? ORDER BY designation_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Designation</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['designation_name'].'</option>';
    }
}
?> -->

<?php
include('../includes/dbConfig.php');

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
    $query = "SELECT id, designation_name FROM tbl_designation WHERE department_id = ? ORDER BY designation_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Designation</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['designation_name'].'</option>';
    }
}
?>

