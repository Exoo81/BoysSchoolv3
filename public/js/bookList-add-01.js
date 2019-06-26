/* 
 * Add Book List
 */

//additionam method for select - Value must not equal arg. 0 - --select-- 
$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");


//open modal with form
$(".modal-trigger-add-book-list").click(function(e){
    
    e.preventDefault();
    
    //clear msg label
    $(".response-msg").html('');
    
    //show laoder
    $(".loader").css({"display":"block"});
    //hidde form
    $("#addBookListForm").css({"display":"none"});
    
    //clear all select fields
        $('#addBookListSeason').empty();
        $('#addBookListLevel').empty();
        $('#addBookListTeacher').empty();
    
    //reset form
    document.getElementById("addBookListForm").reset();
    
    //remove all book tr 
    //$(".book").remove();
    $('.book').each(function() {
        if(parseInt($(this).attr("id")) !== 1){
          $(this).remove();  
        }
        
    });
    //reset last index from input_fields_wrap_book data-last-index
    $("#input_fields_wrap_book").attr("data-last-index",1);
    
    
    //remove all stationary tr
    //$(".item").remove();
    $('.item').each(function() {
        if(parseInt($(this).attr("id")) !== 1){
          $(this).remove();  
        }
        
    });
    //reset last index from input_fields_wrap_book data-last-index
    $("#input_fields_wrap_stationary").attr("data-last-index",1);
    
    //reset last book and last sationary error information
    $('#error-valid-last-book').html('');
    $('#error-valid-last-stationary').html('');
    // hide last book and last sationary error information
    $('#error-valid-last-book').css({"display":"none"});
    $('#error-valid-last-stationary').css({"display":"none"});
    
    // get data
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    

    //insert data to form
    $('#addBookList_authorID').val(authorID);

//    console.log('--------- '+ dataModal +'--------------');
//    console.log('author id: '+ authorID);
    

    
    //clear all textarea sumernote
    $('#addAdditionalMonies').summernote('reset');
    $('#addUniform').summernote('reset');
    $('#addOtherInformation').summernote('reset');

    //send id by AJAX to get full object
    $.ajax({
        url:'parents/getaddbooklistselect',
        type:'POST',
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            console.log(data);
            if(data.success === true){
                
                //insert options for school year
                $.each(data.seasons, function (key, value){
                    $('#addBookListSeason').append($('<option></option>').attr('value', key).text(value));
                });
                
                //insert options for classLevel
                $.each(data.classLevel, function (key, value){
                    $('#addBookListLevel').append($('<option></option>').attr('value', key).text(value));
                });
                
                //insert options for classLevel
                $.each(data.teachers, function (key, value){
                    $('#addBookListTeacher').append($('<option></option>').attr('value', key).text(value));
                });
                
                
                //current teacher selected if author not on list then selected value = 0 (--SELECT-)
                $('#addBookListTeacher option[value="'+authorID+'"]').attr('selected','selected');
                

                //hide laoder
                $(".loader").css({"display":"none"});
                //show form
                $("#addBookListForm").css({"display":"block"});
                    
            }else{
                //hide laoder
                $(".loader").css({"display":"none"});
                //show message
                $(".response-msg").html(data.responseMsg);
//                $("#addClassBlogForm").css({"display":"none"});
            }
            return false;
        }
    });
    
    //display modal
    $("#" + dataModal).css({"display":"block"});
});



