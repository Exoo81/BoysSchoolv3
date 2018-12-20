/* 
 * Add Newsletter
 */

//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

//additionam method for jQuery validate addNewsletterDate with format eg. 01 June 2018
$.validator.addMethod(
    "formatDateAddMethod",
    function(value, element) {
        
//TODO
        //format value to format requested
        //if not error return true
        return true;
        
        /**
         * TODO this method add to event-edit.js
         */
       
    },
    "Please enter a date in the format eg. \"05 April 2018\"."
);

//open modal with form
$(".modal-trigger-add-newsletter").click(function(e){
    
    e.preventDefault();
    
    //close manage newsletter modal
    $("#manage-newsletter-modal").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addNewsletterForm").reset();
    
    $("#addNewsletterForm").css({"display":"block"});
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addNewsletterForm").validate({
    rules: {
        addNewsletterDate: {
            required: true,
            formatDateAddMethod : true
        },
        addNewsletterFile: {          
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
    
    //var title = $("#addNewsletterTitle").val();
    var title = 'Newsletter';
    var addNewsletterDate = $("#addNewsletterDate").val();
    var addNewsletterDoc = document.querySelector('#addNewsletterFile').files[0]; 

    console.log(title);
    console.log(addNewsletterDate);
    console.log(addNewsletterDoc);
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({"title":title, "addNewsletterDate":addNewsletterDate});
    
    formData.append('objArr', JSON.stringify(objArr));

    formData.append('addNewsletterDoc', addNewsletterDoc);
    
    $.ajax({
        url: 'application/addnewsletter',
        type: 'POST',
        cache: false,
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


