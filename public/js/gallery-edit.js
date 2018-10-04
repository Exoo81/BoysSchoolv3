/* 
 * Edit Gallery
 */

// open modal with news data
$(".modal-trigger-edit-gallery").click(function(e){
    
    e.preventDefault();
    
    //show laoder
    $(".loader").css({"display":"block"});
    
    //clear msg label
    $(".response-msg").html(''); 
    //reset form
    document.getElementById("editGalleryForm").reset();
    

    //set edit_gallery_remove_video as false 
    $("#edit_gallery_remove_video").attr("checked", false);
    
    //clear current_gallery_wrap (new selected photos)
    $('#current_gallery_wrap').empty();
    //clear list of photos to remove from current gallery
    $("ul#picture_to_remove_from_gallery").empty();
    
    //set default last index for added gallery
    $('#input_fields_wrap_edit').attr('data-last-index', 0);
    
    //clear all post-gallery from added gallery
    $("#input_fields_wrap_edit").empty();

    //hidde form
    $("#editGalleryForm").css({"display":"none"});

    //hide all gallery video fields
    $("#editGalleryVideoLabel").css({"display":"none"});
    $("#editGalleryVideo").css({"display":"none"});
    $("#editGalleryVideoLabel_current").css({"display":"none"});
    $("#editGalleryVideo_current").css({"display":"none"});
    //hide input_fields_wrap_label & input_fields_wrap
    $("#input_fields_wrap_edit_label").css({"display":"none"});
    $("#input_fields_wrap_edit").css({"display":"none"});
    
    
    //read pass data
    dataModal = $(this).attr("data-modal");
    
    var galleryId = $(this).attr("data-galleryID");
    var authorId = $(this).attr("data-authorID");

    
//    console.log('--= Get edit gallery =--');
//    console.log('galleryID: ' + galleryId);
//    console.log('authorId: ' + authorId);
    
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
    //send id by AJAX to get full object
    $.ajax({
        url:'gallery/geteditgallery',
        type:'POST',
        data:{galleryId:galleryId},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
//            console.log(data);
            if(data.success === true){
                

                //inster data to fields
                $("#editGalleryID").val(data.postToEdit.id);
                $("#editGalleryAuthorID").val(authorId);
                $("#editGalleryTitle").val(data.postToEdit.title);
                $("#editGalleryContent").val(data.postToEdit.content);
               
                // if post video exist
                if(data.postToEdit.videoName !== null){
                  
                    //show label for current video
                    $("#editGalleryVideoLabel_current").css({"display":"block"});
                    //show icon + video title + remove
                    $("#editGalleryVideo_current").css({"display":"block"});
                    //insert video title
                    $("#editGalleryVideoTitle").html(data.postToEdit.videoName);
                }else{
                    //show input field label for video
                    $("#editGalleryVideoLabel").css({"display":"block"});
                    //show input field for document
                    $("#editGalleryVideo").css({"display":"block"});
                }

                //if gallery with photo
                if(!(jQuery.isEmptyObject(data.pictureList))){
////                    $(".response-msg").html('not empty');
                    $.each(data.pictureList, function (key, value){
                        $('#current_gallery_wrap').append('<div class="post-gallery" data-pictureID="'+key+'">\n\
                                                                <img class="preview-img" id="preview-img-edit-gallery" src="'+value+'" />\n\
                                                                <label class="preview-img-label" id="preview-img-edit-gallery-label" for="preview-img-edit-gallery">\n\
                                                                    <a href="#" class="remove_photo_gallery_current" id="remove_photo_gallery_current">X</a>\n\
                                                                </label>\n\
                                                           </div>');
                    });
 
                    //show current gallery
                        $("#current_gallery_wrap").css({"display":"inline-block"});
                    //show current gallery label
                        $("#current_gallery_wrap_label").css({"display":"block"});
                    //show remove link
                        $(".preview-img-label").css({"display":"block"});
                }else{
                    //hide current gallery
                    $("#current_gallery_wrap").css({"display":"none"});
                    //hide current gallery label
                    $("#current_gallery_wrap_label").css({"display":"none"});
                    
                }
                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#editGalleryForm").css({"display":"block"});
            }else{
                //show error message
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"});
            }
            return false;
        }
    });
    
});

