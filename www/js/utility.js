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
                localStorage.setItem("message", data);
                location.reload();
            }
        }
    });
});
 
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
 
// Code to create select element containing exists file list
$("#selectFile").click(function() {
    var optionCreatedFlag = $("#optionCreated").text();
    if (!optionCreatedFlag) {
        fetchFile();
        var unFormatArray = $('#fileArray').val();
        var fileArray = unFormatArray.split(',');
        var option = "";
        if (fileArray.length > 0) {
            for (var index in fileArray) {
                var fileName = fileArray[index].replace(/\s/g, "&nbsp;");
                option += "<option id='file"+index+"' value="+fileName+" onClick='selectFile()'>"+fileName+"</option>";
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
        $("#previousSelectedFile").val(selectedValue);
        $("#columnSelected").html('Select the Sheet');
        $("#sheetName").html('');
        fetchData("sheetName");
    }
}
 
// Code to dynamically change the value of column field
$("#sheetName").change(function() {
    $("#columnSelected").html('');
    fetchData("column");
    $("#columnSelected").removeAttr("disabled");
    $("#processFile").attr("disabled", false);
});
 
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
                var fileName = fileArray[index].replace(/\s/g, "&nbsp;");
                option += "<option id='file"+index+"' value="+fileName+" onClick='delFileCreator()'>"+fileName+"</option>";
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
 
 // Function used to fetch the sheet & column data
function fetchData(fetchValue)
{
    var fileSubmitted = $("#fileSubmitted").val();
    if (fetchValue === "column") {
        var sheetName = document.getElementById("sheetName");
        var sheetSelected = sheetName.options[sheetName.selectedIndex].value;
    }
    $.ajax({
        url: "ProcessFile.php",
        method: "POST",
        data: {
            fetchData: true,
            fileSubmitted: fileSubmitted,
            sheetSelected: sheetSelected
        },
        success: function(dataValue) {
            var data = JSON.parse(dataValue);
            for (var dataIndex in data) {
                if (data.hasOwnProperty(dataIndex)) {
                    if (dataIndex == "error" && data[dataIndex]) {
                        $("#message").html(data[dataIndex]);
                    } else {
                        if (fetchValue == "sheetName" && dataIndex == "sheetName" && data[dataIndex]) {
                            var sheetName = data[dataIndex];
                            var optionList = '<option>Select the sheet</option>';
                            for (var nameIndex in sheetName) {
                                if (sheetName[nameIndex] && sheetName.hasOwnProperty(nameIndex)) {
                                    var sheetValue = sheetName[nameIndex].replace(/\s/g, "&nbsp;");
                                    optionList += "<option value="+sheetValue+">"+sheetValue+"</option>";
                                }
                            }
                            $("#sheetName").append(optionList);
                            $("#sheetName").removeAttr("disabled");
                        } else if (fetchValue == "column" && dataIndex == "column" && data[dataIndex]) {
                            var columnData = data[dataIndex];
                            var option = '<option>Select the column</option>';
                            for (var cNameIndex in columnData) {
                                if (columnData[cNameIndex] && columnData.hasOwnProperty(cNameIndex)) {
                                   var value = columnData[cNameIndex].replace(/\s/g, "&nbsp;");
                                    option += "<option value="+value+">"+value+"</option>";
                                }
                            }
                            $("#columnSelected").append(option);
                            $("#columnSelected").removeAttr("readonly");
                        }
                    }
                }
            }
        }
    });
}

$("#valueProvided").keypress(function(event){
    if (event.which === 13) {
        processFile();
    }
});
var rowPresent = false;
// Return submitted file result
function processFile()
{
    // Fetch input value
    var fileSubmitted = $("#fileSubmitted").val();
    var columnSelected = $("#columnSelected").val();
    var valueProvided = $("#valueProvided").val();
    var sheetName = document.getElementById("sheetName");
    var sheetSelected = sheetName.options[sheetName.selectedIndex].value;
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
            valueProvided: valueProvided,
            sheetSelected: sheetSelected
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
                localStorage.setItem("message", '<div class="alert alert-success"><strong>Great!</strong> Your file has been deleted succesfully.</div>');
            } else {
                localStorage.setItem("message",'<div class="alert alert-warning"><strong>Warning!</strong> There seems to be some problem while deleting your file. Please try again.</div>');
            }
            location.reload();
        }
    });
}
// Funciton needed before page load
$(document).ready(function(){
    fetchFile();
    // Check message is set or not
    if (localStorage.getItem("message")) {
        $("#message").html(localStorage.getItem("message"));
        localStorage.clear();
    }
});