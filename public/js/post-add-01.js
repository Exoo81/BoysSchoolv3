/* 
 * Add Post
 */

//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

//open modal with form
$(".modal-trigger-add-post").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addPostForm").reset();
    
    //hidde 'Current document' Label
        $("#addPostDocLabel_current").css({"display":"none"});
    //show 'Add doc' Label
        $("#addPostDocLabel").css({"display":"block"});
    //remove jquert validation error for doc field - addPostDoc
        $("#addPostDoc-error").css({"display":"none"});
    //hide remove 'X' button
        $("#preview-doc-add-post-label").css({"display":"none"});
        
    //hidde 'Current video' Label
        $("#addPostVideoLabel_current").css({"display":"none"});
    //show 'Add video' Label
        $("#addPostVideoLabel").css({"display":"block"});
    //remove jquert validation error for video field - addPostVideo
        $("#addPostVideo-error").css({"display":"none"});
    //hide remove 'X' button
        $("#preview-video-add-post-label").css({"display":"none"});
        
    //remove all post-gallery div's
        $(".post-gallery").remove();
    //reset lase index from gallery field data-last-index
        $("#input_fields_wrap").attr("data-last-index",0);
    //hide input_fields_wrap_label & input_fields_wrap
        $("#input_fields_wrap_label").css({"display":"none"});
        $("#input_fields_wrap").css({"display":"none"});
    
    
    
    dataModal = $(this).attr("data-modal");

    var blogId = $(this).attr("data-blogID");
    var teacherID = $(this).attr("data-teacherID");
    var learningSupportID = $(this).attr("data-learningSupportID");
    var authorID = $(this).attr("data-authorID");
    
    //insert blogID
    $('#addPost_blogID').val(blogId);
    //insert teacherID
    $('#addPost_teacherID').val(teacherID);
    //insert learningSupportID
    $('#addPost_learningSupportID').val(learningSupportID);
    //insert authorID
    $('#addPost_authorID').val(authorID);
   
