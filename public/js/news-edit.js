/* 
 * Edit News
 */
//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');

// open modal with news data
$(".modal-trigger-edit-news").click(function(e){
	e.preventDefault();
        
        //show laoder
            $(".loader").css({"display":"block"});
        //clear msg label
            $(".response-msg").html('');
        //clear form
            document.getElementById("editNewsForm").reset();
        //clear src to img
            $("#editNewsPhoto_current").attr("src",'');
            
        //remove doc set false
            $("#edit-news-remove-doc").attr("checked", false);
            
        //remove photo set false
            $("#edit-news-remove-photo").attr("checked", false);
        
        //hide edit news form
        $("#editNewsForm").css({"display":"none"});
        //hide all news document fields
        $("#editNewsDocLabel").css({"display":"none"});
        $("#editNewsDoc").css({"display":"none"});
        $("#editNewsDocLabel_current").css({"display":"none"});
        $("#editNewsDoc_current").css({"display":"none"});
        $("#preview-doc-edit-news-label").css({"display":"none"});
        
        
        
        //hide all news photo fields
        $("#editNewsPhotoLabel_current").css({"display":"none"});
        $("#editNewsPhoto_current").css({"display":"none"});
        $("#editNewsPhotoLabel").css({"display":"none"});
        $("#editNewsPhoto").css({"display":"none"});
        $("#preview-photo-edit-news-label").css({"display":"none"});
        
        dataModal = $(this).attr("data-modal");
        
        //display modal
        $("#" + dataModal).css({"display":"block"});

        var id = $(this).data("id");
        
        //send id by AJAX to get full object
        $.ajax({
            url:'application/geteditnews',
            type:'POST',
            data:{id:id},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                //console.log(data);
                if(data.success === true){
                    
                    $("#" + dataModal).css({"display":"block"});

                    //inster data to fields
                        $("#editNewsID").val(data.newsToEdit.id);
                        $("#editNewsTitle").val(data.newsToEdit.title);
                        $("#editNewsContent").val(data.newsToEdit.content);
                        
                    //if news doc exist
                    if(data.newsToEdit.docName !== null){
                    
                        //show label for current doc
                        $("#editNewsDocLabel_current").css({"display":"block"});
                        //show icon + doc title + remove
                        $("#editNewsDoc_current").css({"display":"block"});
                        //insert doc title
                        $("#editNewsDocTitle").html(data.newsToEdit.docName);
                    }else{  
                        //show input field label for document
                        $("#editNewsDocLabel").css({"display":"block"});
                        //show input field for document
                        $("#editNewsDoc").css({"display":"block"});
                    }
                    
                    //if news with photo
                    if(data.newsToEdit.photoName !== null){
                        //show "Current photo" label
                        $("#editNewsPhotoLabel_current").css({"display":"block"});
                        //show img + insert src of file
                        $("#editNewsPhoto_current").attr("src",data.photoPath);
                        $("#editNewsPhoto_current").css({"display":"block"}); 
                        //show remove link
                        $("#preview-photo-edit-news-label").css({"display":"block"});
                        //hide input field for photo
                        $("#editNewsPhoto").css({"display":"none"});                       
                    }else{
                        //show "Add news photo" label
                        $("#editNewsPhotoLabel").css({"display":"block"});
                        //show input field for photo
                        $("#editNewsPhoto").css({"display":"block"});
                    }
                    //hide laoder
                        $(".loader").css({"display":"none"});
                    //show form
                        $("#editNewsForm").css({"display":"block"});
                }else{
                    $(".response-msg").html(data.responseMsg);
                    $("#" + dataModal).css({"display":"block"});
                    $("#editNewsForm").css({"display":"none"});
                }
                return false;
            }
         });
});

//after submit
$("#editNewsForm").validate({
    rules: {
        editNewsID: {
            required: true
        },  
        editNewsTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editNewsContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        editNewsPhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif",
            filesize: 4000000           // 4MB
        },
        editNewsDoc: {
            required: false,
            extension: "pdf|docx|doc",
            filesize: 4000000           // 4MB
        }
      
    },
    messages:{
        editNewsPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif",
            filesize: "File size must be less than 4MB"
        },
        editNewsDoc:{
            extension: "Allowed file extensions: pdf, docx, doc",
            filesize: "File size must be less than 4MB"
        }
    },
            
    submitHandler: function() {
        
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#editNewsForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');
        
        //get fields values from form
        var editNewsID = $("#editNewsID").val();
        var editNewsTitle = $("#editNewsTitle").val();
        var editNewsContent = $("#editNewsContent").val();
        var removeDoc = $('#edit-news-remove-doc').attr('checked');
        if(removeDoc === 'checked'){
            removeDoc = true;
        }else{
            removeDoc = false;
        }
        var removePhoto = $('#edit-news-remove-photo').attr('checked');
        if(removePhoto === 'checked'){
            removePhoto = true;
        }else{
            removePhoto = false;
        }
        //get input doc
        var editNewsDoc = document.querySelector('#editNewsDoc').files[0]; 
        //get input photo
        var editNewsPhoto = document.querySelector('#editNewsPhoto').files[0];
        
//        console.log('editNewsID: ' + editNewsID);
//        console.log('editNewsTitle: ' +editNewsTitle );
//        console.log('editNewsContent: ' + editNewsContent);
//        console.log('removeDoc: ' + removeDoc);
//        console.log('removePhoto: ' + removePhoto);
//        console.log('editNewsDoc: ' + editNewsDoc);
//        console.log('editNewsPhoto: ' + editNewsPhoto);

        //add to dataForm
        var formData = new FormData();
    
        var objArr = [];
        objArr.push({"editNewsID":editNewsID, "editNewsTitle":editNewsTitle, "editNewsContent":editNewsContent, "removeDoc":removeDoc, "removePhoto":removePhoto });
    
        formData.append('objArr', JSON.stringify(objArr));

        formData.append('editNewsDoc', editNewsDoc);
        formData.append('editNewsPhoto', editNewsPhoto);
        
        $.ajax({
            url: 'application/editnews',
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
                        //display response-msg
                        $(".response-msg").html(data.responseMsg);
                        //hide laoder
                        $(".loader").css({"display":"none"});
                          
                    }
                    return false;
            }
        });
    }

});

