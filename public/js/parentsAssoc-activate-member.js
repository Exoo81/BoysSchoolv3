/* 
 * Activate Parents Assoc member
 */

//open modal
$(".modal-trigger-activate-parents-assoc-member").click(function(e){
    
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
     
    var memberID = $(this).attr("data-memberID");
    var memberName = $(this).attr("data-memberName");

//        console.log('---=== Parents Assoc member DATA TO Activate ===---');
//        console.log('member ID: ' + memberID);
//        console.log('member Name: ' + memberName);
        
    //close previous modal base on type
    $("#parents-assoc-manage-modal").css({"display":"none"});
       
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#activateParentsAssocMemeberID").val(memberID);
    
    $("#confirmation-question-pa-activate span").html(memberName);
    
    //show confirmation-question-our-team-activate
    $("#confirmation-question-pa-activate").css({"display":"block"});
    //show form
    $("#activateParentsAssocMemeberForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#activateParentsAssocMemeberForm").validate({
    rules: {
        activateParentsAssocMemeberID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-pa-activate").css({"display":"none"});
    //hidde form
    $("#activateParentsAssocMemeberForm").css({"display":"none"});
          
    var memberID = $("#activateParentsAssocMemeberID").val();
    
//    console.log('---=== Parents Assoc member DATA SEND BY AJAX ===---');
//    console.log('member ID: ' + memberID);


    $.ajax({
        url: 'parents/activateparentsassocmember',                   
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


