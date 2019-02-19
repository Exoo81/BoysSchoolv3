/* 
 * Edit Gallery
 */
//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

// open modal with news data
$(".modal-trigger-edit-gallery").click(function(e){
    
    e.preventDefault();
    
    //show laoder
    $(".loader").css({"display":"block"});
    
    //clear msg label
    $(".response-msg").html(''); 
    //reset form
    document.getElementById("editGalleryForm").reset();
    
    //hide all gallery video fields
    $("#editGalleryVideoLabel").css({"display":"none"});
    $("#editGalleryVideo").css({"display":"none"});
    $("#editGalleryVideoLabel_current").css({"display":"none"});
    $("#editGalleryVideo_current").css({"display":"none"});
    //remove jquert validation error for video field - editPostVideo
        $("#editGalleryVideo-error").css({"display":"none"});
    $("#preview-video-edit-gallery-label").css({"display":"none"}); 
    //set edit_post_remove_video as false 
    $("#edit_gallery_remove_video").attr("checked", false);
    
    
    //clear current_gallery_wrap (new selected photos)
    $('#current_gallery_wrap').empty();
    //clear list of photos to remove from current gallery
    $("ul#picture_to_remove_from_gallery").empty();
    
    //set default last index for added gallery
    $('#input_fields_wrap_edit_gallery').attr('data-last-index', 0);
    
    //clear all post-gallery from added gallery
    $("#input_fields_wrap_edit_gallery").empty();

    //hidde form
    $("#editGalleryForm").css({"display":"none"});

//    //hide input_fields_wrap_label & input_fields_wrap
    $("#input_fields_wrap_edit_gallery_label").css({"display":"none"});
    $("#input_fields_wrap_edit_gallery").css({"display":"none"});
    
    
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
            required: true
        },
        editGalleryAuthorID: {
            required: true
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
            accept: "video/*",
            filesize: 300000000           // 300MB raw file before commpression
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
            accept: "Allowed only video file.",
            filesize: "The video file is too big."
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
        var inputCount = $('#input_fields_wrap_edit_gallery div').length;
//            console.log( 'No. of added photos: ' + inputCount );  
     
        var formData = new FormData();
        
        var objArr = [];
        objArr.push({"editGalleryID":editGalleryID,
                        "editAuthorID":editAuthorID,
                        "editGalleryTitle":editGalleryTitle, 
                        "editGalleryContent":editGalleryContent,  
                        "removeVideo":removeVideo
                    });
        
        formData.append('objArr', JSON.stringify(objArr));
        formData.append('picture_to_remove', JSON.stringify(picture_to_remove));
        
        formData.append('editGalleryVideo', editGalleryVideo);
        
        if(inputCount > 0){
//            console.log('---=== ADDED PHOTOS TO GALLERY ===---');
            var count = 0;
            $("#input_fields_wrap_edit_gallery .post-gallery > input").each(function() {
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
            url: 'gallery/editgallerypost',         
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

/*
 * 
 *  VIDEO FIELD
 * 
 */
//show remove button "X" for file in VIDEO field
$( "#editGalleryVideo" ).change(function(event) {
    //hide add video label and show current video label
    $("#editGalleryVideoLabel_current").css({"display":"block"});
    $("#editGalleryVideoLabel").css({"display":"none"});
    
    //show remove "X" button
    $("#preview-video-edit-gallery-label").css({"display":"block"});

});

//remove file from VIDEO field
$( "#preview-video-edit-gallery-label" ).click(function() {
    
    //show add video label and hide current video label
    $("#editGalleryVideoLabel").css({"display":"block"});
    $("#editGalleryVideoLabel_current").css({"display":"none"});
  
    //reset file field and remove img src=''
        $("#editGalleryVideo").val(null);
    //hide remove "X" button for field
        $("#preview-video-edit-gallery-label").css({"display":"none"});
    //remove jquert validation error for editPostVideo
        $("#editGalleryVideo-error").css({"display":"none"});

});


//remove CURRENT VIDEO -  remove button "X" for CURRENT video
$( "#preview-video-edit-gallery-label_current" ).click(function() {
    
    //hide current video
    $('#editGalleryVideo_current').css({"display":"none"});
    //hide current label
    $('#editGalleryVideoLabel_current').css({"display":"none"});
    
    //show add video label editGalleryVideoLabel
    $('#editGalleryVideoLabel').css({"display":"block"});
    //show input video
    $("#editGalleryVideo").val(null);
    $("#editGalleryVideo").css({"display":"block"});
    
    //remove video - set true
    $("#edit_gallery_remove_video").attr("checked", true);

});


//remove PHOTO from CURRENT gallery
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



// add extra PHOTO to gallery
$("#add-photo-gallery-button-edit").click(function(e){ //on add input button click
        
        e.preventDefault();
        //    alert('add photo to gallery');
        
        // max size of gallery
        var max_fields = 15;
        
        //get current gallery size
        var current_gallery_size = $('#current_gallery_wrap div').length;        
        //get size of added to gallery
        var added_gallery_size = $('#input_fields_wrap_edit_gallery div').length;
            //alert('Current gallery size: ' + current_gallery_size + '\n\
            //    Added gallery size: ' + added_gallery_size);
    
        //total gallery size
        var totalGallerySize = current_gallery_size + added_gallery_size;
            //alert('Total size of gallery: ' + totalGallerySize);
        
        //get last index used
        var last_index = parseInt($('#input_fields_wrap_edit_gallery').attr("data-last-index"));
        
//        var current_gallery_wrap = document.getElementById('current_gallery_wrap');
//        var currentGalleryCount = current_gallery_wrap.getElementsByTagName('div').length;

        var index = 0; //initlal text box count
        if(totalGallerySize < max_fields){ //max input box allowed
            index = last_index + 1;
            //alert('index: ' + index);
            $('#input_fields_wrap_edit_gallery').append('<div class="post-gallery">\n\
                                <img class="preview-img" id="preview-img-edit-gallery-'+index+'" />\n\
                                <input type="file" name="editGalleryFile"  id="'+index+'" onchange="preview_image_edit_gallery(this, event)" />\n\
                                <label class="preview-img-label" id="preview-img-edit-gallery-label-'+index+'" for="preview-img-edit-gallery-'+index+'"><a href="#" class="remove_photo_edit">X</a></label>\n\
                             </div>'); //add input box
            //set new last_index
            $('#input_fields_wrap_edit_gallery').attr('data-last-index', index++);
        }else{
            //show error modal
                $("#error-post-modal").css({"display":"block"});
            //insert error-information
                $("#error-input-file").html("Gallery is full.");
            //insert error-description
                $("#error-description-input-file span").html('The maximum size of the gallery is '+max_fields+' photos.'); 
        }
        
        //if more then 0 post-gallery show gallery input_fields_wrap_edit_gallery and input_fields_wrap_edit_gallery_label
        if(index>0){
            //show input_fields_wrap_label & input_fields_wrap
            $("#input_fields_wrap_edit_gallery_label").css({"display":"block"});
            $("#input_fields_wrap_edit_gallery").css({"display":"block"});
        }
      
});

//remove PHOTO from EXTRA gallery
$('#input_fields_wrap_edit_gallery').on("click",".remove_photo_edit", function(e){  
        
     e.preventDefault();
//     alert('remove photo');
     
        var label = $(this).parent('label');
        var div_to_remove = label.parent('div');
        //var pictureID = div_to_remove[0].getAttribute('data-pictureID');
        //alert(pictureID);
        //$("ul#picture_to_remove_from_post").append('<li data-pictureID='+pictureID+'></li>');
        div_to_remove.remove();
        
        //get current gallery size
        var numberOfAddedPhotos = $('#input_fields_wrap_edit_gallery div').length;
        if(numberOfAddedPhotos === 0){
            $('#input_fields_wrap_edit_gallery_label').css({"display":"none"});
            $('#input_fields_wrap_edit_gallery').css({"display":"none"});
        }
});

function preview_image_edit_gallery(inputFile, event) {
    
        var inputID = inputFile.getAttribute('id');   
        image = document.getElementById('preview-img-edit-gallery-'+inputID);

    // validation if photo and photo size
    var isValid = checkValidationForEditGalleryImage(event.target.files[0]);
    
    if(isValid === null){
        //remove addPostFile file field
            $("input#"+inputID).css({"display":"none"});
        // add src to image
            image.src = URL.createObjectURL(event.target.files[0]);
        //show image
            $("#preview-img-edit-gallery-"+inputID).css({"display":"block"});
        //disply 'X' button - remove img 
            $("#preview-img-edit-gallery-label-"+inputID).css({"display":"block"});
        //remove default background from post-gallery
            $("#preview-img-edit-gallery-"+inputID).parent('div').css({"background":"none"});

    }else{
        //show error modal
            $("#error-post-modal").css({"display":"block"});
        //insert error-information
            $("#error-input-file").html("You can not add this file to gallery.");
        //insert error-description
            $("#error-description-input-file span").html(isValid); 
        //remove file from input
            $("input#"+inputID).val(null);
        
    }
        

}

function checkValidationForEditGalleryImage(file){
    
    var isValid = null;
    var fileType = file["type"];
    
    //validation for file type;
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        return isValid = 'Allowed file extensions: jpg, jpeg, png, gif';
    }
    
    //validation for file size max. 4MB
    var fileSize =  file["size"];
    //alert ("File size: " + fileSize);
    
    if(fileSize > 4000000){
        return isValid = 'File size must be less than 4MB.';
    }
    
    
    return isValid;
    
}

//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


