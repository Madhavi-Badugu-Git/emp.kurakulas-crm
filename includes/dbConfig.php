<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kurakulas_admin_db_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$comp_name = "KURAKULA'S";
$comp_logo = "../assets/img/logos/kurakulas.png";

date_default_timezone_set('Asia/Kolkata');

// Fix max_input_vars error
ini_set('max_input_vars', 5000);
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);
?>