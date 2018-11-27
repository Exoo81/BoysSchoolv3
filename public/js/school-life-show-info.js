/* 
 * Show school life
 */

// open modal with school life data
$(".modal-trigger-show-school-life").click(function(e){
    
    e.preventDefault();
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //hide admin-option
    $('#adminOption').css({"display":"none"});
    //hide bookList content
    $("#schoolLifeContent").css({"display":"none"});

    //clear and show schoolList
    $("#showSchoolLifeTitle span").empty(); 
    $("#showSchoolLifeTitle span").css({"display":"block"}); 
    // clear img src
    $('#schoolLifeImg').removeAttr('src');

//    
    dataModal = $(this).attr("data-modal");
    var schoolLifeID = $(this).attr("data-schoolLifeID");
    var color = $(this).attr("data-color"); 
    var access = $(this).data("access") === 1 ? true : false;

    
//    console.log('======== Show School Life =======');
//    console.log('School Life ID: ' + schoolLifeID);
//    console.log('School Life color: ' + color);
//    console.log('access: '+ access);
    
    $("#modal-color").attr("class", 'modal-box lg '+color);
    $("#show-school-life-modal .btn-modal-box button").attr("class", 'modal-btn btn-'+color+' small close-btn');
  
    //display modal
    $("#" + dataModal).css({"display":"block"});
 

        
    //send id by AJAX to get full object
    $.ajax({
        url:'schoollife/getschoollife',
        type:'POST',
        data:{schoolLifeID:schoolLifeID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){

                /**
                 * insert title
                 */
                
                $('#showSchoolLifeTitle span').html(data.schoolLife.title);
               
                /*
                 * insert attributes to admin options
                 */
                
                if(access){

                    $('#adminOption').css({"display":"inline-block"});
                    var editElement = $("a.modal-trigger-edit-school-life");
                        editElement.attr('data-schoolLifeID', data.schoolLife.id);
                        
//                    var deleteElement = $( "a.modal-trigger-delete-policy" );
//                        deleteElement.attr('data-policyID', data.policy.id);
//                        deleteElement.attr('data-title', data.policy.title);
                                      
                }
                
                /*
                 *   insert school life content
                 */
                $("#schoolLifeContent").html(data.schoolLife.content);
                $("#schoolLifeContent").css({"display":"block"});
                
                /*
                 * insert school life img
                 */
                //var imgURL = '/upload/school-life/'+data.schoolLife.id+'/'+data.schoolLife.photoName;
                $("#schoolLifeImg").attr("src", data.schoolLifePhotoPath);

                //hide laoder
                $(".loader").css({"display":"none"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });
});


