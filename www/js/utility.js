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

// select file
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

function selectFile()
{
    var selectFile = document.getElementById("selectFile");
    var selectedValue = selectFile.options[selectFile.selectedIndex].value;
    $("#fileSubmitted").val(selectedValue);
}

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
                            // Fetch Column Name
                            if (data === "column") {
                                var columnObject = dataObject[data];
                                for (var columnIndex in columnObject) {
                                    if (columnObject.hasOwnProperty(columnIndex)) {
                                        $('<th>'+columnObject[columnIndex]+'</th>').appendTo("#columnData");
                                    }
                                }
                            }
                            // Fetch Row details
                            if (data === "rowDetails") {
                                var rowArray = dataObject[data];
                                for (var rowObjectIndex in rowArray) {
                                    if (rowArray.hasOwnProperty(rowObjectIndex)) {
                                        var rowObject = rowArray[rowObjectIndex];
                                        for (var rowIndex in rowObject) {
                                            if (rowObject.hasOwnProperty(rowIndex)) {
                                                $('<td>'+rowObject[rowIndex]+'</td>').appendTo("#rowData")
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            },
            error: function(a, b, c) {
                alert(a,b,c);
            }
    });
}

$(document).ready(function(){
    fetchFile();
})