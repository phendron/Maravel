// JavaScript Document#

$(document).ready(function(){
	
	function updateCSFRToken(token=false){
		$('meta[name="csrf-token"]').attr('content', token);	
	}
	
	var project = $("input[name='name']").val();
	
	var app_info = $("section.app-info");
	var host = app_info.find(".host");
	var port = app_info.find(".port");
	
	$("button.action-btn").on("click", function(e){
		e.preventDefault();
		console.log("button clicked");
		var action = $(this).attr("action");
		var type = $(this).attr("action-type");
		var button = $(this);
		var status = $(this).parent().parent().parent().parent().find("span.badge");
		var states = null;
		var app_link = $("a.app-link");
		$.ajax({
			url: project+"/api",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, action: action, type: type}
		}).done(function(msg){
			console.log("we done ajax");
			var packet = JSON.parse(msg);
			packet = packet['success'];
			if(packet['csrf-token']){updateCSFRToken(packet['csrf-token']);}
			if(packet['success']==true){
				if(button.hasClass("btn-danger")){
					button.removeClass("btn-danger");
					button.addClass("btn-success");
					button.text("Enable");
					button.attr("action","1");
					states = status.attr("state-text");
					states = states.replace(/'/g, '"');
					states = JSON.parse(states);
					console.log(states);
					status.text(states[0]);
					if(type=="serve"){app_link.addClass("disabled"); app_link.attr("href","#");}
				} else if(button.hasClass("btn-success")){
					button.removeClass("btn-success");
					button.addClass("btn-danger");
					button.text("Disable");
					button.attr("action","0");
					states = status.attr("state-text");
					states = states.replace(/'/g, '"');
					states = JSON.parse(states);
					console.log(states);
					status.text(states[1]);
				}

				if(type=="serve"){
				setTimeout(function(){getProcessInfo();}, 2000);}

			}
		});
	});
	
	
	function getProcessInfo(){
		var app_link=$("a.app-link");
		$.ajax({
			url: project+"/api",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, action: null, type: 'process'}
		}).done(function(msg){
			var packet = JSON.parse(msg);
			updateCSFRToken(packet['csrf-token']);
			if(packet['success']==true){
				var data = packet['data'];
				host.text(data['host']);
				port.text(data['port']);
				app_link.attr("href","http://"+data['host']+":"+data['port']);
				app_link.removeClass("disabled");
			}
		});
	}
	
	
	
	
	function validateRouteCache(){
		$.ajax({
			url: project+"/api",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, action: 'route', type: 'cache-check'}
		}).done(function(msg){
			var packet = JSON.parse(msg);
			packet = packet['success'];
			if(packet['success']){
			    var data = packet['data'];
				if(data['valid']==false){
					$(".refresh-route-cache").show();
				}
			}
		});
	}

	function validateViewCache(){
		$.ajax({
			url: project+"/api",
			type: "post",
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: {project: project, action: 'view', type: 'cache-check'}
		}).done(function(msg){
			var packet = JSON.parse(msg);
			packet = packet['success'];
			if(packet['success']){
			    var data = packet['data'];
				if(data['valid']==false){
					$(".refresh-view-cache").show();
				}
			}
		});
	}
	
	setInterval(function(){validateRouteCache();}, 1000);
	setInterval(function(){validateViewCache();}, 1000);
	
});
