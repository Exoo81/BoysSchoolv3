/* 
 * Add member of the Our Team
 */

// add the rule select type of information
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");


//open modal with form
$(".modal-trigger-add-our-team").click(function(e){
    
    e.preventDefault();
    
    // get data
    dataModal = $(this).attr("data-modal");
    var type = $(this).attr("data-role");
    
    //close Parents Assoc manage modal
    $("#manage-"+type+"-modal").css({"display":"none"});
    
    //clear msg label
    $(".response-msg").html('');
    //reset form
    document.getElementById("addOurTeamForm").reset();
      
//    console.log("OPEN MODAL - ADD OUR TEAM MEMBER");
//    console.log("Type: " + type);
    
    var typeString = getTypeString(type);

    //insert data to form
    $('#add-role-type').html('&nbsp;'+typeString);
    $('#addOurTeamType').val(type);
    
    //show/hide role fields if type management
    if(type === 'management'){
        $('#management-select-list').html('<label id="addOurTeamRoleLabel">Role:</label>\n\
                                            <select id="addOurTeamRoleSelect" name="addOurTeamRoleSelect" class="dropdown">\n\
                                                <option value="0">--- SELECT ---</option>\n\
                                                <option value="1">Chairperson</option>\n\
                                                <option value="2">Bishop\'s Representative</option>\n\
                                                <option value="3">Parents\' Nominee</option>\n\
                                                <option value="4">Teachers\' Representative</option>\n\
                                                <option value="5">Community Representative</option>\n\
                                                <option value="6">Secretary of the Board</option>\n\
                                            </select>');
    }else{
        $('#management-select-list').html(''); 
    }

    //display form
    $("#addOurTeamForm").css({"display":"block"});
   
    //display modal
    $("#" + dataModal).css({"display":"block"});
});


$("#addOurTeamForm").validate({
    rules: {
        addOurTeamType: {
            required: true
        },
        addOurTeamTitleSelect: {
            valueNotEquals: "0"
        },
        addOurTeamFirstName: {
            required: true
        },
        addOurTeamLastName: {
            required: true
        },
        addOurTeamRoleSelect:{
            valueNotEquals: "0"
        }
 
    },
    messages:{
        addOurTeamTitleSelect:{
            valueNotEquals: "Please select title"
        },
        addOurTeamFirstName:{
            required: "Please enter first name"
        },
        addOurTeamLastName:{
            required: "Please enter last name"
        },
        addOurTeamRoleSelect:{
            valueNotEquals: "Please select role in board"
        }
    },
            
    submitHandler: function() {
    //show laoder
        $(".loader").css({"display":"block"});
    //hidde form
        $("#addOurTeamForm").css({"display":"none"});
    //clear msg label
        $(".response-msg").html('');
    
    var memberTitle = $("#addOurTeamTitleSelect").val();
    var memberType = $("#addOurTeamType").val();
    var memberFirstName = $("#addOurTeamFirstName").val();
    var memberLastName = $("#addOurTeamLastName").val();
    var boardMemberRole = $("#addOurTeamRoleSelect").val();




//    console.log('member Title: ' + memberTitle);
//    console.log('member Type: ' + memberType);
//    console.log('member First Name:' + memberFirstName);
//    console.log('member Last Name:' + memberLastName);
//    console.log('board member role:' + boardMemberRole);

    //if adding member not from board of management
    if(memberType !== 'management'){
        
        $.ajax({
            url:'ourteam/addourteammember',
            type:'POST',
            data:{memberTitle:memberTitle, memberType:memberType, memberFirstName:memberFirstName, memberLastName:memberLastName},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                console.log(data);
                if(data.success === true){
                    //hidde laoder
                        $(".loader").css({"display":"none"});
                    //display response-msg
                        $(".response-msg").html(data.responseMsg);
                    
                    //close Add Our Team Member Modal
                    $("#add-our-team").css({"display":"none"});
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
        
    }else if(memberType === 'management'){//if adding mamber from board of management
        
        $.ajax({
            url:'ourteam/addmanagementmember',
            type:'POST',
            data:{memberTitle:memberTitle, boardMemberRole:boardMemberRole, memberFirstName:memberFirstName, memberLastName:memberLastName},
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
        
    }else{
        //hidde laoder
            $(".loader").css({"display":"none"});
        //display response-msg
            $(".response-msg").html('The member type can not be determined');
    }

    
    }
});


function getTypeString(type){
    
    var typeString = '';
    
    switch (type) {
    case 'principal':
        typeString = "principal";
        break;
    case 'teacher':
        typeString = "teacher";
        break;
    case 'management':
        typeString = "member of the Board";
        break; 
    case 'learning_support':
        typeString = "learning support member";
        break;
    case 'sna':
        typeString = "member of SNA";
        break;
    case 'asd_unit':
        typeString = "member of ASD Unit";
        break;
    case 'secretary':
        typeString = "secretary";
        break;
    case 'caretaker':
        typeString = "caretaker";
        break;
    }
    
    return typeString;
}


