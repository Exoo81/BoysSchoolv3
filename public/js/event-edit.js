/* 
 * Edit Event
 */

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
    var eventDate = $(this).attr("data-eventDate"); 
    var eventLocation = $(this).attr("data-eventLocation"); 
    var eventContent = $(this).attr("data-eventContent"); 
    
    //convert date to format eg. 05 May 2018
    var date = new Date(eventDate);
    var dateEvent = getCurrentDate(date);
    
//    console.log('eventID: ' + eventID);
//    console.log('eventTitle: ' + eventTitle);
//    console.log('eventDate: ' + eventDate);
//    console.log('eventLocation: ' + eventLocation);
//    console.log('eventContent: ' + eventContent);
//    console.log('dateEvent: ' + dateEvent);      
    

    
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
            required: true,
        },
        editEventTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editEventDate: {
            required: true,
        },
        editEventContent: {
            required: true,
            maxlength: 1000
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
                console.log(data);
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

function getCurrentDate(date){
    var month_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
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
    
    $("#editEventID").val(id);
    $("#editEventTitle").val(title);
    $("#editEventDate").val(eventDate);
    $("#editEventLocation").val(location);
    $("#editEventContent").val(content);
});


