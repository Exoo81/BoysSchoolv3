/* 
 * Delete booklist
 */

//open modal
$(".modal-trigger-delete-book-list").click(function(e){
    
    e.preventDefault();
    
    //close book-list-show-info modal
    $("#show-book-list-modal").css({"display":"none"});

    dataModal = $(this).attr("data-modal");
    var bookListID = $(this).attr("data-bookListID");
    var level = $(this).attr("data-bookListLevel");
    var teacherID = $(this).attr("data-bookListTeacherID");
    

//        console.log('---=== GET Booklist DATA TO DELETE ===---');
//        console.log('bookList ID: ' + bookListID);
//        console.log('level: ' + level);
//        console.log('teacher ID: ' + teacherID);
  
        
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteBookListID").val(bookListID);
    $("#deleteTeacherID").val(teacherID);

  
    $("#confirmation-question-book-list span").html("<br />\"" + level + " booklist \" ?");
    
    //show confirmation-question-book-list
    $("#confirmation-question-book-list").css({"display":"block"});
    //show form
    $("#deleteBookListForm").css({"display":"block"});
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});

$("#deleteBookListForm").validate({
    rules: {
        deleteBookListID: {
            required: true
        },
        deleteTeacherID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-book-list").css({"display":"none"});
    //hidde form
    $("#deleteBookListForm").css({"display":"none"});
        
    
    var bookListID = $("#deleteBookListID").val();
    var teacherID = $("#deleteTeacherID").val();
 
//    console.log('---=== ParentsInformation DATA SEND BY AJAX ===---');
//    console.log('bookList ID: ' + bookListID);
//    console.log('teacher ID: ' + teacherID);
    
    $.ajax({
         url: 'parents/deletebooklist',                   
        type:'POST',
        data:{bookListID:bookListID, teacherID:teacherID},
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


