/* 
 * Edit Parents Assoc Meeting
 */

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");

// open modal with parents information data
$(".modal-trigger-edit-parents-assoc-meeting").click(function(e){
    
    e.preventDefault();
    
    //close Parents Information-List modal
    $("#parents-assoc-manage-modal").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    
    //clear msg label
    $(".response-msg").html(''); 
    //reset form
    document.getElementById("editParentsAssocMeetingForm").reset();
    
    //clear current cycle meeting
    $("#editParentsAssocMeetingStmtTitle").html("");
    //clear current meeting date title
     $("#editParentsAssocMeetingDateTitle").html("");
    
    //hidde form
    $("#editParentsAssocMeetingForm").css({"display":"none"});

    //hide select date status box and label
    $("#editParentsAssocMeetingStatusLabel").css({"display":"none"});
    $("#editParentsAssocMeetingStatusSelect").css({"display":"none"});
    //hide label and field cycle stmt
    $("#editParentsAssocMeetingStmtSelectLabel").css({"display":"none"});
    $("#editParentsAssocMeetingStmtSelect").css({"display":"none"});
    //hide label and field cycle stmt current
    $("#editParentsAssocMeetingStmtLabel_current").css({"display":"none"});
    $("#editParentsAssocMeetingStmt_current").css({"display":"none"});
    //hide label and field next meeting date
    $("#editParentsAssocMeetingDateLabel").css({"display":"none"});
    $("#editParentsAssocMeetingDate").css({"display":"none"});
    //hide label and field date meeting current
    $("#editParentsAssocMeetingDateLabel_current").css({"display":"none"});
    $("#editParentsAssocMeetingDate_current").css({"display":"none"});
    
    
    
    //read pass data
    dataModal = $(this).attr("data-modal");
    
    var parentsAssocId = $(this).attr("data-parentsAssocID");

//        console.log('--= Get edit parent assoc meeting =--');
//        console.log('parentsAssoc Id: ' + parentsAssocId);

    
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
    //send id by AJAX to get full object
    $.ajax({
        url:'parents/geteditparentsassocmeeting',
        type:'POST',
        data:{parentsAssocId:parentsAssocId},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
 
                //inster data to fields
                $("#editParentsAssocMeeting_paID").val(data.meetingToEdit.id);

                var stmtList = {0: "--- SELECT ---",
                                1: "first Monday of next month",
                                2: "first Tuesday of next month", 
                                3: "first Wednesday of next month",
                                4: "first Thursday of next month" ,
                                5: "first Friday of next month",
                                6: "second Monday of next month",
                                7: "second Tuesday of next month", 
                                8: "second Wednesday of next month",
                                9: "second Thursday of next month" ,
                                10: "second Friday of next month",
                                11: "third Monday of next month",
                                12: "third Tuesday of next month", 
                                13: "third Wednesday of next month",
                                14: "third Thursday of next month" ,
                                15: "third Friday of next month"};
                
                //insert options for editParentsAssocMeetingStmtSelect
                $.each(stmtList, function (key, value){
                    $('#editParentsAssocMeetingStmtSelect').append($('<option></option>').attr('value', key).text(value));
                });
                
                $("#editParentsAssocMeetingTime").val(data.meetingToEdit.meetingTime);
                $("#editParentsAssocMeetingLocation").val(data.meetingToEdit.location);
                
                
                if(data.meetingToEdit.dateStatus === "1"){

                    //insert current value to field
                    $("#editParentsAssocMeetingStmtTitle").append(data.meetingToEdit.meetingStmt);
                    //show label for current stmt
                    $("#editParentsAssocMeetingStmtLabel_current").css({"display":"block"});
                    //show icon + textField + remove
                    $("#editParentsAssocMeetingStmt_current").css({"display":"block"});
                }
                if(data.meetingToEdit.dateStatus === "2"){
                    //convert date to format eg. 05 May 2018
                    var date = new Date(data.meetingToEdit.dateNextMeeting);
                    var dateNextMeeting = getCurrentDate(date);
                    //insert current value to field
                    $("#editParentsAssocMeetingDateTitle").append(dateNextMeeting);
                    //show label for current stmt
                    $("#editParentsAssocMeetingDateLabel_current").css({"display":"block"});
                    //show icon + textField + remove
                    $("#editParentsAssocMeetingDate_current").css({"display":"block"});
                }

                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#editParentsAssocMeetingForm").css({"display":"block"});
            }else{
                //show error message
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"});
            }
            return false;
        }
    });
    
});


