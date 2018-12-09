/* 
 * Delete Newsletter
 */

//open modal with form
$(".modal-trigger-delete-newsletter").click(function(e){
    e.preventDefault();
    //close manage newsletter modal
    $("#manage-newsletter-modal").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");
    var newsletterId = $(this).data("id");
    var newsletterTitle = $(this).data("title");
    var newsletterDate = $(this).data("date");
    
    //clear msg label
    $(".response-msg").html('');
    
    //insert data
    $("#deleteNewsletterID").val(newsletterId);
    $("#confirmation-question-newsletter span").html("<br />\""+newsletterTitle+" "+ newsletterDate + "\"");
    
    //display confirmation question
    $("#confirmation-question-newsletter").css({"display":"block"});
    //display form
    $("#deleteNewsletterForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
});


$("#deleteNewsletterForm").validate({
    rules: {
        deleteNewsletterID: {
            required: true
        }  
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-newsletter").css({"display":"none"});
    //hidde form
    $("#deleteNewsletterForm").css({"display":"none"});
    
    var id = $("#deleteNewsletterID").val();
    
    $.ajax({
        url:'application/deletenewsletter',
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


