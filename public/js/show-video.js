/* 
 * Show video
 */

//open modal with video
$(".modal-trigger-show-video").click(function(e){
    

    e.preventDefault();
    
    
    dataModal = $(this).attr("data-modal");

        
    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    //removing
    $("source").remove();
    
    
    var videoLink = $(this).attr("data-video");
    var title = $(this).attr("data-title");
    
    $(".modal-title span").html(title);
    
//    console.log('title: ' + title);
//    console.log('link: ' + videoLink);
    
    //to reload video 
    $("#post-video")[0].load();
    //to reload src in source
    $('#post-video').empty();
    $('#post-video').append('<source src="'+videoLink+'"> ');
    
   
});



