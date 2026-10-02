<?php
include('../includes/dbConfig.php');

if (isset($_POST['calling_status_id'])) {
    $calling_status_id = $_POST['calling_status_id'];
    $query = "SELECT id, calling_sub_status FROM tbl_calling_sub_status WHERE calling_status_id = ? ORDER BY calling_sub_status ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $calling_status_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Calling Sub Status</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['calling_sub_status'].'</option>';
    }
}
?>