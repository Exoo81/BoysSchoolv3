/* 
 * Add School Life
 */



//open modal with form
$(".modal-trigger-add-school-life").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    
    //reset form
    document.getElementById("addSchoolLifeForm").reset();
    //clear textarea sumernote
    $('#addSchoolLifeContent').summernote('reset');
    
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addSchoolLifePhoto").val(null);
        $('#addSchoolLifePhoto_current').removeAttr('src');
    
    //remove jquert validation error for editSchoolLifePhoto
        $("#addSchoolLifePhoto-error").css({"display":"none"});
    //remove not-falid class
        $("#preview-photo-add-school-life-label").removeClass("not-valid");
        
    // hide
        $("#addSchoolLifePhoto_current").css({"display":"none"});
        $("#addSchoolLifePhotoLabel_current").css({"display":"none"});
        
        $("#preview-photo-add-school-life-label").css({"display":"none"});
    
    //show addSchoolLifePhotoLabel
    $("#addSchoolLifePhotoLabel").css({"display":"block"});
    $("#addSchoolLifePhoto").css({"display":"block"});
       
    // get data
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
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
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
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
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
    
        
    console.log('---=== School Life ADD Form DATA posted ===---');
    console.log("schoolLife Title: " + schoolLifeTitle);
    console.log("schoolLife Content: " + schoolLifeContent);
    console.log("authorID: " + authorID);
    console.log("schoolLife Photo: " + schoolLifePhoto);

    
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
            console.log(data);
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
    
    //remove jquert validation error for editSchoolLifePhoto
    $("#addSchoolLifePhoto-error").css({"display":"none"});
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('addSchoolLifePhoto_current');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    // validation if photo file
    var isValid = checkValidationForFileExtension(event.target.files[0]); 
    
    if(isValid){
        //remove "add school-life photo" label
        $("#addSchoolLifePhotoLabel").css({"display":"none"});
        //remove "add school-life photo" field
        $("#addSchoolLifePhoto").css({"display":"none"});
        // show curent photo label
        $("#addSchoolLifePhotoLabel_current").css({"display":"block"});
        // show img
        $("#addSchoolLifePhoto_current").css({"display":"block"});
        //disply remove img button
        $("#preview-photo-add-school-life-label").css({"display":"block"});
        //remove not-falid class
            $("#preview-photo-add-school-life-label").removeClass("not-valid");
    }else{
        $("#editSchoolLifePhoto").css({"display":"block"});
        //disply remove img button
        $("#preview-photo-add-school-life-label").css({"display":"block"});
        //add not-valid class
        $("#preview-photo-add-school-life-label").addClass("not-valid");

    }
    
    
    
    
});

//remove img src + hidde "remove button" for photo
$("#preview-photo-add-school-life-label").click(function() {
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addSchoolLifePhoto").val(null);
        $('#addSchoolLifePhoto_current').removeAttr('src');
        $("#addSchoolLifePhoto_current").css({"display":"none"});
        $("#addSchoolLifePhotoLabel_current").css({"display":"none"});
    //hide remove link
        $("#preview-photo-add-school-life-label").css({"display":"none"});
    
    //show "add school life photo" label
    $("#addSchoolLifePhotoLabel").css({"display":"block"});
    //show input field for photo
    $("#addSchoolLifePhoto").css({"display":"block"});
    
    //remove jquert validation error for editSchoolLifePhoto
    $("#addSchoolLifePhoto-error").css({"display":"none"});
    
    

});

function checkValidationForFileExtension(file){
    
    var isValid = true;
    var fileType = file["type"];
    
    //alert(fileType);
    var ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, ValidImageTypes) < 0) {
         isValid = false;
    }
    
    return isValid;
    
}


