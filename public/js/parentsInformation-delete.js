/* 
 * Delete Parents Information
 */

//open modal
$(".modal-trigger-delete-parents-information").click(function(e){
    
    e.preventDefault();
    
    //close Parents Information-List modal
    $("#show-all-parents-information-modal").css({"display":"none"});

    dataModal = $(this).attr("data-modal");
    var parentsInformationID = $(this).attr("data-parentsInformationID");
    var parentsInformationTitle = $(this).attr("data-parentsInformationTitle");

    

//        console.log('---=== GET Parents Information DATA TO DELETE ===---');
//        console.log('parentsInformation ID: ' + parentsInformationID);
//        console.log('parentsInformation Title: ' + parentsInformationTitle);
  
        
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteParentsInformationID").val(parentsInformationID);

  
    $("#confirmation-question-pi span").html("<br />\"" + parentsInformationTitle + "\"");
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteParentsInformationForm").validate({
    rules: {
        deleteParentsInformationID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-pi").css({"display":"none"});
    //hidde form
    $("#deleteParentsInformationForm").css({"display":"none"});
        
    
    var parentsInformationID = $("#deleteParentsInformationID").val();
 
//    console.log('---=== ParentsInformation DATA SEND BY AJAX ===---');
//    console.log('parentsInformation ID: ' + parentsInformationID);
    
    $.ajax({
         url: 'parents/deleteparentsinformation',                   // ???
        type:'POST',
        data:{parentsInformationID:parentsInformationID},
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


