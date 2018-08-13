/* 
 * Delete Policy
 */

//open modal
$(".modal-trigger-delete-policy").click(function(e){
    
    e.preventDefault();
    
    //close book-list-show-info modal
    $("#show-policy-modal-origin").css({"display":"none"});

    dataModal = $(this).attr("data-modal");
    var policyID = $(this).attr("data-policyID");
    var policyTitle = $(this).attr("data-title");

//        console.log('---=== GET Policy DATA TO DELETE ===---');
//        console.log('policy ID: ' + policyID);
//        console.log('policy title: ' + policyTitle);
       
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deletePolicyID").val(policyID);



  
    $("#confirmation-question-policy span").html("<br />\"" + policyTitle + " \" ?");
    
    //show confirmation-question-policy
    $("#confirmation-question-policy").css({"display":"block"});
    //show form
    $("#deletePolicyForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deletePolicyForm").validate({
    rules: {
        deletePolicyID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-policy").css({"display":"none"});
    //hidde form
    $("#deletePolicyForm").css({"display":"none"});
          
    var policyID = $("#deletePolicyID").val();

 
//    console.log('---=== Policy DATA SEND BY AJAX ===---');
//    console.log('policy ID: ' + policyID);

    
    $.ajax({
         url: 'parents/deletepolicy',                   
        type:'POST',
        data:{policyID:policyID},
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

