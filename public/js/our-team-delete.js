/* 
 * Deactivate Our team member
 */

//open modal
$(".modal-trigger-delete-our-team").click(function(e){
    
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
     
    var memberID = $(this).attr("data-memberID");
    var memberName = $(this).attr("data-memberFullName");
    var type = $(this).attr("data-type");

//        console.log('---=== GET Our Team member DATA TO Deactivate ===---');
//        console.log('member ID: ' + memberID);
//        console.log('member Name: ' + memberName);
//        console.log('type: ' + type);
        
    //close previous modal base on type
    $("#manage-"+type+"-modal").css({"display":"none"});
       
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteOurTeamID").val(memberID);
    //insert data to modal
    $("#deleteOurTeamType").val(type);

    $("#confirmation-question-our-team-deactivate span").html(memberName);
    
    //show confirmation-question-our-team-deactivate
    $("#confirmation-question-our-team-deactivate").css({"display":"block"});
    //show form
    $("#deleteOurTeamForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteOurTeamForm").validate({
    rules: {
        deleteOurTeamID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-our-team-deactivate").css({"display":"none"});
    //hidde form
    $("#deleteOurTeamForm").css({"display":"none"});
          
    var memberID = $("#deleteOurTeamID").val();
    var memberType = $("#deleteOurTeamType").val();

 
    console.log('---=== Our Team member DATA SEND BY AJAX ===---');
    console.log('member ID: ' + memberID);
    console.log('member type: ' + memberType);

    //if member to delete not from board of management
    if(memberType !== 'management'){
        $.ajax({
             url: 'ourteam/deleteourteam',                   
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
    }else if(memberType === 'management'){//if mamber to delete from board of management
        $.ajax({
            url: 'ourteam/deletemanagment',                   
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
    }else{
        //hidde laoder
            $(".loader").css({"display":"none"});
        //display response-msg
            $(".response-msg").html('The member type can not be determined');
    }
    
 
    
    }
});


//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});


