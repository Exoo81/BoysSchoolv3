/* 
 * Add Class Blog
 */


//additionam method for select - Value must not equal arg. 0 - --select-- 
$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

//open modal with form
$(".modal-trigger-add-class-blog").click(function(e){
    
    e.preventDefault();
    
    dataModal = $(this).attr("data-modal");
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //reset form 
    document.getElementById("addClassBlogForm").reset();
        
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addClassPhoto").val(null);
        $('#preview-img-add-class').removeAttr('src');
        $("#preview-img-add-class").css({"display":"none"});
        $("#preview-img-add-class-label").css({"display":"none"});
        
     //show "add Class photo" label
        $("#addClassPhotoLabel").css({"display":"block"});
    //hide "current class photo" label
        $("#addClassPhotoLabel_current").css({"display":"none"});
        
    //clear all select fields
        $('#classLevel').empty();
        $('#classTeacher').empty();
        $('#classLearningSupport').empty();
    
    //display form
    $("#addClassBlogForm").css({"display":"none"});
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    //send id by AJAX to get full object
    $.ajax({
        url:'classes/getselectoptions',
        type:'POST',
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                
                //insert options for classLevel
                $.each(data.classLevel, function (key, value){
                    $('#classLevel').append($('<option></option>').attr('value', key).text(value));
                });
                    
                //insert options for classTeacher
                $.each(data.teachers, function (key, value){
                    $('#classTeacher').append($('<option></option>').attr('value', key).text(value));
                });
                    
                //insert options for classLearningSupport
                $.each(data.learningSupport, function (key, value){
                    $('#classLearningSupport').append($('<option></option>').attr('value', key).text(value));
                });
                
                $(".loader").css({"display":"none"});
                $("#addClassBlogForm").css({"display":"block"});
                    
            }else{
                $(".loader").css({"display":"none"});
                $(".response-msg").html(data.responseMsg);
                $("#addClassBlogForm").css({"display":"none"});
            }
            return false;
        }
    });  
});

$("#addClassBlogForm").validate({
    rules: {
        classLevel: {
            required: true,
            valueNotEquals: "0"
        },
        classTeacher: {
            required: true,
            valueNotEquals: "0"
        },
        addClassPhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 4000000           // 4MB
        }
      
    },
    messages:{
        classLevel: { 
            valueNotEquals: "Please select class level" 
        },
        classTeacher: { 
            valueNotEquals: "Please select teacher" 
        },
        addClassPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 4MB"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addClassBlogForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var classLevel = $('#classLevel').val();
    var teacherID = $("#classTeacher").val();
    var learningSupportID = $("#classLearningSupport").val();
    var classPhoto = document.querySelector('#addClassPhoto').files[0];
    
//    console.log(classLevel);
//    console.log(teacherID);
//    console.log(learningSupportID);
//    console.log(classPhoto);
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({"classLevel":classLevel, "teacherID":teacherID, "learningSupportID":learningSupportID});
    
    formData.append('objArr', JSON.stringify(objArr));
    formData.append('classPhoto', classPhoto);
    
    $.ajax({
        url: 'classes/addclassblog',
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
$( "#addClassPhoto" ).change(function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-add-class');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    
    
    //disply remove img button (X)
    $("#preview-img-add-class-label").css({"display":"block"});


    //hide "add News photo" label AND show "current news photo" label
        $("#addClassPhotoLabel").css({"display":"none"});
        $("#addClassPhotoLabel_current").css({"display":"block"});
        
    // validation if photo and photo size
    var isValid = checkValidationForImage(event.target.files[0]);
    
    if(isValid){
        //remove addClassPhoto file field
        $("#addClassPhoto").css({"display":"none"});
        // show img
        $("#preview-img-add-class").css({"display":"block"});
        //disply remove img button
        $("#preview-img-add-class-label").css({"display":"block"});
        //remove not-falid class
        $("#preview-img-add-class-label").removeClass("not-valid");
        
        //hide error info if exist
            $("#addClassPhoto-error").css({"display":"none"});
    }else{
        $("#addClassPhoto").css({"display":"block"});
        //disply remove img button
        $("#preview-img-add-class-label").css({"display":"block"});
        //add not-valid class
        $("#preview-img-add-class-label").addClass("not-valid");
 
        //show error info if exist
            $("#addClassPhoto-error").css({"display":"block"});
    }
});


//remove img src + hidde "remove button"
$( "#preview-img-add-class-label" ).click(function() {

    //show "add Class photo" label AND hide "current Class photo" label
        $("#addClassPhotoLabel").css({"display":"block"});
        $("#addClassPhotoLabel_current").css({"display":"none"});
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addClassPhoto").val(null);
        $('#preview-img-add-class').removeAttr('src');
        $("#preview-img-add-class").css({"display":"none"});
    //hide remove link
        $("#preview-img-add-class-label").css({"display":"none"});
        
        //show addClassPhoto file field
        $("#addClassPhoto").css({"display":"block"});
        
        //remove jquert validation error for addClassPhoto
        $("#addClassPhoto-error").css({"display":"none"});
});


function checkValidationForImage(file){
    
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





