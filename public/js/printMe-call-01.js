/* 
 * Call jquery-printMe 
 */

// print book list
$(".option-trigger-printMe-bookList").click(function(e){
    
    $("#bookListContent").printMe({ "path": ["css/boy_school-06.css"] });
});

// print policyOrgin
$(".option-trigger-printMe-policyOrgin").click(function(e){
    
   $("#policyContentOrigin").printMe({ "path": ["css/boy_school-06.css"] });
});

// print Policy
$(".option-trigger-printMe-policy").click(function(e){
    
    $("#policyContent").printMe({ "path": ["css/boy_school-06.css"] });

});


