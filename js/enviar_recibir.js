window.onload=function(){
	$("#enviar").css("color","#308c3b");
	$("#enviar").css("box-shadow","0px 1px #308c3b");
	$("#content-info").css("display","");
}
function cambio(op){
	document.getElementById('aviso').innerHTML='';
	$(".texto").css("color","#666");
	$(".texto").css("box-shadow","0px 1px #666");
	$("#"+op).css("color","#308c3b");
	$("#"+op).css("box-shadow","0px 1px #308c3b");

	$(".content-datos").css("display","none");
	$("#content-"+op).css("display","");
}
function enviar(qr){
	document.getElementById('aviso').innerHTML='';
	var cuenta=$("#cuenta-enviar").val();
	var cantidad=$("#cantidad-enviar").val();
	var password=$("#password-enviar").val();
	$("#btn-enviar-qr").attr("disabled");
	$("#btn-enviar").attr("disabled");
	$("#btn-enviar-qr").css("opacity","0.5");
	$("#btn-enviar").css("opacity","0.5");
	document.getElementById('qr').innerHTML="";
	$.ajax({
		url:"controllers/cuenta.php?v=enviar&qr="+qr,
		method:"post",
		data:{
			'cuenta':cuenta,
			'cantidad':cantidad,
			'password':password
		},
		success:function(value){
			$("#cuenta-enviar").css("border","1px solid #c2c2c2");
			$("#cantidad-enviar").css("border","1px solid #c2c2c2");
			$("#password-enviar").css("border","1px solid #c2c2c2");
			$("#aviso").css("color","red");
			$("#btn-enviar-qr").removeAttr("disabled");
			$("#btn-enviar").removeAttr("disabled");
			$("#btn-enviar-qr").css("opacity","1");
			$("#btn-enviar").css("opacity","1");
			if(value=='vacio'){
				document.getElementById('aviso').innerHTML='Llena todos campos';
			}
			if(value=='cuenta'){
				document.getElementById('aviso').innerHTML='La cuenta no existe';
				$("#cuenta-enviar").css("border","1px solid red");
			}
			if(value=='cantidad'){
				document.getElementById('aviso').innerHTML='Saldo insuficiente';
				$("#cantidad-enviar").css("border","1px solid red");
			}
			if(value=='password'){
				document.getElementById('aviso').innerHTML='Contraseña incorrecta';
				$("#password-enviar").css("border","1px solid red");
			}
			if(value=='error' || value=='errorerror'){
				document.getElementById('aviso').innerHTML='ERROR AL ENVÍAR INTENTE MÁS TARDE';
			}
			if(value=='enviado'){
				$("#cuenta-enviar").val('');
				$("#cantidad-enviar").val('');
				$("#password-enviar").val('');
				$("#aviso").css("color","#308c3b");
				document.getElementById('aviso').innerHTML='Dinero enviado';
			}
			if(value=='qr'){
				$("#cuenta-enviar").val('');
				$("#cantidad-enviar").val('');
				$("#password-enviar").val('');
				$("#aviso").css("color","#308c3b");
				document.getElementById('aviso').innerHTML="El código caduca a los 7 dias";
				show_qr();
			}
		}
	})
	.fail(function(){
		document.getElementById('aviso').innerHTML='ERROR AL ENVÍAR INTENTE MÁS TARDE';
	})
}
function show_qr(){
	$.ajax({
		url:"controllers/cuenta.php?v=show-qr",
		method:"post",
		data:{},
		success:function(value){
			document.getElementById('qr').innerHTML=value;
		}
	})
	.fail(function(){
		document.getElementById('aviso').innerHTML='ERROR AL MOSTRAR QR INTENTE MÁS TARDE';
	})
}
function recibir(){
	event.preventDefault();
	document.getElementById('aviso-qr').innerHTML='';
	document.getElementById('info').innerHTML='';
	$("#btn-recibir").attr("disabled");
	$("#btn-recibir").attr("disabled");
	$("#btn-recibir").css("opacity","0.5");
	$("#btn-recibir").css("opacity","0.5");
	var datos = new FormData();
	datos.append('file', $('#file-recibir')[0].files[0]);
	datos.append('password',$('#password-recibir').val());
	$.ajax({
		url:"controllers/cuenta.php?v=recibir",
		method:"post",
		contentType: "multipart/form-data",
		contentType: false,
		data: datos,
		processData: false,
		cache: false, 
		success:function(value){
			$("#file-recibir").css("border","1px solid #c2c2c2");
			$("#password-recibir").css("border","1px solid #c2c2c2");
			$("#aviso-qr").css("color","red");
			$("#btn-recibir").removeAttr("disabled");
			$("#btn-recibir").removeAttr("disabled");
			$("#btn-recibir").css("opacity","1");
			$("#btn-recibir").css("opacity","1");
			if(value=='vacio'){
				document.getElementById('aviso-qr').innerHTML='Llena todos campos';
			}
			if(value=='no_valido'){
				document.getElementById('aviso-qr').innerHTML='Archivo no válido';
				$("#password").css("border","1px solid red");
			}
			if(value=='password'){
				document.getElementById('aviso-qr').innerHTML='Contraseña incorrecta';
				$("#password").css("border","1px solid red");
			}
			if(value=='cobrado'){ 
				document.getElementById('aviso-qr').innerHTML='Este codigo ya ha sido cobrado';
			}
			if(value=='no_es_tuyo'){ 
				document.getElementById('aviso-qr').innerHTML='No puedes cobrar';
			}
			if(value=='misma_cuenta'){ 
				document.getElementById('aviso-qr').innerHTML='No puedes enviarte dinero a ti mismo';
			}
			if(value=='transferencia'){ 
				document.getElementById('aviso-qr').innerHTML='Este código no existe';
			}
			if(value=='recibido'){
				$("#aviso-qr").css("color","#308c3b");
				document.getElementById('aviso-qr').innerHTML='Transferencia correcta';
				show_info();
			}
			if(value=='error1' || value=='errorerror'){
				document.getElementById('aviso-qr').innerHTML='ERROR AL COBRÁR INTENTE MÁS TARDE';
			}
		}
	})
	.fail(function(){
		document.getElementById('aviso-qr').innerHTML='ERROR AL COBRÁR INTENTE MÁS TARDE';
	})
}
function show_info(){
	$.ajax({
		url:"controllers/cuenta.php?v=show-info",
		method:"post",
		data:{},
		success:function(value){
			document.getElementById('info').innerHTML=value;
		}
	})
	.fail(function(){
		document.getElementById('info').innerHTML='ERROR AL MOSTRAR INFORMACIÓN';
	})	
}