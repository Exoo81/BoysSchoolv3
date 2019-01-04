/* 
 * Edit Class Blog
 */

//additionam method for select - Value must not equal arg. 0 
$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

// open modal with news data
$(".modal-trigger-edit-class-blog").click(function(e){
    
    e.preventDefault();
    
    //show laoder
        $(".loader").css({"display":"block"});
    //clear msg label
        $(".response-msg").html('');
    //clear form
        document.getElementById("editClassBlogForm").reset();
    //clear src to img
        $("#editClassBlogPhoto_current").attr("src",'');
    //remove photo set false
        $("#edit-class-blog-remove-photo").attr("checked", false);
    
    //hide edit news form
        $("#editClassBlogForm").css({"display":"none"});
        
    //hide all class photo fields
        $("#editClassBlogPhotoLabel_current").css({"display":"none"});
        $("#editClassBlogPhoto_current").css({"display":"none"});
        $("#editClassBlogPhotoLabel").css({"display":"none"});
        $("#editClassBlogPhoto").css({"display":"none"});
        $("#preview-photo-edit-class-blog-label").css({"display":"none"});
        
    //clear all select fields
        $('#editClassLevel').empty();
        $('#editClassTeacher').empty();
        $('#editClassLearningSupport').empty();
    
//    //show form
//            $("#editClassBlogForm").css({"display":"block"});
        
//        //hide img
//            $("#preview-img-edit-class").css({"display":"none"});
//        //hide "remove link"
//            $("#preview-img-edit-class-label").css({"display":"none"});
        
        
    dataModal = $(this).attr("data-modal");
        
    //display modal
        $("#" + dataModal).css({"display":"block"});

    var classBlogID = $(this).attr("data-blogID");
    var teacherID = $(this).attr("data-teacherID");
    var learningSupportID = $(this).attr("data-learningSupportID");
        
    if(learningSupportID === ""){
        learningSupportID = null;
    }
        
//    console.log('blog id: '+classBlogID);
//    console.log('teacher id: '+teacherID);
//    console.log('learningSupport id: '+learningSupportID);
        
    //send id by AJAX to get full object
    $.ajax({
        url:'classes/geteditclassblog',
        type:'POST',
        data:{classBlogID:classBlogID, teacherID:teacherID, learningSupportID:learningSupportID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
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
                    //show label for current doc
                    $("#editClassBlogPhotoLabel_current").css({"display":"block"});
                    //show img + insert src of file
                    $("#editClassBlogPhoto_current").attr("src",data.classPhotoPath);
                    $("#editClassBlogPhoto_current").css({"display":"block"});
                    //show remove link
                    $("#preview-photo-edit-class-blog-label").css({"display":"block"});
                    //show input field for photo
                    //$("#editClassBlogPhoto").css({"display":"block"});
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

//after submit
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
        var output = document.getElementById('editClassBlogPhoto_current');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    //disply remove img button (X)
    $("#preview-photo-edit-class-blog-label").css({"display":"block"});


    //hide "add ClassBlog photo" label AND show "current ClassBlog photo" label
        $("#editClassBlogPhotoLabel").css({"display":"none"});
        $("#editClassBlogPhotoLabel_current").css({"display":"block"});
        
    // validation if photo and photo size
    var isValid = checkValidationForImage(event.target.files[0]);
    
    if(isValid){
        //remove addClassPhoto file field
        $("#editClassBlogPhoto").css({"display":"none"});
        // show img
        $("#editClassBlogPhoto_current").css({"display":"block"});
        //disply remove img button (X)
        $("#preview-photo-edit-class-blog-label").css({"display":"block"});
        //remove not-falid class
        $("#preview-photo-edit-class-blog-label").removeClass("not-valid");
        
        //hide error info if exist
            $("#editClassBlogPhoto-error").css({"display":"none"});
    }else{
        $("#editClassBlogPhoto").css({"display":"block"});
        //disply remove img button (X)
        $("#preview-photo-edit-class-blog-label").css({"display":"block"});
        //add not-valid class
        $("#preview-photo-edit-class-blog-label").addClass("not-valid");
 
        //show error info if exist
            $("#editClassBlogPhoto-error").css({"display":"block"});
    }

    
});

//remove img src + hidde "remove button" for photo
$("#preview-photo-edit-class-blog-label").click(function() {

    //show "add ClassBlog photo" label AND hide "current ClassBlog photo" label
        $("#editClassBlogPhotoLabel").css({"display":"block"});
        $("#editClassBlogPhotoLabel_current").css({"display":"none"});
    //restet preview photo field
    //reset file field and remove img src=''
        $("#editClassBlogPhoto").val(null);
        $('#editClassBlogPhoto_current').removeAttr('src');
        $("#editClassBlogPhoto_current").css({"display":"none"});
    //hide remove link (X)
        $("#preview-photo-edit-class-blog-label").css({"display":"none"});
        
        //show editClassPhoto file field
        $("#editClassBlogPhoto").css({"display":"block"});
        
        //remove jquert validation error for editClassBlogPhoto
        $("#editClassBlogPhoto-error").css({"display":"none"});

});



