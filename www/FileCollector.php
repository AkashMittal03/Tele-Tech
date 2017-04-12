<?php
// check the file received or not

if (isset($_FILES['file'])) {
	$name = $_FILES['file']['name'];
	$temp_name = $_FILES['file']['tmp_name'];
	$type = $_FILES['file']['type'];
	$size = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	// Store the uploaded file
	move_uploaded_file($temp_name, "C:/dev/www/".$name);
	
    echo "File uploaded successfully.";
	
	
}