/* 
 * Edit Welcome message modal
 */

// get data to edit
$(".modal-trigger-about-us-edit").click(function(e){
	
    e.preventDefault();
        
     //close about-us modal
     $("#about-us").css({"display":"none"});
        
    //clear msg label
    $(".response-msg").html('');
        
    dataModal = $(this).attr("data-modal");

    var aboutUsId = $(this).data("id");
        
    //send id by AJAX to get full object
    $.ajax({
        url:'application/getaboutus',
        type:'POST',
        data:{aboutUsId:aboutUsId},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                    $("#" + dataModal).css({"display":"block"});
                    $("#editAboutUsID").val(data.aboutUs.id);
                    $("#editAboutUsTitle").val(data.aboutUs.title);
                    $("#editAboutUsContent").val(data.aboutUs.content);
                    $("#editAboutUsPrincipalName").val(data.aboutUs.principalName);
                    
            }else{
                    $("#" + dataModal).css({"display":"block"});
                    $(".response-msg").html(data.responseMsg);
                    $("#editAboutUsForm").css({"display":"none"});
            }
            return false;
        }
    });
});

//after submit
$("#editAboutUsForm").validate({
    rules: {
        editAboutUsID: {
            required: true
        },
        editAboutUsTitle: {
            required: true
        },
        editAboutUsContent: {              //input name: editAboutUsContent
            required: true   
            
        },
        editAboutUsPrincipalName: {
            required: true,
            letterswithbasicpunc: true
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editAboutUsForm").css({"display":"none"});
        
        var id = $("#editAboutUsID").val();
        var title = $("#editAboutUsTitle").val();
        var content = $("#editAboutUsContent").val();
        var principalName = $("#editAboutUsPrincipalName").val();

        $.ajax({
            url:'application/editaboutus',
            type:'POST',
            data:{id:id, title:title, content:content, principalName:principalName},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                console.log(data);
                    if(data.success === true){
                        //1. with refresh page
                            //location.reload();
                        //2. without refresh page
                            $('#about-us .modal-text #title').html(data.aboutUs.title);
                            $('#about-us .modal-text #content').html(data.aboutUs.content);
                            $('#about-us .modal-text #principal_name').html(data.aboutUs.principalName);
                            $(".modal").css({"display":"none"});
                            $(".response-msg").html(data.responseMsg);
                            //hidde laoder
                                $(".loader").css({"display":"none"});
                            //show form
                                $("#editAboutUsForm").css({"display":"block"});  
                    }else{
                        $(".response-msg").html(data.responseMsg);
                        //hidde laoder
                        $(".loader").css({"display":"none"});

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



