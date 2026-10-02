<?php
include('../includes/dbConfig.php');

if (isset($_POST['rating_type_id'])) {
    $rating_type_id = $_POST['rating_type_id'];
    $query = "SELECT id, rating_name FROM tbl_rating WHERE rating_type_id = ? ORDER BY rating_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $rating_type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select Rating</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="'.$row['id'].'">'.$row['rating_name'].'</option>';
    }
}
?>