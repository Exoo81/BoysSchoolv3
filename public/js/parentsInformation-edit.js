/* 
 * Edit Parents Information
 */

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");

// open modal with parents information data
$(".modal-trigger-edit-parents-information").click(function(e){
    
    e.preventDefault();
    
    //close Parents Information-List modal
    $("#show-all-parents-information-modal").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    
    //clear msg label
    $(".response-msg").html(''); 
    //reset form
    document.getElementById("editParentsInformationForm").reset();
    
//    //default string for url
//    var i = "http://";
//     $("#editParentsInformationURL").html(i);
    

    //set edit_parents-information_remove_doc as false 
    $("#edit_parents-information_remove_doc").attr("checked", false);

    //hidde form
    $("#editParentsInformationForm").css({"display":"none"});

    //hide select type box and label
    $("#editParentsInformationSelect").css({"display":"none"});
    $("#editParentsInformationSelectLabel").css({"display":"none"});
    //hide file input and label
    $("#editParentsInformationDoc").css({"display":"none"});
    $("#editParentsInformationDocLabel").css({"display":"none"});
    $("#editParentsInformationDoc_current").css({"display":"none"});
    $("#editParentsInformationDocLabel_current").css({"display":"none"});
    //hide link field and label
    $("#editParentsInformationURL").css({"display":"none"});
    $("#editParentsInformationURLLabel").css({"display":"none"});
    $("#editParentsInformationUrl_current").css({"display":"none"});
    $("#editParentsInformationUrlLabel_current").css({"display":"none"});
    
    
    //read pass data
    dataModal = $(this).attr("data-modal");
    
    var parentsInformationId = $(this).attr("data-parentsInformationId");
    var authorId = $(this).attr("data-authorID");

    
//    console.log('--= Get edit parent information =--');
//    console.log('parentsInformationId: ' + parentsInformationId);
//    console.log('authorId: ' + authorId);
    
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    
    //send id by AJAX to get full object
    $.ajax({
        url:'parents/geteditparentsinformation',
        type:'POST',
        data:{parentsInformationId:parentsInformationId},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                
                //inster data to fields
                $("#editParentsInformationID").val(data.parentsInformationToEdit.id);
                $("#editParentsInformationAuthorID").val(authorId);
                $("#editParentsInformationTitle").val(data.parentsInformationToEdit.title);
               
                
                // if parentsInformation doc exist
                if(data.parentsInformationToEdit.docName !== null){
                    
                    //show label for current video
                    $("#editParentsInformationDocLabel_current").css({"display":"block"});
                    //show icon + doc title + remove
                    $("#editParentsInformationDoc_current").css({"display":"block"});
                    //insert document title
                    $("#editParentsInformationDocTitle").html(data.parentsInformationToEdit.docName);
                }else{
                    //show link if exist
                    if(data.parentsInformationToEdit.url !== null){
                        //show label for current textInput
                        $("#editParentsInformationUrlLabel_current").css({"display":"block"});
                        //show icon + textInput title + remove
                        $("#editParentsInformationUrl_current").css({"display":"block"});
                        //insert document title
                        $("#editParentsInformationUrlTitle").html(data.parentsInformationToEdit.url);
                    }else{
                        //show SELECT BOX  an label
                        $("#editParentsInformationSelectLabel").css({"display":"block"});
                        $("#editParentsInformationSelect").css({"display":"block"});
                    }
                    

                }

                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#editParentsInformationForm").css({"display":"block"});
            }else{
                //show error message
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"});
            }
            return false;
        }
    });
    
});

