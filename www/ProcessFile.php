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
                  <strong>Warning!</strong> Sorry, we are facing some technical issue. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-warning">
            <strong>Warning!</strong> Submitted file cannot be processed. Kindly, upload the file having <strong>.xls, .xlsx, .csv</strong> extension.</div>';
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
        // Fetching the table details
        $tableObj = new Table();
        $fileSubmitted = $tableObj->removeHtmlEntity($_POST['fileSubmitted'], "&nbsp;", " ");
        // Check if file exists
        $filePath = $root."/UploadedFiles/".$fileSubmitted;
        if (!file_exists($filePath)) {
            echo false;
            exit;
        }
        if (!empty($fileSubmitted)) {
            unlink($filePath);
            echo true;
            exit;
        }
    }
 
    // Process file as per passed parameters
    if (!empty($_POST['processFile'])) {
        // Fetching the table details
        $tableObj = new Table();
        $table = $tableObj->fetchTable();
        $error = '';
        $columns = '';
        $matchedRow = array();
        if (!is_array($table)) {
            $error = '<div class="alert alert-warning">
                <strong>Warning!</strong> There seems some issue while fetching the file. Please check the file and try again.</div>';
        } else {
            $columnSelected = $_POST['columnSelected'];
            $providedValue = $_POST['valueProvided'];
            $columns = $table["table"][0];
            $dataRows = array_slice($table["table"], 1);
            $columnIndex = 0;
            foreach($columns as $column) {
                $columnSelected = $tableObj->removeHtmlEntity($columnSelected, "&nbsp;", " ");
                if (strtoupper($column) == strtoupper($columnSelected)) {
                    foreach($dataRows as $row) {
                       if (preg_match("/$providedValue/i", strtoupper($row[$columnIndex]))) {
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
    if (!empty($_POST['fetchData'])) {
        $result = array();
        $error = '';
        $sheetName = '';
        $columns = '';
        $tableObj = new Table();
         $table = $tableObj->fetchTable();
            if (!is_array($table)) {
                $error = '<div class="alert alert-warning">
                    <strong>Warning!</strong> There seems some issue while fetching the file. Please check the file and try again.</div>';
            } else {
                if (!empty($table["table"])) {
                    $columns = array_filter($table["table"][0]);
                }
                $sheetName = array_filter($table["sheetName"]);
            }
            $result["error"] = $error;
            $result["sheetName"] = $sheetName;
            $result["column"] = $columns;
 
            print_r(json_encode($result));
    }
 
    /**
     * Class used to get the details of excel file submitted
     */
    class Table
    {
        /**
         * Function used to fetch table details of excel
         *
         * @return string
         */
        public function fetchTable()
        {
            $result = '';
            $table = '';
            $root = $_SERVER['DOCUMENT_ROOT'];
            require($root."/libraries/Excel/Classes/PHPExcel.php");
            // Fetch input value passed
            $fileSubmitted = $_POST['fileSubmitted'];
            if (empty($fileSubmitted)) {
                return;
            }
            $fileSubmitted = $this->removeHtmlEntity($fileSubmitted, "&nbsp;", " ");
            $fileName = $root."/UploadedFiles/".$fileSubmitted;
            // Read files from directory
            if (!file_exists($fileName)) {
                return $result;
            } else {
                chmod($root, 777);
                // Create Reader object
                $inputFileType = PHPExcel_IOFactory::identify($fileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $sheetName = $objReader->listWorksheetNames($fileName);
                $objReader->setReadDataOnly(true);
                if (!empty($_POST['sheetSelected'])) {
                    $sheetName[0] = $this->removeHtmlEntity($_POST['sheetSelected'], "&nbsp;", " ");
                    $objReader->setLoadSheetsOnly($sheetName[0]);
                    $excel = $objReader->load($fileName);
                    $workSheet = $excel->getActiveSheet();
                    //Argument:
                    //null: for exception handling,
                    //true: formulas like AVG, SUM on sheet should be calculated while returning table
                    //true: format should be taken care i.e. 12.00 should be returned as 12.00 instead of 12
                    //false: formating table in to array. If true then fetching will be done as [1][A] instead of [1][1]
                    $table = $workSheet->toArray(null, true, true, false);
                }
 
                return $result = array("sheetName" => $sheetName, "table" => $table);
            }
        }
 
        /**
         * Function used to remove HTML entities from data
         *
         * @param string $data
         * @param string $entity
         * @param string $replacer
         * @return string
         *
         */
        public function removeHtmlEntity($data, $entity, $replacer)
        {
            $viewEntities = htmlentities($data, null, "UTF-8");
            $replaceEntities = str_replace($entity, $replacer, $viewEntities);
            $processedData = html_entity_decode($replaceEntities);
 
            return $processedData;
        }
    }