$("#addBookListForm").validate({
    
    
    rules: {
        addBookListSeason: {
            required: true,
            valueNotEquals: "0"
        },
        addBookListLevel: {
            required: true,
            valueNotEquals: "0"
        },
        addBookListTeacher: {
            required: true,
            valueNotEquals: "0"
        },
        addBookList_authorID:{
            required: true, 
        },
        addBookSubject:{
            required: true
        },
        addBookTitle:{
            required: true
        },
        addBookPublisher:{
            required: true
        },
        addStationaryItemName:{
            required: true
        },
        addStationaryQty:{
            required: true,
            number: true
        }
    },
    messages:{
        addBookListSeason: { 
            valueNotEquals: "Please select school year" 
        },
        addBookListLevel: { 
            valueNotEquals: "Please select class level" 
        },
        addBookListTeacher: { 
            valueNotEquals: "Please select teacher" 
        },
        addBookSubject:{
            required: 'Subject is required'
        },
        addBookTitle:{
            required: 'Title is required'
        },
        addBookPublisher:{
            required: 'Publisher is required'
        },
        addStationaryItemName:{
            required: 'Name of the item is required'
        },
        addStationaryQty:{
            required: 'Quantity is required',
            number: 'Only numbers allowed'
        }
    },
    
    
            
    submitHandler: function() {
        
    $('#error-valid-last-book').css({"display":"none"});
    $('#error-valid-last-stationary').css({"display":"none"});
        
    var lastBookInFormIsValid = validLastBookInForm();
    var lastStationaryInFormIsValid = validLastStationaryInForm();
    
    
        
        if(!lastBookInFormIsValid){ 
            $('#error-valid-last-book').html('Last book on this list has not been validated. Complete the missing fields or delete the entire row.');
            $('#error-valid-last-book').css({"display":"block"});
        }
        
        if(!lastStationaryInFormIsValid){ 
            $('#error-valid-last-stationary').html('Last stationary on this list has not been validated. Complete the missing fields or delete the entire row.');
            $('#error-valid-last-stationary').css({"display":"block"});
        }
    
    
    
        if(lastBookInFormIsValid && lastStationaryInFormIsValid){    

            //show laoder
                $(".loader").css({"display":"block"});
            //hidde form
                $("#addBookListForm").css({"display":"none"});
            //clear msg label
                $(".response-msg").html('');

            var authorID = $("#addBookList_authorID").val();
            var seasonID = $('#addBookListSeason').val();
            var classLevel = $('#addBookListLevel').val();
            var teacherID = $('#addBookListTeacher').val();
            var additionalMonies = null;
            var uniform = null;
            var otherInformation = null;



            if ($('#addAdditionalMonies').summernote('isEmpty')) {
                additionalMonies = null;
            }else{
                additionalMonies = $("#addAdditionalMonies").val();
            }

            if ($('#addUniform').summernote('isEmpty')) {
                uniform = null;
            }else{
                uniform = $("#addUniform").val();
            }

            if ($('#addOtherInformation').summernote('isEmpty')) {
                otherInformation = null;
            }else{
                otherInformation = $("#addOtherInformation").val();
            }

            //number of books 
            var booksListSize = $('#books tr').length;
            // number of Stationary
            var stationaryListSize = $('#stationary tr').length;


//            console.log('---=== Book list Form DATA posted ===---');
//            console.log('Book list size : '+ booksListSize);
//            console.log('Stationary list size : '+ stationaryListSize);
//            console.log('additional Monies: ' + additionalMonies);
//            console.log('uniform: ' + uniform);
//            console.log('other Information: ' + otherInformation);
//            console.log('author ID: '+ authorID);
//            console.log('season: '+ seasonID);
//            console.log('class Level: '+ classLevel);
//            console.log('teacher ID: '+ teacherID);
//            console.log('---================================---');



            var formData = new FormData();

            var objArr = [];
            objArr.push({additionalMonies:additionalMonies, 
                         uniform:uniform, 
                         otherInformation:otherInformation, 
                         authorID:authorID,
                         seasonID:seasonID,
                         classLevel:classLevel,
                         teacherID:teacherID
                     });

            formData.append('objArr', JSON.stringify(objArr));


            var booksArr = [];
            if(booksListSize > 0){

                $("#books tr.book").each(function() {

                    var bookFormID = $(this).attr('id');

                    var inputBookSubject = $(this).find("#subject-"+bookFormID);
                    var bookSubject = inputBookSubject.val();

                    var inputBookTitle = $(this).find("#title-"+bookFormID);
                    var bookTitle = inputBookTitle.val();

                    var inputBookPublisher = $(this).find("#publisher-"+bookFormID);
                    var bookPublisher = inputBookPublisher.val();

                    booksArr.push({
                                    bookSubject:bookSubject,
                                    bookTitle:bookTitle,
                                    bookPublisher:bookPublisher
                                    });

        //            console.log(' =========== Book: ' + bookFormID +'============');
        //            console.log('book Subject: ' + bookSubject);
        //            console.log('book Title: ' + bookTitle);
        //            console.log('book Publisher: ' + bookPublisher);
        //            console.log(' =======================');

                });
            }

            formData.append('booksArr', JSON.stringify(booksArr));

            var stationaryArr = [];
            if(stationaryListSize > 0){

                $("#stationary tr.item").each(function() {

                    var formItemID = $(this).attr('id');

                    var inputItemName = $(this).find("#item-name-"+formItemID);
                    var itemName = inputItemName.val();

                    var inputItemQty = $(this).find("#qty-"+formItemID);
                    var itemQty = inputItemQty.val();


                    stationaryArr.push({
                                    itemName:itemName,
                                    itemQty:itemQty
                                    });

        //            console.log(' =========== Stationary: ' + formItemID +'============');
        //            console.log('item Name: ' + itemName);
        //            console.log('item Qty: ' + itemQty);
        //            console.log(' =======================');

                });
            }

            formData.append('stationaryArr', JSON.stringify(stationaryArr));




            $.ajax({
                    url: 'parents/addbooklist',                  
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(data){
                        console.log(data);
                        if(data.success === true){
                            location.reload();
                        }else{
                            //hidde laoder
                                $(".loader").css({"display":"none"});
                            //display response-msg
                                $(".response-msg").html(data.responseMsg);    
                        }  
                        return false;
                    }      
            });

        }
    
    }
    
});



/*
 *  List of book builder
 */
