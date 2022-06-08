function login(){
	var user=$("#user").val();
	var password=$("#password").val();
	$.ajax({
		url:"controllers/user.php?v=login",
		method:"post",
		data:{
			'user':user,
			'password':password
		},
		success:function(value){
			if(value=='logiado'){
				window.history.back();
			}
			if(value=='login-admin'){
				window.location='panel/index.php';
			}
			$(".caja").css("border","1px solid #c2c2c2");
			document.getElementById('aviso').innerHTML='';
			if(value=='vacio'){
				document.getElementById('aviso').innerHTML='Llene todos los campos';
				$("#user").css("border","1px solid red");
				$("#password").css("border","1px solid red");
			}
			if(value=='error' || value=='errorerror'){
				document.getElementById('aviso').innerHTML='El usuario o contraseña son incorrectas';
			}
		}
	})
	.fail(function(){
		document.getElementById('aviso').innerHTML='ERROR AL REGISTRARSE INTENTE MÁS TARDE';
	})
}
function login2(){
	var keycode=event.keyCode;
    if(keycode=='13'){
		var user=$("#user").val();
		var password=$("#password").val();
		$.ajax({
			url:"controllers/user.php?v=login",
			method:"post",
			data:{
				'user':user,
				'password':password
			},
			success:function(value){
				$(".caja").css("border","1px solid #c2c2c2");
				document.getElementById('aviso').innerHTML='';
				if(value=='vacio'){
					document.getElementById('aviso').innerHTML='Llene todos los campos';
					$("#user").css("border","1px solid red");
					$("#password").css("border","1px solid red");
				}
				if(value=='error' || value=='errorerror'){
					document.getElementById('aviso').innerHTML='El usuario o contraseña son incorrectas';
				}
				if(value=='logiado'){
					window.history.back();
				}
				if(value=='login-admin'){
					window.location='panel/index.php';
				}
			}
		})
		.fail(function(){
			document.getElementById('aviso').innerHTML='ERROR AL REGISTRARSE INTENTE MÁS TARDE';
		})
	}
}