/* 
 * Show Policy
 */


// open modal with book list data
$(".modal-trigger-show-policy").click(function(e){
    
    e.preventDefault();
    
    //close Manage Policy Modal
    $("#manage-policy-modal").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //hide admin-option
    $('.admin-option').css({"display":"none"});
    //hide policy content (all info for policies Helper)
    $("#policyContent").css({"display":"none"});
    //hide policy content (all info for Parents/Policies block)
    $("#policyContentOrigin").css({"display":"none"});
    //hide print option
    $(".print-option-bottom").css({"display":"none"});
    $(".print-option-top").css({"display":"none"});
    
   
    dataModal = $(this).attr("data-modal");
    var policyID = $(this).attr("data-policyID");
    var authorID = $(this).attr("data-authorID");
    
    var access = $(this).data("access") === 1 ? true : false; ;
    
    var origin =  $(this).data("origin") === 1 ? true : false; ;

    
//    console.log('======== Policies =======');
//    console.log('policy ID: ' + policyID);
//    console.log('author ID: ' + authorID);
//    console.log('origin: ' + origin);
//    console.log('access: '+ access);
    
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
            //console.log(data);
            if(data.success === true){
               
                /*
                 * if request from Parents/Policies block
                 */
                if(origin){
                    /**
                    * insert title
                    */
                   $('#showPolicyTitleOrigin').html(data.policy.title);

                   /*
                    * insert content (text)
                    */
                   $('#showPolicyContentOrigin').html(data.policy.content);
                   //show policy details (all info)
                    $("#policyContentOrigin").css({"display":"block"});
                    
                }else{ /// if request for Polcy Helper
                    
                    /**
                    * insert title
                    */
                   $('#showPolicyTitle').html(data.policy.title);

                   /*
                    * insert content (text)
                    */
                   $('#showPolicyContent').html(data.policy.content);
                   
                   //show policy details (all info)
                    $("#policyContent").css({"display":"block"});
                    
                }

                
                /*
                 * insert attributes to admin options
                 */
                if(access){
                    $('.admin-option').css({"display":"inline-block"});
                    var editElement = $(".admin-option a.modal-trigger-edit-policy");
                        editElement.attr('data-policyID', data.policy.id);
                        editElement.attr('data-authorID', authorID);
                        
                    var deleteElement = $(".admin-option a.modal-trigger-delete-policy" );
                        deleteElement.attr('data-policyID', data.policy.id);
                        deleteElement.attr('data-title', data.policy.title);
                }

               
                
                //show print option
                $(".print-option-bottom").css({"display":"block"});
                $(".print-option-top").css({"display":"block"});
                //hide laoder
                $(".loader").css({"display":"none"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });
});


