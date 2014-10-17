/*
	función para ingresar en el cne y extraer los datos de un elector

	
*/
function revisarcne(txtci){
	var data = {};
	data.nacionalidad=txtci.substr(0, 1).toUpperCase();
	data.cedula=txtci.substr(1, 8);
	$.get('http://www.corsproxy.com/www.cne.gov.ve/web/registro_electoral/ce.php', data, function(xml){
		if(xml.indexOf("no corresponde a un elector")==-1){
			var res =  xml.substr(xml.indexOf('<table cellpadding="2" width="530">')+36 );
			res=res.substr(0,res.indexOf('</table>'));
			res=res.replace(/<\/?[^>]+>/gi, '').replace("\n","");
			persona={};
			persona.cedula = res.substr(res.indexOf("dula:")+6 ,(res.indexOf("Nombre:")-res.indexOf("dula:")-16) );
			persona.nombre=  res.substr(res.indexOf("mbre:")+6 ,(res.indexOf("Estado:")-res.indexOf("mbre:")-16) );
			persona.estado=  res.substr(res.indexOf("tado:")+6 ,(res.indexOf("Municipio:")-res.indexOf("tado:")-16) );
			persona.municipio=  res.substr(res.indexOf("ipio:")+6 ,(res.indexOf("Parroquia:")-res.indexOf("ipio:")-16) );
			persona.parroquia=  res.substr(res.indexOf("quia:")+6 ,(res.indexOf("Centro:")-res.indexOf("quia:")-16) );
			persona.centro=  	res.substr(res.indexOf("n:")+3 ,(res.length-res.indexOf("ntro:")-90));
			/*
				De aquí en adelante se puede realizar lo que se desee con la informacion obtenida
			*/
		}else{
			alert("El número de cédula no corresponde a ningún elector");
		}

	}).fail(function(){ 
  		alert("Error en el Votante Buscado");
	});
}