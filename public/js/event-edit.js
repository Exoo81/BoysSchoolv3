/* 
 * Edit Event
 */

//additionam method for jQuery validate addEventDate with format eg. 01 June 2018
$.validator.addMethod(
    "editEventFormatDateAddMethod",
    function(value, element) {
        var d = Date.parse(value);
        //alert('value: ' +value + '    Date: ' + d );     
        if (!isNaN(d)) {  // d.valueOf() could also work
            return true;
        }
    },
    "Please enter a date in the format eg. \"06 April 2018\"."
);

// open modal with event data (event-list)
$(".modal-trigger-edit-event").click(function(e){

    e.preventDefault();
    
    //close Events-List modal
        $("#events-list").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    //clear form
        document.getElementById("editEventForm").reset();
    
    dataModal = $(this).attr("data-modal");
    //display modal
        $("#" + dataModal).css({"display":"block"});
      
    var eventID = $(this).attr("data-eventID"); 
    var eventTitle = $(this).attr("data-eventTitle"); 
    var eventYear = $(this).attr("data-eventYear");
    var eventMonth = $(this).attr("data-eventMonth");
    var eventDay = $(this).attr("data-eventDay");  
    var eventLocation = $(this).attr("data-eventLocation"); 
    var eventContent = $(this).attr("data-eventContent"); 
    
    
//    console.log('year: ' + eventYear);
//    console.log('month: ' + eventMonth);
//    console.log('day: ' + eventDay);
    
    //create date base on passed year, month, day 
    var date = new Date(eventYear, eventMonth, eventDay, 19,30, 00);
    //convert date to format eg. 05 May 2018
    var dateEvent = getCurrentDateEventEdit(date);

//    console.log('==============');
//    console.log('event ID: ' + eventID);
//    console.log('event Title: ' + eventTitle);
//    console.log('event Location: ' + eventLocation);
//    console.log('event Content: ' + eventContent);
//   
//    console.log('dateEvent (created): ' + date);
//    console.log('dateEvent: (convertetd date)' + dateEvent); 
//    console.log('==============');
    

    
    $("#editEventID").val(eventID);
    $("#editEventTitle").val(eventTitle);
    $("#editEventDate").val(dateEvent);
    $("#editEventLocation").val(eventLocation);
    $("#editEventContent").val(eventContent);
});

//after submit
$("#editEventForm").validate({
    rules: {
        editEventID: {
            required: true
        },
        editEventTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editEventDate: {
            required: true,
            editEventFormatDateAddMethod: true
        },
        editEventContent: {
            required: true,
            maxlength: 1000
        }
    },
    messages:{
        editEventDate:{
            editEventFormatDateAddMethod: "Incorrect date format. The correct format is eg. 03 April 2019."
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editEventForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');
        
        //get fields values from form
        var editEventID = $("#editEventID").val();
        var editEventTitle = $("#editEventTitle").val();
        var editEventDate = $("#editEventDate").val();
        var editEventLocation = $("#editEventLocation").val();
        var editEventContent = $("#editEventContent").val();
        
        console.log('editEventID: ' + editEventID);
        console.log('editEventTitle: ' + editEventTitle);
        console.log('editEventDate: ' + editEventDate);
        console.log('editEventLocation: ' + editEventLocation);
        console.log('editEventContent: ' + editEventContent);   
        
        
        $.ajax({
            url: 'application/editevent',
            type: 'POST',
            data:{id:editEventID, title:editEventTitle, eventDate:editEventDate, location:editEventLocation, content:editEventContent},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                //console.log(data);
                if(data.success === true){
                    // refresh page
                    location.reload(); 
                }else{
                    $(".response-msg").html(data.responseMsg);
                    //hide laoder
                    $(".loader").css({"display":"none"}); 
                }
                return false;
            }
        });
    }

});

function getCurrentDateEventEdit(date){
    var month_short = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return (date.getDate()<10?("0"+date.getDate()):date.getDate()) + ' ' + month_short[date.getMonth()] + ' ' + date.getFullYear();
}

// open modal with event data - (event details)
$(".modal-trigger-edit-event-details").click(function(e){

    e.preventDefault();
    
    //close Events-List modal
        $("#show-event-modal").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    //clear form
        document.getElementById("editEventForm").reset();
    
    dataModal = $(this).attr("data-modal");
    //display modal
        $("#" + dataModal).css({"display":"block"});
      
    var id = $(this).attr("data-id"); 
    var title = $(this).attr("data-title"); 
    var location = $(this).attr("data-location"); 
    var content = $(this).attr("data-content"); 

    var eventDate = $(this).attr("data-eventDate"); 
    
//    console.log('===========');
//    console.log('event ID: ' + id);
//    console.log('event title: ' + title);
//    console.log('event location: ' + location);
//    console.log('event content: ' + content);
//    console.log('eventDate: ' + eventDate);
//    console.log('===========');
    
    $("#editEventID").val(id);
    $("#editEventTitle").val(title);
    $("#editEventDate").val(eventDate);
    $("#editEventLocation").val(location);
    $("#editEventContent").val(content);
});


