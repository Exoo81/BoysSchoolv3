/* 
 * Add Parent information
 */

//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");

//open modal with form
$(".modal-trigger-add-parents-information").click(function(e){
    
    e.preventDefault();
    
    //close Manage infor. for parents Modal
    $("#show-all-parents-information-modal").css({"display":"none"});
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addParentsInformationForm").reset();
    
    // get data
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    

    //insert data to form
    $('#addParentsInformationURL').val('http://');
    $('#addParentsInformation_authorID').val(authorID);

    //console.log('author id: '+ authorID);
    

    //display form
    $("#addParentsInformationForm").css({"display":"block"});
    
    //hide inputs for link and document
    $("#addParentsInformationDocLabel").css({"display":"none"});
    $("#addParentsInformationDoc").css({"display":"none"});
    $("#addParentsInformationURLLabel").css({"display":"none"});
    $("#addParentsInformationURL").css({"display":"none"});
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addParentsInformationForm").validate({
    rules: {
        addParentsInformation_authorID:{
            required: true, 
        },
        addParentsInformationTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addParentsInformationSelect:{
            valueNotEquals: "0"
        },
        addParentsInformationDoc: {
            required: function(){
                return $("#addParentsInformationSelect").val() === "1";
            }
        },
        addParentsInformationURL: {
            required: function(){
                return $("#addParentsInformationSelect").val() === "2";
            },
            url: true
        }
        
      
    },
    messages:{
        addParentsInformationSelect:{
            valueNotEquals: "Please select type of information"
        },
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addParentsInformationForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var parentsInformationITitle = $("#addParentsInformationTitle").val();
    var parentsInformationURL = $("#addParentsInformationURL").val();
    var authorID = $("#addParentsInformation_authorID").val();

    var parentsInformationDoc = document.querySelector('#addParentsInformationDoc').files[0];
    
    if(parentsInformationURL === 'http://'){
        parentsInformationURL = null;
    }

//    console.log('parentsInformationITitle: ' + parentsInformationITitle);
//    console.log('parentsInformationURL: ' + parentsInformationURL);
//    console.log('parentsInformationDoc:' + parentsInformationDoc);
//    console.log('author ID: '+ authorID);
    
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({parentsInformationITitle:parentsInformationITitle, 
                    parentsInformationURL:parentsInformationURL,
                    authorID:authorID
                });

    formData.append('objArr', JSON.stringify(objArr));
    
    formData.append('parentsInformationDoc', parentsInformationDoc);

    
    $.ajax({
        url: 'parents/addparentsinformation',                   // ???
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


$("#addParentsInformationSelect").change(function() {
    var val = $(this).val();
    if(val === "1") {
        $("#addParentsInformationDocLabel").css({"display":"inline-block"});
        $("#addParentsInformationDoc").css({"display":"inline-block"});
        $("#addParentsInformationURLLabel").css({"display":"none"});
        $("#addParentsInformationURL").css({"display":"none"});
        
        //empty url field
        $('#addParentsInformationURL').val('http://');
    }
    else if(val === "2") {
        $("#addParentsInformationURLLabel").css({"display":"inline-block"});
        $("#addParentsInformationURL").css({"display":"inline-block"});
        $("#addParentsInformationDocLabel").css({"display":"none"});
        $("#addParentsInformationDoc").css({"display":"none"});
        
        //empty file field
        $("#addParentsInformationDoc").val(null);
    }else{
        $("#addParentsInformationURLLabel").css({"display":"none"});
        $("#addParentsInformationURL").css({"display":"none"});
        $("#addParentsInformationDocLabel").css({"display":"none"});
        $("#addParentsInformationDoc").css({"display":"none"});
        
        //empty both fields
        $('#addParentsInformationURL').val('http://');
        $("#addParentsInformationDoc").val(null);
    }
  });
  
  //close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/
  


