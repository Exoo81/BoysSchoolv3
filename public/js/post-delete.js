/* 
 * Delete Post
 */

//open modal
$(".modal-trigger-delete-post").click(function(e){
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
    var postID = $(this).attr("data-postID");
    var postTitle = $(this).attr("data-postTitle");
    var teacherID = $(this).attr("data-teacherID");
    var learningSupportID = $(this).attr("data-learningSupportID");
    

//        console.log('---=== GET POST DATA TO DELETE ===---');
//        console.log('postID: ' + postID);
//        console.log('postTitle: ' + postTitle);
//        console.log('teacherID: ' + teacherID);
//        console.log('learningSupportID: ' + learningSupportID);
  
        
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deletePostID").val(postID);
    $("#deleteTeacherID").val(teacherID);
    $("#deleteLearningSupportID").val(learningSupportID);
  
    $("#confirmation-question-post span").html("<br />\"" + postTitle + "\"");
    
//    display confirmation question
//    $("#confirmation-question-post").css({"display":"block"});
//    display form
//    $("#deletePostForm").css({"display":"block"});
//    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deletePostForm").validate({
    rules: {
        deletePostID: {
            required: true
        },
        deleteTeacherID:{
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-post").css({"display":"none"});
    //hidde form
    $("#deletePostForm").css({"display":"none"});
        
    
    var postID = $("#deletePostID").val();
    var teacherID = $("#deleteTeacherID").val();
    var learningSupportID = $("#deleteLearningSupportID").val();
    
    if(learningSupportID === ""){
        learningSupportID = null;
    }
    
    
//    console.log('---=== POST DATA SEND BY AJAX ===---');
//    console.log('post ID: ' + postID);
//    console.log('teacher ID: ' + teacherID);
//    console.log('learning support ID: ' + learningSupportID);
    
    $.ajax({
         url: 'classblog/deletepost',                   // ???
        type:'POST',
        data:{postID:postID, teacherID:teacherID, learningSupportID:learningSupportID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
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