//add next book fields for bookList
$( "#add_book_button" ).click(function() {
    
    var max_fields = 30;

    //get current list of book size
    var current_list_of_books_size = $('#books tr').length;
    //get last index used
    var last_index = parseInt($('#input_fields_wrap_book').attr("data-last-index"));
    //alert('current_list_of_books_size: ' + current_list_of_books_size);
    //alert('last_index: ' + last_index);
    
    var preview_book_isValid = validateBook();


    if(preview_book_isValid){
        var index = 0; //initlal text box count
        if(current_list_of_books_size < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#books').append('<tr class="book" id="'+index+'">\n\
                                    <td class="subject">\n\
                                        <label>Subject:</label>\n\
                                        <input type="text" id="subject-'+index+'" name="addBookSubject" required>\n\
                                    </td>\n\
                                    <td class="book-title">\n\
                                        <label>Title:</label>\n\
                                        <input type="text" id="title-'+index+'" name="addBookTitle" autocomplete="off" required>\n\
                                    </td>\n\
                                    <td class="publisher">\n\
                                        <label>Publisher:</label>\n\
                                        <input type="text" id="publisher-'+index+'" name="addBookPublisher" required>\n\
                                    </td>\n\
                                    <td class="option book">\n\
                                        <label class="preview-book-list-label" id="preview-add-book-label">\n\
                                            <a href="#" class="remove_field" id="book-'+index+'">X</a>\n\
                                        </label>\n\
                                    </td>\n\
                                </tr>');
            //set new last_index
            $('#input_fields_wrap_book').attr('data-last-index', index++);
        }else{
            alert('List of books is full.');
        }


        //remove book from list
        $('#books').on("click",".remove_field", function(e){ //user click on remove text //user click on remove text
            //alert('remove book');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove(); 
            
            $('#error-valid-last-book').css({"display":"none"});

        });

    }
    
});


/*
 *  List of stationary
 */
//add next stationary fields for bookList
$( "#add_stationary_button" ).click(function() {
    
    var max_fields = 30;
    
    //get current list of stationary size
    var current_list_of_stationary_size = $('#stationary tr').length;
    //get last index used
    var last_index = parseInt($('#input_fields_wrap_stationary').attr("data-last-index"));
    //alert('current_list_of_stationary_size: ' + current_list_of_stationary_size);
    //alert('last_index: ' + last_index);
    
    var preview_stationary_isValid = validateStationary();
    
    if(preview_stationary_isValid){
        var index = 0; //initlal text box count
        if(current_list_of_stationary_size < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#stationary').append('<tr class="item" id="'+index+'">\n\
                                        <td class="item-name">\n\
                                            <label>Item name:</label>\n\
                                            <input type="text" id="item-name-'+index+'" name="addStationaryItemName" required>\n\
                                        </td>\n\
                                        <td class="qty">\n\
                                            <label>Quantity:</label>\n\
                                            <input type="text" id="qty-'+index+'" name="addStationaryQty" autocomplete="off" required>\n\
                                        </td>\n\
                                        <td class="option stationary">\n\
                                            <label class="preview-book-list-label" id="preview-add-stationary-label">\n\
                                                <a href="#" class="remove_field" id="stationary-'+index+'">X</a>\n\
                                            </label>\n\
                                        </td>\n\
                                    </tr>'); //add input box for new stationary
            //set new last_index
            $('#input_fields_wrap_stationary').attr('data-last-index', index++);
        }else{
            alert('Stationary list is full.');
        }

        //remove stationary item from list
        $('#stationary').on("click",".remove_field", function(e){ //user click on remove text //user click on remove text
            //alert('remove book');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove();
            
            $('#error-valid-last-stationary').css({"display":"none"});

        });

    }
    
});



function validateBook(last_index) {
    
    var validator = true;
     
    if($( "input[name='addBookSubject']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='addBookTitle']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='addBookPublisher']" ).valid() === false){
        return false;
    }

    return validator;
}

function validateStationary(last_index) {
    
    var validator = true;
     
    if($( "input[name='addStationaryItemName']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='addStationaryQty']" ).valid() === false){
        return false;
    }

    return validator;
}

function validLastBookInForm(){
    
    var validate = true;
    
    $("#books tr.book").each(function() {

        var bookFormID = $(this).attr('id');

        var inputBookSubject = $(this).find("#subject-"+bookFormID);
        if(!inputBookSubject.val()){
            validate = false;
            return validate;
        }

        var inputBookTitle = $(this).find("#title-"+bookFormID);
        if(!inputBookTitle.val()){
            validate = false;
            return validate;
        }
        var inputBookPublisher = $(this).find("#publisher-"+bookFormID);
        
        if(!inputBookPublisher.val()){
            validate = false;
            return validate;
        }


    });
    
    return validate;
    
}

function validLastStationaryInForm(){
    
    var validate = true;
    
    $("#stationary tr.item").each(function() {

        var formItemID = $(this).attr('id');

        var inputItemName = $(this).find("#item-name-"+formItemID);
        if(!inputItemName.val()){
            validate = false;
            return validate;
        }

        var inputItemQty = $(this).find("#qty-"+formItemID);
        if(!inputItemQty.val()){
            validate = false;
            return validate;
        }

    });
    
    return validate;
    
}



