/* 
 * Add News
 */

//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');


//open modal with form
$(".modal-trigger-add-news").click(function(e){
    
    e.preventDefault();

    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addNewsForm").reset();
    
    //hide "current News doc" label
    $("#addNewsDocLabel_current").css({"display":"none"});
    //show "add News doc" label
    $("#addNewsDocLabel").css({"display":"block"});
    
    //hide 'X' doc button
    $("#preview-doc-add-news-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-doc-add-news-label").removeClass("not-valid");
    
    
    
    //hide "current News photo" label
    $("#addNewsPhotoLabel_current").css({"display":"none"});
    //show "add News photo" label
    $("#addNewsPhotoLabel").css({"display":"block"});
    
    //restet preview photo field
    $("#addNewsPhoto").val(null);
    //show addNewsPhoto field
    $("#addNewsPhoto").css({"display":"block"});
        //remove jquert validation error for addNewsPhoto
        $("#addNewsPhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $('#preview-img-add-news').removeAttr('src');
    //hide preview image
    $("#preview-img-add-news").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-add-news-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-add-news-label").removeClass("not-valid");

    
    //get data
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    var blogID = $(this).attr("data-blogID");  
//    console.log('authorID: ' + authorID);
//    console.log('blog ID: ' + blogID);
    
    //insert data
    $('#addNewsAuthorID').val(authorID);
    $('#addNewsBlogID').val(blogID);
    
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addNewsForm").validate({
    rules: {
        addNewsAuthorID: {
            required: true
        },
        addNewsBlogID: {
            required: true
        },
        addNewsTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addNewsContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        addNewsPhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 6000000           // 6MB
        },
        addNewsDoc: {              //input name: content
            required: false,   
            extension: "pdf|docx|doc",
            filesize: 4000000           // 4MB
        }
      
    },
    messages:{
        addNewsPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 6MB"
        },
        addNewsDoc:{
            extension: "Allowed file extensions: pdf, docx, doc",
            filesize: "File size must be less than 4MB"
        }
    },
            
    submitHandler: function() {
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#addNewsForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');

        var newsTitle = $("#addNewsTitle").val();
        var newsContent = $("#addNewsContent").val();

        var newsDoc = document.querySelector('#addNewsDoc').files[0];

        var newsPhoto = document.querySelector('#addNewsPhoto').files[0];

        var authorID = $("#addNewsAuthorID").val();
        
        var blogID = $("#addNewsBlogID").val();

//    console.log('newsTitle: ' + newsTitle);
//    console.log('newsContent: ' + newsContent);
//    console.log('newsDoc: ' + newsDoc);
//    console.log('newsPhoto: ' + newsPhoto);
//    console.log('authorID: ' + authorID);
//    console.log('blog ID: ' + blogID);

        var formData = new FormData();

        var objArr = [];
        objArr.push({"newsTitle":newsTitle, "newsContent":newsContent, "authorID":authorID, blogID:blogID});

        formData.append('objArr', JSON.stringify(objArr));

        formData.append('newsDoc', newsDoc);

        formData.append('newsPhoto', newsPhoto);

        $.ajax({
            url: 'application/addnews',
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

//show 'Current doc' Label and remove 'X' button for doc field after file loaded
$( "#addNewsDoc" ).change(function(event) {
    
    //show current doc label and hide add doc label
    $("#addNewsDocLabel_current").css({"display":"block"});
    $("#addNewsDocLabel").css({"display":"none"});
    
    $("#preview-doc-add-news-label").css({"display":"block"});
});

//remove file from doc field - "X"
$( "#preview-doc-add-news-label" ).click(function() {
    
    //show 'Add doc' Label and hide 'Current doc' Label
    $("#addNewsDocLabel").css({"display":"block"});
    $("#addNewsDocLabel_current").css({"display":"none"});
    

    //reset doc field 
        $("#addNewsDoc").val(null);
    //hide remove link
        $("#preview-doc-add-news-label").css({"display":"none"});
    //remove not-falid class
        $("#preview-doc-add-news-label").removeClass("not-valid");
    //remove jquert validation error for addNewsDoc
        $("#addNewsDoc-error").css({"display":"none"});
});

//preview photo loaded in file field in form
$( "#addNewsPhoto" ).change(function(event) {
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-add-news');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    //hide "add News photo" label AND show "current news photo" label
    $("#addNewsPhotoLabel").css({"display":"none"});
    $("#addNewsPhotoLabel_current").css({"display":"block"});
    
    
    // validation if photo and photo size
    var isValid = checkValidationForAddNewsImage(event.target.files[0]); 
    
    if(isValid){
        //remove addNewsPhoto file field
        $("#addNewsPhoto").css({"display":"none"});
        // show img
        $("#preview-img-add-news").css({"display":"block"});
        //remove not-falid class
        $("#preview-img-add-news-label").removeClass("not-valid");
        //hide error info if exist
        $("#addNewsPhoto-error").css({"display":"none"});
    }else{
        $("#addNewsPhoto").css({"display":"block"});
        //add not-valid class
        $("#preview-img-add-news-label").addClass("not-valid");
        //show error info if exist
        $("#addNewsPhoto-error").css({"display":"block"});
    }

    //disply remove img button (X)
    $("#preview-img-add-news-label").css({"display":"block"});
    
});

//remove img src + hidde "remove button"
$( "#preview-img-add-news-label" ).click(function() {
    
    //show "add News photo" label AND hide "current news photo" label
    $("#addNewsPhotoLabel").css({"display":"block"});
    $("#addNewsPhotoLabel_current").css({"display":"none"});
    
    //restet preview photo field
    $("#addNewsPhoto").val(null);
    //show addNewsPhoto file field
    $("#addNewsPhoto").css({"display":"block"});
    //remove jquert validation error for addNewsPhoto
        $("#addNewsPhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $('#preview-img-add-news').removeAttr('src');
    $("#preview-img-add-news").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-add-news-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-add-class-label").removeClass("not-valid");    

});


function checkValidationForAddNewsImage(file){
    
    var isValid = true;
    var fileType = file["type"];
    
    //validation for file type;
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
         isValid = false;
    }
    
    //validation for file size max. 4MB
    var fileSize =  file["size"];
    //alert ("File size: " + fileSize);
    
    if(fileSize > 6000000){
        isValid = false;
    }
    
    
    return isValid;
    
}

//function checkValidationForDoc(file){
//    
//    var isValid = true;
//    var fileType = file["type"];
//    //alert("Doc type: " + fileType);
//    
//    //validation for file type;
//    var validDocTypes = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
//    if ($.inArray(fileType, validDocTypes) < 0) {
//         isValid = false;
//    }
//    
//    //validation for file size max. 4MB
//    var fileSize =  file["size"];
//    //alert ("File size: " + fileSize);
//    
//    if(fileSize > 6000000){
//        isValid = false;
//    }
//    
//    
//    return isValid;
//    
//}


//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


