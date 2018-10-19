/* 
 * Delete Class Blog
 */

//open modal
$(".modal-trigger-delete-class-blog").click(function(e){
    
    e.preventDefault();
    
    dataModal = $(this).attr("data-modal");
    var blogId = $(this).data("id");
    var level = $(this).data("level");
    var teacherFullName = $(this).data("teacher");
    
//        console.log(blogId);
//        console.log(level);
//        console.log(teacherFullName);
    
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteClassBlogID").val(blogId);
    $("#confirmation-question-class-blog span").html("<br />\"" + teacherFullName +" "+level+" BLOG\" ?");
    
    //display confirmation question
    $("#confirmation-question-class-blog").css({"display":"block"});
    //display form
    $("#deleteClassBlogForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteClassBlogForm").validate({
    rules: {
        deleteClassBlogID: {
            required: true
        }  
    },
            
    submitHandler: function() {

    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-class-blog").css({"display":"none"});
    //hidde form
    $("#deleteClassBlogForm").css({"display":"none"});
        
    
    var blogID = $("#deleteClassBlogID").val();
    

    $.ajax({
        url:'classes/deleteblog',
        type:'POST',
        data:{blogID:blogID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                //reload page
                location.reload();
            }else{
                //hidde laoder
                    $(".loader").css({"display":"none"});
                //display response-msg
                    $(".response-msg").html(data.responseMsg);                
            }   
        }      
    }); 
    return false;
    }
});


//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});


