/* 
 * Edit Post
 */

// open modal with news data
$(".modal-trigger-edit-post").click(function(e){
    
    e.preventDefault();
    
    //show laoder
    $(".loader").css({"display":"block"});
    
    //clear msg label
    $(".response-msg").html(''); 
    //reset form
    document.getElementById("editPostForm").reset();
    
    //set edit_post_remove_doc as false 
    $("#edit_post_remove_doc").attr("checked", false);
    //set edit_post_remove_video as false 
    $("#edit_post_remove_video").attr("checked", false);
    
    //clear current_gallery_wrap_class_post
    $('#current_gallery_wrap_class_post').empty();
    //clear list of photos to remove from current gallery
    $("ul#picture_to_remove_from_post").empty();
    
    //set default last index for added gallery
    $('#input_fields_wrap_edit_class_post').attr('data-last-index', 0);
    
    //clear all post-gallery from added gallery
    $("#input_fields_wrap_edit_class_post").empty();

    //hidde form
    $("#editPostForm").css({"display":"none"});
    //hide all post document fields
    $("#editPostDocLabel").css({"display":"none"});
    $("#editPostDoc").css({"display":"none"});
    $("#editPostDocLabel_current").css({"display":"none"});
    $("#editPostDoc_current").css({"display":"none"});
    //hide all post video fields
    $("#editPostVideoLabel").css({"display":"none"});
    $("#editPostVideo").css({"display":"none"});
    $("#editPostVideoLabel_current").css({"display":"none"});
    $("#editPostVideo_current").css({"display":"none"});
    //hide input_fields_wrap_label & input_fields_wrap
    $("#input_fields_wrap_edit_class_post_label").css({"display":"none"});
    $("#input_fields_wrap_edit_class_post").css({"display":"none"});
    
    
    //read pass data
    dataModal = $(this).attr("data-modal");
    
    var postId = $(this).attr("data-postID");
    var learningSupportID = $(this).attr("data-learningSupportID");
    var teacherID = $(this).attr("data-teacherID");
    var authorID = $(this).attr("data-authorID");
    
    if(learningSupportID === ""){
        learningSupportID = null;
    }
    
//    console.log('--= Get edit post =--');
//    console.log('postID: ' + postId);
//    console.log('teacherID: ' + teacherID);
//    console.log('learningSupportID: ' + learningSupportID);
//    console.log('authorID: ' + authorID);
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
//    var base_url = window.location.origin;
    
    //send id by AJAX to get full object
    $.ajax({
        url: 'classblog/geteditpost',
        type:'POST',
        data:{postId:postId, teacherID:teacherID, learningSupportID:learningSupportID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                

                //inster data to fields
                $("#editPostID").val(data.postToEdit.id);
                $("#editPostTitle").val(data.postToEdit.title);
                $("#editPostContent").val(data.postToEdit.content);
                $("#editPost_teacherID").val(teacherID);
                $("#editPost_learningSupportID").val(learningSupportID);
                $("#editPost_authorID").val(authorID);
                
                // if post document exist
                if(data.postToEdit.docName !== null){
                    
                    //show label for current doc
                    $("#editPostDocLabel_current").css({"display":"block"});
                    //show icon + doc title + remove
                    $("#editPostDoc_current").css({"display":"block"});
                    //insert doc title
                    $("#editPostDocTitle").html(data.postToEdit.docName);
                }else{  
                    //show input field label for document
                    $("#editPostDocLabel").css({"display":"block"});
                    //show input field for document
                    $("#editPostDoc").css({"display":"block"});
                }
                
                // if post video exist
                if(data.postToEdit.videoName !== null){
                  
                    //show label for current video
                    $("#editPostVideoLabel_current").css({"display":"block"});
                    //show icon + video title + remove
                    $("#editPostVideo_current").css({"display":"block"});
                    //insert video title
                    $("#editPostVideoTitle").html(data.postToEdit.videoName);
                }else{
                    //show input field label for video
                    $("#editPostVideoLabel").css({"display":"block"});
                    //show input field for document
                    $("#editPostVideo").css({"display":"block"});
                }

                //if post with photo
                if(!(jQuery.isEmptyObject(data.pictureList))){
//                    $(".response-msg").html('not empty');
                    $.each(data.pictureList, function (key, value){
                        $('#current_gallery_wrap_class_post').append('<div class="post-gallery" data-pictureID="'+key+'">\n\
                                                                <img class="preview-img" id="preview-img-edit-post" src="'+value+'" />\n\
                                                                <label class="preview-img-label" id="preview-img-edit-post-label" for="preview-img-edit-post">\n\
                                                                    <a href="#" class="remove_photo_gallery_current" id="remove_photo_gallery_current">X</a>\n\
                                                                </label>\n\
                                                           </div>');
                    });
 
                    //show current gallery
                        $("#current_gallery_wrap_class_post").css({"display":"inline-block"});
                    //show current gallery label
                        $("#current_gallery_wrap_class_post_label").css({"display":"block"});
                    //show remove link
                        $(".preview-img-label").css({"display":"block"});
                }else{
                    //hide current gallery
                    $("#current_gallery_wrap_class_post").css({"display":"none"});
                    //hide current gallery label
                    $("#current_gallery_wrap_class_post_label").css({"display":"none"});
                    
                }
                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#editPostForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
//                $("#editNewsForm").css({"display":"none"});
                //hide laoder
                $(".loader").css({"display":"none"});
            }
            return false;
        }
    });
    
});

