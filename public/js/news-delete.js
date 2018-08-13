/* 
 * Delete News
 */

//open modal
$(".modal-trigger-delete-news").click(function(e){
    e.preventDefault();
    
    
    dataModal = $(this).attr("data-modal");
    var newsId = $(this).data("id");
    var newsTitle = $(this).data("title");
    
//    console.log('newsId: ' + newsId);
//    console.log('newsTitle: ' + newsTitle);
    
    //clear msg label
    $(".response-msg").html('');
    
    //insert data
    $("#deleteNewsID").val(newsId);
    $("#confirmation-question-news span").html("<br />\"" + newsTitle + "\"");
    
    //display confirmation question
    $("#confirmation-question-news").css({"display":"block"});
    //display form
    $("#deleteNewsForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#deleteNewsForm").validate({
    rules: {
        deleteNewsID: {
            required: true
        }  
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-news").css({"display":"none"});
    //hidde form
    $("#deleteNewsForm").css({"display":"none"});
        
    
    var id = $("#deleteNewsID").val();
    
    $.ajax({
        url:'application/deletenews',
        type:'POST',
        data:{id:id},
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


