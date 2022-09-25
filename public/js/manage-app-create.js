// JavaScript Document

$(document).ready(function(){
	
	
	
	$("button.submit").on("click", function(e){
		e.preventDefault();
		
		var project = $("input[name='name']").val();
		
		$.ajax({
			url: "create",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, type: "create"}
		}).done(function(msg){
			var packet = JSON.parse(msg);
			if(packet['success']==true){
					// show success redirect to manage page
				} else {
					// display error message
				}
		});
		
	})
});