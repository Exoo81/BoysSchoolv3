/* 
 * Add School Life
 */
//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');


//open modal with form
$(".modal-trigger-add-school-life").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
     
    //reset form
    document.getElementById("addSchoolLifeForm").reset();
    
    //clear textarea sumernote
    $('#addSchoolLifeContent').summernote('reset');
    
    //hide "current School Life photo" label
    $("#addSchoolLifePhotoLabel_current").css({"display":"none"});
    //show "add School Life photo" label
    $("#addSchoolLifePhotoLabel").css({"display":"block"});
          
    //restet preview photo field
    $("#addSchoolLifePhoto").val(null);
    //show addClassPhoto field
    $("#addSchoolLifePhoto").css({"display":"block"});
        //remove jquert validation error for editSchoolLifePhoto
        $("#addSchoolLifePhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $('#preview-img-add-school-life').removeAttr('src');
    //hide preview image
    $("#preview-img-add-school-life").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-add-school-life-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-add-school-life-label").removeClass("not-valid");
         
         
    //get data
    dataModal = $(this).attr("data-modal"); 
    var authorID = $(this).attr("data-authorID");
    
    //insert authorID
    $('#addSchoolLife_authorID').val(authorID);

    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addSchoolLifeForm").validate({
    rules: {
        addSchoolLife_authorID: {
            required: true
        },
        addSchoolLifeTitle: {
            required: true
        },
        addSchoolLifePhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 6000000           // 6MB
        }
      
    },
    messages:{
        addSchoolLife_authorID:{
            required: 'Error - author required.'
        },
        addSchoolLifeTitle:{
            required: 'Title is required.'
        },
        addSchoolLifePhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 6MB"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addSchoolLifeForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var schoolLifeTitle = $('#addSchoolLifeTitle').val();
    var schoolLifeContent = $('#addSchoolLifeContent').val();
    var authorID = $('#addSchoolLife_authorID').val();
    
    //get input photo
        var schoolLifePhoto = document.querySelector('#addSchoolLifePhoto').files[0];
    
        
//    console.log('---=== School Life ADD Form DATA posted ===---');
//    console.log("schoolLife Title: " + schoolLifeTitle);
//    console.log("schoolLife Content: " + schoolLifeContent);
//    console.log("authorID: " + authorID);
//    console.log("schoolLife Photo: " + schoolLifePhoto);

    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({
        "schoolLifeTitle":schoolLifeTitle, 
        "schoolLifeContent":schoolLifeContent,
        "authorID":authorID
    });
    
    formData.append('objArr', JSON.stringify(objArr));
    formData.append('schoolLifePhoto', schoolLifePhoto);
    
    $.ajax({
        url: 'schoollife/addschoollife',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                //reload page
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


//preview photo loaded in file field in form
$( "#addSchoolLifePhoto" ).change(function(event) {
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-add-school-life');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    //hide "add School Life photo" label AND show "current school life photo" label
    $("#addSchoolLifePhotoLabel").css({"display":"none"});
    $("#addSchoolLifePhotoLabel_current").css({"display":"block"});
    
    
    // validation if photo file
    var isValid = checkValidationForAddSchoolLifeImage(event.target.files[0]); 
    
    if(isValid){
        //remove addSchoolLifePhoto file field
        $("#addSchoolLifePhoto").css({"display":"none"});
        // show img
        $("#preview-img-add-school-life").css({"display":"block"});        
        //remove not-falid class
        $("#preview-img-add-school-life-label").removeClass("not-valid");        
        //hide error info if exist
        $("#addSchoolLifePhoto-error").css({"display":"none"});

    }else{
        $("#editSchoolLifePhoto").css({"display":"block"});  
        //add not-valid class
        $("#preview-img-add-school-life-label").addClass("not-valid"); 
        //show error info if exist
        $("#editSchoolLifePhoto-error").css({"display":"block"});
    }
    
    //disply remove img button (X)
    $("#preview-img-add-school-life-label").css({"display":"block"});
    
    
});

//remove img src + hidde "remove button"
$( "#preview-img-add-school-life-label" ).click(function() {

    //show "add School Life photo" label AND hide "current School Life photo" label
    $("#addSchoolLifePhotoLabel").css({"display":"block"});
    $("#addSchoolLifePhotoLabel_current").css({"display":"none"});
    
    //restet preview photo field
    $("#addSchoolLifePhoto").val(null);
    //show addSchoolLifePhoto file field
    $("#addSchoolLifePhoto").css({"display":"block"});
    //remove jquert validation error for addClassPhoto
        $("#addSchoolLifePhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''       
    $('#preview-img-add-school-life').removeAttr('src');
    $("#preview-img-add-school-life").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-add-school-life-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-add-school-life-label").removeClass("not-valid");
   
});

function checkValidationForAddSchoolLifeImage(file){
    
    var isValid = true;
    var fileType = file["type"];
    
    //validation for file type;
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
         isValid = false;
    }
    
    //validation for file size max. 6MB
    var fileSize =  file["size"];
    //alert ("File size: " + fileSize);
    
    if(fileSize > 6000000){
        isValid = false;
    }
    
    
    return isValid;
    
}


