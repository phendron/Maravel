
$(document).ready(function(){
	
	var filter= [];
	filter['datetime'] = $("filter select[name='datetime']");
	filter['error'] = $("filter select[name='error']");
	
	var errors = $("table tbody tr");
	
	filter['datetime'].on("change", function(e){
		e.preventDefault();
		
		var value = $(this).find(":selected").val();
	});
	
	filter['error'].on("change", function(e){
		e.preventDefault();


		var value = $(this).find(":selected").val();
		if(value!='NONE'){
		errors.hide();
		errors.each(function(){
			if($(this).find("td.error-level").attr("value") == value){
				$(this).show();
			}
		});
		} else {
			errors.show();
		}
	});
	
	
});