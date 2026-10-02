<?php
include('../includes/dbConfig.php');

if (isset($_POST['branch_state_id'])) {
    $branch_state_id = $_POST['branch_state_id'];
    $query = "SELECT id, branch_location FROM tbl_branch_location WHERE branch_state_id = ? ORDER BY branch_location ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $branch_state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Branch Location</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['branch_location'].'</option>';
    }
}
?>