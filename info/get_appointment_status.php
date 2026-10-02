<?php
include('../includes/dbConfig.php');

if (isset($_POST['appt_status_id'])) {
    $appt_status_id = $_POST['appt_status_id'];
    $query = "SELECT id, appt_sub_status FROM tbl_appointment_sub_status WHERE appt_status_id = ? ORDER BY appt_sub_status ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appt_status_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Appointment Sub Status</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['appt_sub_status'].'</option>';
    }
}
?>