function generaIndicadores()
{
	$.ajax({
	  	type: "POST",
	  	url: "ajax/calculaIndicadores.php",
	  	data: $("#formReporte").serialize(true),
		beforeSend: function(){
			$('#caminoExcelencia').html('<center><img src="images/loading.gif"></center>');
		},
	  	success: function(response) {
			$("#caminoExcelencia").html(response)//alert(response);
			//removeLoading()
		},
		error:function(){
			  alert("Something went wrong...");
		}
    });
}