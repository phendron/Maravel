$(document).ready(function(){		
		

	$("button.configure").on("click", function(e){
		e.preventDefault();

		$(".database-packages > div.col").hide();
		var col = $(this).parent().parent().parent();
		var package_name = col.find("input[name='package']").val();
		console.log("package: "+package_name);
		col.show();
		var select= $(".database-packages div.configure-database div.card div.card-body form select[name='DB_CONNECTION']");
		select.find("option:selected").removeAttr("selected");
		select.find("option[value='"+package_name+"']").attr("selected","selected");
		
		$(".database-packages div.configure-database").show();
		$(".database-packages").removeClass("row-cols-md-5");
		$(".database-packages").addClass("row-cols-md-2");
		
		
		$(".database-packages div.configure-database div.card div.card-footer button.close").on("click", function(){
		$(".database-packages > div.col").show();
		$(".database-packages div.configure-database").hide();
		$(".database-packages").removeClass("row-cols-md-2");
		$(".database-packages").addClass("row-cols-md-5");
		});
		
	});
	
var os_info = {name: $('input[name="system-os"]').val(), arch: $('input[name="system-arch"]').val() };

var system_name = os_info.name.split(" ")[0];

$("button.install-package").on("click", function(e){
	e.preventDefault();
	
	var fpackages = install_packages.getPackagesByOS(system_name);

	var package_name = $(this).attr("package");
	
	var spackage = fpackages.getPackagesByName(package_name).distros;
	if(spackage.length>0){
		spackage = spackage[0];
	if(spackage.supported){
		download(spackage.url);
	} else {
		alert(package_name+" is not supported on this system");
	}
	} else {
		console.log(fpackages);
		alert('package not found');
	}
});
	
function download(link){
	window.open(link, '_blank');
}
	
});