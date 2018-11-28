/* 
 * Edit School Life
 */

//open modal with form
$(".modal-trigger-edit-school-life").click(function(e){
    
    e.preventDefault();
    
    //close school-life-show-info modal
    $("#show-school-life-modal").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //clear form
        document.getElementById("editSchoolLifeForm").reset();
    //clear src to img
        $("#editSchoolLifePhoto_current").attr("src",'');
    //remove photo set false
        $("#edit-school-life-remove-photo").attr("checked", false);
    //remove not-falid class
        $("#preview-photo-edit-school-life-label").removeClass("not-valid");
    //hide edit school life form
        $("#editSchoolLifeForm").css({"display":"none"});
    //hide all school life photo fields
        $("#editSchoolLifePhotoLabel_current").css({"display":"none"});
        $("#editSchoolLifePhoto_current").css({"display":"none"});
        $("#editSchoolLifePhotoLabel").css({"display":"none"});
        $("#editSchoolLifePhoto").css({"display":"none"});
        $("#preview-photo-edit-school-life-label").css({"display":"none"});
  
    //clear  textarea (summernote)
    $('#editSchoolLifeContent').summernote('reset');

    
    

    dataModal = $(this).attr("data-modal");
    var schoolLifeID = $(this).attr("data-schoolLifeID");
   
    //console.log('========Edit school life =======');
    //console.log('school life ID: ' + schoolLifeID);


    
    //insert bookListID to form
//    $('#editPolicyID').val(policyID);

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
                    //show label for current doc
                    $("#editSchoolLifePhotoLabel_current").css({"display":"block"});
                    //show img + insert src of file
                    $("#editSchoolLifePhoto_current").attr("src",data.schoolLifePhotoPath);
                    $("#editSchoolLifePhoto_current").css({"display":"block"});
                    //show remove link
                    $("#preview-photo-edit-school-life-label").css({"display":"block"});
                    //show input field for photo
                    //$("#editSchoolLifePhoto").css({"display":"block"});
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
        editSchoolLifeID:{
            required: true
        },
        editSchoolLifeTitle: {
            required: true,
            maxlength: 100
        },
        editSchoolLifePhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        }
    },
    messages:{
        editSchoolLifeTitle:{
            required: 'Title is required.'
        },
        editSchoolLifePhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
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
    var schoolLifeTitle = $("#editSchoolLifeTitle").val();
    var schoolLifeStatus = $('#editSchoolLifeStatus').val();
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
            "schoolLifeTitle":schoolLifeTitle, 
            "schoolLifeStatus":schoolLifeStatus, 
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
    
    //remove jquert validation error for editSchoolLifePhoto
    $("#editSchoolLifePhoto-error").css({"display":"none"});
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('editSchoolLifePhoto_current');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    // validation if photo file
    var isValid = checkValidationForFileExtension(event.target.files[0]); 
    
    if(isValid){
        //remove "add school-life photo" label
        $("#editSchoolLifePhotoLabel").css({"display":"none"});
        //remove "add school-life photo" field
        $("#editSchoolLifePhoto").css({"display":"none"});
        // show curent photo label
        $("#editSchoolLifePhotoLabel_current").css({"display":"block"});
        // show img
        $("#editSchoolLifePhoto_current").css({"display":"block"});
        //disply remove img button
        $("#preview-photo-edit-school-life-label").css({"display":"block"});
        //remove not-falid class
            $("#preview-photo-edit-school-life-label").removeClass("not-valid");
    }else{
        $("#editSchoolLifePhoto").css({"display":"block"});
        //disply remove img button
        $("#preview-photo-edit-school-life-label").css({"display":"block"});
        //add not-valid class
        $("#preview-photo-edit-school-life-label").addClass("not-valid");

    }
    
    
    
    
});

//remove img src + hidde "remove button" for photo
$("#preview-photo-edit-school-life-label").click(function() {
    //restet preview photo field
    //reset file field and remove img src=''
        $("#editSchoolLifePhoto").val(null);
        $('#editSchoolLifePhoto_current').removeAttr('src');
        $("#editSchoolLifePhoto_current").css({"display":"none"});
        $("#editSchoolLifePhotoLabel_current").css({"display":"none"});
    //hide remove link
        $("#preview-photo-edit-school-life-label").css({"display":"none"});
    //remove photo set true
    $("#edit-school-life-remove-photo").attr("checked", true);
    
    //show "add school life photo" label
    $("#editSchoolLifePhotoLabel").css({"display":"block"});
    //show input field for photo
    $("#editSchoolLifePhoto").css({"display":"block"});
    
    //remove jquert validation error for editSchoolLifePhoto
    $("#editSchoolLifePhoto-error").css({"display":"none"});
    
    

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




