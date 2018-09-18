/* 
 * Calendar/ yellow page
 */

window.onload = function(){
    
    //$(".loader").css({"display":"block"});

    var date = new Date();
    var current_date = getCurrentDate(date);
    var dayofweek = getDayOfWeek(date.getDay());

    
    getCalendar(date.getFullYear(), date.getMonth()-1, 0);
    getEvents(dayofweek, current_date, true);
    
    $("#current_date").html('Today is ' + dayofweek + ', ' + current_date);
      
};



function getEvents(dayofweek, current_date, isInit) {

    $.ajax({
        url: 'application/getevents', 
        type:'POST',
        data:{date:current_date},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){

                //yellow page event
                var eventsList = '<img src="img/events/icon-paper-pin.png">';

                if(data.currentEvents.length > 0){
                    
                    if(isInit){
                        eventsList += '<div class="event_header">Today\'s events list</div>';
                    }else{
                        eventsList += '<div class="event_header">Events list <br>'+dayofweek+', '+current_date+'</div>';;
                    }
                    eventsList += '<ul>';
                        $.each(data.currentEvents, function(index, event) {
//                        console.log(event.id);
                            eventsList += '<li>';
                                eventsList += '<div class="event_wrapper">';
                                    eventsList += '<span class="event-title">';
                                        eventsList += '<a href="#" class="bs" onclick="showEvent('+event.id+')">';
//                                        eventsList += '<a href="#" class="bs modal-trigger-show-event" data-modal="show-event-modal" data-id='+event.id+'>'; 
                                            eventsList += '<span class="space-10"># </span>' + event.title;
                                        eventsList += '</a>';
                                    eventsList += '</span>';
                                eventsList += '</div>';

                            eventsList += '</li>';


                        });
                    eventsList += '</ul>';
                    
                }else{
                    if(isInit){
                        eventsList += '<div class="event_header">No events<br>today</br><img src="img/events/icon-no-events.png"></div>';
                    }else{
                        eventsList += '<div class="event_header">No events<br>' + dayofweek + ', ' + current_date + '</br><img src="img/events/icon-no-events.png"></div>';
                    }
                }
                
                $("#event_list").html(eventsList);
            }else{
//                 
            }   
        }      
    }); 
    return false;
}

/*
 * Get months options list.
 */
