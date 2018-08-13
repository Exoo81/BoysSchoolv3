/* 
 * Delete Event
 */

//open modal
$(".modal-trigger-delete-event").click(function(e){
    e.preventDefault();
    
    //close Events-List modal
       $("#events-list").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    //clear form
        document.getElementById("deleteEventForm").reset();
    
    dataModal = $(this).attr("data-modal");
      
    var id = $(this).attr("data-id"); 
    var title = $(this).attr("data-title"); 
    
    $("#deleteEventID").val(id);
    $("#confirmation-question-event span").html("<br />\"" + title + "\"");
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteEventForm").validate({
    rules: {
        deleteEventID: {
            required: true
        }  
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-event").css({"display":"none"});
    //hidde form
    $("#deleteEventForm").css({"display":"none"});
        
    
    var id = $("#deleteEventID").val();
    
    $.ajax({
        url:'application/deleteevent',
        type:'POST',
        data:{id:id},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                // refresh page
                location.reload();
            }else{
                //hidde laoder
                $(".loader").css({"display":"none"});
                //show response-msg
                $(".response-msg").html(data.responseMsg);                
            }   
        }      
    }); 
    return false;
    }
});

// open modal with event data - (event details)
$(".modal-trigger-delete-event-details").click(function(e){

    e.preventDefault();
    
    //close Events-List modal
        $("#show-event-modal").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    //clear form
        document.getElementById("deleteEventForm").reset();
    
    dataModal = $(this).attr("data-modal");
    //display modal
        $("#" + dataModal).css({"display":"block"});
      
    var id = $(this).attr("data-id"); 
    var title = $(this).attr("data-title"); 
    
    $("#deleteEventID").val(id);
    $("#confirmation-question-event span").html("<br />\"" + title + "\"");
});


//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});


