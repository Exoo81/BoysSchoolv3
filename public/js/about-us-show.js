/* 
 * Show About Us in footer
 */


// open modal with book list data
$(".modal-trigger-show-about-us").click(function(e){
    
    e.preventDefault();
    
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    //hide modal-text
    $("#about-us-origin .modal-text").css({"display":"none"});
  
    dataModal = $(this).attr("data-modal");

    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    

        
    //send id by AJAX to get full object
    $.ajax({
        url:'application/getaboutusorigin',
        type:'POST',
        data:{},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                $('#about-us-origin #title').html(data.aboutUs.title);
                $('#about-us-origin #content').html(data.aboutUs.content);
                $('#about-us-origin #principal_name').html(data.aboutUs.principalName);
               
               //hide laoder
               $(".loader").css({"display":"none"});
               //show modal-text
               $("#about-us-origin .modal-text").css({"display":"block"});
//                /*
//                 * if request from Parents/Policies block
//                 */
//                if(origin){
//                    /**
//                    * insert title
//                    */
//                   $('#showPolicyTitleOrigin').html(data.policy.title);
//
//                   /*
//                    * insert content (text)
//                    */
//                   $('#showPolicyContentOrigin').html(data.policy.content);
//                   //show policy details (all info)
//                    $("#policyContentOrigin").css({"display":"block"});
//                    
//                }else{ /// if request for Polcy Helper
//                    
//                    /**
//                    * insert title
//                    */
//                   $('#showPolicyTitle').html(data.policy.title);
//
//                   /*
//                    * insert content (text)
//                    */
//                   $('#showPolicyContent').html(data.policy.content);
//                   
//                   //show policy details (all info)
//                    $("#policyContent").css({"display":"block"});
//                    
//                }
//
//                
//                /*
//                 * insert attributes to admin options
//                 */
//                if(access){
//                    $('.admin-option').css({"display":"inline-block"});
//                    var editElement = $("a.modal-trigger-edit-policy");
//                        editElement.attr('data-policyID', data.policy.id);
//                        
//                    var deleteElement = $( "a.modal-trigger-delete-policy" );
//                        deleteElement.attr('data-policyID', data.policy.id);
//                        deleteElement.attr('data-title', data.policy.title);
//                }
//
//               
//                
//                //show print option
//                $(".print-option-bottom").css({"display":"block"});
//                $(".print-option-top").css({"display":"block"});
//                //hide laoder
//                $(".loader").css({"display":"none"});
            }else{
//                $(".response-msg").html(data.responseMsg);
//                //hide laoder
//                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });
});


