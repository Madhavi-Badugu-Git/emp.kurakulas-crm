<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

if (isset($_POST['user_id']) && isset($_POST['icon_id']) && isset($_POST['checked'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $icon_id = mysqli_real_escape_string($conn, $_POST['icon_id']);
    $checked = $_POST['checked'];

    // Fetch current account_icons from the database
    $query = mysqli_query($conn, "SELECT account_icons FROM tbl_user WHERE id = '$user_id'");
    $row = mysqli_fetch_assoc($query);
    $account_icons = json_decode($row['account_icons'], true) ?? [];

    // Ensure it's an array
    if (!is_array($account_icons)) {
        $account_icons = [];
    }

    if ($checked) {
        if (!in_array($icon_id, $account_icons)) {
            $account_icons[] = $icon_id; // Add icon_id if checked
        }
    } else {
        if (($key = array_search($icon_id, $account_icons)) !== false) {
            unset($account_icons[$key]); // Remove icon_id if unchecked
        }
    }

    // Re-index array and convert to JSON
    $account_icons = array_values($account_icons);
    $account_icons_json = json_encode($account_icons);

    // Update the database
    $update_query = "UPDATE tbl_user SET account_icons = '$account_icons_json' WHERE id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}
?>
