// Code used to upload the file to backend
$("#uploadFile").click(function(event){
    event.preventDefault();
    var formData = new FormData($(this).parents('form')[0]);
    $.ajax({
            url: "ProcessFile.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data != null) {
                    $("#message").html(data);
                }
            }
    });
})

// Functionality to search the input passed in backend system folder
$("#searchFile").keyup(function() {
    fetchFile();
    // Search the file
    var fileList = [];
    var value = $("#searchFile").val();
    var fileArray = [];
    var unFormatArray = $('#fileArray').val();
    fileArray = unFormatArray.split(',');
    /* Initiate regular expression object to match
       against received file name */
    var re = new RegExp(value.toUpperCase());
    fileArray.forEach(function(file){
            var fileName = file.toUpperCase().match(re);
            if (fileName != null) {
                    // strore the matched file in array
                    fileList.push(file);
                    fileList.push("<br/>");
            }
    });
    if (fileList.length != 0) {
            result = fileList;
    } else {
            result = "No record found.";
    }
    $("#result").html(result);
});

// Code to create select element containing exists file
$("#selectFile").click(function() {
    var optionCreatedFlag = $("#optionCreated").text();
    if (!optionCreatedFlag) {
        fetchFile();
        var unFormatArray = $('#fileArray').val();
        var fileArray = unFormatArray.split(',');
        var option = "";
        if (fileArray.length > 0) {
            for (var index in fileArray) {
                option += "<option id='file"+index+"' value="+fileArray[index]+" onClick='selectFile()'>"+fileArray[index]+"</option>";
            }
            $("#selectFile").append(option);
            $("#selectFile").attr("size", "4");
            $("#optionCreated").text(true);
        }
    }
});

// Function used to create dynamic assignment of values needed for processing the file
function selectFile()
{
    var selectFile = document.getElementById("selectFile");
    var selectedValue = selectFile.options[selectFile.selectedIndex].value;
    $("#fileSubmitted").val(selectedValue);
    if (!$("#previousSelectedFile").val() || $("#previousSelectedFile").val() != selectedValue) {
        $("#columnDetails").val(false);
        $("#previousSelectedFile").val(selectedValue);
        $("#columnSelected").html('');
    }
    $("#columnSelected").removeAttr("disabled");
    $("#processFile").attr("disabled", false);
    fetchColumn();
}

// Code used to create the select element for delete section
$("#delFileCreator").click(function(){
    fetchFile();
    var optionCreatedFlag = $("#optionCreated1").text();
    if (!optionCreatedFlag) {
        fetchFile();
        var unFormatArray = $('#fileArray').val();
        var fileArray = unFormatArray.split(',');
        var option = "";
        if (fileArray.length > 0) {
            for (var index in fileArray) {
                option += "<option id='file"+index+"' value="+fileArray[index]+" onClick='delFileCreator()'>"+fileArray[index]+"</option>";
            }
            $("#delFileCreator").append(option);
            $("#delFileCreator").attr("size", "4");
            $("#optionCreated1").text(true);
        }
    }
});

// fetch file from backend
function fetchFile()
{
    // Fetch file present in folder
    $.ajax({
        url: "ProcessFile.php",
        method: "POST",
        data: {readfile: true},
        success: function(data) {
            if (data != null) {
                $('#fileArray').val(JSON.parse(data));
            }
        }
    });
    return false;
}

