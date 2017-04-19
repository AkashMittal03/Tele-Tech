<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="TeleTech">                   
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TeleTech Dashboard</title>
    </head>
    <!-- Embedding Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 
    <body>
        <div class="row" >
            <div class="col-md-4">
                <img src="http://www.teletech.com/sites/all/themes/main/img/logo-text.png" alt="TeleTech">
            </div>
            <h2><div class="col-md-8">Welcome to TeleTech Dashboard</div></h2>
        </div>
        <hr>
        <div class="container-fluid">
            <div id="message"></div>
            <div class="row">
                <div class="col-md-2"  align="right">
                    <h1><span class="label label-default">Upload File</span></h1>
                </div>
                <!-- Form to upload the file on server -->
                <div class="col-md-2">Please upload the file to process:
                <br/>
                <form name="upload" enctype="multipart/form-data" method="POST">
                    <input type="file" name="file" id="file" />
                    &nbsp;<br/>
                    <input type="submit" class="btn btn-primary btn-md" value="Upload" id="uploadFile"/>
                </form>
                </div>
                <div class="col-md-2"  align="right"><h1><span class="label label-default">Select File</span></h1></div>
                <div class="col-md-2">Please select the file from list to process:
                    &nbsp <br/>
                    <div>Search you file:</div>
                    <!-- <input type="search" name="fileSearch" id="searchFile" placeholder="Search you file"/> -->
                    <input type="hidden" id="fileArray" name="fileArray" />
                    <div id="result" class="list-group"></div>
                   <!-- <div>OR</div><br/> -->
                    <div type="hidden" id="select" ></div>
                    <div style="width: 200px; overflow: auto;">
                    <select id="selectFile" name="selectFile" >
                        <option id="select" >Select your file</option>
                        <option id="optionCreated" hidden="true"></option>
                    </select>
                    </div>
                    <br/>
                </div>
                <div class="col-md-2"  align="right"><h1><span class="label label-default">Delete File</span></h1></div>
                <div class="col-md-2">Please select the file from list to delete:<br/>
                    <select id="delFileCreator" name="delete" >
                        <option id="select" >Select your file</option>
                        <option id="optionCreated1" hidden="true"></option>
                    </select>
                    <br/><br/>
                    <input type="submit" id="deleteFile" name="deleteFile" class="btn btn-primary btn-md" value="Delete" onClick="deleteFile()"/>
                </div>
                <div id="deleteFileName" hidden="true"></div>
            </div>
            <br/><hr>
            <h3>Please provide the value to fetch data:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>File Selected</th>
                        <th>Select the Sheet</th>
                        <th>Choose column</th>
                        <th>Enter the value of that column</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" id="fileSubmitted" name="fileSubmitted" value="Please select the file first" readonly="readonly"/>
                        </td>
                        <td>
                            <select id="sheetName" name="sheetName" style="width: 140px; height: 24.72px;" disabled="true">
                                <option id="select" >Select your sheet</option>
                            </select>
                        <td style="overflow: auto">
                            <select name="columnSelected" id="columnSelected" style="height: 24.72px;" disabled="true">
                                <option id="processColumnText" readonly="readonly">Please select the file first</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="valueProvided" id="valueProvided" placeholder="Enter the value" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="processFile" id="processFile" onClick="processFile()" class="btn btn-primary btn-md" value="Fetch" disabled="true"/>
            <div id="columnDetails" hidden="true"></div>
            <div id="previousSelectedFile" hidden="true"></div>
            <hr>
            <table class="table table-striped">
                <thead>
                    <tr id="columnData">
                    </tr>
                </thead>
                <tbody id="rowData">
                </tbody>
            </table>
        </div>
        <br/><br/><br/><br/>
        <div class="panel-footer" align="center">2017 &copy; Infosys Development Team</div>
       
        <!-- dependency script -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="js/utility.js"></script>
    </body>
</html>