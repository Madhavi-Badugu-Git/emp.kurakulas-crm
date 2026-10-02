<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid Request!");
        window.location.href = "add.php";
    </script>';
    exit();
}

$user_id = $_GET['id'];

$sql = "SELECT * FROM tbl_user WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)){
        $status = $row['status'];
        if($status == '1'){
            mysqli_query($conn, "UPDATE tbl_user SET status = '0' WHERE id = '$user_id'");

        } else if($status == '0'){
            mysqli_query($conn, "UPDATE tbl_user SET status = '1' WHERE id = '$user_id'");
        }
    }
}

// Update status

// if ($sql) {
//     $_SESSION['success_message'] = "Status Changed Successfully"; 
// } else {
//     $_SESSION['error_message'] = "Something Went Wrong, Please Try Again";
// }

header("Location: list.php");
exit();
?>