function active(id){
	$.ajax({
		url:"controllers/cuenta.php?v=active",
		method:"post",
		data:{'id':id},
		success:function(value){
			$("#activar"+id).attr("src",value);
		}
	})
	.fail(function(){
		alert("ERROR EN SERVIDOR");
	})
}
function admin(id){
	$.ajax({
		url:"controllers/cuenta.php?v=admin",
		method:"post",
		data:{'id':id},
		success:function(value){
			$("#admin"+id).attr("src",value);
		}
	})
	.fail(function(){
		alert("ERROR EN SERVIDOR");
	})
}
function eliminar(id){
	if(confirm("Esta seguro de eliminar")){
		$.ajax({
			url:"controllers/cuenta.php?v=delete",
			method:"post",
			data:{'id':id},
			success:function(value){
				if(value=='error_eliminar'){
					alert("ERROR AL ELIMINAR");
				}
				if(value=='eliminado'){
					modal(id);
				}
			}
		})
		.fail(function(){
			alert("ERROR EN SERVIDOR");
		})
	}
}