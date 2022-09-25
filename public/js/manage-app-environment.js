// JavaScript Document

$(document).ready(function(){
	
	var project = $("input[name='project']").val();
	
	var buttons = $(".window button");
	buttons.on("click", function(e){
		e.preventDefault();
		
		buttons.removeClass("active");
		buttons.removeAttr("aria-pressed");
		$(this).addClass("active");
		$(this).attr("aria-pressed",true);
		var action = $(this).attr("action");
		$("form section").hide();
		$("section."+action).show();
	});
	
	var notify_success = $("notification msg.success");
	var notify_error = $("notification msg.error");
	var notify_msgs = $("notification msg");
	
	var form = $("form.environment");
	$("form.environment button.submit").on("click", function(e){
	    e.preventDefault();
		
		var form_data = form.serializeArray();
		
		$.ajax({
			url: "../"+project+"/api",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, action: 'update', type: 'environment', data: form_data}
		}).done(function(msg){
			var packet = JSON.parse(msg);
			notify_msgs.hide();
			if(packet['success']){
				notify_success.show();
			} else {
				notify_success.hide();
			}
		});
	});
	
});