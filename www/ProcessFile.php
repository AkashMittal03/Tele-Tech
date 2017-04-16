<?php
    $root = $_SERVER['DOCUMENT_ROOT'];
    // check the file received or not
    if (!empty($_FILES['file'])) {
            $name = $_FILES['file']['name'];
            $temp_name = $_FILES['file']['tmp_name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];
            $error = $_FILES['file']['error'];
            // check the type of file uploaded
            if (preg_match("/(spreadsheet|ms-excel)/", $type)) {
                    if (!file_exists($root."/UploadedFiles/")) {
                            mkdir($root."/UploadedFiles/");
                    }
                    // Store the uploaded file
                    if (move_uploaded_file($temp_name, $root."/UploadedFiles/".$name)) {	
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
    }

    // Read file present in folder
    if (!empty($_POST['readfile'])) {
            $fileArray = array();
            $dirPath = $root."/UploadedFiles/";
            $fileArray = array_values(array_diff(scandir($dirPath), array('.', '..')));
            print_r(json_encode($fileArray));
    }

    // Process file as per passed parameters
    if (!empty($_POST['processFile'])) {
        require($root."/libraries/PHPExcel/excel_reader.php");
        // Fetch input value passed
        $fileName = $root."/UploadedFiles/".$_POST['fileSubmitted'];
        $columnSelected = $_POST['columnSelected'];
        $providedValue = $_POST['valueProvided'];
        // Read files from directory
        if (!file_exists($fileName)) {
            echo '<div class="alert alert-warning">
                    <strong>Warning!</strong> Please upload the file and try again.</div>';
            exit;
        } else {
            chmod($root, 777);
            // Create Reader object
            $excel = new PHPExcelReader();
            $excel->setUTFEncoder('iconv');
            $excel->setOutputEncoding('UTF-8');
            $excel->read($fileName);
            $table = $excel->sheets['0']['cells'];
            $columns = $excel->sheets['0']['cells'][1];
            $dataRows = array_slice($table, 1);
            $columnIndex = 1;
            $matchedRow = array();
            foreach($columns as $column) {
                if (strtoupper($column) == strtoupper($columnSelected)) {
                    foreach($dataRows as $row) {
                       if ($row[$columnIndex] == $providedValue) {
                            $matchedRow[] = $row;
                        }
                    }
                } else {
                    ++$columnIndex;
                }
                if (!empty($matchedRow)) {
                    break;
                }
            }
            $result = array("column"=> $columns, "rowDetails"=>$matchedRow);
            print_r(json_encode($result));
        }
    }