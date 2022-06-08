function update_user(){
	event.preventDefault();
	document.getElementById('aviso').innerHTML='';
	var datos = new FormData();
	datos.append('file', $('#file')[0].files[0]);
	datos.append('user',$('#user').val());
	datos.append('password',$('#password').val());
	datos.append('password-new',$('#password-new').val());
	$.ajax({
		url:"controllers/user.php?v=update-user",
		method:"post",
		contentType: "multipart/form-data",
		contentType: false,
		data: datos,
		processData: false,
		cache: false, 
		success:function(value){
			if(value=="update"){
				window.location="login.php";
			}else{
				document.getElementById('aviso').innerHTML=value;
			}
		}
	})
	.fail(function(){
		document.getElementById('aviso').innerHTML='ERROR AL ACTUALIZAR DATOS MÁS TARDE';
	})
}