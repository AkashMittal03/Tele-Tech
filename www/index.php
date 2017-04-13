<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="TeleTech">		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>TeleTech Dashboard</title>
	</head>

	<body>
	<div class="row" >
		<div class="col-sm-4">
			<img src="http://www.teletech.com/sites/all/themes/main/img/logo-text.png" alt="TeleTech">
		</div>
		<h2><div class="col-sm-8">Welcome to TeleTech Dashboard</div></h2>
	</div>
	<hr>
	<div class="container-fluid">
	<?php
	// check the file received or not
	if (isset($_FILES['file'])) {
		$name = $_FILES['file']['name'];
		$temp_name = $_FILES['file']['tmp_name'];
		$type = $_FILES['file']['type'];
		$size = $_FILES['file']['size'];
		$error = $_FILES['file']['error'];
		// check the type of file uploaded
		if (preg_match("/(spreadsheet|ms-excel)/", $type)) {
			// Store the uploaded file
			if (move_uploaded_file($temp_name, "C:/dev/www/".$name)) {	
				echo '<div class="alert alert-success">
				  <strong>Great!</strong> File uploaded successfully.</div>';
			} else {
				echo '<div class="alert alert-warning">
				  <strong>Warning!</strong> Please upload the file and try again.</div>';
				
			}
		} else {
			echo '<div class="alert alert-warning">
				  <strong>Warning!</strong> Submitted file cannot be processed. Kindly, upload the file with extensions: <strong>.xlsx, .csv</strong></div>';
		}
	} else {
		echo '<div class="alert alert-info"> Please upload the file.</div>';
	}
	?>
	&nbsp <br/>
		<form name="upload" enctype="multipart/form-data" method="POST">
			<input type="file" name="file" size="40" />
			&nbsp <br/>
			&nbsp <br/>
			<input type="submit" class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal" name="Upload File" />
		</form>
	</div>
		<!-- Scripts -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</body>
</html>