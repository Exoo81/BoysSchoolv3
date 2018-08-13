/* 
 * Add Newsletter
 */

//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

//open modal with form
$(".modal-trigger-add-newsletter").click(function(e){
    
    e.preventDefault();
    
    dataModal = $(this).attr("data-modal");
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addNewsletterForm").reset();
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addNewsletterForm").validate({
    rules: {
        addNewsletterTitle: {
            required: true
        },
        addNewsletterFile: {              //input name: content
            required: true,   
            extension: "pdf|docx|doc"
            //filesize: 10000000           // 4MB
        }
      
    },
    messages:{
        addNewsletterFile:{
            extension: "Allowed file extensions: pdf, docx, doc"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addNewsletterForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var title = $("#addNewsletterTitle").val();
    var addNewsletterDoc = document.querySelector('#addNewsletterFile').files[0]; 

//    console.log(title);
//    console.log(addNewsletterDoc);
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({"title":title});
    
    formData.append('objArr', JSON.stringify(objArr));
    //formData.append('tempField', tempField);
    formData.append('addNewsletterDoc', addNewsletterDoc);
    
    $.ajax({
        url: 'application/addnewsletter',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
//            console.log(data);
            if(data.success === true){
                //refresh page
                location.reload();
            }else{
                //hidde laoder
                    $(".loader").css({"display":"none"});
                //show form
                   //$("#addNewsletterForm").css({"display":"block"});
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


