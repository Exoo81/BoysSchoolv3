/* 
 * Deactivate Parents Assoc member
 */

//open modal
$(".modal-trigger-delete-parents-assoc-member").click(function(e){
    
    e.preventDefault();
    
    //close Parents Assoc manage modal
    $("#parents-assoc-manage-modal").css({"display":"none"});

    dataModal = $(this).attr("data-modal");
    var memberID = $(this).attr("data-memberID");
    var memberName = $(this).attr("data-memberName");

    

//        console.log('---=== GET Parents Information DATA TO DELETE ===---');
//        console.log('member ID: ' + memberID);
//        console.log('member Name: ' + memberName);
  
    //close previous modal base on type
    $("#parents-assoc-manage-modal").css({"display":"none"});
        
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteParentsAssocMemeberID").val(memberID);

  
    $("#confirmation-question-pa-deactivate span").html(memberName);
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteParentsAssocMemeberForm").validate({
    rules: {
        deleteParentsAssocMemeberID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-pa-deactivate").css({"display":"none"});
    //hidde form
    $("#deleteParentsAssocMemeberForm").css({"display":"none"});
        
    
    var memberID = $("#deleteParentsAssocMemeberID").val();
 
//    console.log('---=== ParentsAssoc DATA SEND BY AJAX ===---');
//    console.log('ParentsAssoc Memeber ID: ' + memberID);
    
    $.ajax({
         url: 'parents/deleteparentsassocmember',                   
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

