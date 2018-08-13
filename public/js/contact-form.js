/**
 * SCRIPT CONTACT FORM
 */
 $(document).ready(function(){
     
    $("#contactForm").validate({
        rules: {
            contactName:{
                required: true,
            },
            contactEmail: {            
                required: true,   
                email: true      
            },
            contactTitle: {            
                required: true,   
                maxlength: 100      
            },
            contactMessage: {            
                required: true,   
                maxlength: 1000      
            }
        },
            
        submitHandler: function() {
            var name = $("#contactName").val();        
            var email = $("#contactEmail").val();
            var title = $("#contactTitle").val();
            var message = $("#contactMessage").val();
            $.ajax({
                url:'contact/sendmessage',
                type:'POST',
                data:{name:name, email:email, title:title, message:message},
                dataType: 'JSON', 
                async: true ,
                success: function(data){
                    console.log(data);
                    if(data.success === true){
                        $(".contact-end").attr("id", 'sentSuccess');
                        $('.contact-end h3').html('Message has been sent.<BR><span class="thankYou">THANK YOU <br>'+data.author+'</span>' );
                            
                        $('.contact-start').animate({top : '-500px'},500);
                        $('.contact-start').siblings('.contact-end').animate({top : '0px'},500);
                    }else{
                         $(".contact-end").attr("id", 'sentFaild');
                        $('.contact-end h3').html('Sorry but there was a problem <br>sending the message.<br><span class="tryAgain">Please, try again</span>' );
                                 
                        $('.contact-start').animate({top : '-500px'},500);
                        $('.contact-start').siblings('.contact-end').animate({top : '0px'},500);
                    }
                            
                }
            });
            return false;
        }

    });  
        
    //close notification window
    $('.contact-end .close-contact-form').on('click',function(){
        $(this).parent().animate({top : '-500px'},500);
                
        $(this).parent().siblings('.contact-start').animate({top : '0px'},500);
                
        //$('#sub-email').val('');
        //$("#contactForm").reset();
        //reset form
        document.getElementById("contactForm").reset();
        return false;
    });
        

});


