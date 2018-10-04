/* 
 * Delete Gallery
 */

//open modal
$(".modal-trigger-delete-gallery").click(function(e){
    
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
    var galleryID = $(this).attr("data-galleryID");
    var galleryTitle = $(this).attr("data-galleryTitle");
    

//        console.log('---=== GET POST DATA TO DELETE ===---');
//        console.log('galleryID: ' + galleryID);
//        console.log('galleryTitle: ' + galleryTitle);
  
        
    //clear msg label
    $(".response-msg").html('');

    //insert data to modal
    $("#deleteGalleryID").val(galleryID);

  
    $("#confirmation-question-gallery span").html("<br />\"" + galleryTitle + "\"");
    
//    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});


$("#deleteGalleryForm").validate({
    rules: {
        deleteGalleryID: {
            required: true
        }
    },
            
    submitHandler: function() {
     
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde confirmation question
    $("#confirmation-question-gallery").css({"display":"none"});
    //hidde form
    $("#deleteGalleryForm").css({"display":"none"});
        
    
    var galleryID = $("#deleteGalleryID").val();

//    console.log('---=== POST DATA SEND BY AJAX ===---');
//    console.log('gallery ID: ' + galleryID);

    
    $.ajax({
         url: 'gallery/deletegallerypost',                  
        type:'POST',
        data:{galleryID:galleryID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
//            console.log(data);
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