//after submit
$("#editPostForm").validate({
    rules: {
        editPostID: {
            required: true
        },
        editPost_teacherID: {
            required: true
        },
        editPost_authorID: {
            required: true
        },
        editPostTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editPostContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        editPostDoc: {
            required: false,
            extension: "pdf|docx|doc"
            //filesize: 10000000           // 4MB
        },
        editPostVideo: {
            required: false,   
            accept: "video/*"
            //filesize: 10000000           // 4MB
        },
        editPostFile: {
            required: false,
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        }
    },
    messages:{
        editPostFile:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
        },
        editPostDoc:{
            extension: "Allowed file extensions: pdf, docx, doc"
        },
        editPostVideo:{
            accept: "Allowed only video file."
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editPostForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');
        
        //get fields values from form
        var editPostID = $("#editPostID").val();
        var editPostTitle = $("#editPostTitle").val();
        var editPostContent = $("#editPostContent").val();
        var removeDoc = $('#edit_post_remove_doc').attr('checked');
        if(removeDoc === 'checked'){
            removeDoc = true;
        }else{
            removeDoc = false;
        }
        
        var removeVideo = $('#edit_post_remove_video').attr('checked');
        if(removeVideo === 'checked'){
            removeVideo = true;
        }else{
            removeVideo = false;
        }
        var editPostTeacherID = $("#editPost_teacherID").val();
        var editPostLearningSupportID = $("#editPost_learningSupportID").val();
        var editPostAuthorID = $("#editPost_authorID").val();
        
        //get document field
        var editPostDoc = document.querySelector('#editPostDoc').files[0];
        //get video field
        var editPostVideo = document.querySelector('#editPostVideo').files[0];
        
//            console.log('-- =Edit post ==-');
//            console.log('editPostID: ' + editPostID);
//            console.log('editPostTitle: ' + editPostTitle);
//            console.log('editPostContent: ' + editPostContent);
//            console.log('editPostDoc: ' + editPostDoc);
//            console.log('editPostVideo: ' + editPostVideo);
//            console.log('removeDoc: ' + removeDoc);
//            console.log('removeVideo: ' + removeVideo);
//            console.log('TeacherID: ' + editPostTeacherID);
//            console.log('LearningSupportID: ' + editPostLearningSupportID);
//            console.log('AuthorID: ' + editPostAuthorID);
        
        //list of picture to remove from current saved gallery
        var picture_to_remove = [];
        
        $('ul#picture_to_remove_from_post li').each(function(i){
            var pictureID = $(this).attr('data-pictureID'); // This is your rel value
            picture_to_remove.push(pictureID);
//            console.log( 'Photo ID to remove: ' + pictureID );
        });
        
        //number of inputs in input_fields_wrap
        //var inputCount = document.getElementById('input_fields_wrap_edit').getElementsByTagName('input').length;
        var inputCount = $('#input_fields_wrap_edit_class_post div').length;
//            console.log( 'No. of added photos: ' + inputCount );  
     
        var formData = new FormData();
        
        var objArr = [];
        objArr.push({"editPostID":editPostID, 
                        "editPostTitle":editPostTitle, 
                        "editPostContent":editPostContent, 
                        "removeDoc":removeDoc, 
                        "removeVideo":removeVideo,
                        "editPostTeacherID":editPostTeacherID,
                        "editPostLearningSupportID":editPostLearningSupportID,
                        "editPostAuthorID":editPostAuthorID
                    });
        
        formData.append('objArr', JSON.stringify(objArr));
        formData.append('picture_to_remove', JSON.stringify(picture_to_remove));
        
        formData.append('editPostDoc', editPostDoc);
        formData.append('editPostVideo', editPostVideo);
        
        if(inputCount > 0){
//            console.log('---=== ADDED PHOTOS TO GALLERY ===---');
            var count = 0;
            $("#input_fields_wrap_edit_class_post .post-gallery > input").each(function() {
                //if input field has photo check if .post-gallery img has attr src
                var imgAttr = $(this).closest('.post-gallery').find('img').attr('src');
                if(typeof imgAttr !== typeof undefined && imgAttr !== false){
//                  console.log( 'New input id for picture: ' + $(this).attr("id") );
                    var inputFile = $(this)[0].files[0];
//                  console.log( 'New picture added : ' + inputFile );
                    formData.append('file'+count , inputFile);
                    count ++;
                }
            });
        }
        
        
        $.ajax({
            url: '../../../../classblog/editpost',          //??
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(data){
                //console.log(data);
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



// remove document from edit form 
$( "#remove_field_doc" ).click(function(e) {
        
        e.preventDefault();
//        alert('remove doc');

        //remove current label and file name + icon
        $("#editPostDocLabel_current").css({"display":"none"});
        $("#editPostDoc_current").css({"display":"none"});
        
        //show choose field with label
        $("#editPostDocLabel").css({"display":"block"});
        $("#editPostDoc").css({"display":"block"});
        
        //check old file "to remove"
        $("#edit_post_remove_doc").attr("checked", true);
 
});

// remove video from edit form
$( "#remove_field_video_class_post" ).click(function(e) { 

        e.preventDefault();
//        alert('remove video');

        //remove current label and file name + icon
        $("#editPostVideoLabel_current").css({"display":"none"});
        $("#editPostVideo_current").css({"display":"none"});

        //show choose field with label
        $("#editPostVideoLabel").css({"display":"block"});
        $("#editPostVideo").css({"display":"block"});
        
        //check old file "to remove"
        $("#edit_post_remove_video").attr("checked", true);
 
});

//remove photo from current gallery (post-edit) - build list to remove
$('#current_gallery_wrap_class_post').on("click",".remove_photo_gallery_current", function(e){  
        
     e.preventDefault();
//     alert('remove photo');
     
        var label = $(this).parent('label');
        var div_to_remove = label.parent('div');
        var pictureID = div_to_remove[0].getAttribute('data-pictureID');
        //alert(pictureID);
        $("ul#picture_to_remove_from_post").append('<li data-pictureID='+pictureID+'></li>');
        div_to_remove.remove();
        
        //get current gallery size
        var current_gallery_size = $('#current_gallery_wrap_class_post div').length;
        if(current_gallery_size === 0){
            $('#current_gallery_wrap_class_post_label').css({"display":"none"});
            $('#current_gallery_wrap_class_post').css({"display":"none"});
        }
});

//add post-gallery box to gallery (EDIT POST - add extra photo to post gallery)
$("#add-photo-gallery-button-edit-class-post").click(function(e){ //on add input button click
        
        e.preventDefault();
            //alert('add photo to gallery');
        
        // max size of gallery
        var max_fields = 20;
        
        //get current gallery size
        var current_gallery_size = $('#current_gallery_wrap_class_post div').length;        
        //get size of added to gallery
        var added_gallery_size = $('#input_fields_wrap_edit_class_post div').length;
            //alert('Current gallery size: ' + current_gallery_size + '\n\
            //    Added gallery size: ' + added_gallery_size);
    
        //total gallery size
        var totalGallerySize = current_gallery_size + added_gallery_size;
            //alert('Total size of gallery: ' + totalGallerySize);
        
        //get last index used
        var last_index = parseInt($('#input_fields_wrap_edit_class_post').attr("data-last-index"));
        
//        var current_gallery_wrap = document.getElementById('current_gallery_wrap');
//        var currentGalleryCount = current_gallery_wrap.getElementsByTagName('div').length;

        var index = 0; //initlal text box count
        if(totalGallerySize < max_fields){ //max input box allowed
            index = last_index + 1;
            //alert('index: ' + index);
            $('#input_fields_wrap_edit_class_post').append('<div class="post-gallery">\n\
                                <img class="preview-img" id="preview-img-edit-post-'+index+'" />\n\
                                <input type="file" name="editPostFile"  id="'+index+'" onchange="preview_image_edit(this, event)" />\n\
                                <label class="preview-img-label" id="preview-img-edit-post-label-'+index+'" for="preview-img-edit-post-'+index+'"><a href="#" class="remove_photo_edit">X</a></label>\n\
                             </div>'); //add input box
            //set new last_index
            $('#input_fields_wrap_edit_class_post').attr('data-last-index', index++);
        }else{
            alert('Gallery is full.');
        }
        
        //if more then 0 post-gallery show gallery input_fields_wrap_edit_class_post and input_fields_wrap_edit_class_post_label
        if(index>0){
            //show input_fields_wrap_label & input_fields_wrap
            $("#input_fields_wrap_edit_class_post_label").css({"display":"block"});
            $("#input_fields_wrap_edit_class_post").css({"display":"block"});
        }
      
});




//remove photo from added gallery (post-edit) - build list to remove
$('#input_fields_wrap_edit_class_post').on("click",".remove_photo_edit", function(e){  
        
     e.preventDefault();
     //alert('remove photo');
     
        var label = $(this).parent('label');
        var div_to_remove = label.parent('div');
        //var pictureID = div_to_remove[0].getAttribute('data-pictureID');
        //alert(pictureID);
        //$("ul#picture_to_remove_from_post").append('<li data-pictureID='+pictureID+'></li>');
        div_to_remove.remove();
        
        //get current gallery size
        var numberOfAddedPhotos = $('#input_fields_wrap_edit_class_post div').length;
        if(numberOfAddedPhotos === 0){
            $('#input_fields_wrap_edit_class_post_label').css({"display":"none"});
            $('#input_fields_wrap_edit_class_post').css({"display":"none"});
        }
});


//// remove video from edit form
//$( "input" ).click(function(e) { 
//    
//    alert('dddd');
//
////        e.preventDefault();
//////        alert('remove video');
////
////        //remove current label and file name + icon
////        $("#editPostVideoLabel_current").css({"display":"none"});
////        $("#editPostVideo_current").css({"display":"none"});
////
////        //show choose field with label
////        $("#editPostVideoLabel").css({"display":"block"});
////        $("#editPostVideo").css({"display":"block"});
////        
////        //check old file "to remove"
////        $("#edit_post_remove_video").attr("checked", true);
// 
//});


function preview_image_edit(inputFile, event) { 
    
        inputID = inputFile.getAttribute('id');


           
        image = document.getElementById('preview-img-edit-post-'+inputID);
        image.src = URL.createObjectURL(event.target.files[0]);

        
        $("#preview-img-edit-post-"+inputID).css({"display":"block"});
        $("#preview-img-edit-post-label-"+inputID).css({"display":"block"});
        
        //remove default background from post-gallery
        $("#preview-img-edit-post-"+inputID).parent('div').css({"background-image":"none"});
        //hide input field
        $("input#"+inputID).css({"display":"none"});
        //change post-gallery background on transparent
        var post_gallery = $("#preview-img-edit-post-"+inputID).parent('div');
            post_gallery.css({"background":"none"});
        

}


//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/






