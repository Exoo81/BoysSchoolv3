/**
 * Slider BXSlider 
 * https://bxslider.com
 */

$(document).ready(function(){
	var slider = $('.slider').bxSlider({
    	  auto: true,
    	  pager: true,
    	  speed: 1000,
    	  pause: 6000, 
    	  preloadImages: 'all',
    	  infiniteLoop: true,  	
      });
//slideshow stop after menu open
	$(document).on('click','.hamburger',function() {
		slider.stopAuto();        
    });
//slideshow start after menu close
    $(document).on('click','.close-nav ',function() {
        slider.startAuto(); 
    });
    
});