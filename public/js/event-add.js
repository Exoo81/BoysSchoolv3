/* 
 * Add Event
 */
//additionam method for jQuery validate addEventDate with format eg. 01 June 2018
$.validator.addMethod(
    "formatDateAddMethod",
    function(value, element) {
        
//TODO
        //format value to format requested
        //if not error return true
        return true;
        
        /**
         * TODO this method add to event-edit.js
         */
       
    },
    "Please enter a date in the format eg. \"05 April 2018\"."
);

//open modal with form
$(".modal-trigger-add-event").click(function(e){
    
    e.preventDefault();
    
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    
    //clear msg label
     $(".response-msg").html('');
    //reset form
    document.getElementById("addEventForm").reset();
    
    //insert data
    $('#addEventAuthorID').val(authorID);
        
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addEventForm").validate({
    rules: {
        addEventAuthorID: {
            required: true,
        },
        addEventTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addEventDate: {
            required: true,
            formatDateAddMethod : true
        },
        addEventContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        
      
    },
             
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addEventForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var eventTitle = $("#addEventTitle").val();
    var eventDate = $("#addEventDate").val();
    var eventLocation = $("#addEventLocation").val();
    var eventContent = $("#addEventContent").val();
    var authorID = $("#addEventAuthorID").val();

//    console.log(eventTitle);
//    console.log(eventDate);
//    console.log(eventLocation);
//    console.log(eventContent);
//    console.log(authorID);
    
    $.ajax({
        url: 'application/addevent',
        type: 'POST',
        data:{title:eventTitle, eventDate:eventDate, location:eventLocation, content:eventContent, authorID:authorID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                //refresh page
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