//    console.log('blog id: '+blogId);
//    console.log('teacher id: '+teacherID);
//    console.log('learningSupport id: '+ learningSupportID);
//    console.log('author id: '+ authorID);

    //display form
    $("#addPostForm").css({"display":"block"});
    

    
    
    
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addPostForm").validate({
    rules: {
        addPost_blogID:{
            required: true, 
        },
        addPost_teacherID:{
            required: true,
        },
        addPost_authorID:{
            required: true,
        },
        addPostTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addPostContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        addPostDoc: {              //input name: content
            required: false,   
            extension: "pdf|docx|doc",
            filesize: 4000000           // 4MB
        },
        addPostVideo: {              //input name: content
            required: false,   
            accept: "video/*",
            filesize: 300000000           // 300MB raw file before commpression
        },
        addPostFile: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 4000000           // 4MB
        }
        
      
    },
    messages:{
        addPostFile:{
            extension: "Allowed file extensions:<br> jpg, jpeg, png, gif",
            filesize: "File size must be<br> less than 4MB"
        },
        addPostDoc:{
            extension: "Allowed file extensions: pdf, docx, doc",
            filesize: "File size must be less than 4MB"
        },
        addPostVideo:{
            accept: "Allowed only video file.",
            filesize: "The video file is too big."
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addPostForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var postTitle = $("#addPostTitle").val();
    var postContent = $("#addPostContent").val();
    var blogID = $("#addPost_blogID").val();
    var teacherID = $("#addPost_teacherID").val();
    var learningSupportID = $("#addPost_learningSupportID").val();
    var authorID = $("#addPost_authorID").val();
    
    if(learningSupportID === ""){
        learningSupportID = null;
    }
    

    var addPostDoc = document.querySelector('#addPostDoc').files[0];

    var addPostVideo = document.querySelector('#addPostVideo').files[0];
    
    
    //number of inputs in input_fields_wrap
    var gallerySize = $('#input_fields_wrap input').length;
    
//    console.log('--=== ADD POST DATA ==--');
//    console.log('postTitle: ' + postTitle);
//    console.log('postContent: ' + postContent);
//    console.log('blogID: ' + blogID);
//    console.log('postDoc:' + addPostDoc);
//    console.log('postVideo: ' + addPostVideo);
//    console.log('gallery size: ' + gallerySize);
//    console.log('teacher ID: '+ teacherID);
//    console.log('learningSupport ID: '+ learningSupportID);
//    console.log('author ID: '+ authorID);
//    console.log('--====================--');
    
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({postTitle:postTitle, postContent:postContent, blogID:blogID, teacherID:teacherID, learningSupportID:learningSupportID, authorID:authorID});

    formData.append('objArr', JSON.stringify(objArr));
    
    formData.append('addPostDoc', addPostDoc);
    
    formData.append('addPostVideo', addPostVideo);
    
    if(gallerySize > 0){
        var count = 0;
        $("#input_fields_wrap .post-gallery > input").each(function() {
            //if input field has photo check if .post-gallery img has attr src
            var imgAttr = $(this).closest('.post-gallery').find('img').attr('src');
            if(typeof imgAttr !== typeof undefined && imgAttr !== false){
//              console.log( 'input id: ' + $(this).attr("id") );
                var inputFile = $(this)[0].files[0];
//              console.log( 'inputFile : ' + inputFile );
                formData.append('file'+count , inputFile);
                count ++;
            }
        });
    }

    
    $.ajax({
        url: 'classblog/addpost',                   
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
            //console.log(data);
            if(data.success === true){
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

//show 'Current doc' Label and show 'remove X button' for doc field after file loaded
$( "#addPostDoc" ).change(function(event) {
    
    //show 'Current doc' Label and hide 'Add doc' Label
    $("#addPostDocLabel_current").css({"display":"block"});
    $("#addPostDocLabel").css({"display":"none"});
    
    //show 'remove X button'
    $("#preview-doc-add-post-label").css({"display":"block"});
});

//remove file from doc field - "X"
$( "#preview-doc-add-post-label" ).click(function() {
    
    //show 'Add doc' Label and hide 'Current doc' Label
    $("#addPostDocLabel").css({"display":"block"});
    $("#addPostDocLabel_current").css({"display":"none"});
    

    //reset doc field 
        $("#addPostDoc").val(null);
    //hide 'remove X button'
        $("#preview-doc-add-post-label").css({"display":"none"});
    //remove not-falid class
        //$("#preview-doc-add-news-label").removeClass("not-valid");
    //remove jquert validation error for doc field - addPostDoc
        $("#addPostDoc-error").css({"display":"none"});
});

//show 'Current video' Label and show 'remove X button' for video field after file loaded
$( "#addPostVideo" ).change(function(event) {
    
    //show 'Current video' Label and hide 'Add video' Label
    $("#addPostVideoLabel_current").css({"display":"block"});
    $("#addPostVideoLabel").css({"display":"none"});
    
    //show 'remove X button'
    $("#preview-video-add-post-label").css({"display":"block"});
});

//remove file from video field - "X"
$( "#preview-video-add-post-label" ).click(function() {
    
    //show 'Add video' Label and hide 'Current video' Label
    $("#addPostVideoLabel").css({"display":"block"});
    $("#addPostVideoLabel_current").css({"display":"none"});
    

    //reset video field 
        $("#addPostVideo").val(null);
    //hide 'remove X button'
        $("#preview-video-add-post-label").css({"display":"none"});
    //remove not-falid class
        //$("#preview-doc-add-news-label").removeClass("not-valid");
    //remove jquert validation error for doc field - addPostDoc
        $("#addPostVideo-error").css({"display":"none"});
});


/*
 *  Gallery builder
 */
//add next photo field for galllery
$( "#add_file_button" ).click(function() {
    
    var max_fields = 12;
    
    //get current gallery size
    var current_gallery_size = $('#input_fields_wrap div').length;
    //get last index used
    var last_index = parseInt($('#input_fields_wrap').attr("data-last-index"));
//    alert('current_gallery_size: ' + current_gallery_size);
//    alert('last_index: ' + last_index);
    
    
    var index = 0; //initlal text box count
    if(current_gallery_size < max_fields){ //max input box allowed
        //increment index 
        index = last_index + 1;
        $('#input_fields_wrap').append('<div class="post-gallery">\n\
                                <img class="preview-img" id="preview-img-add-post-'+index+'" />\n\
                                <input type="file" name="addPostFile" id="'+index+'" onchange="preview_image(this, event)" />\n\
                                <label class="preview-img-label" id="preview-img-add-post-label-'+index+'" for="preview-img-add-post-'+index+'"><a href="#" class="remove_field">X</a></label>\n\
                             </div>'); //add input box
        //set new last_index
        $('#input_fields_wrap').attr('data-last-index', index++);
    }else{
        //show error modal
            $("#error-post-modal").css({"display":"block"});
        //insert error-information
            $("#error-input-file").html("Gallery is full.");
        //insert error-description
            $("#error-description-input-file span").html('The maximum size of the gallery is 12 photos.'); 
    }
    
    //if more then 0 post-gallery show gallery input_fields_wrap and label
    if(index>0){
        //show input_fields_wrap_label & input_fields_wrap
        $("#input_fields_wrap_label").css({"display":"block"});
        $("#input_fields_wrap").css({"display":"block"});
    }

        
        
    //remove post-gallery box (ADD POST)
    $('#input_fields_wrap').on("click",".remove_field", function(e){ //user click on remove text //user click on remove text

        var label = $(this).parent('label');
        label.parent('div').remove(); 
                
        //check size of gallery
        //if 0 hide input_fields_wrap_label & input_fields_wrap
        var countPostGallery = $('#input_fields_wrap div').length;
            if(countPostGallery === 0){
                $("#input_fields_wrap_label").css({"display":"none"});
                $("#input_fields_wrap").css({"display":"none"});
            }
    });
    
});

function preview_image(inputFile, event) {
    
    var inputID = inputFile.getAttribute('id');
    image = document.getElementById('preview-img-add-post-'+inputID);
    
    // validation if photo and photo size
    var isValid = checkValidationForImage(event.target.files[0]);
    
    if(isValid === null){
        //remove addPostFile file field
            $("input#"+inputID).css({"display":"none"});
        // add src to image
            image.src = URL.createObjectURL(event.target.files[0]);
        //show image
            $("#preview-img-add-post-"+inputID).css({"display":"block"});
        //disply remove img button
            $("#preview-img-add-post-label-"+inputID).css({"display":"block"});
        //remove default background from post-gallery
            $("#preview-img-add-post-"+inputID).parent('div').css({"background":"none"});

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


function checkValidationForImage(file){
    
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
        return isValid = 'File size must be less than 4MB';
    }
    
    
    return isValid;
    
}





