/* 
 * Edit News
 */

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
          
//        //hide img
//            $("#previewImgEdit").css({"display":"none"});
//        //hide "remove link"
//            $("#previewImgEditLabel").css({"display":"none"});
        
        //hide edit news form
        $("#editNewsForm").css({"display":"none"});
        //hide all news document fields
        $("#editNewsDocLabel").css({"display":"none"});
        $("#editNewsDoc").css({"display":"none"});
        $("#editNewsDocLabel_current").css({"display":"none"});
        $("#editNewsDoc_current").css({"display":"none"});
        
        
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
                        //show label for current doc
                        $("#editNewsPhotoLabel_current").css({"display":"block"});
                        //show img + insert src of file
                        $("#editNewsPhoto_current").attr("src",data.photoPath);
                        $("#editNewsPhoto_current").css({"display":"block"}); 
                        //show remove link
                        $("#preview-photo-edit-news-label").css({"display":"block"});
                        //show input field for photo
                        $("#editNewsPhoto").css({"display":"block"});                       
                    }else{
                        //show input field label for photo
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
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        },
        editNewsDoc: {
            required: false,
            extension: "pdf|docx|doc"
            //filesize: 10000000           // 4MB
        }
      
    },
    messages:{
        editNewsPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
        },
        editNewsDoc:{
            extension: "Allowed file extensions: pdf, docx, doc"
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
        var removeDoc = $('#edit-news-remove-doc').is(':checked');
        var removePhoto = $('#edit-news-remove-photo').is(':checked');
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
                console.log(data);
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

//remove img src + hidde "remove button" for doc
$( "#preview-doc-edit-news-label" ).click(function() {
    //show input doc
    $("#editNewsDoc").css({"display":"block"});
    $("#editNewsDoc").val(null);
    
    //hide current document
    $('#editNewsDoc_current').css({"display":"none"});
    
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
    $("#editNewsPhoto_current").css({"display":"block"});
    //disply remove img button
    $("#preview-photo-edit-news-label").css({"display":"block"});
    
    //remove photo set false
    //$("#edit-news-remove-photo").attr("checked", false);
    
});

//remove img src + hidde "remove button" for photo
$( "#preview-photo-edit-news-label" ).click(function() {
    //restet preview photo field
    //reset file field and remove img src=''
        $("#editNewsPhoto").val(null);
        $('#editNewsPhoto_current').removeAttr('src');
        $("#editNewsPhoto_current").css({"display":"none"});
    //hide remove link
        $("#preview-photo-edit-news-label").css({"display":"none"});
    //remove photo set true
    $("#edit-news-remove-photo").attr("checked", true);

});



//close modal
//$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
//	$(".modal").css({"display":"none"});
//});

