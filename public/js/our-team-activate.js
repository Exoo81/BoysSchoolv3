/* 
 * Activate Our team member
 */

//open modal
$(".modal-trigger-activate-our-team-member").click(function(e){
    
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
     
    var memberID = $(this).attr("data-memberID");
    var memberName = $(this).attr("data-memberFullName");
    var type = $(this).attr("data-type");

//        console.log('---=== GET Our Team member DATA TO Activate ===---');
//        console.log('member ID: ' + memberID);
//        console.log('member Name: ' + memberName);
//        console.log('type: ' + type);
        
    //close previous modal base on type
    $("#manage-"+type+"-modal").css({"display":"none"});
       
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#activateOurTeamID").val(memberID);
    $("#activateOurTeamType").val(type);

    $("#confirmation-question-our-team-activate span").html(memberName);
    
    //show confirmation-question-our-team-activate
    $("#confirmation-question-our-team-activate").css({"display":"block"});
    //show form
    $("#activateOurTeamForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#activateOurTeamForm").validate({
    rules: {
        activateOurTeamID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-our-team-activate").css({"display":"none"});
    //hidde form
    $("#activateOurTeamForm").css({"display":"none"});
          
    var memberID = $("#activateOurTeamID").val();
    var memberType = $("#activateOurTeamType").val();

 
//    console.log('---=== Our Team member DATA SEND BY AJAX ===---');
//    console.log('member ID: ' + memberID);
//    console.log('member type: ' + memberType);


    $.ajax({
        url: 'ourteam/activateourteam',                   
        type:'POST',
        data:{memberID:memberID},
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


