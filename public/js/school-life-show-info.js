/* 
 * Show school life
 */

// open modal with school life data
$(".modal-trigger-show-school-life").click(function(e){
    
    e.preventDefault();
    
//    //show laoder
//    $(".loader").css({"display":"block"});
//    //clear msg label
//    $(".response-msg").html('');
//    
//    //hide admin-option
//    $('.admin-option').css({"display":"none"});
//    //hide bookList content
//    $("#bookListContent").css({"display":"none"});
//    //hide print option
//    $(".print-option-bottom").css({"display":"none"});
//    $(".print-option-top").css({"display":"none"});
//    
//    //clear and show listOfBooks
//    $("#listOfBooks").empty(); 
//    $("#listOfBooks").css({"display":"block"}); 
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
    var schoolLifeTitle = $(this).attr("data-schoolLifeTitle");
    var schoolLifeContent = $(this).attr("data-schoolLifeContent");
    var schoolLifePhotoName = $(this).attr("data-schoolLifePhoto");
    var color = $(this).attr("data-color"); 
    
    var access = $(this).data("access") === 1 ? true : false;

    
    console.log('======== Show School Life =======');
    console.log('School Life ID: ' + schoolLifeID);
    console.log('School Life Title: ' + schoolLifeTitle);
    console.log('School Life content: ' + schoolLifeContent);
    console.log('School Life Photo: ' + schoolLifePhotoName);
    console.log('School Life color: ' + color);
    console.log('access: '+ access);
    
    
    //insert school life data
    $("#school-life-title span").html(schoolLifeTitle);
    
    $("#school-life-content").html(schoolLifeContent);
    
    var imgURL = '/upload/school-life/'+schoolLifeID+'/'+schoolLifePhotoName;
    $("#school-life-img").attr("src", imgURL);
    
    $("#modal-color").attr("class", 'modal-box lg '+color);
    $(".btn-modal-box button").attr("class", 'modal-btn btn-'+color+' small close-btn');
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
    

        
//    //send id by AJAX to get full object
//    $.ajax({
//        url:'parents/getbooklist',
//        type:'POST',
//        data:{bookListID:bookListID},
//        dataType: 'JSON', 
//        async: true ,
//        success: function(data){
//            console.log(data);
//            if(data.success === true){
//                    
//                /**
//                 * insert title
//                 */
//                $('#showBookListTitle').html(data.bookListInfo.level + " Book List - " + data.teacherInfo.full_name );
//                $('#bookListSeason').html('School year: ' + data.seasonInfo.season_name);
//                
//                /*
//                 * insert attributes to admin options
//                 */
//                if(access){
//                    $('.admin-option').css({"display":"inline-block"});
//                    var editElement = $("a.modal-trigger-edit-book-list");
//                        editElement.attr('data-bookListID', data.bookListInfo.id);
//                        editElement.attr('data-authorID', authorID);
//                        
//                    var deleteElement = $( "a.modal-trigger-delete-book-list" );
//                        deleteElement.attr('data-bookListID', data.bookListInfo.id);
//                        deleteElement.attr('data-bookListLevel', data.bookListInfo.level);
//                        deleteElement.attr('data-bookListTeacherID', data.teacherInfo.id);
//                    
//                }
//                
//                /*
//                 * insert book list ordered by 'subject'
//                 */
//                if(data.listOfSubjects.length !== 0){
//                    
//                    $('#listOfBooks').append('<div class="title centered book-list-title">Books</div>');
//                    $.each(data.listOfSubjects, function (key, value){
//                        $('#listOfBooks').append('<div class="books-table" id="table_'+key+'">\n\
//                                                        <div class="books-table-row subject">\n\
//                                                            <div class="books-table-cell">'+value+'</div>\n\
//                                                        </div>\n\
//                                                </div>');
//                    });
//                    
//                    $.each(data.books, function (key, value){
//                        var bookSubject = value['subject'];
//                        var bookTitle = value['title'];
//                        var bookPublisher = value['publisher'];
//                        var tableKey = null;
//                        $.each(data.listOfSubjects, function (subjectKey, subjectValue){
//                            if(subjectValue === bookSubject){
//                                tableKey = subjectKey;
//                            }
//                        });
//                 
//                        //alert(value['subject']);
//                        $('#table_'+tableKey+'').append('<div class="books-table-row" id="book_'+key+'">\n\
//                                                            <div class="books-table-cell book-title">&#9655 '+bookTitle+'</div>\n\
//                                                            <div class="books-table-cell book-publisher">'+bookPublisher+'</div>\n\
//                                                        </div>');
//                        
//                        });
//          
//                }else{
//                    //hide listOfBooks
//                    $("#listOfBooks").css({"display":"none"});
//                }
//                
//    
//                /*
//                 * insert stationary list
//                 */
//                if(data.stationaryList.length !== 0){
//                    $('#listOfStationary').append('<div class="title centered book-list-title">Stationery</div>');
//                    $('#listOfStationary').append('<div class="stationary-table" id="stationaryTable"></div>');
//                    
//                    $.each(data.stationaryList, function (key, value){
//                        var stationaryName = value['name'];
//                        var stationaryQty = value['qty'];
//                        $('#stationaryTable').append('<div class="stationary-table-row">\n\
//                                                            <div class="stationary-table-cell stationary-name">&#9655 '+stationaryName+'</div>\n\
//                                                            <div class="stationary-table-cell stationary-qty">x '+stationaryQty+'</div>\n\
//                                                        </div>');
//                    });
//   
//                }else{
//                   //hide listOfStationary
//                   $("#listOfStationary").css({"display":"none"}); 
//                }
//                
//                
//                /*
//                 * insert additional monies info
//                 */
//                if(data.bookListInfo.additional_monies_info !== null){
//                    $('#additionalMoniesInfo').append('<div class="title centered book-list-title">Additional Monies</div>');
//                    $('#additionalMoniesInfo').append('<div class="additional-monies-info-table" id="additionalMoniesInfoTable"></div>');
//                    
//                    $('#additionalMoniesInfoTable').append('<div class="additional-monies-info-table-row">\n\
//                                                                <div class="additional-monies-info-table-cell additional-monies-info-content">'+data.bookListInfo.additional_monies_info+'</div>\n\
//                                                            </div>');
//                    
//                    
//                }else{
//                    //hide additionalMoniesInfo
//                   $("#additionalMoniesInfo").css({"display":"none"});
//                }
//                
//                /*
//                 * insert uniform info
//                 */
//                if(data.bookListInfo.uniform_info !== null){
//                    $('#uniformInfo').append('<div class="title centered book-list-title">Uniform</div>');
//                    $('#uniformInfo').append('<div class="uniform-info-table" id="uniformInfoTable"></div>');
//                    
//                    $('#uniformInfoTable').append('<div class="uniform-info-table-row">\n\
//                                                        <div class="uniform-info-table-cell uniform-info-content">'+data.bookListInfo.uniform_info+'</div>\n\
//                                                    </div>');
//                    
//                    
//                }else{
//                    //alert('uniform null');
//                    //hide uniformInfo
//                    $("#uniformInfo").css({"display":"none"});
//                }
//                
//                /*
//                 * insert other info
//                 */
//                if(data.bookListInfo.other_info !== null){
//                    $('#otherInfo').append('<div class="title centered book-list-title">Other information</div>');
//                    $('#otherInfo').append('<div class="other-info-table" id="otherInfoTable"></div>');
//                    
//                    $('#otherInfoTable').append('<div class="other-info-table-row">\n\
//                                                    <div class="other-info-table-cell other-info-content">'+data.bookListInfo.other_info+'</div>\n\
//                                                </div>');
//                    
//                    
//                }else{
//                    //alert('other info null');
//                    //hide otherInfo
//                    $("#otherInfo").css({"display":"none"});
//                }
//
//                
//                //show book list details
//                $("#bookListContent").css({"display":"block"});
//                //show print option
//                $(".print-option-bottom").css({"display":"block"});
//                $(".print-option-top").css({"display":"block"});
//                //hide laoder
//                $(".loader").css({"display":"none"});
//            }else{
//                $(".response-msg").html(data.responseMsg);
//                //hide laoder
//                $(".loader").css({"display":"none"}); 
//            }
//            return false;
//        }
//    });
});


