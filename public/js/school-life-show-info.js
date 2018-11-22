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
//    //hide print option
//    $(".print-option-bottom").css({"display":"none"});
//    $(".print-option-top").css({"display":"none"});
//    
    //clear and show schoolList
    $("#showSchoolLifeTitle span").empty(); 
    $("#showSchoolLifeTitle span").css({"display":"block"}); 
    // clear img src
    $('#schoolLifeImg').removeAttr('src');
//    //clear and show listOfStationary
//    $("#listOfStationary").empty(); 
//    $("#listOfStationary").css({"display":"block"}); 
//    //clear and show additionalMoniesInfo
//    $("#additionalMoniesInfo").empty(); 
//    $("#additionalMoniesInfo").css({"display":"block"}); 
//    //clear and show uniformInfo
//    $("#uniformInfo").empty(); 
//    $("#uniformInfo").css({"display":"block"}); 
//    //clear and show otherInfo
//    $("#otherInfo").empty(); 
//    $("#otherInfo").css({"display":"block"});
//    
    dataModal = $(this).attr("data-modal");
    var schoolLifeID = $(this).attr("data-schoolLifeID");
    var color = $(this).attr("data-color"); 
    var access = $(this).data("access") === 1 ? true : false;

    
    console.log('======== Show School Life =======');
    console.log('School Life ID: ' + schoolLifeID);
    console.log('School Life color: ' + color);
    console.log('access: '+ access);
    
    $("#modal-color").attr("class", 'modal-box lg '+color);
    $(".btn-modal-box button").attr("class", 'modal-btn btn-'+color+' small close-btn');
  
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
                
                var adminOption = '<a href="#" class="tooltip modal-trigger-edit-school-life" data-modal="edit-school-life-modal" data-schoolLife="'+data.schoolLife.id+'">\n\
                                        <button class="btn-admin"><img src="img/icons/icon-edit-blue.png"></button>\n\
                                        <span class="tooltiptext bottom edit">Edit</span>\n\
                                    </a>\n\
                                    <a href="#" class="tooltip modal-trigger-delete-policy" data-modal="delete-policy-modal">\n\
                                        <button class="btn-admin"><img class="icon-list" src="img/icons/icon-bin-red.png"></button>\n\
                                        <span class="tooltiptext bottom remove">Remove</span>\n\
                                    </a>';
                
                if(access){
                    $('#adminOption').html(adminOption);
                    $("#adminOption").css({"display":"block"});
                                      
                }
                
                /*
                 *   insert school life content
                 */
                $("#schoolLifeContent").html(data.schoolLife.content);
                $("#schoolLifeContent").css({"display":"block"});
                
                /*
                 * insert school life img
                 */
                var imgURL = '/upload/school-life/'+data.schoolLife.id+'/'+data.schoolLife.photoName;
                $("#schoolLifeImg").attr("src", imgURL);

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