function fetchColumn()
{
    var columnDetails = $("#columnDetails").val();
    if (!columnDetails) {
        var fileSubmitted = $("#fileSubmitted").val();
        $.ajax({
            url: "ProcessFile.php",
            method: "POST",
            data: {
            fetchColumn: true,
            fileSubmitted: fileSubmitted
            },
            success: function(data) {
                var data = JSON.parse(data);
                for (var dataIndex in data) {
                    if (data.hasOwnProperty(dataIndex)) {
                        if (dataIndex == "error" && data[dataIndex]) {
                            $("#message").html(data[dataIndex]);
                        } else if (dataIndex == "column" && data[dataIndex]) {
                            var columnData = data[dataIndex];
                            var option = '<option>Select the column</option>';
                            for (var cNameIndex in columnData) {
                                if (columnData.hasOwnProperty(cNameIndex)) {
                                    var value = columnData[cNameIndex].replace(/\s/, "&nbsp;");
                                    option += "<option value="+value+">"+value+"</option>";
                                }
                            }
                            $("#columnSelected").append(option);
                            $("#columnSelected").removeAttr("readonly");
                        }
                    }
                }
            }
        });
    }
}
var rowPresent = false;
// Return submitted file result
function processFile()
{
    // Fetch input value
    var fileSubmitted = $("#fileSubmitted").val();
    var columnSelected = $("#columnSelected").val();
    var valueProvided = $("#valueProvided").val();
    $("#columnData").html('');
    $("#rowData").html('');
    // Fetch file present in folder
    $.ajax({
            url: "ProcessFile.php",
            method: "POST",
            data: {
                processFile: true,
                fileSubmitted: fileSubmitted,
                columnSelected: columnSelected,
                valueProvided: valueProvided
            },
            success: function(dataArray) {
                dataObject = JSON.parse(dataArray);
                if (dataObject != null) {
                    $("#columnData").val('');
                    $("#rowData").val('');
                    for (var data in dataObject) {
                        if (dataObject.hasOwnProperty(data)) {
                            // Show error if received
                            if (data === "error" && dataObject[data]) {
                                $("#message").html(dataObject[data]);
                                return;
                            } else {
                                // Fetch Row details
                                if (data === "rowDetails") {
                                    if (dataObject[data].length == 0) {
                                        rowPresent = false;
                                        $("#message").html('<div class="alert alert-info"><strong>Info!</strong> Entered value not found. Please try again.</div>');
                                    } else {
                                        $("#message").html('');
                                        rowPresent = true;
                                        var rowArray = dataObject[data];
                                        for (var rowObjectIndex in rowArray) {
                                            var rowData = '<tr>';
                                            if (rowArray.hasOwnProperty(rowObjectIndex)) {
                                                var rowObject = rowArray[rowObjectIndex];
                                                for (var rowIndex in rowObject) {
                                                    if (rowObject.hasOwnProperty(rowIndex)) {
                                                        rowData += '<td>'+rowObject[rowIndex]+'</td>';
                                                    }
                                                }
                                            }
                                            rowData += '</tr>';
                                            $("#rowData").append(rowData);
                                        }
                                    }
                                }
                                // Fetch Column Name
                                if (data === "column" && dataObject[data]) {
                                    var columnObject = dataObject[data];
                                    for (var columnIndex in columnObject) {
                                        if (columnObject.hasOwnProperty(columnIndex)) {
                                            $('<th>'+columnObject[columnIndex]+'</th>').appendTo("#columnData");
                                        }
                                    }
                                }
                            }
                        }
                    }
                    (rowPresent) ? $("#columnData").show() : $("#columnData").hide();
                }
            },
            error: function(a, b, c) {
                alert(a,b,c);
            }
    });
}

// Function used to store the selected value
function delFileCreator()
{
    var delFileCreator = document.getElementById("delFileCreator");
    $("#deleteFileName").val(delFileCreator.options[delFileCreator.selectedIndex].value);
}

// Function used to delete the submitted file from backend
function deleteFile()
{
    $.ajax({
        url: "ProcessFile.php",
        method: "POST",
        data: {
            deleteFile: true,
            fileSubmitted: $("#deleteFileName").val()
        },
        success: function(data) {
            if (data) {
                $("#message").html('<div class="alert alert-info">Your file is deleted succesfully.</div>');
            } else {
                $("#message").html('<div class="alert alert-warning">There seems to be some problem while deleting your file. Please try agian.</div>');
            }
        }
    });
}
// Funciton needed before page load
$(document).ready(function(){
    fetchFile();
})