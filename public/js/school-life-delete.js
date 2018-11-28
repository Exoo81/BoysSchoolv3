/* 
 * Delete Policy
 */

//open modal
$(".modal-trigger-delete-school-life").click(function(e){
    
    e.preventDefault();
    
    //close show-school-life-modal 
    $("#show-school-life-modal").css({"display":"none"});

    dataModal = $(this).attr("data-modal");
    var schoolLifeID = $(this).attr("data-schoolLifeID");
    var schoolLifeTitle = $(this).attr("data-schoolLifeTitle");

        console.log('---=== GET SCHOOL LIFE DATA TO DELETE ===---');
        console.log('school Life ID: ' + schoolLifeID);
        console.log('school Life title: ' + schoolLifeTitle);
       
    //clear msg label
    $(".response-msg").html('');
    
    //insert data to modal
    $("#deleteSchoolLifeID").val(schoolLifeID);



  
    $("#confirmation-question-school-life span").html("<br />\"" + schoolLifeTitle + " \" ?");
    
    //show confirmation-question-policy
    $("#confirmation-question-school-life").css({"display":"block"});
    //show form
    $("#deleteSchoolLifeForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteSchoolLifeForm").validate({
    rules: {
        deleteSchoolLifeID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-school-life").css({"display":"none"});
    //hidde form
    $("#deleteSchoolLifeForm").css({"display":"none"});
          
    var schoolLifeID = $("#deleteSchoolLifeID").val();

 
    console.log('---=== School Life DATA SEND BY AJAX ===---');
    console.log('school Life ID: ' + schoolLifeID);

    
    $.ajax({
         url: 'schoollife/deleteschoollife',                   
        type:'POST',
        data:{schoolLifeID:schoolLifeID},
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


