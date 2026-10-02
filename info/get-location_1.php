<?php
include('../includes/dbConfig.php');
session_start();

$user_id = $_SESSION['user_id']; // Ensure session is set

if (isset($_POST['state_id']) && !empty($_POST['state_id'])) {
    $state_id = $_POST['state_id'];

    // Fetch user's current work location
    $userQuery = mysqli_query($conn, "SELECT work_location FROM tbl_user WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($userQuery);
    $selected_location = $user['work_location'];

    // Fetch locations for the selected state
    $query = "SELECT id, location FROM tbl_location WHERE state_id='$state_id'";
    $result = mysqli_query($conn, $query);

    echo '<option value="">Select Work Location</option>'; // Default option
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = ($row['id'] == $selected_location) ? 'selected' : '';
        echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['location'].'</option>';
    }
}
?>
