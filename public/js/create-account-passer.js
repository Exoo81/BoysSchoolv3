/* 
 * Create account passer
 */

//open modal
$(".modal-trigger-create-account").click(function(e){
    
    e.preventDefault();

    dataModal = $(this).attr("data-modal");
    
    //clear new account full name 
    $("#newAccountFullName").html('');
     
    var memberID = $(this).attr("data-memberID");
    var memberFullName = $(this).attr("data-memberFullName");

//        console.log('---=== Member ID passer ===---');
//        console.log('member ID: ' + memberID);
//        console.log('member Full Name: ' + memberFullName);
    var addAccountLink = "./user/addaccount/"+memberID;

     $("#new-member-account").attr("href", addAccountLink); 
     $("#newAccountFullName").html("<i>" +memberFullName + "</i> does not have an account yet.");
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
});


