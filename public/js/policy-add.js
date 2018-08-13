/* 
 * Add Policy
 */

////additionam method for select - Value must not equal arg. 0 - --select-- 
//$.validator.addMethod("valueNotEquals", function(value, element, arg){
//  return arg !== value;
// }, "Value must not equal arg.");


//open modal with form
$(".modal-trigger-add-policy").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    
    //reset form
    document.getElementById("addPolicyForm").reset();
    //clear textarea sumernote
    $('#addPolicyContent').summernote('reset');
       
    // get data
    dataModal = $(this).attr("data-modal");

    //console.log('--------- '+ dataModal +'--------------');


    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addPolicyForm").validate({
    
     
    rules: {
        addPolicyTitle: {
            required: true,
            maxlength: 100
        }
    },
    messages:{
        addPolicyTitle:{
            required: 'Title is required.'
        }
    },
    
    
            
    submitHandler: function() {
        
    
        
    //substitute of validate policy content (summernote)
    
    if($('#addPolicyContent').summernote('isEmpty')){
        $(".response-msg").html("You must add some policy text"); 
        
    }else{
        
        //show laoder
        $(".loader").css({"display":"block"});
        //hidde form
        $("#addPolicyForm").css({"display":"none"});
        //clear msg label
        $(".response-msg").html('');
        
        var policyTitle = $('#addPolicyTitle').val();
        var policyContent = $('#addPolicyContent').val();



//        console.log('---=== Policy Form DATA posted ===---');
//        console.log('Policy title : '+ policyTitle);
//        console.log('Policy content : '+ policyContent);
//        console.log('---================================---');

        $.ajax({
            url: 'parents/addpolicy',
            type: 'POST',
            data:{policyTitle:policyTitle, policyContent:policyContent},
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
    
    }
});

