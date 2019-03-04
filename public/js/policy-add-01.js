/* 
 * Add Policy
 */

////additionam method for select - Value must not equal arg. 0 - --select-- 
//$.validator.addMethod("valueNotEquals", function(value, element, arg){
//  return arg !== value;
// }, "Value must not equal arg.");

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");


//open modal with form
$(".modal-trigger-add-policy").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    
    //reset form
    document.getElementById("addPolicyForm").reset();
    //clear textarea sumernote
    $('#addPolicyContent').summernote('reset');
        //hide note editor summernote
        $('#addPolicyContent').summernote('destroy');
       
    // get data
    dataModal = $(this).attr("data-modal");

    //console.log('--------- '+ dataModal +'--------------');

    //display form
    $("#addPolicyForm").css({"display":"block"});
    
    //hide inputs befor selection
    $("#addPolicyDocLabel").css({"display":"none"});
    $("#addPolicyDoc").css({"display":"none"});
    $("#addPolicyContentLabel").css({"display":"none"});
    $("#addPolicyContent").css({"display":"none"});
        
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addPolicyForm").validate({
    
     
    rules: {
        addPolicyTitle: {
            required: true,
            maxlength: 100
        },
        addPolicySelect:{
            valueNotEquals: "0"
        }
    },
    
    messages:{
        addPolicyTitle:{
            required: 'Title is required.'
        },
        addPolicySelect:{
            valueNotEquals: "Please select type of policy (file or modal)"
        },
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


$("#addPolicySelect").change(function() {
    var val = $(this).val();
    if(val === "1") {
        
        //hide note editor summernote
        $('#addPolicyContent').summernote('destroy');
        
        $("#addPolicyDocLabel").css({"display":"block"});
        $("#addPolicyDoc").css({"display":"block"});
        $("#addPolicyContentLabel").css({"display":"none"});
        $("#addPolicyContent").css({"display":"none"});

    }
    else if(val === "2") {
        $("#addPolicyContentLabel").css({"display":"block"});
        $("#addPolicyContent").css({"display":"block"});
        $("#addPolicyDocLabel").css({"display":"none"});
        $("#addPolicyDoc").css({"display":"none"});
        
        $('#addPolicyContent').summernote({
            toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['insert',['table', 'picture']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['link', ['linkDialogShow', 'unlink']],
                    ['misc', ['codeview']]
            ],
            height: 400,
            dialogsInBody: true
        });
    }else{
        //hide note editor summernote
        $('#addPolicyContent').summernote('destroy');
        
        $("#addPolicyDocLabel").css({"display":"none"});
        $("#addPolicyDoc").css({"display":"none"});
        $("#addPolicyContentLabel").css({"display":"none"});
        $("#addPolicyContent").css({"display":"none"});      
    }
    
    //empty file field
        $("#addPolicyDoc").val(null);
  });