//after submit
$("#editGalleryForm").validate({
    rules: {
        editGalleryID: {
            required: true,
        },
        editGalleryAuthorID: {
            required: true,
        },
        editGalleryTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editGalleryContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        editGalleryVideo: {
            required: false,   
            accept: "video/*"
            //filesize: 10000000           // 4MB
        },
        editGalleryFile: {
            required: false,
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        }
    },
    messages:{
        editGalleryFile:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
        },
        editGalleryVideo:{
            accept: "Allowed only video file."
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editGalleryForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');
        
        //get fields values from form
        var editGalleryID = $("#editGalleryID").val();
        var editAuthorID = $("#editGalleryAuthorID").val();
        var editGalleryTitle = $("#editGalleryTitle").val();
        var editGalleryContent = $("#editGalleryContent").val();
        var removeVideo = $('#edit_gallery_remove_video').attr('checked');
        if(removeVideo === 'checked'){
            removeVideo = true;
        }else{
            removeVideo = false;
        }

        //get video field
        var editGalleryVideo = document.querySelector('#editGalleryVideo').files[0];
        
//            console.log('-- =Edit post sended via ajax ==-');
//            console.log('editGalleryID: ' + editGalleryID);
//            console.log('editAuthorID: ' + editAuthorID);
//            console.log('editGalleryTitle: ' + editGalleryTitle);
//            console.log('editGalleryContent: ' + editGalleryContent);
//            console.log('editGalleryVideo: ' + editGalleryVideo);
//            console.log('removeVideo: ' + removeVideo);
        
        //list of picture to remove from current saved gallery
        var picture_to_remove = [];
        
        $('ul#picture_to_remove_from_gallery li').each(function(i){
            var pictureID = $(this).attr('data-pictureID'); // This is your rel value
            picture_to_remove.push(pictureID);
//            console.log( 'Photo ID to remove: ' + pictureID );
        });
        
        //number of inputs in input_fields_wrap (no. of added photo)
        var inputCount = $('#input_fields_wrap_edit div').length;
//            console.log( 'No. of added photos: ' + inputCount );  
     
        var formData = new FormData();
        
        var objArr = [];
        objArr.push({"editGalleryID":editGalleryID,
                        "editAuthorID":editAuthorID,
                        "editGalleryTitle":editGalleryTitle, 
                        "editGalleryContent":editGalleryContent,  
                        "removeVideo":removeVideo,
                    });
        
        formData.append('objArr', JSON.stringify(objArr));
        formData.append('picture_to_remove', JSON.stringify(picture_to_remove));
        
        formData.append('editGalleryVideo', editGalleryVideo);
        
        if(inputCount > 0){
//            console.log('---=== ADDED PHOTOS TO GALLERY ===---');
            var count = 0;
            $("#input_fields_wrap_edit .post-gallery > input").each(function() {
                //if input field has photo check if .post-gallery img has attr src
                var imgAttr = $(this).closest('.post-gallery').find('img').attr('src');
                if(typeof imgAttr !== typeof undefined && imgAttr !== false){
//                    console.log( 'New input id for picture: ' + $(this).attr("id") );
                    var inputFile = $(this)[0].files[0];
//                        console.log( 'New picture added : ' + inputFile );
                        formData.append('file'+count , inputFile);
                        count ++;
                }
                
            });
        }
        
        
        $.ajax({
            url: '../../../../gallery/editgallerypost',          //??
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(data){
//                console.log(data);
                if(data.success === true){
                    //refresh page
                    location.reload(); 
                }else{
                    $(".response-msg").html(data.responseMsg);
                    //hide laoder
                    $(".loader").css({"display":"none"}); 
                }
                return false;
            }
        });
        
    }

});


// remove video from edit form
$( "#remove_field_video" ).click(function(e) { 

        e.preventDefault();
//        alert('remove video');

        //remove current label and file name + icon
        $("#editGalleryVideoLabel_current").css({"display":"none"});
        $("#editGalleryVideo_current").css({"display":"none"});

        //show choose field with label
        $("#editGalleryVideoLabel").css({"display":"block"});
        $("#editGalleryVideo").css({"display":"block"});
        
        //check old file "to remove"
        $("#edit_gallery_remove_video").attr("checked", true);
 
});

