<?php
include('../includes/dbConfig.php');

if (isset($_POST['vehical_make_id'])) {
    $vehical_make_id = $_POST['vehical_make_id'];
    $query = "SELECT id, vehical_modal FROM tbl_vehical_modal WHERE vehical_make_id = ? ORDER BY vehical_modal ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehical_make_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Vehical Make</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['vehical_modal'].'</option>';
    }
}
?>