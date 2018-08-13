/* 
 * Add News
 */

//additionam method for file max size
/*$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File size must be less than {0}');*/

//open modal with form
$(".modal-trigger-add-news").click(function(e){
    e.preventDefault();
    
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    
//    console.log('authorID: ' + authorID);
    
    //clear msg label
        $(".response-msg").html('');
    
    //reset form
        document.getElementById("addNewsForm").reset();
        
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addNewsPhoto").val(null);
        $('#preview-img-add-news').removeAttr('src');
        $("#preview-img-add-news").css({"display":"none"});
        $("#preview-img-add-news-label").css({"display":"none"});
    
    //insert data
    $('#addNewsAuthorID').val(authorID);
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addNewsForm").validate({
    rules: {
        addNewsAuthorID: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addNewsTitle: {
            required: true,
            maxlength: 100       //max. text length = 100 char
        },
        addNewsContent: {
            required: true,
            maxlength: 1000       //max. text length = 1000 char
        },
        addNewsPhoto: {
            required: false,
            extension: "jpg|jpeg|png|gif"
            //filesize: 10000000           // 4MB
        },
        addNewsDoc: {              //input name: content
            required: false,   
            extension: "pdf|docx|doc"
            //filesize: 10000000           // 4MB
        }
      
    },
    messages:{
        addNewsPhoto:{
            extension: "Allowed file extensions: jpg, jpeg, png, gif"
        },
        addNewsDoc:{
            extension: "Allowed file extensions: pdf, docx, doc"
        },
    },
            
    submitHandler: function() {
        //show laoder
            $(".loader").css({"display":"block"});
        //hidde form
            $("#addNewsForm").css({"display":"none"});
        //clear msg label
            $(".response-msg").html('');

        var newsTitle = $("#addNewsTitle").val();
        var newsContent = $("#addNewsContent").val();

        var newsDoc = document.querySelector('#addNewsDoc').files[0];

        var newsPhoto = document.querySelector('#addNewsPhoto').files[0];

        var authorID = $("#addNewsAuthorID").val();

//    console.log('newsTitle: ' + newsTitle);
//    console.log('newsContent: ' + newsContent);
//    console.log('newsDoc: ' + newsDoc);
//    console.log('newsPhoto: ' + newsPhoto);
//    console.log('authorID: ' + authorID);

        var formData = new FormData();

        var objArr = [];
        objArr.push({"newsTitle":newsTitle, "newsContent":newsContent, "authorID":authorID});

        formData.append('objArr', JSON.stringify(objArr));

        formData.append('newsDoc', newsDoc);

        formData.append('newsPhoto', newsPhoto);

        $.ajax({
            url: 'application/addnews',
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

//preview photo loaded in file field in form
$( "#addNewsPhoto" ).change(function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-img-add-news');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    $("#preview-img-add-news").css({"display":"block"});
    //disply remove img button
    $("#preview-img-add-news-label").css({"display":"block"});
    
});

//remove img src + hidde "remove button"
$( "#preview-img-add-news-label" ).click(function() {
    //restet preview photo field
    //reset file field and remove img src=''
        $("#addNewsPhoto").val(null);
        $('#preview-img-add-news').removeAttr('src');
        $("#preview-img-add-news").css({"display":"none"});
    //hide remove link
        $("#preview-img-add-news-label").css({"display":"none"});

});


//close modal
/*$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
    $(".modal").css({"display":"none"}); 
});*/


