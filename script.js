$(".toggleForms").on("click", function(){
	$("#signUpForm").toggle();
	$("#logInForm").toggle();

});

$("#notes").on('input propertychange', function() {
            
	$.ajax({
	  method: "POST",
	  url: "updateDatabase.php",
	  data: {content: $("#notes").val() }
	})

  });