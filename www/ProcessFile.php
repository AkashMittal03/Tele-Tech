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
                              <strong>Warning!</strong> Submitted file cannot be processed. Kindly, upload the file with extension: <strong>.xls, .xlsx, .csv</strong></div>';
            }
    }

    // Read file present in folder
    if (!empty($_POST['readfile'])) {
            $fileArray = array();
            $dirPath = $root."/UploadedFiles/";
            $fileArray = array_values(array_diff(scandir($dirPath), array('.', '..')));
            print_r(json_encode($fileArray));
    }

    /**
     * Below code is used to delete the submitted file
     */
    if (!empty($_POST['deleteFile'])) {
        $fileSubmitted = $_POST['fileSubmitted'];
        $filePath = $root."/UploadedFiles/".$fileSubmitted;
        // Check if file exists
        if (file_exists($filePath)) {
            unlink($filePath);
            echo true;
        } else {
            echo false;
        }
    }

    // Process file as per passed parameters
    if (!empty($_POST['processFile'])) {
        // Fetching the table details
        $table = new Table();
        $tableData = $table->fetchTable();
        $error = '';
        $columns = '';
        $matchedRow = array();
        if (empty($tableData)) {
            $error = '<div class="alert alert-warning">
                <strong>Warning!</strong> Please select the file and try again.</div>';
        } else {
            $columnSelected = $_POST['columnSelected'];
            $providedValue = $_POST['valueProvided'];
            $columns = $tableData[0];
            $dataRows = array_slice($tableData, 1);
            $columnIndex = 0;
            foreach($columns as $column) {
                $viewEntities = htmlentities($columnSelected, null, "UTF-8");
                $replaceEntities = str_replace("&nbsp;", " ", $viewEntities);
                $columnSelected = html_entity_decode($replaceEntities);
                if (strtoupper($column) == strtoupper($columnSelected)) {
                    foreach($dataRows as $row) {
                       if (strtoupper($row[$columnIndex]) == strtoupper($providedValue)) {
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
        }
        $result = array("error"=> $error, "column"=> $columns, "rowDetails"=>$matchedRow);
        print_r(json_encode($result));
    }

    /**
     * Below code is use to fetch the column name present in file
     */
    if (!empty($_POST['fetchColumn'])) {
        $result = array();
        $columns = '';
        $error = '';
        $table = new Table();
         $tableData = $table->fetchTable();
            if (empty($tableData)) {
                $error = '<div class="alert alert-warning">
                    <strong>Warning!</strong> Please upload the file and try again.</div>';
            } else {
                $columns = $tableData[0];
            }
            $result["error"] = $error;
            $result["column"] = $columns;

            print_r(json_encode($result));
    }

    /**
     * Class used to get the details of excel file submitted
     */
    class Table
    {
        public function fetchTable()
        {
            $result = '';
            $root = $_SERVER['DOCUMENT_ROOT'];
            require($root."/libraries/Excel/Classes/PHPExcel.php");
            // Fetch input value passed
            $fileName = $root."/UploadedFiles/".$_POST['fileSubmitted'];
            // Read files from directory
            if (!file_exists($fileName)) {
                return $result;
            } else {
                chmod($root, 777);
                // Create Reader object
                $inputFileType = PHPExcel_IOFactory::identify($fileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($fileName);
                $worksheet = $objPHPExcel->getSheet(0);
                //Argument:
                //null: for exception handling,
                //true: formulas like AVG, SUM on sheet should be calculated while returning table
                //true: format should be taken care i.e. 12.00 should be returned as 12.00 instead of 12
                //false: formating table in to array. If true then fetching will be done as [1][A] instead of [1][1]
                $table = $worksheet->toArray(null, true, true, false);

                return $result = $table;
            }
        }
    }