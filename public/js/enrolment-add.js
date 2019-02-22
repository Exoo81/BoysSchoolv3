/* 
 * Add Enrolment
 */

//additionam method for file max size
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');


//open modal with form
$(".modal-trigger-add-enrolment").click(function(e){
    
    e.preventDefault();
    
    //close manage enrolment modal
    $("#manage-enrolment-modal").css({"display":"none"});
    
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    
    console.log('Authot ID: '+ authorID);
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addEnrolmentForm").reset();
    
    //insert data
    $('#addEnrolmentAuthorID').val(authorID);
    
    //display form
    $("#addEnrolmentForm").css({"display":"block"});
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});


$("#addEnrolmentForm").validate({
    rules: {
        addEnrolmentAuthorID: {
            required: true
        },
        addEnrolmentTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addEnrolmentFile: {          
            required: true,   
            extension: "pdf|docx|doc",
            filesize: 8000000           // 8MB
        } 
    },
    messages:{
        addEnrolmentFile:{
            extension: "Allowed file extensions: pdf, docx, doc",
            filesize: "File size must be less than 8MB"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addEnrolmentForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var title = $("#addEnrolmentTitle").val();
    var addEnrolmentDoc = document.querySelector('#addEnrolmentFile').files[0]; 

    console.log(title);
    console.log(addEnrolmentDoc);
    
    var formData = new FormData();
    
    var objArr = [];
    objArr.push({"title":title});
    
    formData.append('objArr', JSON.stringify(objArr));

    formData.append('addEnrolmentDoc', addEnrolmentDoc);
    
    $.ajax({
        url: 'parents/addenrolment',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: formData,
        success: function(data){
            console.log(data);
            if(data.success === true){
                //refresh page
                //location.reload();
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


