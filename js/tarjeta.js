window.onload=function(){
	$("#info").css("color","#308c3b");
	$("#info").css("box-shadow","0px 1px #308c3b");
	$("#content-info").css("display","");
}
function cambio(op){
	$(".texto").css("color","#666");
	$(".texto").css("box-shadow","0px 1px #666");
	$("#"+op).css("color","#308c3b");
	$("#"+op).css("box-shadow","0px 1px #308c3b");

	$(".content-datos").css("display","none");
	$("#content-"+op).css("display","");
}
function cancelar(tarjeta){
	if($('#cancelar').prop('checked')){
    	var status="cancelar";
	}else{
		var status="descancelar";
	}
	$.ajax({
		url:"controllers/tarjeta.php?v=cancelar",
		method:"post",
		data:{
			'tarjeta':tarjeta,
			'status':status
		},
		success:function(value){
			if(value=='cance'){
				alert("TARJETA CANCELADA");
			}
			if(value=='desca'){
				alert("TARJETA DESCANCELADA");
			}
			if(value=='error' || value=='errorerror'){
				alert("ERROR AL CANCELAR INTENTE MÁS TARDE");
			}
		}
	})
	.fail(function(){
		alert("ERROR AL CANCELAR INTENTE MÁS TARDE");
	})
}