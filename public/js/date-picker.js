/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {
    $('#addEventDate').datepicker({

        dateFormat: 'dd MM yy'

    }),
    
    $('#editEventDate').datepicker({

        dateFormat: 'dd MM yy'

    }),
    
    $('#addNewsletterDate').datepicker({

        dateFormat: 'dd MM yy'

    }),
    
    $('#editParentsAssocMeetingDate').datepicker({

        dateFormat: 'dd MM yy'

    }),
    
     $('#editParentsAssocMeetingTime').timepicker({

  
        'minTime': '7:00am',
        'maxTime': '11:30pm',

    });
    
    
    
    
 });


