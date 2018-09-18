/* 
 * Add Our Awards
 */

//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

//open modal with form
$(".modal-trigger-add-our-awards").click(function(e){
    
    e.preventDefault();
    
    //close Our award manage modal
    $("#manage-our-awards-modal").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addOurAwardForm").reset();
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addOurAwardForm").validate({
    rules: {
        addOurAwardTitle: {
            required: true,
            maxlength: 100
        },
        addOurAwardFile: {              //input name: content
            required: true,   
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        }
      
    },
    messages:{
        addOurAwardFile:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addOurAwardForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var title = $("#addOurAwardTitle").val();
    var ourAwardPhoto = document.querySelector('#addOurAwardFile').files[0]; 
    
//    console.log(title);
//    console.log(ourAwardPhoto);
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({"title":title});
    
    formData.append('objArr', JSON.stringify(objArr));
    //formData.append('tempField', tempField);
    formData.append('ourAwardPhoto', ourAwardPhoto);
    
    $.ajax({
        url: 'application/addouraward',
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

//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


