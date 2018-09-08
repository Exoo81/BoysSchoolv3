/**
 * Edit Welcome message modal
 */
// get data to edit
$(".modal-trigger-welcome-msg-edit").click(function(e){
	e.preventDefault();
        
        //clear msg label
        $(".response-msg").html('');
               
        
        dataModal = $(this).attr("data-modal");

        var msgId = $(this).data("id");
        
        
        //send id by AJAX to get full object
        $.ajax({
            url:'application/geteditwelcomemsg',
            type:'POST',
            data:{msgId:msgId},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                console.log(data);
                if(data.success === true){
                    $("#" + dataModal).css({"display":"block"});
                    $(".editWelcomeMsgForm").css({"display":"block"});
                    $("#editWelcomeMsgID").val(data.welcomeMsg.id);
                    $("#editWelcomeMsgContent").val(data.welcomeMsg.content);
                }else{
                    $("#" + dataModal).css({"display":"block"});
                    $(".response-msg").html(data.responseMsg);
                    $("#editWelcomeMsgForm").css({"display":"none"});
                }
                return false;
            }
         }); 
});

//after submit
$("#editWelcomeMsgForm").validate({
    rules: {
        editWelcomeMsgContent: {              //input name: editWelcomeMsgContent
            required: true,   //required boolean: true/false
            maxlength: 715       //max. text length = 715 char
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editWelcomeMsgForm").css({"display":"none"});
        
        var id = $("#editWelcomeMsgID").val();
        var welcomeMsg = $("#editWelcomeMsgContent").val();

        $.ajax({
            url:'application/editwelcomemsg',
            type:'POST',
            data:{id:id, welcomeMsg:welcomeMsg},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                console.log(data);
                    if(data.success === true){
                        location.reload(); 

                    }else{
                        $(".response-msg").html(data.responseMsg);
                        //hidde laoder
                            $(".loader").css({"display":"none"});
                         //show form
                            $("#editWelcomeMsgForm").css({"display":"block"});  
                    }
                    return false;
            }
        });
    }

});

//close modal 
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
	$(".modal").css({"display":"none"});
});*/


