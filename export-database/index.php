<?php 
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php'); 
include('../includes/functions.php'); 

	// Fetch data from tbl_database_import_test_data
	$SQLSELECT = "SELECT * FROM tbl_database_import_test_data WHERE customer_type = '35' AND location = '35'";
	$result_set = mysqli_query($conn, $SQLSELECT);

	// Check for errors
	if (!$result_set) {
	    die("Database query failed: " . mysqli_error($conn));
	}
?>	
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Import Excel To Mysql Database Using PHP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Import Excel File To MySql Database Using PHP">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/bootstrap-custom.css">
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
</style>
<body>    

	<!-- Navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container"> 
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="https://hemant9807.blogspot.in/2016/09/import-excelcsv-file-to-mysql-database.html">
					Import Excel To Mysql Database Using PHP
				</a>
			</div>
		</div>
	</div>

	<div id="wrap">
		<div class="container">
			<div class="row">
				<div class="span3 hidden-phone"></div>
				<div class="span6" id="form-login">
					<form class="form-horizontal well" action="import.php" method="post" enctype="multipart/form-data">
						<fieldset>
							<legend>Import CSV/Excel file</legend>
							<label>Select CSV File:</label>
							<input type="file" name="file" accept=".csv" required>
							<button type="submit" name="Import" class="btn btn-primary">Upload and Import</button>
						</fieldset>
					</form>
				</div>
				<div class="span3 hidden-phone"></div>
			</div>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Mobile Number</th>
						<th>Lead Name</th>
						<th>Company Name</th>
						<th>Customer Type</th>
						<th>Created By</th>
					</tr>   
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($result_set)): ?>
					<tr>
						<td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
						<td><?php echo htmlspecialchars($row['lead_name']); ?></td>
						<td><?php echo htmlspecialchars($row['company_name']); ?></td>
						<td><?php echo htmlspecialchars($row['customer_type']); ?></td>
						<td><?php echo htmlspecialchars($row['createdBy']); ?></td>
					</tr>
					<?php endwhile; ?>
					<?php mysqli_free_result($result_set); ?>
				</tbody>
			</table>
		</div>
	</div>

</body>
</html>