function getAllMonths(selectedMonth){
       var months_long = ['January', 'February' , 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October' , 'November' , 'December'];
	options = '';

	for(var i=0; i<=11; i++){
            var selectedOpt = (i === selectedMonth)?'selected':'';
            options += '<option value="'+ i +'" ' + selectedOpt +'>' + months_long[i] + '</option>';
	}      
	return options;
}

/*
 * Get years options list.
 */
function getYearList(selectedYear){
	options = '';
	for(i=2017; i<=2030; i++){
            selectedOpt = (i === selectedYear)?'selected':'';
            options += '<option value="'+ i +'" '+ selectedOpt +' >'+ i +'</option>';
	}
	return options;
}

/*
 * Get calendar 
 */
function getCalendar(selectedYear, selectedMonth, step){
    
    var stepDown = -1;
    var stepUp = 1;
    
    var today = new Date();

    var date = new Date(selectedYear, selectedMonth, 1);
    if(step === -1){
        date.setMonth(date.getMonth()- 1);
        selectedMonth = date.getMonth();
        selectedYear = date.getFullYear();
    }else{
        date.setMonth(date.getMonth() + 1);
        selectedMonth = date.getMonth();
        selectedYear = date.getFullYear();
    }//3rd option 0 (initial calendar) - data from function parameters remain unchanged
    
    var dateYear = date.getFullYear();
    var dateMonth = date.getMonth();

    /*old*/
    //var currentMonthFirstDay = new Date(dateYear+'-'+(dateMonth+1)+'-'+1).getDay();
    /*new*/
    var currentMonthFirstDay = new Date(dateYear, (dateMonth), 1, 19, 30, 0).getDay();
    
    var totalDaysOfMonth = new Date(dateYear, (dateMonth+1), 0).getDate();
    var totalDaysOfMonthDisplay = (currentMonthFirstDay === 7)?(totalDaysOfMonth):(totalDaysOfMonth + currentMonthFirstDay);
    var boxDisplay = (totalDaysOfMonthDisplay <= 35)?35:42;
    
    
    $.ajax({
        url: 'application/getcalendar',
        type:'POST',
        data:{year:selectedYear, month:selectedMonth},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
//             console.log(data);
             if(data.success === true){
                 //calendar
                var dayCount = 1; 
                var calendar = '<h2>';
                    calendar += '<a href="#" onclick="getCalendar('+selectedYear+', '+selectedMonth+', '+stepDown+')"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>';
                        calendar += '<select name="month_dropdown" class="month_dropdown dropdown" onchange="selectNewDate()" >'+ getAllMonths(selectedMonth) +'</select>';
                        calendar += '<select name="year_dropdown" class="year_dropdown dropdown" onchange="selectNewDate()">'+ getYearList(selectedYear) +'</select>';
                    calendar += '<a href="#" onclick="getCalendar('+selectedYear+', '+selectedMonth+', '+stepUp+')"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';
                calendar += '</h2>';
                calendar += '<div id="calender_section_top">';
                    calendar += '<ul>';
			calendar += '<li>Sun</li>';
			calendar += '<li>Mon</li>';
			calendar += '<li>Tue</li>';
			calendar += '<li>Wed</li>';
			calendar += '<li>Thu</li>';
			calendar += '<li>Fri</li>';
			calendar += '<li>Sat</li>';
                    calendar += '</ul>';
                calendar += '</div>';
                calendar += '<div id="calender_section_bot">';
                    calendar += '<ul>';
                        for(var cb=1; cb<= boxDisplay; cb++){
                            //console.log(cb);
                            
                            if((cb >= currentMonthFirstDay+1 || currentMonthFirstDay === 7) && cb <= (totalDaysOfMonthDisplay)){
                                //Current date
                                var currentDate = dateYear+'-'+(dateMonth+1)+'-'+dayCount;
                                // init event number
                                var eventNum = 0;
                                //console.log('currentDate ' + currentDate);
                                //console.log(data.eventsNumberPerDayInMonth);
                                if(currentDate in data.eventsNumberPerDayInMonth){
                                    eventNum = data.eventsNumberPerDayInMonth[currentDate];
                                    //console.log('current date: '+ currentDate);
                                    //console.log('number of events: '+eventNum);
                                }
                                
                                
                                var dayofweek = new Date(dateYear, dateMonth, dayCount).getDay();

                                if((new Date(dateYear, dateMonth, dayCount)).getTime() === (new Date(today.getFullYear(), today.getMonth(), today.getDate())).getTime()){
//                                    console.log('date from loop: '+ getCurrentDate(new Date(dateYear, dateMonth, dayCount)));
//                                    console.log('today: '+ new Date(dateYear, dateMonth, today.getDate()));
//                                    console.log('dayofweek: '+ dayofweek);
                                    calendar += '<a href="#" onclick="prepareForEventList('+dayofweek+', '+dateYear+', '+dateMonth+', '+dayCount+')" >';
                                    calendar += '<li date="'+currentDate+'" class=" date_cell">';
                                    //Date cell
                                    calendar += '<div class="today"><span>';
                                    calendar += dayCount;
                                    calendar += '</span></div>';
                                }else if(eventNum > 0){
					calendar += '<a href="#" onclick="prepareForEventList('+dayofweek+', '+dateYear+', '+dateMonth+', '+dayCount+')">';
					calendar += '<li date="'+currentDate+'" class="date_cell">';
					//Date cell
					calendar += '<div class="events"><span>';
					calendar += dayCount;
					calendar += '</span></div>';
							
                                }else{
                                        calendar += '<a href="#" onclick="prepareForEventList('+dayofweek+', '+dateYear+', '+dateMonth+', '+dayCount+')">';
					calendar += '<li date="'+currentDate+'" class="date_cell">';
					//Date cell
					calendar += '<div><span>';
					calendar += dayCount;
					calendar += '</span></div>';
                                }
                                //Hover event popup
                                if(eventNum > 0){
                                  calendar +=  '<span class="events_number">'+eventNum+'</span>';
                                }
                                    calendar += '</li>';
				    calendar += '</a>';
                                    
                                    dayCount ++;
                            }else{
                                calendar += '<li><span>&nbsp;</span></li>';
                            }
                        }
                    calendar += '</ul>';
                calendar += '</div>';
                $("#calender_section").html(calendar);
                $("#calender_section").css({"display":"block"});
                $("#calendar-page").css({"display":"block"});
                //$(".loader").css({"display":"none"});
             }
        }      
    }); 
    return false;
}

