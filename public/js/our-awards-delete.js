/* 
 * Delete Our Award
 */

//open modal
$(".modal-trigger-delete-our-awards").click(function(e){
    e.preventDefault();
    
    //close Our award manage modal
    $("#manage-our-awards-modal").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");
    var ourAwardId = $(this).data("id");
    var ourAwardTitle = $(this).data("title");
    
    //clear msg label
    $(".response-msg").html('');
    
    
    //insert data
    $("#ourAwardID").val(ourAwardId);
    $("#confirmation-question-our-award span").html("<br />\"" + ourAwardTitle + "\"");
    
    //display confirmation question
    $("#confirmation-question-our-award").css({"display":"block"});
    //display form
    $("#deleteOurAwardForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});

});

$("#deleteOurAwardForm").validate({
    rules: {
        ourAwardID: {
            required: true
        }  
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-our-award").css({"display":"none"});
    //hidde form
    $("#deleteOurAwardForm").css({"display":"none"});
        
    
    var id = $("#ourAwardID").val();
    
    console.log('Our Award ID: ' + id);
    
    $.ajax({
        url:'application/deleteouraward',
        type:'POST',
        data:{id:id},
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


//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});


