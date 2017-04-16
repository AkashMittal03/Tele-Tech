<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="TeleTech">		
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TeleTech Dashboard</title>
    </head>
    <!-- Embeding Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <body>
        <div class="row" >
            <div class="col-sm-4">
                <img src="http://www.teletech.com/sites/all/themes/main/img/logo-text.png" alt="TeleTech">
            </div>
            <h2><div class="col-sm-8">Welcome to TeleTech Dashboard</div></h2>
        </div>
        <hr>
        <div class="container-fluid">
            <div id="message"></div>
            <div class="row">
                <div class="col-sm-4"  align="right"><h1><span class="label label-default">Upload File</span></h1></div>
                <!-- Form to upload the file on server -->
                <div class="col-sm-4">Please upload the file to process:
                    &nbsp <br/>
                    <form name="upload" enctype="multipart/form-data" method="POST">
                        <input type="file" name="file" id="file" />
                        &nbsp <br/>
                        <input type="submit" class="btn btn-primary btn-md" name="Upload File" id="uploadFile"/>
                    </form>
                </div>
            </div>
            &nbsp <br/>
            &nbsp <br/>
            <div class="row">
                <div class="col-sm-4"  align="right"><h1><span class="label label-default">Select File</span></h1></div>
                <div class="col-sm-4">Please select the file from list to process:
                    &nbsp <br/>
                    <div>Search you file:</div>
                    <input type="search" name="AvailableFilesSearch" id="searchFile" placeholder="Search you file"/>
                    <input type="hidden" id="fileArray" name="fileArray" />
                    <div id="result" class="list-group"></div>
                    <div>OR</div><br/>
                    <div type="hidden" id="select" ></div>
                    <div style="width: 200px; overflow: auto;">
                    <select id="selectFile" name="selectFile" >
                        <option id="select" >Select your file</option>
                        <option id="optionCreated" hidden></option>
                    </select>
                    </div>
                </div>
            </div>
            <br/><hr>
            <div>Please provide the value to fetch:</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>File Selected</th>
                        <th>Choose column</th>
                        <th>Enter the value of that column</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="fileSubmitted" id="fileSubmitted" value="Please first select the file." readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="columnSelected" id="columnSelected" value="Please first select the file" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="valueProvided" id="valueProvided" value="123.1.2.37" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="processFile" id="processFile" onClick="processFile()" class="btn btn-primary btn-md"/>
            <div id="columnDetails" hidden></div>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr id="columnData">
                    </tr>
                </thead>
                <tbody>
                    <tr id="rowData">
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- dependecy script -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="js/utility.js"></script>
    </body>
</html>