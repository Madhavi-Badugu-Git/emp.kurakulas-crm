<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kurakulas_login";
$conn = mysqli_connect($servername,$username,$password,$dbname) or die("Connection Failed: " . mysqli_connect_error());

$comp_name = "KURAKULA'S";
$comp_logo = "../assets/img/logos/kurakulas.png";
$comp_favicon = "../assets/img/logos/favicon.jpeg";

date_default_timezone_set('Asia/Kolkata');
?>



