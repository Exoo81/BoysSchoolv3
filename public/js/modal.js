/**
 * Modals
 */

$(".modal-trigger").click(function(e){
	 e.preventDefault();
	 dataModal = $(this).attr("data-modal");
	 $("#" + dataModal).css({"display":"block"});

         
});

$(".close-modal, .btn-modal-box .close-btn, .modal-sandbox").click(function(){
	$(".modal").css({"display":"none"});


});