//show remove button "X" for not valid file in DOC field
$( "#editNewsDoc" ).change(function(event) {
    //hide add doc label and show current doc label
    $("#editNewsDocLabel_current").css({"display":"block"});
    $("#editNewsDocLabel").css({"display":"none"});
    
    //show remove "X" button
    $("#preview-doc-edit-news-label").css({"display":"block"});
//    // validation if photo and photo size
//    var isValid = checkValidationForDoc(event.target.files[0]);
//    if(!isValid){
//        //alert("is Valid file type? " + isValid);
//        if(!isValid){
//            //add not-valid class
//           //$("#preview-doc-edit-news-label").addClass("not-valid");
//        }
//    }
    
    
});

//remove NOT-VALID file from DOC field
$( "#preview-doc-edit-news-label" ).click(function() {
    
    //show add doc label and hide current doc label
    $("#editNewsDocLabel").css({"display":"block"});
    $("#editNewsDocLabel_current").css({"display":"none"});
  
    //reset file field and remove img src=''
        $("#editNewsDoc").val(null);
    //hide remove "X" button for not-valid field
        $("#preview-doc-edit-news-label").css({"display":"none"});
    //remove not-falid class
        $("#preview-doc-edit-news-label").removeClass("not-valid");
    //remove jquert validation error for editSchoolLifePhoto
        $("#editNewsDoc-error").css({"display":"none"});

});

//remove CURRENT DOC -  remove button "X" for CURRENT doc
$( "#preview-doc-edit-news-label_current" ).click(function() {
    
    //hide current document
    $('#editNewsDoc_current').css({"display":"none"});
    //hide current label
    $('#editNewsDocLabel_current').css({"display":"none"});
    
    //show add doc label editNewsDocLabel
    $('#editNewsDocLabel').css({"display":"block"});
    //show input doc
    $("#editNewsDoc").val(null);
    $("#editNewsDoc").css({"display":"block"});
    
    //remove doc set true
    $("#edit-news-remove-doc").attr("checked", true);

});


//preview photo loaded in file field in form
$( "#editNewsPhoto" ).change(function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('editNewsPhoto_current');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
//    $("#editNewsPhoto_current").css({"display":"block"});
//    //disply remove img button
//    $("#preview-photo-edit-news-label").css({"display":"block"});
    
    
    //hide "Add news photo" label AND show "Current news photo" label
        $("#editNewsPhotoLabel").css({"display":"none"});
        $("#editNewsPhotoLabel_current").css({"display":"block"});
        
    // validation if photo and photo size
    var isValid = checkValidationForImage(event.target.files[0]); 
    
    if(isValid){
        //hide editNewsPhoto file field
            $("#editNewsPhoto").css({"display":"none"});
        // show img
            $("#editNewsPhoto_current").css({"display":"block"});
        //disply remove img button
            $("#preview-photo-edit-news-label").css({"display":"block"});
        //remove not-falid class
            $("#preview-photo-edit-news-label").removeClass("not-valid");
        //remove jquert validation error for editSchoolLifePhoto
            $("#editNewsPhoto-error").css({"display":"none"});    
    }else{
        //show editNewsPhoto file field
            $("#editNewsPhoto").css({"display":"block"});
        //disply remove img button
            $("#preview-photo-edit-news-label").css({"display":"block"});
        //add not-valid class
            $("#preview-photo-edit-news-label").addClass("not-valid");
    }
    
});

//remove img src + hidde "remove button" for photo
$( "#preview-photo-edit-news-label" ).click(function() {
    
    //hide "Current photo" label AND show "Add news photo" label
        $("#editNewsPhotoLabel_current").css({"display":"none"});
        $("#editNewsPhotoLabel").css({"display":"block"});
    
    
    //reset file field AND show
        $("#editNewsPhoto").val(null);
        $("#editNewsPhoto").css({"display":"block"});
    //preview photo - remove 'src' AND hidde
        $('#editNewsPhoto_current').removeAttr('src');
        $("#editNewsPhoto_current").css({"display":"none"});
    //hide remove "X" button for photo
        $("#preview-photo-edit-news-label").css({"display":"none"});
        
    //remove jquert validation error for editSchoolLifePhoto
        $("#editNewsPhoto-error").css({"display":"none"});
        
        
    //set - 'remove photo' == true
    $("#edit-news-remove-photo").attr("checked", true);

});



//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});

