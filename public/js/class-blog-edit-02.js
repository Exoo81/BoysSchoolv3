/* 
 * Edit Class Blog
 */

//additionam method for select - Value must not equal arg. 0 
$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

// open modal with news data
$(".modal-trigger-edit-class-blog").click(function(e){
    
    e.preventDefault();
    
    //hide edit Class Blog form
    $("#editClassBlogForm").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
        
    //clear form
    document.getElementById("editClassBlogForm").reset();
        
    //clear all select fields
    $('#editClassLevel').empty();
    $('#editClassTeacher').empty();
    $('#editClassLearningSupport').empty();
        
    //hide "current Class Blog photo" label
    $("#editClassBlogPhotoLabel_current").css({"display":"none"}); 
    //hide "add Class Blog photo" label    
    $("#editClassBlogPhotoLabel").css({"display":"none"});
    
    //restet preview photo
    $("#editClassBlogPhoto").val(null);
    //hide editClassBlogPhoto
    $("#editClassBlogPhoto").css({"display":"none"});
    //remove jquert validation error for editClassBlogPhoto
        $("#editClassBlogPhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $("#preview-img-edit-class-blog").attr("src",'');
    //hide preview image
    $("#preview-img-edit-class-blog").css({"display":"none"});
    
    //hide 'X' button
    $("#preview-img-edit-class-blog-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-edit-class-blog-label").removeClass("not-valid");
     
      
    //remove photo set false
    $("#edit-class-blog-remove-photo").attr("checked", false);      
    
    //get data
    dataModal = $(this).attr("data-modal");
    var classBlogID = $(this).attr("data-blogID");
    var teacherID = $(this).attr("data-teacherID");
    var learningSupportID = $(this).attr("data-learningSupportID");
        
    if(learningSupportID === ""){
        learningSupportID = null;
    }       
//    console.log('blog id: '+classBlogID);
//    console.log('teacher id: '+teacherID);
//    console.log('learningSupport id: '+learningSupportID);
        
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    

          
    //send id by AJAX to get full object
    $.ajax({
        url:'classes/geteditclassblog',
        type:'POST',
        data:{classBlogID:classBlogID, teacherID:teacherID, learningSupportID:learningSupportID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                    
                //insert classBlogID
                $('#editClassBlogID').val(classBlogID);
                    
                //insert options for editClassLevel
                $.each(data.classLevel, function (key, value){
                    $('#editClassLevel').append($('<option></option>').attr('value', key).text(value));
                });
                //current level selected
                $('#editClassLevel option[value="'+data.currentLevel+'"]').attr('selected','selected');
                    
                    
                //insert options for editClassTeacher           
                $.each(data.teachers, function (key, value){
                    $('#editClassTeacher').append($('<option></option>').attr('value', key).text(value));
                });
                //current teacher selected
                $('#editClassTeacher option[value='+data.teacherID+']').attr('selected','selected');
                    
                //insert options for editClassLearningSupport                  
                $.each(data.learningSupport, function (key, value){
                    $('#editClassLearningSupport').append($('<option></option>').attr('value', key).text(value));
                });
                //current learning support selected
                $('#editClassLearningSupport option[value='+data.learningSupportID+']').attr('selected','selected');
                
                //if class with photo
                if(data.classPhotoPath !== null){
                    //show label for current file
                    $("#editClassBlogPhotoLabel_current").css({"display":"block"});
                    //show img + insert src of file
                    $("#preview-img-edit-class-blog").attr("src",data.classPhotoPath);
                    $("#preview-img-edit-class-blog").css({"display":"block"});
                    //show 'X' link
                    $("#preview-img-edit-class-blog-label").css({"display":"block"});
                }else{
                    //show input field label for photo
                    $("#editClassBlogPhotoLabel").css({"display":"block"});
                    //show input field for photo
                    $("#editClassBlogPhoto").css({"display":"block"});
                }
                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#editClassBlogForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });
});



$("#editClassBlogForm").validate({
    rules: {
        editClassBlogID:{
            required: true,
        },
        editClassLevel: {
            required: true,
            valueNotEquals: "0"
        },
        editClassTeacher: {
            required: true,
            valueNotEquals: "0"
        },
        editClassBlogPhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 4000000           // 4MB
        }
      
    },
    messages:{
        editClassLevel: { 
            valueNotEquals: "Please select class level" 
        },
        editClassTeacher: { 
            valueNotEquals: "Please select teacher" 
        },
        editClassBlogPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 4MB"
        }
    },
    
    
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editClassBlogForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');
        
        var editClassBlogID = $('#editClassBlogID').val();
        var editClassLevel = $('#editClassLevel').val();
        var editClassTeacherID = $("#editClassTeacher").val();
        var editClassLearningSupportID = $("#editClassLearningSupport").val();
        var removePhoto = $('#edit-class-blog-remove-photo').attr('checked');
        if(removePhoto === 'checked'){
            removePhoto = true;
        }else{
            removePhoto = false;
        }
        

        //get input photo
        var editClassBlogPhoto = document.querySelector('#editClassBlogPhoto').files[0];
        
//        console.log('editClassBlogID: ' + editClassBlogID);
//        console.log('editClassLevel: ' + editClassLevel);
//        console.log('editClassTeacherID: ' + editClassTeacherID);
//        console.log('editClassLearningSupportID: ' + editClassLearningSupportID);
//        console.log('removePhoto: ' + removePhoto);
//        console.log('editClassBlogPhoto: ' + editClassBlogPhoto);

        //add to dataForm
        var formData = new FormData();
    
        var objArr = [];
        objArr.push({
            "editClassBlogID":editClassBlogID,
            "editClassLevel":editClassLevel, 
            "editClassTeacherID":editClassTeacherID, 
            "editClassLearningSupportID":editClassLearningSupportID, 
            "removePhoto":removePhoto });
    
        formData.append('objArr', JSON.stringify(objArr));

        formData.append('editClassBlogPhoto', editClassBlogPhoto);
        
        $.ajax({
            url: 'classes/editclassblog',
            type: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(data){
                //console.log(data);
                if(data.success === true){
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

//preview photo loaded in file field in form
$( "#editClassBlogPhoto" ).change(function(event) {
    
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-edit-class-blog');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    //hide "add Class Blog photo" label AND show "current Class Blog photo" label
    $("#editClassBlogPhotoLabel").css({"display":"none"});
    $("#editClassBlogPhotoLabel_current").css({"display":"block"});
    
    // validation if photo file
    var isValid = checkValidationForEditClassBlogImage(event.target.files[0]);
    
    if(isValid){
        //remove addClassPhoto file field
        $("#editClassBlogPhoto").css({"display":"none"});
        // show img
        $("#preview-img-edit-class-blog").css({"display":"block"});
        //remove not-falid class
        $("#preview-img-edit-class-blog-label").removeClass("not-valid");
        //hide error info if exist
        $("#editClassBlogPhoto-error").css({"display":"none"});   
    }else{    
        $("#editClassBlogPhoto").css({"display":"block"});
        //add not-valid class
        $("#preview-img-edit-class-blog-label").addClass("not-valid");
        //show error info if exist
        $("#editClassBlogPhoto-error").css({"display":"block"});
    }
    
    //disply remove img button (X)
    $("#preview-img-edit-class-blog-label").css({"display":"block"});
    
   
});

//remove img src + hidde "remove button" for photo
$("#preview-img-edit-class-blog-label").click(function() {

    //show "add ClassBlog photo" label AND hide "current ClassBlog photo" label
    $("#editClassBlogPhotoLabel").css({"display":"block"});
    $("#editClassBlogPhotoLabel_current").css({"display":"none"});
        
    //restet preview photo field
    $("#editClassBlogPhoto").val(null);
    //show editClassBlogPhoto file field
    $("#editClassBlogPhoto").css({"display":"block"});
    //remove jquert validation error for editClassBlogPhoto
        $("#editClassBlogPhoto-error").css({"display":"none"});
    
    //reset file field and remove img src=''
    $('#preview-img-edit-class-blog').removeAttr('src');
    $("#preview-img-edit-class-blog").css({"display":"none"});
        
    //hide 'X' button
    $("#preview-img-edit-class-blog-label").css({"display":"none"});
    //remove not-falid class
    $("#preview-img-edit-class-blog-label").removeClass("not-valid");    
           
    //remove photo set true
    $("#edit-class-blog-remove-photo").attr("checked", true);

});


function checkValidationForEditClassBlogImage(file){
    
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
