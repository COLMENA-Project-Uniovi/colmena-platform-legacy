function loadSearchErroresFamilies(){
	$(".select-errores").select2({
		width	: "100%"
	});

	$(".select-errores").on("change", function(){
		var errorID =  $(this).val();

		window.location = "./errors/"+errorID;

	});
	
}