function getCurrentDate(date){
    var month_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return (date.getDate()<10?("0"+date.getDate()):date.getDate()) + ' ' + month_short[date.getMonth()] + ' ' + date.getFullYear();
}

function getCurrentDateEventToEdit(date){
    var month_short = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return (date.getDate()<10?("0"+date.getDate()):date.getDate()) + ' ' + month_short[date.getMonth()] + ' ' + date.getFullYear();
}

function getShortMonth(month){
    var intMonth = parseInt(month);
    var month_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return  month_short[intMonth];
}


function getDayOfWeek(day){
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
   
    return days[day];
}

function selectNewDate(){
    var selectedMonth = $(".month_dropdown").val();
    var selectedYear = $(".year_dropdown").val();
    getCalendar(selectedYear, selectedMonth-1, 0);
}

function prepareForEventList(dayofweek, y, m ,d){
    var date = new Date();
    date.setFullYear(y);
    date.setMonth(m);
    date.setDate(d);
    var dateString = getCurrentDate(date);
    dayofweek = getDayOfWeek(date.getDay());
    getEvents(dayofweek, dateString, false);

}

function showEvent(eventId){
    
    //show laoder
    $(".loader").css({"display":"block"});
    //hide event list
    $(".event-list").css({"display":"none"});
     //hide event list
    $(".response-msg").css({"display":"none"});
    
    
    var dataModal = "show-event-modal";
//    $("#current_date").html('Today is ' + dataModal + ', ' + eventId);
//    alert(eventId + ' ' + dataModal);

    //display modal
      $("#" + dataModal).css({"display":"block"});
//      $("#red-box").html(eventId);
      
      //send id by AJAX to get full object
        $.ajax({
            url:'application/getevent',
            type:'POST',
            data:{eventId:eventId},
            dataType: 'JSON', 
            async: true ,
            success: function(data){
                if(data.success === true){
                    console.log(data);
                    var event = data.event;
                    var eventDate = new Date(data.eventYear, (data.eventMonth)-1, data.eventDay);
                    //console.log(eventDate);
                    $("#color-box .day").html(eventDate.getDate()<10?("0"+eventDate.getDate()):eventDate.getDate());
                    $("#color-box .month").html(getShortMonth(eventDate.getMonth()));
                    $("#color-box .year").html(eventDate.getFullYear());
                    $("#event-details .title").html(event.title);
                    
                    if(event.location !== null){
                        $("#event-details .location span").html(event.location);
                        $("#event-details .location").css({"display":"inline-block"});
                    }else{
                        $("#event-details .location").css({"display":"none"});
                    }
                    $("#event-details .text").html(event.content);
                    $("#eventBox .date").attr('id',   data.colorBox);
//                    $("#eventBox .date").attr('id',   data.colorBox);
                    
                    //data for edit action
                    $("#eventBox .admin-option .modal-trigger-edit-event-details").attr('data-id', event.id);
                    $("#eventBox .admin-option .modal-trigger-edit-event-details").attr('data-title', event.title);
                    $("#eventBox .admin-option .modal-trigger-edit-event-details").attr('data-eventDate', getCurrentDateEventToEdit(eventDate));
                    $("#eventBox .admin-option .modal-trigger-edit-event-details").attr('data-location', event.location);
                    $("#eventBox .admin-option .modal-trigger-edit-event-details").attr('data-content', event.content);
                    
                    //data for delete action
                    $("#eventBox .admin-option .modal-trigger-delete-event-details").attr('data-id', event.id);
                    $("#eventBox .admin-option .modal-trigger-delete-event-details").attr('data-title', event.title);
                    
                    //hide laoder
                        $(".loader").css({"display":"none"});
                    //show event list
                       $(".event-list").css({"display":"block"});
                }else{
                    console.log(data);
                    //hide laoder
                        $(".loader").css({"display":"none"});
                    //show event list
                       $(".response-msg").css({"display":"block"});
                       $(".response-msg").html(data.responseMsg);
                   
                }
                return false;
            }
         }); 
}





