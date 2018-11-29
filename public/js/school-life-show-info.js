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
    
    var schoolLifeAuthorID = null;
    if(access){
        schoolLifeAuthorID = $(this).attr("data-schoolLifeAuthorID");
    }

    
//    console.log('======== Show School Life =======');
//    console.log('School Life ID: ' + schoolLifeID);
//    console.log('School Life color: ' + color);
//    console.log('access: '+ access);
//    console.log('author ID: '+ schoolLifeAuthorID);
    
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
            //console.log(data);
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
                        editElement.attr('data-schoolLifeAuthorID', schoolLifeAuthorID);
                        
                    var deleteElement = $( "a.modal-trigger-delete-school-life" );
                        deleteElement.attr('data-schoolLifeID', data.schoolLife.id);
                        deleteElement.attr('data-schoolLifeTitle', data.schoolLife.title);
                                      
                }
                
                /*
                 *   insert school life content
                 */
                $("#schoolLifeContent").html(data.schoolLife.content);
                $("#schoolLifeContent").css({"display":"block"});
                
                /*
                 * insert school life img
                 */
                if(data.schoolLife.photoName !== null){
                    $("#schoolLifeImg").attr("src", data.schoolLifePhotoPath);
                    $("#schoolLifeImg").css({"display":"block"});
                }else{
                    $("#schoolLifeImg").css({"display":"none"});
                }

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


