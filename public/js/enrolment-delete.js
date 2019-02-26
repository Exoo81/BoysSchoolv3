/* 
 * Delete Enrolment
 */

//open modal with form
$(".modal-trigger-delete-enrolment").click(function(e){
    
    e.preventDefault();
    //close manage enrolment modal
    $("#manage-enrolment-modal").css({"display":"none"});
    
    
    dataModal = $(this).attr("data-modal");
    var enrolmentId = $(this).data("id");
    var enrolmentTitle = $(this).data("title");
    
    //clear msg label
    $(".response-msg").html('');
    
    //insert data
    $("#deleteEnrolmentID").val(enrolmentId);
    $("#confirmation-question-enrolment span").html("<br />\""+enrolmentTitle);
    
    //display confirmation question
    $("#confirmation-question-enrolment").css({"display":"block"});
    //display form
    $("#deleteEnrolmentForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
});


$("#deleteEnrolmentForm").validate({
    rules: {
        deleteNewsletterID: {
            required: true
        }  
    },
            
    submitHandler: function() {
     
    //hidde confirmation question
    $("#confirmation-question-enrolment").css({"display":"none"});
    //hidde form
    $("#deleteEnrolmentForm").css({"display":"none"});
    //show laoder
    $(".loader").css({"display":"block"});
    
    
    var id = $("#deleteEnrolmentID").val();
    
    $.ajax({
        url:'parents/deleteenrolment',
        type:'POST',
        data:{id:id},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
//            console.log(data);
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


