/* 
 * Edit Policy
 */

//open modal with form
$(".modal-trigger-edit-policy").click(function(e){
    
    e.preventDefault();
    
    //close book-list-show-info modal
    $("#show-policy-modal-origin").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
  
    //clear  textarea (summernote)
    $('#editPolicyContent').summernote('reset');

    
    //reset form
    document.getElementById("editPolicyForm").reset();
    //hide editBookListForm
    $("#editPolicyForm").css({"display":"none"});
    

    dataModal = $(this).attr("data-modal");
    var policyID = $(this).attr("data-policyID");
   
//    console.log('========Edit policy =======');
//    console.log('policy ID: ' + policyID);

    
    //insert bookListID to form
    $('#editPolicyID').val(policyID);

    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    //send id by AJAX to get full object
    $.ajax({
        url:'parents/getpolicy',
        type:'POST',
        data:{policyID:policyID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                    
                /**
                 * insert policy data
                 */
                $('#editPolicyTitle').val(data.policy.title);
                $('#editPolicyContent').summernote('code', data.policy.content);
                
                //hide laoder
                $(".loader").css({"display":"none"});
                //show edit form
                $("#editPolicyForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });

});


$("#editPolicyForm").validate({
    
     
    rules: {
        editPolicyID:{
            required: true
        },
        editPolicyTitle: {
            required: true,
            maxlength: 100
        }
    },
    messages:{
        editPolicyTitle:{
            required: 'Title is required.'
        }
    },
    
    
            
    submitHandler: function() {
        
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#editPolicyForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var policyID = $("#editPolicyID").val();
    var policyTitle = $("#editPolicyTitle").val();
    var policyContent = $('#editPolicyContent').val();
    

    
    
//    console.log('---=== Policy edit Form DATA posted ===---');
//    console.log('policy ID: '+ policyID);
//    console.log('policy Title: '+ policyTitle);
//    console.log('policy Content: ' + policyContent);
//    console.log('---================================---');
   
    $.ajax({
        url: 'parents/editpolicy',
        type: 'POST',
        data:{policyID:policyID, policyTitle:policyTitle, policyContent:policyContent},
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

