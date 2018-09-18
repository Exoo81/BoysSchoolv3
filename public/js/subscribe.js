/**
 * SCRIPT SUBSCRIPTION
 */
 $(document).ready(function(){
     
    $("#subscribeForm").validate({
        rules: {
            email: {              //input name: email
                required: true,   //required boolean: true/false
                email: true       //required boolean: true/false
            }
        },
            
        submitHandler: function() {
            var email = $("#sub-email").val();
            
            console.log('email ' + email);
            
            $.ajax({
                url:'application/addsubscriptions',
                type:'POST',
                data:{email:email},
                dataType: 'JSON', 
                async: true ,
                success: function(data){
                    console.log(data);
                    if(data.success === true){
                        $(".sub-end").attr("id", 'subscribingSuccess');
                        $('.sub-end h3').html('The email address <br><span class="subEmail">'+data.email+'</span><br> has been saved <br><span class="subThankYou">Thank you for subscribing !</span>' );
                            
                        $('.sub-start').animate({top : '-500px'},500);
                        $('.sub-start').siblings('.sub-end').animate({top : '0px'},500);
                    }else{
                         $(".sub-end").attr("id", 'subscribingFaild');
                         $('.sub-end h3').html('You are already subscribed<br>to the newsletter at the address <br><span class="subEmail">'+data.email+'</span>' );
                               
                        $('.sub-start').animate({top : '-500px'},500);
                        $('.sub-start').siblings('.sub-end').animate({top : '0px'},500);
                    }
                            
                }
            });
            return false;
        }

    });  
        
    //close notification window
    $('.sub-end .close-subscribing').on('click',function(){
        $(this).parent().animate({top : '-500px'},500);
                
        $(this).parent().siblings('.sub-start').animate({top : '0px'},500);
                
        $('#sub-email').val('');
        return false;
    });
        

});