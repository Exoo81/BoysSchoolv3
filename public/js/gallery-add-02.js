/* 
 * Add Gallery
 */

//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

//open modal with form
$(".modal-trigger-add-gallery").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addGalleryForm").reset();
    
    //hidde 'Current video' Label
        $("#addGalleryVideoLabel_current").css({"display":"none"});
    //show 'Add video' Label
        $("#addGalleryVideoLabel").css({"display":"block"});
    //remove jquert validation error for video field - addGalleryVideo
        $("#addGalleryVideo-error").css({"display":"none"});
    //hide remove 'X' button
        $("#preview-video-add-gallery-label").css({"display":"none"});
        
    //remove all post-gallery div's
        $(".post-gallery").remove();
    //reset lase index from gallery field data-last-index
        $("#input_fields_wrap").attr("data-last-index",0);
    //hide input_fields_wrap_label & input_fields_wrap
        $("#input_fields_wrap_label").css({"display":"none"});
        $("#input_fields_wrap").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");

    var blogId = $(this).attr("data-blogID");
    var authorID = $(this).attr("data-authorID");
    
    //insert blogID
    $('#addGallery_blogID').val(blogId);
    //insert authorID
    $('#addGallery_authorID').val(authorID);
   
//    console.log('blog id: '+blogId);
//    console.log('author id: '+ authorID);

    //display form
    $("#addGalleryForm").css({"display":"block"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addGalleryForm").validate({
    rules: {
        addGallery_blogID:{
            required: true 
        },
        addGallery_authorID:{
            required: true
        },
        addGalleryTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addGalleryContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        addGalleryVideo: {              //input name: content
            required: false,   
            accept: "video/*",
            filesize: 300000000           // 300MB raw file before commpression
        },
        addGalleryFile: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 6000000           // 6MB
        }   
    },
    messages:{
        addGalleryFile:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be<br> less than 6MB"
        },
        addGalleryVideo:{
            accept: "Allowed only video file.",
            filesize: "The video file is too big."
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addGalleryForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var galleryTitle = $("#addGalleryTitle").val();
    var galleryContent = $("#addGalleryContent").val();
    var blogID = $("#addGallery_blogID").val();
    var authorID = $("#addGallery_authorID").val();


    var addGalleryVideo = document.querySelector('#addGalleryVideo').files[0];
    
    
    //number of inputs in input_fields_wrap
    var gallerySize = $('#input_fields_wrap input').length;
    
//    console.log('---=== Gallery Form DATA posted ===---');
//    console.log('galleryTitle: ' + galleryTitle);
//    console.log('galleryContent: ' + galleryContent);
//    console.log('blogID: ' + blogID);
//    console.log('addGalleryVideo: ' + addGalleryVideo);
//    console.log('gallery size: ' + gallerySize);
//    console.log('author ID: '+ authorID);
//    console.log('---================================---');
    
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({galleryTitle:galleryTitle, 
                 galleryContent:galleryContent, 
                 blogID:blogID, 
                 authorID:authorID});

    formData.append('objArr', JSON.stringify(objArr));
      
    formData.append('addGalleryVideo', addGalleryVideo);
    
    if(gallerySize > 0){
        var count = 0;
        $("#input_fields_wrap .post-gallery > input").each(function() {
            //if input field has photo check if .post-gallery img has attr src
            var imgAttr = $(this).closest('.post-gallery').find('img').attr('src');
            if(typeof imgAttr !== typeof undefined && imgAttr !== false){
//                console.log( 'input id: ' + $(this).attr("id") );
                var inputFile = $(this)[0].files[0];
//               console.log( 'inputFile : ' + inputFile );
                formData.append('file'+count , inputFile);
            count ++;
            }
        });
    }

    
    $.ajax({
        url: 'gallery/addgallerypost',                  
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

//show 'Current video' Label and show 'remove X button' for video field after file loaded
$( "#addGalleryVideo" ).change(function(event) {
    
    //show 'Current video' Label and hide 'Add video' Label
    $("#addGalleryVideoLabel_current").css({"display":"block"});
    $("#addGalleryVideoLabel").css({"display":"none"});
    
    //show 'remove X button'
    $("#preview-video-add-gallery-label").css({"display":"block"});
});

//remove file from video field - "X"
$( "#preview-video-add-gallery-label" ).click(function() {
    
    //show 'Add video' Label and hide 'Current video' Label
    $("#addGalleryVideoLabel").css({"display":"block"});
    $("#addGalleryVideoLabel_current").css({"display":"none"});
    

    //reset video field 
        $("#addGalleryVideo").val(null);
    //hide 'remove X button'
        $("#preview-video-add-gallery-label").css({"display":"none"});
    //remove jquert validation error for doc field - addPostDoc
        $("#addGalleryVideo-error").css({"display":"none"});
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
                                <img class="preview-img" id="preview-img-add-gallery-'+index+'" />\n\
                                <input type="file" name="addGalleryFile" id="'+index+'" onchange="preview_image(this, event)" />\n\
                                <label class="preview-img-label" id="preview-img-add-gallery-label-'+index+'" for="preview-img-gallery-post-'+index+'"><a href="#" class="remove_field">X</a></label>\n\
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
    image = document.getElementById('preview-img-add-gallery-'+inputID);
    
    // validation if photo and photo size
    var isValid = checkValidationForAddGalleryImage(event.target.files[0]);
    
    if(isValid === null){
        //remove addGalleryFile file field
            $("input#"+inputID).css({"display":"none"});
        // add src to image
            image.src = URL.createObjectURL(event.target.files[0]);
        //show image
            $("#preview-img-add-gallery-"+inputID).css({"display":"block"});
        //disply remove img button
            $("#preview-img-add-gallery-label-"+inputID).css({"display":"block"});
        //remove default background from post-gallery
            $("#preview-img-add-gallery-"+inputID).parent('div').css({"background":"none"});
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

function checkValidationForAddGalleryImage(file){
    
    var isValid = null;
    var fileType = file["type"];
    
    //validation for file type;
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        return isValid = 'Allowed file extensions: jpg, jpeg, png, gif';
    }
    
    //validation for file size max. 6MB
    var fileSize =  file["size"];
    //alert ("File size: " + fileSize);
    
    if(fileSize > 6000000){
        return isValid = 'File size must be less than 6MB';
    }
    
    
    return isValid;
    
}


//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


