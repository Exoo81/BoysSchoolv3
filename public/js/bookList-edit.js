/* 
 * Edit Book List
 */

//open modal with form
$(".modal-trigger-edit-book-list").click(function(e){
    
    e.preventDefault();
    
    //close book-list-show-info modal
    $("#show-book-list-modal").css({"display":"none"});
    
    //show laoder
    $(".loader").css({"display":"block"});
    //clear msg label
    $(".response-msg").html('');
    
    //clear  editListOfBooks
    $("#editListOfBooks").empty(); 
    //clear list of book to remove
    $("ul#edit-book-to-remove").empty();
    
    
    //clear editListOfStationary
    $("#editListOfStationary").empty();
    //clear list of stationary to remove
    $("ul#edit-stationary-to-remove").empty();
    
    //clear all textarea's (summernote)
    $('#editAdditionalMonies').summernote('reset');
    $('#editUniform').summernote('reset');
    $('#editOtherInformation').summernote('reset');
    
    //reset last book and last sationary error information
    $('#error-valid-last-book-edit').html('');
    $('#error-valid-last-stationary-edit').html('');
    // hide last book and last sationary error information
    $('#error-valid-last-book-edit').css({"display":"none"});
    $('#error-valid-last-stationary-edit').css({"display":"none"});
    
    //reset form
    document.getElementById("editBookListForm").reset();
    //hide editBookListForm
    $("#editBookListForm").css({"display":"none"});
    

    
    dataModal = $(this).attr("data-modal");
    var authorID = $(this).attr("data-authorID");
    var bookListID = $(this).attr("data-bookListID");
   
//    console.log('========Edit Book List =======');
//    console.log('bookList ID: ' + bookListID);
//    console.log('author ID: ' + authorID);
    
    //insert bookListID to form
    $('#editBookListID').val(bookListID);
    $('#editBookList_authorID').val(authorID);

    //display modal
    $("#" + dataModal).css({"display":"block"});
    
    //send id by AJAX to get full object
    $.ajax({
        url:'parents/getbooklist',
        type:'POST',
        data:{bookListID:bookListID},
        dataType: 'JSON', 
        async: true ,
        success: function(data){
            //console.log(data);
            if(data.success === true){
                    
                /**
                 * insert title
                 */
                $('#editBookListTitle').html(data.bookListInfo.level + " Book List - " + data.teacherInfo.full_name );
                $('#editBookListSeason').html('School year: ' + data.seasonInfo.season_name);
                
                
                /*
                 * insert list of books ordered by 'subject'
                 */
                if(data.listOfSubjects.length !== 0){
                    
                    $('#editListOfBooks').append('<div class="title centered book-list-title">Books</div>');
                    $.each(data.listOfSubjects, function (key, value){
                        $('#editListOfBooks').append('<div class="books-table" id="editTable_'+key+'">\n\
                                                        <div class="books-table-row subject">\n\
                                                            <div class="books-table-cell">'+value+'</div>\n\
                                                        </div>\n\
                                                    </div>');
                    });
                    
                    $.each(data.books, function (key, value){
                        var bookSubject = value['subject'];
                        var bookTitle = value['title'];
                        var bookPublisher = value['publisher'];
                        var tableKey = null;
                        $.each(data.listOfSubjects, function (subjectKey, subjectValue){
                            if(subjectValue === bookSubject){
                                tableKey = subjectKey;
                            }
                        });
                 
                        //alert(value['subject']);
                        $('#editTable_'+tableKey+'').append('<div class="books-table-row" id="book_'+key+'" bookID="'+key+'">\n\
                                                                <div class="books-table-cell book-title">&#9655 '+bookTitle+'</div>\n\
                                                                <div class="books-table-cell book-publisher">'+bookPublisher+'</div>\n\
                                                                <div class="books-table-cell remove">\n\
                                                                    <label class="preview-book-list-label edit" id="preview-edit-book-list-label">\n\
                                                                        <a href="#" id="edit_remove_book">X</a>\n\
                                                                    </label>\n\
                                                                </div>\n\
                                                        </div>');
                        
                        });
          
                }else{
                    //hide listOfBooks
                    $("#editListOfBooks").css({"display":"none"});
                }
                
                /*
                 * table to add new book with add button
                 */
                 $('#editListOfBooks').append('<hr class="burgundy" />\n\
                                                <table id="edit-books" data-last-index="0">\n\
                                                </table>\n\
                                                <div class="width-100 error" id="error-valid-last-book-edit"></div>\n\
                                                <div class="btn-modal-box">\n\
                                                    <button type="button" id="edit-add_book_button" class="btn-max btn-green reversed" data-text="Add another book">\n\
                                                        <span><i class="fa fa-plus"></i>Add another book</span>\n\
                                                    </button>\n\
                                                </div>');
                
    
                /*
                 * 
                 * insert stationary list
                 * 
                 */
           
                if(data.stationaryList.length !== 0){
                    $('#editListOfStationary').append('<div class="title centered book-list-title">Stationary</div>');
                    $('#editListOfStationary').append('<div class="stationary-table" id="edit-stationaryTable"></div>');
                    
                    $.each(data.stationaryList, function (key, value){
                        var stationaryID = value['id'];
                        var stationaryName = value['name'];
                        var stationaryQty = value['qty'];
                        $('#edit-stationaryTable').append('<div class="stationary-table-row" stationaryId="'+stationaryID+'">\n\
                                                                <div class="stationary-table-cell stationary-name">&#9655 '+stationaryName+'</div>\n\
                                                                <div class="stationary-table-cell stationary-qty">x '+stationaryQty+'</div>\n\
                                                                <div class="stationary-table-cell remove">\n\
                                                                        <label class="preview-book-list-label edit" id="preview-edit-stationary-label">\n\
                                                                            <a href="#" id="edit_remove_stationary">X</a>\n\
                                                                        </label>\n\
                                                                </div>\n\
                                                        </div>');
                    });
   
                }else{
                   //hide listOfStationary
                   $("#listOfStationary").css({"display":"none"}); 
                }
                
                /*
                 * table to add new stationary with add button
                 */
                 $('#editListOfStationary').append('<hr class="burgundy" />\n\
                                                <table id="edit-stationary" data-last-index="0">\n\
                                                </table>\n\
                                                <div class="width-100 error" id="error-valid-last-stationary-edit"></div>\n\
                                                <div class="btn-modal-box">\n\
                                                    <button type="button" id="edit-add_stationary_button" class="btn-max btn-blue reversed" data-text="Add another item">\n\
                                                        <span><i class="fa fa-plus"></i>Add another item</span>\n\
                                                    </button>\n\
                                                </div>');
                
                
                /*
                 * insert additional monies info
                 */
                if(data.bookListInfo.additional_monies_info !== null){
                    $('#editAdditionalMonies').summernote('code', data.bookListInfo.additional_monies_info);                             
                }
                
                /*
                 * insert uniform info
                 */
                if(data.bookListInfo.uniform_info !== null){
                    $('#editUniform').summernote('code', data.bookListInfo.uniform_info);                             
                }

                
                /*
                 * insert other info
                 */
                if(data.bookListInfo.other_info !== null){
                    $('#editOtherInformation').summernote('code', data.bookListInfo.other_info);                             
                }

                
                //hide laoder
                $(".loader").css({"display":"none"});
                //show edit form
                $("#editBookListForm").css({"display":"block"});
            }else{
                $(".response-msg").html(data.responseMsg);
                //hide laoder
                $(".loader").css({"display":"none"}); 
            }
            return false;
        }
    });

});