$("#editParentsInformationForm").validate({
    rules: {
        editParentsInformationAuthorID:{
            required: true, 
        },
        editParentsInformationID:{
            required: true, 
        },
        editParentsInformationTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        editParentsInformationSelect:{
            valueNotEquals: "0"
        },
        editParentsInformationDoc: {
            required: function(){
                return $("#editParentsInformationSelect").val() === "1";
            },
            extension: "pdf|docx|doc"
        },
        editParentsInformationURL: {
            required: function(){
                return $("#editParentsInformationSelect").val() === "2";
            },
            url: true
        }
        
      
    },
    messages:{
        editParentsInformationSelect:{
            valueNotEquals: "Please select type of information"
        },
        editParentsInformationDoc:{
            extension: "Allowed file extensions: pdf, docx, doc"
        },
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#editParentsInformationForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var parentsInformationID = $("#editParentsInformationID").val();
    var authorID = $("#editParentsInformationAuthorID").val();
    var parentsInformationTitle = $("#editParentsInformationTitle").val();
    var parentsInformationURL = $("#editParentsInformationURL").val();
    var removeUrl = $('#edit_parents-information_remove_textInput').attr('checked');
        if(removeUrl === 'checked'){
            removeUrl = true;
        }else{
            removeUrl = false;
        }

    var removeDoc = $('#edit_parents-information_remove_doc').attr('checked');
        if(removeDoc === 'checked'){
            removeDoc = true;
        }else{
            removeDoc = false;
        }
    

    var parentsInformationDoc = document.querySelector('#editParentsInformationDoc').files[0];
    
    if(parentsInformationURL === 'http://' || parentsInformationURL === ""){
        parentsInformationURL = null;
    }

//    console.log('author ID: '+ authorID);
//    console.log('parentsInformation ID: '+ parentsInformationID);
//    console.log('parentsInformation Title: ' + parentsInformationTitle);
//    console.log('parentsInformation URL: ' + parentsInformationURL);
//    console.log('remove URL?: ' + removeUrl);
//    console.log('parentsInformation Doc: ' + parentsInformationDoc);
//    console.log('remove Doc?: ' + removeDoc);
    
    
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({authorID:authorID, 
                    parentsInformationID:parentsInformationID,
                    parentsInformationTitle:parentsInformationTitle,
                    parentsInformationURL:parentsInformationURL,
                    removeUrl:removeUrl,
                    removeDoc:removeDoc
                });

    formData.append('objArr', JSON.stringify(objArr));
    
    formData.append('parentsInformationDoc', parentsInformationDoc);

    
    $.ajax({
        url: 'parents/editparentsinformation',                   // ???
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
            console.log(data);
            if(data.success === true){
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

// remove document from edit form WITH SELECT BOX
$( "#remove_field_doc" ).click(function(e) {
        
        e.preventDefault();
//        alert('remove doc');

        //remove current label and file name + icon
        $("#editParentsInformationDocLabel_current").css({"display":"none"});
        $("#editParentsInformationDoc_current").css({"display":"none"});
        
        //show select box and label
        $("#editParentsInformationSelect").css({"display":"block"});
        $("#editParentsInformationSelectLabel").css({"display":"block"});
        
        //check old file "to remove"
        $("#edit_parents-information_remove_doc").attr("checked", true);
 
});

// remove url from edit form WITH SELECT BOX
$( "#remove_field_textInput" ).click(function(e) {
        
        e.preventDefault();
//        alert('remove url');

        //remove current label and url + icon
        $("#editParentsInformationUrl_current").css({"display":"none"});
        $("#editParentsInformationUrlLabel_current").css({"display":"none"});
        
        //show select box and label
        $("#editParentsInformationSelect").css({"display":"block"});
        $("#editParentsInformationSelectLabel").css({"display":"block"});
        
        //check old file "to remove"
        $("#edit_parents-information_remove_textInput").attr("checked", true);
 
});

// show/hide by SELECT BOX
$("#editParentsInformationSelect").change(function() {
    var val = $(this).val();
    if(val === "1") {
        $("#editParentsInformationDocLabel").css({"display":"inline-block"});
        $("#editParentsInformationDoc").css({"display":"inline-block"});
        $("#editParentsInformationURLLabel").css({"display":"none"});
        $("#editParentsInformationURL").css({"display":"none"});
        
        //empty url field
        $('#editParentsInformationURL').val('http://');
        $("#editParentsInformationDoc").val(null);
    }
    else if(val === "2") {
        $("#editParentsInformationURLLabel").css({"display":"inline-block"});
        $("#editParentsInformationURL").css({"display":"inline-block"});
        $("#editParentsInformationDocLabel").css({"display":"none"});
        $("#editParentsInformationDoc").css({"display":"none"});
        
        //empty file field
        $('#editParentsInformationURL').val('http://');
        $("#editParentsInformationDoc").val(null);
    }else{
        $("#editParentsInformationURLLabel").css({"display":"none"});
        $("#editParentsInformationURL").css({"display":"none"});
        $("#editParentsInformationDocLabel").css({"display":"none"});
        $("#editParentsInformationDoc").css({"display":"none"});
        
        //empty both fields
        $('#editParentsInformationURL').val('http://');
        $("#editParentsInformationDoc").val(null);
    }
  });
  
//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


