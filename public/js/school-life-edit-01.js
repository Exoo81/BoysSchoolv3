/* 
 * Edit School Life
 */

//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

//open modal with form
$(".modal-trigger-edit-school-life").click(function(e){
    
    e.preventDefault();
    
    //close school-life-show-info modal
    $("#show-school-life-modal").css({"display":"none"});
    
    //hide edit school life form
    $("#editSchoolLifeForm").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //clear form
    document.getElementById("editSchoolLifeForm").reset();
    
    //clear  textarea (summernote)
    $('#editSchoolLifeContent').summernote('reset');
    
    //hide "current School Life photo" label
    $("#editSchoolLifePhotoLabel_current").css({"display":"none"});
    //hide "add School Life photo" label
    $("#editSchoolLifePhotoLabel").css({"display":"none"});
    
    //restet preview photo
    $("#addSchoolLifePhoto").val(null);
    //hide editSchoolLifePhoto
    $("#editSchoolLifePhoto").css({"display":"none"});
        //remove jquert validation error for editSchoolLifePhoto
        $("#editSchoolLifePhoto-error").css({"display":"none"});
        
    //reset file field and remove img src=''
    $("#preview-img-edit-school-life").attr("src",'');
    //hide preview image
    $("#preview-img-edit-school-life").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-edit-school-life-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-edit-school-life-label").removeClass("not-valid");
    
    
    //remove photo set false
    $("#edit-school-life-remove-photo").attr("checked", false);

    //get data
    dataModal = $(this).attr("data-modal");
    var schoolLifeID = $(this).attr("data-schoolLifeID");
    var schoolLifeAuthorID = $(this).attr("data-schoolLifeAuthorID"); 
//    console.log('========Edit school life =======');
//    console.log('school life ID: ' + schoolLifeID);
//    console.log('school life author ID: ' + schoolLifeAuthorID);


    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
    
    //send id by AJAX to get full object
    $.ajax({
        url:'schoollife/getschoollife',
        type:'POST',
        data:{schoolLifeID:schoolLifeID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                
                //insert editSchoolLifeAuthorID
                $('#editSchoolLifeAuthorID').val(schoolLifeAuthorID);
                
                /**
                 * insert school life data
                 */
                $('#editSchoolLifeID').val(data.schoolLife.id);
                $('#editSchoolLifeTitle').val(data.schoolLife.title);
                $('#editSchoolLifeContent').summernote('code', data.schoolLife.content);
                
//                //insert options for editSchoolLifeStatus
//                $.each(data.schoolLife.statusList, function (key, value){
//                    $('#editSchoolLifeStatus').append($('<option></option>').attr('value', key).text(value));
//                });
//                //current status selected
//                $('#editSchoolLifeStatus option[value="'+data.status+'"]').attr('selected','selected');
                
                //if school-life with photo
                if(data.schoolLife.photoName !== null){
                    
                    //show label for current file
                    $("#editSchoolLifePhotoLabel_current").css({"display":"block"});
                    //show img + insert src of file
                    $("#preview-img-edit-school-life").attr("src",data.schoolLifePhotoPath);
                    $("#preview-img-edit-school-life").css({"display":"block"});
                    //show 'X' link
                    $("#preview-img-edit-school-life-label").css({"display":"block"});
                    
                }else{
                    
                    //show input field label for photo
                    $("#editSchoolLifePhotoLabel").css({"display":"block"});
                    //show input field for photo
                    $("#editSchoolLifePhoto").css({"display":"block"});
                }
                
                //hide laoder
                $(".loader").css({"display":"none"});
                //show edit form
                $("#editSchoolLifeForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });

});



$("#editSchoolLifeForm").validate({
    
    ignore: ":hidden:not(#summernote),.note-editable.panel-body",
     
    rules: {
        editSchoolLifeAuthorID:{
            required: true
        },
        editSchoolLifeID:{
            required: true
        },
        editSchoolLifeTitle: {
            required: true,
            maxlength: 100
        },
        editSchoolLifePhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 4000000           // 4MB
        }
    },
    messages:{
        editSchoolLifeAuthorID:{
            required: 'Error - author required.'
        },
        editSchoolLifeTitle:{
            required: 'Title is required.'
        },
        editSchoolLifePhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 4MB"
        }
    },

    
    
    submitHandler: function() {
                    
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#editSchoolLifeForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var schoolLifeID = $("#editSchoolLifeID").val();
    var authorID = $("#editSchoolLifeAuthorID").val();
    var schoolLifeTitle = $("#editSchoolLifeTitle").val();
//    var schoolLifeStatus = $('#editSchoolLifeStatus').val();
    var schoolLifeContent = $('#editSchoolLifeContent').val();
    var removePhoto = $('#edit-school-life-remove-photo').attr('checked');
        if(removePhoto === 'checked'){
            removePhoto = true;
        }else{
            removePhoto = false;
        }
    
    
    //get input photo
        var editSchoolLifePhoto = document.querySelector('#editSchoolLifePhoto').files[0];

    
//        console.log('---=== School Life edit Form DATA posted ===---');
//        console.log('School Life ID: '+ schoolLifeID);
//        console.log('School Life Author ID: '+ authorID);
//        console.log('School Life title: '+ schoolLifeTitle);
//        console.log('School Life status: '+ schoolLifeStatus);
//        console.log('School Life content: '+ schoolLifeContent);
//        console.log('removePhoto: ' + removePhoto);
//        console.log('editSchoolLifePhoto: ' + editSchoolLifePhoto);
//        console.log('---================================---');
        
    //add to dataForm
    var formData = new FormData();
    
    var objArr = [];
        objArr.push({
            "schoolLifeID":schoolLifeID,
            "authorID":authorID,
            "schoolLifeTitle":schoolLifeTitle, 
//            "schoolLifeStatus":schoolLifeStatus, 
            "schoolLifeContent":schoolLifeContent, 
            "removePhoto":removePhoto });
    
        formData.append('objArr', JSON.stringify(objArr));

        formData.append('editSchoolLifePhoto', editSchoolLifePhoto);
   
    $.ajax({
        url: 'schoollife/editschoollife',
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


//preview photo loaded in file field in form
$( "#editSchoolLifePhoto" ).change(function(event) {
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-edit-school-life');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    //hide "add School Life photo" label AND show "current school life photo" label
    $("#editSchoolLifePhotoLabel").css({"display":"none"});
    $("#editSchoolLifePhotoLabel_current").css({"display":"block"});
    
    // validation if photo file
    var isValid = checkValidationForEditSchoolLifeImage(event.target.files[0]); 
    
    if(isValid){
        //remove addSchoolLifePhoto file field
        $("#editSchoolLifePhoto").css({"display":"none"});
        // show img
        $("#preview-img-edit-school-life").css({"display":"block"});
        //remove not-falid class
        $("#preview-img-edit-school-life-label").removeClass("not-valid");
        //hide error info if exist
        $("#editSchoolLifePhoto-error").css({"display":"none"});       
    }else{  
        $("#editSchoolLifePhoto").css({"display":"block"});
        //add not-valid class
        $("#preview-img-edit-school-life-label").addClass("not-valid");
        //show error info if exist
        $("#editSchoolLifePhoto-error").css({"display":"block"});   
    }
    
    //disply remove img button (X)
    $("#preview-img-edit-school-life-label").css({"display":"block"});
    
    
});

//remove img src + hidde "remove button" for photo
$("#preview-img-edit-school-life-label").click(function() {
    
    //show "add School Life photo" label AND hide "current School Life photo" label
    $("#editSchoolLifePhotoLabel").css({"display":"block"});
    $("#editSchoolLifePhotoLabel_current").css({"display":"none"});
    
    //restet preview photo field
    $("#editSchoolLifePhoto").val(null);
    //show editSchoolLifePhoto file field
    $("#editSchoolLifePhoto").css({"display":"block"});
    //remove jquert validation error for editSchoolLifePhoto
        $("#editSchoolLifePhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $('#preview-img-edit-school-life').removeAttr('src');
    $("#preview-img-edit-school-life").css({"display":"none"});

    //hide 'X' button
    $("#preview-img-edit-school-life-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-edit-school-life-label").removeClass("not-valid");
    
    //remove photo set true
    $("#edit-school-life-remove-photo").attr("checked", true);

});


function checkValidationForEditSchoolLifeImage(file){
    
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
    
    if(fileSize > 4000000){
        isValid = false;
    }
    
    
    return isValid;
    
}




