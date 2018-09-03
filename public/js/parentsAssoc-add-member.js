/* 
 * Add member of the Parents Association
 */

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
 


//open modal with form
$(".modal-trigger-add-parents-assoc-member").click(function(e){
    
    e.preventDefault();
    
    //close Parents Assoc manage modal
    $("#parents-assoc-manage-modal").css({"display":"none"});
    
    //clear msg label
    $(".response-msg").html('');
    
    //reset form
    document.getElementById("addParentsAssocNewMemeberForm").reset();
    
    // get data
    dataModal = $(this).attr("data-modal");


    //display form
    $("#addParentsAssocNewMemeberForm").css({"display":"block"});
   
    //display modal
    $("#" + dataModal).css({"display":"block"});
});

$("#addParentsAssocNewMemeberForm").validate({
    rules: {
        addParentsAssocNewMemeberTitleSelect: {
            valueNotEquals: "0"
        },
        addParentsAssocNewMemeberRoleSelect: {
            valueNotEquals: "0"
        },
        addParentsAssocNewMemeberFname: {
            required: true
        },
        addParentsAssocNewMemeberLname: {
            required: true
        },
 
    },
    messages:{
        addParentsAssocNewMemeberTitleSelect:{
            valueNotEquals: "Please select title"
        },
        addParentsAssocNewMemeberRoleSelect:{
            valueNotEquals: "Please select role"
        }
        
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addParentsAssocNewMemeberForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var memberTitle = $("#addParentsAssocNewMemeberTitleSelect").val();
    var memberRole = $("#addParentsAssocNewMemeberRoleSelect").val();
    var memberFirstName = $("#addParentsAssocNewMemeberFname").val();
    var memberLastName = $("#addParentsAssocNewMemeberLname").val();



//    console.log('member Title: ' + memberTitle);
//    console.log('member Role: ' + memberRole);
//    console.log('member First Name:' + memberFirstName);
//    console.log('member Last Name:' + memberLastName);
    

    
    $.ajax({
        url:'parents/addmembertoparentsassoc',
        type:'POST',
        data:{memberTitle:memberTitle, memberRole:memberRole, memberFirstName:memberFirstName, memberLastName:memberLastName},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                //location.reload();
                //hidde laoder
                    $(".loader").css({"display":"none"});
                //display response-msg
                    $(".response-msg").html(data.responseMsg);
                    
                //close Add Our Team Member Modal
                    $("#add-parents-assoc-member-modal").css({"display":"none"});
                    //show dailog with question to create account
                        $("#newAccountFullName").html("<i>" +data.newMemberFullName + "</i> does not have an account yet.");
                        $("#new-member-account").attr("href", "/user/addaccount/"+data.newMemberID); 
  
                        //display modal
                        $("#create-account-modal").css({"display":"block"});
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