$("#editBookListForm").validate({
    
     
    rules: {
        editBookList_authorID:{
            required: true 
        },
        editBookListID:{
            required: true 
        },
        editBookSubject:{
           required: true 
        },
        editBookTitle:{
            required: true
        },
        editBookPublisher:{
            required: true
        },
        editStationaryItemName:{
            required: true
        },
        editStationaryQty:{
            required: true,
            number: true
        }
    },
    messages:{
        editBookListID: { 
            valueNotEquals: "Booklist ID is required" 
        },
        editBookSubject:{
            required: 'Subject is required'
        },
        editBookTitle:{
            required: 'Title is required'
        },
        editBookPublisher:{
            required: 'Publisher is required'
        },
        editStationaryItemName:{
            required: 'Name of the item is required'
        },
        editStationaryQty:{
            required: 'Quantity is required',
            number: 'Only numbers allowed'
        }
        
    },
    
    
            
    submitHandler: function() {
        
    $('#error-valid-last-book-edit').css({"display":"none"});
    $('#error-valid-last-stationary-edit').css({"display":"none"});

    var lastBookInFormIsValid = validLastBookInFormEdit();
    var lastStationaryInFormIsValid = validLastStationaryInFormEdit();
    
        if(!lastBookInFormIsValid){ 
            $('#error-valid-last-book-edit').html('Last book on this list has not been validated. Complete the missing fields or delete the entire row.');
            $('#error-valid-last-book-edit').css({"display":"block"});
        }
        
        if(!lastStationaryInFormIsValid){ 
            $('#error-valid-last-stationary-edit').html('Last stationary on this list has not been validated. Complete the missing fields or delete the entire row.');
            $('#error-valid-last-stationary-edit').css({"display":"block"});
        }
        
        if(lastBookInFormIsValid && lastStationaryInFormIsValid){ 


            //show laoder
                $(".loader").css({"display":"block"});
            //hidde form
                $("#editBookListForm").css({"display":"none"});
            //clear msg label
                $(".response-msg").html('');

            var bookListID = $("#editBookListID").val();
            var authorID = $("#editBookList_authorID").val();
            var additionalMonies = null;
            var uniform = null;
            var otherInformation = null;

            if ($('#editAdditionalMonies').summernote('isEmpty')) {
                additionalMonies = null;
            }else{
                additionalMonies = $("#editAdditionalMonies").val();
            }

            if ($('#editUniform').summernote('isEmpty')) {
                uniform = null;
            }else{
                uniform = $("#editUniform").val();
            }

            if ($('#editOtherInformation').summernote('isEmpty')) {
                otherInformation = null;
            }else{
                otherInformation = $("#editOtherInformation").val();
            }

            //number of added books 
            var addedBooksListSize = $('#edit-books tr').length;
            // number of added Stationary
            var addedStationaryListSize = $('#edit-stationary tr').length;


        //    console.log('---=== Book list edit Form DATA posted ===---');
        //    console.log('Booklist ID: '+ bookListID);
        //    console.log('author ID: '+ authorID);
        //    console.log('additional Monies: ' + additionalMonies);
        //    console.log('uniform: ' + uniform);
        //    console.log('other Information: ' + otherInformation);
        //    console.log('---================================---');


            var formData = new FormData();

            var objArr = [];
            objArr.push({additionalMonies:additionalMonies, 
                         uniform:uniform, 
                         otherInformation:otherInformation, 
                         bookListID:bookListID,
                         authorID:authorID
                     });

            formData.append('objArr', JSON.stringify(objArr));


            var booksArr = [];
            if(addedBooksListSize > 0){

                $("#edit-books tr.book").each(function() {

                    var formBookID = $(this).attr('id');

                    var inputBookSubject = $(this).find("#subject-"+formBookID);
                    var bookSubject = inputBookSubject.val();

                    var inputBookTitle = $(this).find("#title-"+formBookID);
                    var bookTitle = inputBookTitle.val();

                    var inputBookPublisher = $(this).find("#publisher-"+formBookID);
                    var bookPublisher = inputBookPublisher.val();

                    booksArr.push({
                                    bookSubject:bookSubject,
                                    bookTitle:bookTitle,
                                    bookPublisher:bookPublisher
                                    });

        //            console.log(' =========== Added Book : ' + formBookID +'============');
        //            console.log('book Subject: ' + bookSubject);
        //            console.log('book Title: ' + bookTitle);
        //            console.log('book Publisher: ' + bookPublisher);
        //            console.log(' =======================');

                });
            }

            formData.append('booksArr', JSON.stringify(booksArr));

            var stationaryArr = [];
            if(addedStationaryListSize > 0){

                $("#edit-stationary tr.item").each(function() {

                    var formItemID = $(this).attr('id');

                    var inputItemName = $(this).find("#item-name-"+formItemID);
                    var itemName = inputItemName.val();

                    var inputItemQty = $(this).find("#qty-"+formItemID);
                    var itemQty = inputItemQty.val();

                    stationaryArr.push({
                                        itemName:itemName,
                                        itemQty:itemQty
                                    });

        //            console.log(' =========== Addred Stationary: ' + formItemID +'============');
        //            console.log('item Name: ' + itemName);
        //            console.log('item Qty: ' + itemQty);
        //            console.log(' =======================');

                });
            }
            formData.append('stationaryArr', JSON.stringify(stationaryArr));

            //list of books to remove from current bookList
            var books_to_remove = [];

            $('ul#edit-book-to-remove li').each(function(i){
                var bookID = $(this).attr('data-bookID'); 
                books_to_remove.push(bookID);
        //        console.log( 'Book ID to remove: ' + bookID );
            });
            formData.append('books_to_remove', JSON.stringify(books_to_remove));

            //list of stationary to remove from current bookList
            var stationary_to_remove = [];

            $('ul#edit-stationary-to-remove li').each(function(i){
                var stationaryID = $(this).attr('data-stationaryID');
                stationary_to_remove.push(stationaryID);
        //        console.log( 'Stationary ID to remove: ' + stationaryID );
            });
            formData.append('stationary_to_remove', JSON.stringify(stationary_to_remove));




            $.ajax({
                url: 'parents/editbooklist',                  
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






//remove book from current bookList (bookList-edit) - build list to remove
$('#editListOfBooks').on("click","#edit_remove_book", function(e){  
        
     e.preventDefault();
     //alert('remove stationary');
     
        var label = $(this).parent('label');
        var lable_parent_div = label.parent('div');
        var div_to_remove = lable_parent_div.parent('div');
        var bookID = div_to_remove.attr('bookID');//div_to_remove[0].getAttribute('data-pictureID');
        //alert(bookID);
        $("ul.book_to_remove").append('<li data-bookID='+bookID+'></li>');
        
        /*
         * check if book from subject empty remove subject table from bookList
         * 
         */
        //get current book list for subject
        var current_subject_table = div_to_remove.parent('div');
        var current_subject_table_size = current_subject_table.children('div').length;
        
        //alert('current_subject_table_size: ' + current_subject_table_size);

        //alert('Books in subject list: ' + current_subject_table_size);
        if(current_subject_table_size === 2){       // 2 = 1 (div last book) + 1 (div subject title)
            //alert('empty subject');      
            current_subject_table.remove();
            //current_subject_table.css({"display":"none"});
        }
        div_to_remove.remove();
        
        /*
         * check if revoved book was last book on list if is add first book fileds
         */
        var number_of_current_subjects = $( "#editListOfBooks .books-table" ).length;
        //alert('number_of_current_subjects ' + number_of_current_subjects);
        if(number_of_current_subjects === 0){
            addFirstBook();
        }
        
        
});

/*
 *  List of book builder
 */
//add next book fields for bookList
$( "#editListOfBooks" ).on("click","#edit-add_book_button", function(e) {
    
    var max_fields = 30;
    
    //alert('edit add new book');
    
    //check current list of book size 
        //get number of div's with subjects
        var number_of_subjects = $('#editListOfBooks div.books-table').length;
        //get total div's from book-table-row (include 1 subject every book-table and list of books)
        var total_books_table_row = $('#editListOfBooks div.books-table-row').length;
        //get number of added books
        var added_list_of_books_size = $('#edit-books tr').length; 
        
    var current_number_of_books = total_books_table_row - number_of_subjects + added_list_of_books_size;
    //alert('current_number_of_books: ' + current_number_of_books);
    
    
    //get last index used
    var last_index = parseInt($('#edit-books').attr("data-last-index"));
    //alert('last_index: ' + last_index);
    
    //validate additional book fields if some added
    if(added_list_of_books_size > 0){
        //alert('do validation');
        var preview_book_isValid = editValidateBook();
        //alert('is valid?: ' + preview_book_isValid);
    }else{
        preview_book_isValid = true;
    }


    if(preview_book_isValid){
        var index = 0; //initlal text box count
        if(current_number_of_books < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#edit-books').append('<tr class="book" id="'+index+'">\n\
                                        <td class="subject">\n\
                                            <label>Subject:</label>\n\
                                            <input type="text" id="subject-'+index+'" name="editBookSubject" required>\n\
                                        </td>\n\
                                        <td class="book-title">\n\
                                            <label>Title:</label>\n\
                                            <input type="text" id="title-'+index+'" name="editBookTitle" autocomplete="off" required>\n\
                                        </td>\n\
                                        <td class="publisher">\n\
                                            <label>Publisher:</label>\n\
                                            <input type="text" id="publisher-'+index+'" name="editBookPublisher" required>\n\
                                        </td>\n\
                                        <td class="option book">\n\
                                            <label class="preview-book-list-label">\n\
                                                <a href="#" class="remove_field">X</a>\n\
                                            </label>\n\
                                        </td>\n\
                                </tr>');
            //set new last_index
            $('#edit-books').attr('data-last-index', index++);
        }else{
            alert('List of books is full.');
        }


        //remove book from list
        $('#edit-books').on("click",".remove_field", function(e){
            //alert('remove book');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove();
            
            $('#error-valid-last-book-edit').css({"display":"none"});

        });

    }

    
});

/*
 * do this function if the last book in the 
 * current list has been deleted 
 */
function addFirstBook(){
    
    var max_fields = 30;
    
    //alert('edit add new book');
    
    //check current list of book size 
        //get number of div's with subjects
        var number_of_subjects = $('#editListOfBooks div.books-table').length;
        //get total div's from book-table-row (include 1 subject every book-table and list of books)
        var total_books_table_row = $('#editListOfBooks div.books-table-row').length;
        //get number of added books
        var added_list_of_books_size = $('#edit-books tr').length; 
        
    var current_number_of_books = total_books_table_row - number_of_subjects + added_list_of_books_size;
    //alert('current_number_of_books: ' + current_number_of_books);
    
    
    //get last index used
    var last_index = parseInt($('#edit-books').attr("data-last-index"));
    //alert('last_index: ' + last_index);
    
    //validate additional book fields if some added
    if(added_list_of_books_size > 0){
        //alert('do validation');
        var preview_book_isValid = editValidateBook();
        //alert('is valid?: ' + preview_book_isValid);
    }else{
        preview_book_isValid = true;
    }


    if(preview_book_isValid){
        var index = 0; //initlal text box count
        if(current_number_of_books < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#edit-books').append('<tr class="book" id="'+index+'">\n\
                                        <td class="subject">\n\
                                            <label>Subject:</label>\n\
                                            <input type="text" id="subject-'+index+'" name="editBookSubject" required>\n\
                                        </td>\n\
                                        <td class="book-title">\n\
                                            <label>Title:</label>\n\
                                            <input type="text" id="title-'+index+'" name="editBookTitle" autocomplete="off" required>\n\
                                        </td>\n\
                                        <td class="publisher">\n\
                                            <label>Publisher:</label>\n\
                                            <input type="text" id="publisher-'+index+'" name="editBookPublisher" required>\n\
                                        </td>\n\
                                </tr>');
            //set new last_index
            $('#edit-books').attr('data-last-index', index++);
        }else{
            alert('List of books is full.');
        }


        //remove book from list
        $('#edit-books').on("click",".remove_field", function(e){
            //alert('remove book');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove(); 

        });

    }
   
}

//remove stationary from current stationaryList (stationatyList-edit) - build list to remove
$('#editListOfStationary').on("click","#edit_remove_stationary", function(e){  
        
     e.preventDefault();
     //alert('remove Stationary');
     
        var label = $(this).parent('label');
        var lable_parent_div = label.parent('div');
        var div_to_remove = lable_parent_div.parent('div');
        var stationaryID = div_to_remove.attr('stationaryID');//div_to_remove[0].getAttribute('data-pictureID');
        //alert('stationaryID to remove: ' + stationaryID);
        $("ul.stationary_to_remove").append('<li data-stationaryID='+stationaryID+'></li>');

        div_to_remove.remove();
        
        //var stationaryListEmpty = checkCurrentStationarySize();
        /*
         * check if revoved stationary was last stationary on list if is add first stationary fileds
         */
        var  number_of_current_stationary = $( "#edit-stationaryTable .stationary-table-row" ).length;
        //alert('number_of_current_subjects ' + number_of_current_subjects);
        if(number_of_current_stationary === 0){
            addFrstStationary();
            //alert('last stationary - addFrstStationary');
        }
});


/*
 *  List of stationary builder
 */
//add next stationary fields for bookList
$( "#editListOfStationary" ).on("click","#edit-add_stationary_button", function(e) {
    
    var max_fields = 30;
    
    //alert('Add new item to stationaty list');
    
    //check current list of stationary size 
        //get total div's from stationary-table
        var total_current_stationary_table_size = $('#editListOfStationary div.stationary-table-row').length;
        //alert('total_current_stationary_table_size: ' + total_current_stationary_table_size);
        //get number of added stationary
        var added_list_of_stationary_size = $('#edit-stationary tr').length; 
        
    var current_number_of_stationary = total_current_stationary_table_size + added_list_of_stationary_size;
    //alert('current_number_of_stationary: ' + current_number_of_stationary);
    
    
    //get last index used
    var last_index = parseInt($('#edit-stationary').attr("data-last-index"));
    //alert('last_index: ' + last_index);
    
    //validate additional book fields if some added
    if(added_list_of_stationary_size > 0){
        //alert('do validation');
        var preview_stationary_isValid = editValidateStationary();
        //alert('is valid?: ' + preview_stationary_isValid);
    }else{
        preview_stationary_isValid = true;
    }


    if(preview_stationary_isValid){
        var index = 0; //initlal text box count
        if(current_number_of_stationary < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#edit-stationary').append('<tr class="item" id="'+index+'">\n\
                                            <td class="item-name">\n\
                                                <label>Item name:</label>\n\
                                                <input type="text" id="item-name-'+index+'" name="editStationaryItemName" required>\n\
                                            </td>\n\
                                            <td class="qty">\n\
                                                <label>Quantity:</label>\n\
                                                <input type="text" id="qty-'+index+'" name="editStationaryQty" autocomplete="off" required>\n\
                                            </td>\n\
                                            <td class="option stationary">\n\
                                                <label class="preview-book-list-label">\n\
                                                    <a href="#" class="remove_field">X</a>\n\
                                                </label>\n\
                                            </td>\n\
                                        </tr>');
            //set new last_index
            $('#edit-stationary').attr('data-last-index', index++);
        }else{
            alert('List of stationary is full.');
        }


        //remove book from list
        $('#edit-stationary').on("click",".remove_field", function(e){
            //alert('remove stationary');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove(); 
            
            $('#error-valid-last-stationary-edit').css({"display":"none"});

        });

    }
    
});

/*
 * do this function if the last book in the 
 * current list has been deleted 
 */
function addFrstStationary(){
    var max_fields = 30;
    
    //alert('Add new item to stationaty list');
    
    //check current list of stationary size 
        //get total div's from stationary-table
        var total_current_stationary_table_size = $('#editListOfStationary div.stationary-table-row').length;
        //alert('total_current_stationary_table_size: ' + total_current_stationary_table_size);
        //get number of added stationary
        var added_list_of_stationary_size = $('#edit-stationary tr').length; 
        
    var current_number_of_stationary = total_current_stationary_table_size + added_list_of_stationary_size;
    //alert('current_number_of_stationary: ' + current_number_of_stationary);
    
    
    //get last index used
    var last_index = parseInt($('#edit-stationary').attr("data-last-index"));
    //alert('last_index: ' + last_index);
    
    //validate additional book fields if some added
    if(added_list_of_stationary_size > 0){
        //alert('do validation');
        var preview_stationary_isValid = editValidateStationary();
        //alert('is valid?: ' + preview_stationary_isValid);
    }else{
        preview_stationary_isValid = true;
    }


    if(preview_stationary_isValid){
        var index = 0; //initlal text box count
        if(current_number_of_stationary < max_fields){ //max input box allowed
            //increment index 
            index = last_index + 1;
            $('#edit-stationary').append('<tr class="item" id="'+index+'">\n\
                                            <td class="item-name">\n\
                                                <label>Item name:</label>\n\
                                                <input type="text" id="item-name-'+index+'" name="editStationaryItemName" required>\n\
                                            </td>\n\
                                            <td class="qty">\n\
                                                <label>Quantity:</label>\n\
                                                <input type="text" id="qty-'+index+'" name="editStationaryQty" autocomplete="off" required>\n\
                                            </td>\n\
                                        </tr>');
            //set new last_index
            $('#edit-stationary').attr('data-last-index', index++);
        }else{
            alert('List of stationary is full.');
        }


        //remove book from list
        $('#edit-stationary').on("click",".remove_field", function(e){
            //alert('remove stationary');
    
            var label = $(this).parent('label');
            var td = label.parent('td'); 
            td.parent('tr').remove(); 

        });

    }
}


/*
 * validation of added books fields
 */
function editValidateBook(last_index) {
    
    var validator = true;
     
    if($( "input[name='editBookSubject']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='editBookTitle']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='editBookPublisher']" ).valid() === false){
        return false;
    }

    return validator;
}

/*
 * validation of added stationary list
 */
function editValidateStationary(last_index) {
    
    var validator = true;
     
    if($( "input[name='editStationaryItemName']" ).valid() === false){
        return false;
    }
    
    if($( "input[name='editStationaryQty']" ).valid() === false){
        return false;
    }

    return validator;
}



function validLastBookInFormEdit(){
    
    var validate = true;
    
    $("#edit-books tr.book").each(function() {

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

function validLastStationaryInFormEdit(){
    
    var validate = true;
    
    $("#edit-stationary tr.item").each(function() {

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