//remove photo from current gallery (gallery-edit) - build list to remove
$('#current_gallery_wrap').on("click",".remove_photo_gallery_current", function(e){  
        
     e.preventDefault();
//     alert('remove photo');
     
        var label = $(this).parent('label');
        var div_to_remove = label.parent('div');
        var pictureID = div_to_remove[0].getAttribute('data-pictureID');
        //alert(pictureID);
        $("ul#picture_to_remove_from_gallery").append('<li data-pictureID='+pictureID+'></li>');
        div_to_remove.remove();
        
        //get current gallery size
        var current_gallery_size = $('#current_gallery_wrap div').length;
        if(current_gallery_size === 0){
            $('#current_gallery_wrap_label').css({"display":"none"});
            $('#current_gallery_wrap').css({"display":"none"});
        }
});

//add post-gallery box to gallery (EDIT GALLERY - add extra photo to galleryPost gallery)
$("#add-photo-gallery-button-edit").click(function(e){ //on add input button click
        
        e.preventDefault();
        //    alert('add photo to gallery');
        
        // max size of gallery
        var max_fields = 30;
        
        //get current gallery size
        var current_gallery_size = $('#current_gallery_wrap div').length;        
        //get size of added to gallery
        var added_gallery_size = $('#input_fields_wrap_edit div').length;
            //alert('Current gallery size: ' + current_gallery_size + '\n\
            //    Added gallery size: ' + added_gallery_size);
    
        //total gallery size
        var totalGallerySize = current_gallery_size + added_gallery_size;
            //alert('Total size of gallery: ' + totalGallerySize);
        
        //get last index used
        var last_index = parseInt($('#input_fields_wrap_edit').attr("data-last-index"));
        
//        var current_gallery_wrap = document.getElementById('current_gallery_wrap');
//        var currentGalleryCount = current_gallery_wrap.getElementsByTagName('div').length;

        var index = 0; //initlal text box count
        if(totalGallerySize < max_fields){ //max input box allowed
            index = last_index + 1;
            //alert('index: ' + index);
            $('#input_fields_wrap_edit').append('<div class="post-gallery">\n\
                                <img class="preview-img" id="preview-img-edit-gallery-'+index+'" />\n\
                                <input type="file" name="editGalleryFile"  id="'+index+'" onchange="preview_image_edit_gallery(this, event)" />\n\
                                <label class="preview-img-label" id="preview-img-edit-gallery-label-'+index+'" for="preview-img-edit-gallery-'+index+'"><a href="#" class="remove_photo_edit">X</a></label>\n\
                             </div>'); //add input box
            //set new last_index
            $('#input_fields_wrap_edit').attr('data-last-index', index++);
        }else{
            alert('Gallery is full.');
        }
        
        //if more then 0 post-gallery show gallery input_fields_wrap_edit and input_fields_wrap_edit_label
        if(index>0){
            //show input_fields_wrap_label & input_fields_wrap
            $("#input_fields_wrap_edit_label").css({"display":"block"});
            $("#input_fields_wrap_edit").css({"display":"block"});
        }
      
});

//remove photo from added gallery (gallery-edit) - build list to remove
$('#input_fields_wrap_edit').on("click",".remove_photo_edit", function(e){  
        
     e.preventDefault();
//     alert('remove photo');
     
        var label = $(this).parent('label');
        var div_to_remove = label.parent('div');
        //var pictureID = div_to_remove[0].getAttribute('data-pictureID');
        //alert(pictureID);
        //$("ul#picture_to_remove_from_post").append('<li data-pictureID='+pictureID+'></li>');
        div_to_remove.remove();
        
        //get current gallery size
        var numberOfAddedPhotos = $('#input_fields_wrap_edit div').length;
        if(numberOfAddedPhotos === 0){
            $('#input_fields_wrap_edit_label').css({"display":"none"});
            $('#input_fields_wrap_edit').css({"display":"none"});
        }
});

function preview_image_edit_gallery(inputFile, event) { 
        inputID = inputFile.getAttribute('id');
           
        image = document.getElementById('preview-img-edit-gallery-'+inputID);
        image.src = URL.createObjectURL(event.target.files[0]);

        
        $("#preview-img-edit-gallery-"+inputID).css({"display":"block"});
        $("#preview-img-edit-gallery-label-"+inputID).css({"display":"block"});
        
        //remove default background from galleryPost-gallery
        $("#preview-img-edit-gallery-"+inputID).parent('div').css({"background-image":"none"});
        //hide input field
        $("input#"+inputID).css({"display":"none"});
        //change galleryPost-gallery background on transparent
        var post_gallery = $("#preview-img-edit-gallery-"+inputID).parent('div');
            post_gallery.css({"background":"none"});
        

}

//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


