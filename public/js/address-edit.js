/* 
 * Edit Address information
 */

//open modal with form
$(".modal-trigger-edit-address").click(function(e){
    
    e.preventDefault();
        
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("editContactForm").reset();
    //hide editContactForm
    $("#editContactForm").css({"display":"none"});    

    dataModal = $(this).attr("data-modal");

   
//    console.log('========Edit contact =======');

    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    //send id by AJAX to get full object
    $.ajax({
        url:'contact/geteditcontactinfo',
        type:'POST',
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                    
                /**
                 * insert contact data
                 */
                $('#editContactSchoolName').val(data.contactToEdit.school_name);
                $('#editContactAddress').val(data.contactToEdit.address);
                $('#editContactEmail').val(data.contactToEdit.email);
                
                $('#editContactPhoneMain').val(data.contactToEdit.phone_main);
                $('#editContactWwwUrl').val(data.contactToEdit.www);
                $('#editContactPhoneAlt1').val(data.contactToEdit.phone_alt1);
                $('#editContactPhoneAlt2').val(data.contactToEdit.phone_alt2);

                
                //hide laoder
                $(".loader").css({"display":"none"});
                //show edit form
                $("#editContactForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });

});


$("#editContactForm").validate({
    
     
    rules: {
        editContactID:{
            required: true
        },
        editContactSchoolName: {
            required: true,
            maxlength: 100
        },
        editContactAddress: {
            required: true,      
        },
        editContactEmail: {
            required: true,
            email: true
        },
        editContactPhoneMain: {
            required: true,
        },
        editContactWwwUrl: {
            required: true,
            url: true

        }
    },
    messages:{  
        editContactSchoolName:{
            required: 'School name is required.'
        },
        editContactAddress:{
            required: 'Address is required.'
        },
        editContactEmail:{
            required: 'Email is required.',
            email: 'enter e-mail in the correct format.'
        },
        editContactPhoneMain:{
            required: 'Phone number is required.',
        },
        editContactWwwUrl:{
            required: 'WWW url is required.',
            url: 'Enter www url in correct format (http://somepage.com).'
        }
    },
    
    
            
    submitHandler: function() {
        
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#editContactForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var contactSchoolName = $("#editContactSchoolName").val();
    var contactAddress = $("#editContactAddress").val();
    var contactEmail = $('#editContactEmail').val();
    var contactPhoneMain = $('#editContactPhoneMain').val();
    var contactWwwUrl = $('#editContactWwwUrl').val();
    var contactPhoneAlt1 = $('#editContactPhoneAlt1').val();
    var contactPhoneAlt2 = $('#editContactPhoneAlt2').val();
    
    if($.trim($('#editContactPhoneAlt1').val()) === ''){
      contactPhoneAlt1 = 0;
    }
   
    if($.trim($('#editContactPhoneAlt2').val()) === ''){
       contactPhoneAlt2 = 0;
    }
    
    
//    console.log('---=== Contact edit Form DATA posted ===---');
//    console.log('contact SchoolName: '+ contactSchoolName);
//    console.log('contact Address: '+ contactAddress);
//    console.log('contact Email: ' + contactEmail);
//    console.log('contact PhoneMain: ' + contactPhoneMain);
//    console.log('contact WwwUrl: ' + contactWwwUrl);
//    console.log('contact PhoneAlt1: ' + contactPhoneAlt1);
//    console.log('contact PhoneAlt2: ' + contactPhoneAlt2);
//    console.log('---================================---');
   
    $.ajax({
        url: 'contact/editcontact',
        type: 'POST',
        data:{contactSchoolName:contactSchoolName, contactAddress:contactAddress, contactEmail:contactEmail, contactPhoneMain:contactPhoneMain, 
                contactWwwUrl:contactWwwUrl, contactPhoneAlt1:contactPhoneAlt1, contactPhoneAlt2:contactPhoneAlt2},
        dataType: 'JSON', 
        async: true ,
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


