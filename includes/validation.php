<?php 



if (!isset($_SESSION['loggedInUser'])) {

    header("Location: ../login"); // Redirect to login if not authenticated

   

} else{

// Store username in a session for further validation

$loggedInUser = $_SESSION['loggedInUser'];



$sql = "SELECT * FROM tbl_user WHERE username = '$loggedInUser'";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_array($result)) {

$loggedInUserId = $row['id'];

$_SESSION['loggedInUserId'] = $row['id'];
$loggedInUserId = $_SESSION['loggedInUserId'];

$_SESSION['loggedInUserRank'] = $row['rank'];

$loggedInUserRank = $_SESSION['loggedInUserRank'];



$_SESSION['firstName'] = $row['firstName']." ".$row['lastName'];

$loggedInUserFirstName = $_SESSION['firstName'];



$_SESSION['status'] = $row['status'];

$loggedInUserStatus = $_SESSION['status'];



$_SESSION['avatar'] = $row['avatar'];

$loggedInUserAvatar = $_SESSION['avatar'];



$_SESSION['department_id'] = $row['department_id'];

$loggedInUserDepartment = $_SESSION['department_id'];



$_SESSION['designation_id'] = $row['designation_id'];

$loggedInUserDesignation = $_SESSION['designation_id'];

}



}





?>