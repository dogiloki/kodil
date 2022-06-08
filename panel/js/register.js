function register(){
	document.getElementById('aviso').innerHTML='';
	var name=$("#name").val();
	var surname=$("#surname").val();
	var email=$("#email").val();
	var birth=$("#birth").val();
	var phone=$("#phone").val();
	var sexo=$("#sexo").val();
	var user=$("#user").val();
	var password=$("#password").val();
	var password_veri=$("#password_veri").val();
	var debito=$("#debito").val();
	$.ajax({
		url:"../controllers/user.php?v=register",
		method:"post",
		data:{
			'name':name,
			'surname':surname,
			'email':email,
			'birth':birth,
			'phone':phone,
			'sexo':sexo,
			'user':user,
			'password':password,
			'password_veri':password_veri,
			'debito':debito
		},
		success:function(value){
			$(".caja").css("border","1px solid #c2c2c2");
			$("#aviso").css("color","red");
			document.getElementById('aviso').innerHTML='';
			if(value=='vacio'){
				document.getElementById('aviso').innerHTML='Llena todos los campos';
			}
			if(value=='email'){
				document.getElementById('aviso').innerHTML='Este email ya esta registrado';
				$("#email").css("border","1px solid red");
			}
			if(value=='phone'){
				document.getElementById('aviso').innerHTML='Número telefonico no válido';
				$("#phone").css("border","1px solid red");
			}
			if(value=='birth'){
				document.getElementById('aviso').innerHTML='Eres menor de edad';
				$("#birth").css("border","1px solid red");
			}
			if(value=='user'){
				document.getElementById('aviso').innerHTML='El usuario ya existe';
				$("#user").css("border","1px solid red");
			}
			if(value=='password'){
				document.getElementById('aviso').innerHTML='Las contraseñas no coinciden';
				$("#password").css("border","1px solid red");
				$("#password_veri").css("border","1px solid red");
			}
			if(value=='registrado'){
				window.location='administra_cuenta.php';
			}
			if(value=='error'){
				document.getElementById('aviso').innerHTML='ERROR AL REGISTRARSE INTENTE MÁS TARDE';
			}
		}
	})
	.fail(function(){
		document.getElementById('aviso').innerHTML='ERROR AL REGISTRARSE INTENTE MÁS TARDE';
	})
}