$("#editParentsAssocMeetingForm").validate({
    rules: {
        editParentsAssocMeeting_paID:{
            required: true 
        },
        editParentsAssocMeetingTime:{
            required: true 
        },
        editParentsAssocMeetingLocation: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editParentsAssocMeetingStatusSelect:{
            valueNotEquals: "0"
        },
        editParentsAssocMeetingStmtSelect:{
            valueNotEquals: "0"
        },
        editParentsAssocMeetingDate:{
            required: true
        },
        
        
      
    },
    messages:{
        editParentsAssocMeetingStatusSelect:{
            valueNotEquals: "Please select type of information"
        },
        editParentsAssocMeetingStmtSelect:{
            valueNotEquals: "Please select statement for cycle meetings"
        }   
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#editParentsAssocMeetingForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var parentsAssocID = $("#editParentsAssocMeeting_paID").val();
    var meetingMode = $("#editParentsAssocMeetingStatusSelect").val();
    var meetingStmt = $("#editParentsAssocMeetingStmtSelect option:selected" ).text();
    var meetingDate = $('#editParentsAssocMeetingDate').val();
    var meetingTime = $('#editParentsAssocMeetingTime').val();
    var meetingLocation = $('#editParentsAssocMeetingLocation').val();
    
    if($('#editParentsAssocMeetingStmtSelect').val() === "0"){
        meetingStmt = 0;
    }
    
    if(meetingStmt === 0){
        meetingStmt = null;
    }
    
    if(meetingDate === ""){
        meetingDate = null;
    }

//    console.log('parents Assoc ID: '+ parentsAssocID);
//    console.log('meeting Mode: '+ meetingMode);
//    console.log('meeting Stmt: ' + meetingStmt);
//    console.log('meeting Date: ' + meetingDate);
//    console.log('meeting Time: ' + meetingTime);
//    console.log('meeting Location: ' + meetingLocation);

  
    var objArr = [];
    objArr.push({parentsAssocID:parentsAssocID, 
                    meetingMode:meetingMode,
                    meetingStmt:meetingStmt,
                    meetingDate:meetingDate,
                    meetingTime:meetingTime,
                    meetingLocation:meetingLocation
                });

    
    $.ajax({
        url: 'parents/editparentsassocmeeting',
        type: 'POST',
        data:{parentsAssocID:parentsAssocID, meetingMode:meetingMode, meetingStmt:meetingStmt, meetingDate:meetingDate, meetingTime:meetingTime, meetingLocation:meetingLocation},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                location.reload();
            }else{
                //hidde laoder
                    $(".loader").css({"display":"none"});
                //display response-msg
                    $(".response-msg").html(data.responseMsg);    
            }  
            return false;
        }      
    }); 
    
    }
});


function getCurrentDate(date){
    var month_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return (date.getDate()<10?("0"+date.getDate()):date.getDate()) + ' ' + month_short[date.getMonth()] + ' ' + date.getFullYear();
}

// remove cycle meeting current
$( "#remove_field_textInput_stmt" ).click(function(e) {
        
        e.preventDefault();
//        alert('remove url');

        //remove current label and info about "cycle meeting (current)"
        $("#editParentsAssocMeetingStmtLabel_current").css({"display":"none"});
        $("#editParentsAssocMeetingStmt_current").css({"display":"none"});
        
        //show select box "MODE" and label 
        $("#editParentsAssocMeetingStatusLabel").css({"display":"block"});
        $("#editParentsAssocMeetingStatusSelect").css({"display":"block"});
        
        //check old file "to remove"
        //$("#edit_parents-information_remove_textInput").attr("checked", true);
 
});

// remove next meeting date
$( "#remove_field_textInput_date" ).click(function(e) {
        
        e.preventDefault();
//        alert('remove url');

        //remove current label and current next meeting
        $("#editParentsAssocMeetingDateLabel_current").css({"display":"none"});
        $("#editParentsAssocMeetingDate_current").css({"display":"none"});
        
        //show select box "MODE" and label 
        $("#editParentsAssocMeetingStatusLabel").css({"display":"block"});
        $("#editParentsAssocMeetingStatusSelect").css({"display":"block"});
        
        //check old file "to remove"
        //$("#edit_parents-information_remove_textInput").attr("checked", true);
 
});

// show/hide by SELECT BOX
$("#editParentsAssocMeetingStatusSelect").change(function() {
    var val = $(this).val();
    if(val === "1") {
        $("#editParentsAssocMeetingStmtSelectLabel").css({"display":"inline-block"});
        $("#editParentsAssocMeetingStmtSelect").css({"display":"inline-block"});
        $("#editParentsAssocMeetingDateLabel").css({"display":"none"});
        $("#editParentsAssocMeetingDate").css({"display":"none"});
        
        //empty field
        $('#editParentsAssocMeetingStmtSelect').val(0);
        $("#editParentsAssocMeetingDate").val(null);
    }
    else if(val === "2") {
        $("#editParentsAssocMeetingDateLabel").css({"display":"inline-block"});
        $("#editParentsAssocMeetingDate").css({"display":"inline-block"});
        $("#editParentsAssocMeetingStmtSelectLabel").css({"display":"none"});
        $("#editParentsAssocMeetingStmtSelect").css({"display":"none"});
        
        //empty field
        $('#editParentsAssocMeetingStmtSelect').val(0);
        $("#editParentsAssocMeetingDate").val(null);
    }else{
        $("#editParentsAssocMeetingStmtSelectLabel").css({"display":"none"});
        $("#editParentsAssocMeetingStmtSelect").css({"display":"none"});
        $("#editParentsAssocMeetingDateLabel").css({"display":"none"});
        $("#editParentsAssocMeetingDate").css({"display":"none"});
        
        //empty both fields
        $('#editParentsAssocMeetingStmtSelect').val(0);
        $("#editParentsAssocMeetingDate").val(null);
    }
  });
  
//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/




