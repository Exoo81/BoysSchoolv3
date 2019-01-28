/**
 * Modals
 */

$(".modal-trigger").click(function(e){
	 e.preventDefault();
	 dataModal = $(this).attr("data-modal");
	 $("#" + dataModal).css({"display":"block"});
       
});

$(".close-modal, .btn-modal-box .close-btn").click(function(){
	$(".modal").css({"display":"none"});
});




//close error modal
$(".error-close-btn").click(function(){
	$("#error-post-modal").css({"display":"none"});
});



