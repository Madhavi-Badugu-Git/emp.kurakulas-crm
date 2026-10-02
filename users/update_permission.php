<?php session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');


if (isset($_POST['user_id']) && isset($_POST['icon_id']) && isset($_POST['checked'])) {
    $user_id = $_POST['user_id'];
    $icon_id = $_POST['icon_id'];
    $checked = $_POST['checked'];

    // Fetch current manage_icons from database
    $query = mysqli_query($conn, "SELECT manage_icons FROM tbl_user WHERE id = '$user_id'");
    $row = mysqli_fetch_assoc($query);
    $manage_icons = json_decode($row['manage_icons'], true);

    if (!is_array($manage_icons)) {
        $manage_icons = []; // Ensure it's an array
    }

    if ($checked) {
        if (!in_array($icon_id, $manage_icons)) {
            $manage_icons[] = $icon_id; // Add icon_id if checked
        }
    } else {
        if (($key = array_search($icon_id, $manage_icons)) !== false) {
            unset($manage_icons[$key]); // Remove icon_id if unchecked
        }
    }

    // Re-index array and convert to JSON
    $manage_icons = array_values($manage_icons);
    $manage_icons_json = json_encode($manage_icons);

    // Update the database
    $update_query = "UPDATE tbl_user SET manage_icons = '$manage_icons_json' WHERE id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}
?>

