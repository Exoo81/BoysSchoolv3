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
    var parentsAssocID = $(this).attr("data-parentsAssocID");
    
    //console.log("parentsAssocID: " + parentsAssocID);

    //insert data to form
    $('#addParentsAssocNewMemeber_parentsAssocID').val(parentsAssocID);

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
        addParentsAssocNewMemeber_parentsAssocID: {
            required: true
        },
        addParentsAssocNewMemeberRole: {
            required: true
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
    var parentsAssocID = $("#addParentsAssocNewMemeber_parentsAssocID").val();



//    console.log('member Title: ' + memberTitle);
//    console.log('member Role: ' + memberRole);
//    console.log('member First Name:' + memberFirstName);
//    console.log('member Last Name:' + memberLastName);
//    console.log('parents Assoc ID:' + parentsAssocID);
    

    
    $.ajax({
        url:'parents/addmembertoparentsassoc',
        type:'POST',
        data:{memberTitle:memberTitle, memberRole:memberRole, memberFirstName:memberFirstName, memberLastName:memberLastName, parentsAssocID:parentsAssocID},
        dataType: 'JSON', 
        async: true ,
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


