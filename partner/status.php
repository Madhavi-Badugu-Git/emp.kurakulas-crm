<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php');

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<script>
        alert("Invalid Request!");
        window.location.href = "list";
    </script>';
    exit();
}

$partner_id = $_GET['id'];

$sql = "SELECT * FROM tbl_partner WHERE id = '$partner_id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)){
        $status = $row['status'];
        if($status == '1'){
            mysqli_query($conn, "UPDATE tbl_partner SET status = '0' WHERE id = '$partner_id'");

        } else if($status == '0'){
            mysqli_query($conn, "UPDATE tbl_partner SET status = '1' WHERE id = '$partner_id'");
        }
    }
}

header("Location: list");
exit();
?>