<?php
header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
//header("Access-Control-Allow-Headers: X-Requested-With"); 
 


if(isset($_POST['m'])){

	$user = $_POST['vx'];
	$clave = $_POST['vy'];

	if(validaAccesos($user, $clave)==1){

		switch ($_POST['m']) {
			case 100:
				validaLogin($_POST['ui'], $_POST['pw']);
				break;
			case 101:
				getMarks($_POST['ui']);
				break;
			case 102:
				getKPISCS($_POST['ui'], $_POST['f']);
				break;
			case 103:
				getDashHorus($_POST['ui']);
				break;
			case 104:
				getFordis09Sucursales($_POST['id_dealer'],$_POST['anomes']);
				break;
			case 105:
				getFordis09SucursalesDet($_POST['id_sucursal'],$_POST['anomes']);
				break;
			case 201:
				setMarks($_POST['f'], $_POST['ui'], $_POST['lat'], $_POST['lng'], 0);
				break;
			case 202:
				//setDatosCC($_POST['vt'], $_POST['company']);
				setDatosCC(	$_POST['id_cc'], $_POST['telefono'], 
							$_POST['tipo_contacto'], $_POST['agente'], 
							$_POST['direccion'], $_POST['lugar_trabajo'], 
							$_POST['ingreso_aprox'], $_POST['acepta'], 
							$_POST['razon_rechazo'], $_POST['comentario'], 
							$_POST['fecha'], $_POST['app'], 
							$_POST['tipoplan'], $_POST['codplan'], 
							$_POST['incidente'], $_POST['identidad'], 
							$_POST['campana'], $_POST['tipo_llamada'], 
							$_POST['dpg'],$_POST['Estatus'],$_POST['Subestatus'] );
				break;
			case 203:
				setDatosCC2($_POST['id_cc'], $_POST['telefono1'], 
							$_POST['telefono2'], $_POST['agente'], 
							$_POST['acepta'], $_POST['razon_rechazo'], 
							$_POST['fecha'], $_POST['campana'], 
							$_POST['id_campania']);	
				
				break;
			case 204:
				getDatosUsuario($_POST['ui']);
				break;

			case 205: // opcion call center resuelva

				setDatosCC3($_POST['agente'],$_POST['telefono'], $_POST['vendedor'], 
							$_POST['fecha_gestion'], $_POST['tipificacion'], 
							$_POST['plan_activado'] );		

				break;	
				
			case 301:
				getFormsDMS($_POST['ui']);
				break;
			case 302:
				saveFormsApp($_POST['ui'], $_POST['forms']);
				break;
			case 303:
				saveFileServer($_POST['arrFile']);
				break;
			case 304:
				getFileServer($_POST['idFile']);
				break;
			case 305:
				getReporteVentas($_POST['id_dms'], $_POST['aniomes']);
				break;
			case 306:
				setCierreVentas($_POST['ui']);
				break;
			case 310:
				//echo "console.log( 'Debug Objects: " . 'opcion de planing validada ' . "' );";	
				getPlanDMS($_POST['ui']);
				break;
			//Referencias de deposito - Portal SO
			case 401:
				getRefenciaDeposito($_POST['idReferencia']);
				break;
			case 450 :


				callWS($_POST['id_gestion']);	
				break;	
			
			case 402 :

			insertReverse($_POST['id_ingreso'],	
		 	$_POST['cod_equipo'],	
		 	$_POST['serie_p'],
		 	$_POST['serie_s'],
		 	$_POST['serie_o'],
		 	$_POST['commentary'],	
		 	$_POST['flujo'],	
		 	$_POST['estado'],	
		 	$_POST['serie_asociada'],	
		 	$_POST['ciclo_completo'],	
		 	$_POST['reingreso'],	
		 	$_POST['traslado'],	
		 	$_POST['diagnostico'],
		 	$_POST['correcion_diagnostico'],	
		 	$_POST['country']
 			);

			break;	


			default:
				echo 'Opcion No Valida';
				break;
		}
	}else{
		echo 'Error de Accesos';
	}
	

}else{
	echo 'Error de consulta';
}


function insertReverse(

$id_ingreso,
$cod_equipo,
$serie_p,
$serie_s,
$serie_o,
$commentary,
$flujo,
$estado,
$serie_asociada,
$ciclo_completo,
$reingreso,
$traslado,
$diagnostico,
$correcion_diagnostico,
$country

) {

	try{

		include 'db_conection.php';

		$vQry ="insert into tbl_rg_reverse_det_bk_ja (";
		$vQry .="id_ingreso,";
		$vQry .="cod_equipo,";
		$vQry .="serie_p,";
		$vQry .="serie_s,";
		$vQry .="serie_o,";
		$vQry .="commentary,";
		$vQry .="flujo,";
		$vQry .="estado,";
		$vQry .="serie_asociada,";
		$vQry .="ciclo_completo,";
		$vQry .="reingreso,";
		$vQry .="traslado,";
		$vQry .="diagnostico,";
		$vQry .="correcion_diagnostico,";
		$vQry .="country";
		$vQry .=")  values (";
		$vQry .="$id_ingreso,";
		$vQry .="'$cod_equipo',";
		$vQry .="'$serie_p',";
		$vQry .="'$serie_s',";
		$vQry .="'$serie_o',";
		$vQry .="'$commentary',";	
		$vQry .="$flujo,";	
		$vQry .="$estado,";	
		$vQry .="$serie_asociada,";	
		$vQry .="$ciclo_completo,";	
		$vQry .="$reingreso,";	
		$vQry .="$traslado,";	
		$vQry .="$diagnostico,";
		$vQry .="$correcion_diagnostico,";	
		$vQry .="'$country'";
		$vQry .=')';

		$stmt = $conn->prepare($vQry);
		if(!$stmt->execute()){
	    	echo $conn->errorInfo();
	    }else{
			echo 'SUCCESS';
	    }
	    /*var_dump($row);*/
	}catch(Exception $e){		
		echo $e;
	}


} // fin de la funcion reverse

function callWS($id_gestion){

//echo 'success prro <br>';
//echo $id_gestion;

$url = 'http://192.168.107.223/dossier/api/Transactions/Confirm/'.$id_gestion;
//$data = $id_gestion;  //array('key1' => 'value1', 'key2' => 'value2');
$params =  array('idgestion' => $id_gestion);

// use key 'http' even if you send the request to https://...

$options = array(
    'http' => array(
        'header'  => "Content-type: text/plain",
        'method'  => 'POST',//,
        'content' => http_build_query($params)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);


if ($result === FALSE) { 

	echo 'error ';  
	$error = error_get_last();
    echo $error['message']; 


    }

echo $result; //var_dump($result);


}

function validaAccesos($vId, $vClave){
	$vReturn = 0;
	try{
		include 'db_bocload.php';
		$vQry = 'SELECT  count(1) as activo FROM tbl_credenciales_ws where estado=1 and id=\'' . $vId . '\' and clave=\'' . $vClave . '\'';

	    foreach($conn->query($vQry) as $row){
	    	if($row[0] == 1){	    		
	    		$vReturn = 1;
	    	}else{
	    		$vReturn = 0;
	    	}
			return $vReturn;
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		return $vReturn;
		//echo $e;
	}
	return $vReturn;

}


// Obtener las marcaciones por vendedor
function getMarks($idUser){
	$json_result = array();
	try{
		include 'db_conection.php';
		$vQry = 'SELECT usuario_vndr, fecha_marca, lat, lng, estado FROM tbl_scs_mrks_vdr where upper(usuario_vndr)=upper(\'' . $idUser . '\')';
		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("user"=>$row[0], "lat"=>$row[2], "lng"=>$row[3], "fech"=>$row[1]);
	    	array_push($json_result, $temp_arr);
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}


// Funcion para Para insertar los dotos obtenidos del app GPS
function setMarks($fech, $idUser, $lat, $lng, $estado){
	$json_result = array();
	try{		
		include 'db_bocload.php';

		$vQry = 'INSERT INTO tbl_scs_mrks_vdr '; //(fecha_marca, usuario_vndr,lat,lng,estado,geometry) VALUES(?,?,?,?,?,?)';
		$vQry .= 'SELECT upper(\'' . $idUser;
		$vQry .= '\'),' . $fech;
		$vQry .= ',' . $lat;
		$vQry .= ',' . $lng;
		$vQry .= ',' . $estado;
		$vQry .= ',SDO_GEOMETRY(2001,8307,SDO_POINT_TYPE('. $lng .','. $lat .',NULL),NULL,NULL)';
		$vQry .= ' FROM DUAL';

		$stmt = $conn->prepare($vQry);
		if(!$stmt->execute()){
	    	echo $conn->errorInfo();
	    }else{
			echo 'SUCCESS';
	    }
	    /*var_dump($row);*/
	}catch(Exception $e){		
		echo $e;
	}
}


// Obtener las marcaciones por vendedor
function getKPISCS($vUser, $vFech){
	$json_result = array();
	//$vUser = 'VD.TANIA.BRITO';
	try{
		include 'db_conection.php';

		/*$vQry = 'SELECT fech, meta, prospecciones, promedio_prospecciones FROM tbl_prospectos_resumen_kpi ';
		$vQry .= 'where upper(usuario_creador) =upper(\'' . $vUser . '\')';*/
		$vQry =  'SELECT  101 kpi,'; 
		$vQry .= '		  a.fech,';
		$vQry .= '        a.usuario_creador,';
		$vQry .= '        a.meta,';
		$vQry .= '        a.prospecciones,';
		$vQry .= '        a.promedio_prospecciones, ';
		$vQry .= '        substr(b.nombres,0,INSTR(b.nombres, \' \')) || substr(b.apellidos,0,INSTR(b.apellidos, \' \') -1) vendedor ';
		$vQry .= 'FROM    tbl_prospectos_resumen_kpi a,';
		$vQry .= '        tbl_boc_usuarios b ';
		$vQry .= 'where   a.usuario_creador = b.usuario';
		$vQry .= '        and upper(a.usuario_creador) =upper(\'' . $vUser . '\')';
		$vQry .= '		  and a.fech = ' . $vFech . ' ';
		$vQry .= ' union all ';
		$vQry .= 'SELECT  102 kpi,';
		$vQry .= '		  a.fech,';
		$vQry .= '        a.usuario_creador,';
		$vQry .= '        a.meta,';
		$vQry .= '        a.prospecciones,';
		$vQry .= '        a.promedio_prospecciones,';
		$vQry .= '        substr(b.nombres,0,INSTR(b.nombres, \' \')) || substr(b.apellidos,0,INSTR(b.apellidos, \' \') -1) vendedor ';
		$vQry .= 'FROM    tbl_prospectos_resumen_home a,';
		$vQry .= '		  tbl_boc_usuarios b ';
		$vQry .= 'where   a.usuario_creador = b.usuario';
		$vQry .= '        and upper(a.usuario_creador) =upper(\'' . $vUser . '\')';
		$vQry .= '		  and a.fech = ' . $vFech . ' ';


		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("id_kpi"=>$row[0], "fech"=>$row[1], "meta"=>$row[3], "prospecciones"=>$row[4], "promedio_prospecciones"=>$row[5], "vendedor"=>$row[6]);
	    	array_push($json_result, $temp_arr);
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo($e);
	}

	echo JSON_encode($json_result);
}

// Funcio para validad accesos a la plataforma
function validaLogin($vUsuario, $vPDW){
	$json_result = array();
	$temp_dtos = array();

	//echo $vUsuario,;

	try{
		include 'db_conection.php';

		$vQry = 'select login_scs(\''. $vUsuario . '\',\'' . $vPDW  .'\') as flag from dual';
		$temp_arr = array();

		foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("flag"=>$row[0], "vdatos"=>array());
	    	//array_push($json_result, $temp_arr);
	    }

	    if($temp_arr["flag"]=='true'){


	    	//Get datos del usuario

		    	$vQry = "SELECT usuario, 
		    			   nombres || ' ' || apellidos nombre, 
		    			   telefono, 
		    			   correo,
					       'app_dms' job,
					       id_dms,
					       TO_CHAR (SYSDATE + 365, 'YYYYMMDD') license,
					       DECODE (perfil, 89, 101, 201) perfil
					  FROM tbl_boc_usuarios
					 WHERE 1 = 1
					 and UPPER(usuario) ='".strtoupper($vUsuario)."'";

					//echo $vQry; 

					foreach($conn->query($vQry) as $row){

			    	$temp_dtos = array("user"=>$row[0], "name"=>$row[1], "phone"=>$row[2], "email"=>$row[3], "job"=>$row[4], "id_dms"=>$row[5], "license"=>$row[6], "perfil"=>$row[7], "dlr_pdv_dms"=>$row[5]);	
			    }	 

		    

		    if(count($temp_dtos)==0){
		    	$temp_dtos = array("user"=>'NA', "name"=>'NA', "phone"=>'0', "email"=>'NA', "job"=>'NA', "id_dms"=>'0', "license"=>'0', "perfil"=>'0');	
		    }
	    }	    

	    array_push($temp_arr["vdatos"], $temp_dtos);
	    array_push($json_result, $temp_arr);

	}catch(PDOException $e){

		echo($e);
	}

	echo JSON_encode($json_result);
}


function getDatosUsuario($vUsuario){
	$json_result = array();
	
	try{
		include 'db_conection.php';


		 // if  ( stripos(strtoupper($vUsuario),'APP.DMS') !== 0 ) //(strtoupper($vUsuario) <>'APP.DMS.JAIRO.COREA')
		 // { 	

	  //   $vQry = 'select * from boc_load.tbl_app_usuarios where upper(usuario) = \''. strtoupper($vUsuario) . '\'';			

			// 	foreach($conn->query($vQry) as $row){
			//     	array_push($json_result, array("user"=>$row[0], "name"=>$row[3], "phone"=>$row[4], "email"=>$row[5], "job"=>$row[8], "id_dms"=>$row[12], "license"=>$row[10], "perfil"=>$row[6], "dlr_pdv_dms"=>$row[9]));	
			// }

	  //  }else {

		    	$vQry = "SELECT usuario, nombres || ' ' || apellidos nombre, telefono, correo,
					       'app_dms' job,
					       id_dms,
					       TO_CHAR (SYSDATE + 365, 'YYYYMMDD') license,
					       DECODE (perfil, 89, 101, 201) perfil
					  FROM tbl_boc_usuarios
					 WHERE 1 = 1
					 and UPPER(usuario) ='".strtoupper($vUsuario)."'";

					 //echo $vQry;

				foreach($conn->query($vQry) as $row ){

			    	array_push($json_result, array("user"=>$row[0], "name"=>$row[1], "phone"=>$row[2], "email"=>$row[3], "job"=>$row[4], "id_dms"=>$row[5], "license"=>$row[6], "perfil"=>$row[7], "dlr_pdv_dms"=>$row[5]));	
			    }	 	
		   //}	


	    if(count($json_result)==0){
	    	array_push($json_result,array("user"=>'NA', "name"=>'NA', "phone"=>'0', "email"=>'NA', "job"=>'NA', "id_dms"=>'0', "license"=>'0', "perfil"=>'0'));	
	    } 

     
	}catch(PDOException $e){
		echo($e);
	}

	echo JSON_encode($json_result);
}


function setDatosCC3($agente,$telefono, $vendedor, $fecha_gestion,$tipificacion , $plan_activado ){ 							
							
	if(strlen($agente)==0 ){
		echo 'Datos de Agente Incorrectos';
		return;
	}

	if(strlen($telefono)==0 ){
		echo 'Datos de Telefono Incorrectos';
		return;
	}

	if(strlen($tipificacion)==0 ){
		echo 'Datos de Tipificacion Incorrectos';
		return;
	}
	
	// if(strlen($vendedor)==0 ){
	// 	echo 'Datos de Vendedor Incorrectos';
	// 	return;
	// }
	
	// if(strlen($fecha_gestion)==0 ){
	// 	echo 'Datos de Fecha Gestion Incorrectos';
	// 	return;
	// }
	
	

	// if(strlen($plan_activado)==0 ){
	// 	echo 'Datos de Plan Activado Incorrectos';
	// 	return;
	// }

	try{		
		include 'db_bocload.php';

		$vQry = '';
		//$vQry = 'INSERT INTO tbl_datos_cc_test (text_prueba, company, fecha) '; //(fecha_marca, usuario_vndr,lat,lng,estado,geometry) VALUES(?,?,?,?,?,?)';
		//$vQry .= 'values(\'' . $text . '\',\'' . $company . '\', sysdate)';
		$vQry .= 'INSERT INTO  tbl_cc_gestiones_resuelva (id,';
		$vQry .= 'agente,';
		$vQry .= 'telefono,';
		$vQry .= 'vendedor,';
		$vQry .= 'fecha_gestion,';
		$vQry .= 'tipificacion,';
		$vQry .= 'plan_activado';
		$vQry .= ')';

		
		
		$vQry .= 'VALUES(';
		$vQry .= 'seq_cc_gestiones_resuelva.NEXTVAL,';
		$vQry .= '\'' . $agente . '\',';
		$vQry .= '\'' . $telefono . '\',';
		$vQry .= '\'' . $vendedor . '\',';
		$vQry .= 'to_date(\'' . $fecha_gestion . '\', \'yyyy-mm-dd hh24:mi:ss\'),';
		$vQry .= '\'' . $tipificacion . '\',';
		$vQry .= '\'' . $plan_activado . '\'';
		$vQry .= ')';

		$stmt = $conn->prepare($vQry);
		if(!$stmt->execute()){
			$a_err = [];
	    	$a_err = $conn->errorInfo();
	    	if (strpos($a_err[2],'ORA') == false){
				echo $a_err[2];
	    	}else{
	    		echo 'ERROR DE INCONSISTENCIA DE DATOS';
	    		echo  $a_err[2];
	    	}
	    }else{
			echo 'SUCCESS';
	    }
	    /*var_dump($row);*/
	}catch(Exception $e){		
		echo $e;
	}


}

// Funcion para Para insertar los dotos obtenidos desde Call Centers
function setDatosCC($id_cc, $telefono, $tipo_contacto, $agente, $direccion, $lugar_trabajo, $ingreso_aprox, $acepta, $razon_rechazo,
					$comentario, $fecha, $app, $tipoplan, $codplan, $incidente, $identidad, $campana, $tipo_llamada, $dpg,$estatus,$subestatus){

	if(strlen($id_cc)==0 ){
		echo 'Datos Id CC Incorrectos';
		return;
	}

	if(strlen($telefono)==0 ){
		echo 'Datos Telefono Incorrectos';
		return;
	}
	if(strlen($tipo_contacto)==0 ){
		echo 'Datos tipo_contacto Incorrectos';
		return;
	}
	
	if(strlen($agente)==0 ){
		echo 'Datos agente Incorrectos';
		return;
	}
	// if(strlen($direccion)==0 ){
	// 	echo 'Datos direccion Incorrectos';
	// 	return;
	// }
	// if(strlen($lugar_trabajo)==0 ){
	// 	echo 'Datos lugar_trabajo Incorrectos';
	// 	return;
	// }
	// if(strlen($ingreso_aprox)==0 ){
	// 	echo 'Datos ingreso_aprox Incorrectos';
	// 	return;
	// }
	// if(strlen($acepta)==0 ){
	// 	echo 'Datos acepta Incorrectos';
	// 	return;
	// }
	// if(strlen($razon_rechazo)==0 ){
	// 	echo 'Datos razon_rechazo Incorrectos';
	// 	return;
	// }
	// if(strlen($comentario)==0 ){
	// 	echo 'Datos comentario Incorrectos';
	// 	return;
	// }
	// if(strlen($fecha)==0 ){
	// 	echo 'Datos fecha Incorrectos';
	// 	return;
	// }
	// if(strlen($app)==0 ){
	// 	echo 'Datos app Incorrectos';
	// 	return;
	// }
	// if(strlen($tipoplan)==0 ){
	// 	echo 'Datos tipoplan Incorrectos';
	// 	return;
	// }
	// if(strlen($codplan)==0 ){
	// 	echo 'Datos codplan Incorrectos';
	// 	return;
	// }
	// if(strlen($incidente)==0 ){
	// 	echo 'Datos incidente Incorrectos';
	// 	return;
	// }
	// if(strlen($identidad)==0 ){
	// 	echo 'Datos identidad Incorrectos';
	// 	return;
	// }

	// if(strlen($campana)==0 ){
	// 	echo 'Datos campana Incorrectos';
	// 	return;
	// }
	// if(strlen($tipo_llamada)==0 ){
	// 	echo 'Datos tipo_llamada Incorrectos';
	// 	return;
	// }
	// if(strlen($dpg)==0 ){
	// 	echo 'Datos dpg Incorrectos';
	// 	return;
	// }

	try{		
		include 'db_bocload.php';

		$vQry = '';
		//$vQry = 'INSERT INTO tbl_datos_cc_test (text_prueba, company, fecha) '; //(fecha_marca, usuario_vndr,lat,lng,estado,geometry) VALUES(?,?,?,?,?,?)';
		//$vQry .= 'values(\'' . $text . '\',\'' . $company . '\', sysdate)';
		$vQry .= 'INSERT INTO  tbl_cc_gestiones (id,';
		$vQry .= 'id_cc,';
		$vQry .= 'telefono,';
		$vQry .= 'tipo_contacto,';
		$vQry .= 'agente,';
		$vQry .= 'direccion,';
		$vQry .= 'lugar_trabajo,';
		$vQry .= 'ingreso_aprox,';
		$vQry .= 'acepta,';
		$vQry .= 'razon_rechazo,';
		$vQry .= 'comentario,';
		$vQry .= 'fecha,';
		$vQry .= 'app,';
		$vQry .= 'tipoplan,';
		$vQry .= 'codplan,';
		$vQry .= 'incidente,';
		$vQry .= 'identidad,';
		$vQry .= 'campana,';
		$vQry .= 'tipo_llamada,';
		$vQry .= 'dpg,';
		$vQry .= 'estatus,';
		$vQry .= 'subestatus' ;
		$vQry .= ')';

		
		
		$vQry .= 'VALUES(';
		$vQry .= 'seq_id_cc_gestiones.NEXTVAL,';
		$vQry .= '\'' . $id_cc . '\',';
		$vQry .= '\'' . $telefono . '\',';
		$vQry .= '\'' . $tipo_contacto . '\',';
		$vQry .= '\'' . $agente . '\',';
		$vQry .= '\'' . $direccion . '\',';
		$vQry .= '\'' . $lugar_trabajo . '\',';
		$vQry .= '\'' . $ingreso_aprox . '\',';
		$vQry .= '\'' . $acepta . '\',';
		$vQry .= '\'' . $razon_rechazo . '\',';
		$vQry .= '\'' . $comentario . '\',';
		$vQry .= 'to_date(\'' . $fecha . '\', \'yyyy-mm-dd hh24:mi:ss\'),';
		$vQry .= '\'' . $app . '\',';
		$vQry .= '\'' . $tipoplan . '\',';
		$vQry .= '\'' . $codplan . '\',';
		$vQry .= '\'' . $incidente . '\',';
		$vQry .= '\'' . $identidad . '\',';
		$vQry .= '\'' . $campana . '\',';
		$vQry .= '\'' . $tipo_llamada . '\',';
		$vQry .= '\'' . $dpg . '\',';
		$vQry .= '\'' . $estatus . '\',';
		$vQry .= '\'' . $subestatus . '\'';
		$vQry .= ')';


		$stmt = $conn->prepare($vQry);
		if(!$stmt->execute()){
			$a_err = [];
	    	$a_err = $conn->errorInfo();
	    	if (strpos($a_err[2],'ORA') == false){
				echo $a_err[2];
	    	}else{
	    		echo 'ERROR DE INCONSISTENCIA DE DATOS';
	    		echo  $a_err[2];
	    	}
	    }else{
			echo 'SUCCESS';
	    }
	    /*var_dump($row);*/
	}catch(Exception $e){		
		echo $e;
	}
}



//Insert datos ITS
function setDatosCC2($id_cc, $telefono1, $telefono2, $agente, $acepta, $razon_rechazo, $fecha, $campana, $id_campania){

	if(strlen($id_cc)==0 ){
		echo 'Datos Id CC Incorrectos';
		return;
	}

	if(strlen($telefono1)==0 ){
		echo 'Datos Telefono1 Incorrectos';
		return;
	}
	if(strlen($telefono1)==0 ){
		echo 'Datos Telefono2 Incorrectos';
		return;
	}
	if(strlen($agente)==0 ){
		echo 'Datos agente Incorrectos';
		return;
	}
	if(strlen($acepta)==0 ){
		echo 'Datos acepta Incorrectos';
		return;
	}
	if(strlen($razon_rechazo)==0 ){
		echo 'Datos razon_rechazo Incorrectos';
		return;
	}
	if(strlen($fecha)==0 ){
		echo 'Datos fecha Incorrectos';
		return;
	}
	
	if(strlen($campana)==0 ){
		echo 'Datos campania Incorrectos';
		return;
	}
	if(strlen($id_campania)==0 ){
		echo 'Datos Id_campania Incorrectos';
		return;
	}

	try{		
		include 'db_bocload.php';

		$vQry = '';
		//$vQry = 'INSERT INTO tbl_datos_cc_test (text_prueba, company, fecha) '; //(fecha_marca, usuario_vndr,lat,lng,estado,geometry) VALUES(?,?,?,?,?,?)';
		//$vQry .= 'values(\'' . $text . '\',\'' . $company . '\', sysdate)';
		$vQry .= 'INSERT INTO  tbl_cc_gestiones_its (id,';
		$vQry .= 'id_cc,';
		$vQry .= 'tel1,';
		$vQry .= 'tel2,';
		$vQry .= 'agente,';
		$vQry .= 'acepta,';
		$vQry .= 'razon_rechazo,';
		$vQry .= 'fecha,';
		$vQry .= 'campania,';
		$vQry .= 'id_campania) ';
		
		
		$vQry .= 'VALUES(';
		$vQry .= 'seq_id_cc_gestiones2.NEXTVAL,';
		$vQry .= '\'' . $id_cc . '\',';
		$vQry .= '\'' . $telefono1 . '\',';
		$vQry .= '\'' . $telefono2 . '\',';
		$vQry .= '\'' . $agente . '\',';
		$vQry .= '\'' . $acepta . '\',';
		$vQry .= '\'' . $razon_rechazo . '\',';
		$vQry .= 'to_date(\'' . $fecha . '\', \'yyyy-mm-dd hh24:mi:ss\'),';
		$vQry .= '\'' . $campana . '\',';
		$vQry .= '\'' . $id_campania . '\'';
		$vQry .= ')';


		$stmt = $conn->prepare($vQry);
		if(!$stmt->execute()){
			$a_err = [];
	    	$a_err = $conn->errorInfo();
	    	if (strpos($a_err[2],'ORA') == false){
				echo $a_err[2];
	    	}else{
	    		echo 'ERROR DE INCONSISTENCIA DE DATOS';
	    	}
	    }else{
			echo 'SUCCESS';
	    }
	    /*var_dump($row);*/
	}catch(Exception $e){		
		echo $e;
	}
}

function getFormsDMS($usuario){
	$json_result = array();
	$array_qs = array();
	try{
		include 'db_bocload.php';

		


			$vQry = 'SELECT A.*';
			$vQry .= ' FROM tbl_forms_dms a, tbl_forms_perfil_x_form b, boc.tbl_boc_usuarios c';
			$vQry .= ' WHERE     1 = 1';
			$vQry .= ' AND a.id = b.id_form';
			$vQry .= ' AND DECODE (c.perfil, 89, 101, 201) = b.id_perfil';
			$vQry .= ' AND b.estado = 1';		
			$vQry .= ' and c.estado=1';
			$vQry .= ' and UPPER(c.usuario) = UPPER(\'' . $usuario . '\')';

		

		//echo  stripos(strtoupper($usuario),'APP.DMS') ;
		//echo $vQry;

		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();

	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("id"=>$row[0], "desc"=>$row[1], "tipo"=>$row[2], "ver"=>$row[3], "udt_dt"=>$row[5], "data"=>array(), "vscript"=>$row[6]);
	    	array_push($json_result, $temp_arr);
	    }

	    $temp_arr = array();
	    $ops = array();
	    for($i=0; $i<count($json_result); $i++){
	    	$vQry  = 'SELECT * FROM  tbl_forms_qs_info';
			$vQry .= ' where id_form = \''. $json_result[$i]["id"] .'\'';
			$vQry .= ' and version = '. $json_result[$i]["ver"] .'';
			$vQry .= ' ORDER BY num';

			foreach($conn->query($vQry) as $row2){
				$ops = explode('/', $row2[4]);
		    	$temp_arr = array("id"=>"Q". $row2[6], "tipo"=>$row2[2], "name"=>$row2[3], "ops"=>JSON_encode($ops), "func"=>$row2[5]);
		    	array_push($json_result[$i]["data"], $temp_arr);
		    }
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}

function saveFormsApp($user, $dataForm){
	$vResult = '';
	$vMsj	= '';
	$vFlag = 0;
	$vIDs = array();
	try{		
		include 'db_bocload.php';

		$json_result = JSON_decode($dataForm["vdata"]);
		$vIDs = explode('_', $dataForm["id_form"]);

		for($i=0; $i<count($json_result); $i++){

			$vQry = '';
			$vQry .= 'INSERT INTO tbl_forms_resultado (id, usuario, id_form, item_text, item_result, item_num, fech_form, created_date, lat, lng)';
			$vQry .= 'VALUES(';
			$vQry .= '\'' . $dataForm["id_form"] . '\',';
			$vQry .= '\'' . $user . '\',';
			$vQry .= '\'' . $vIDs[0] . '\',';
			$vQry .= '\'' . $json_result[$i]->q . '\',';
			$vQry .= '\'' . $json_result[$i]->r . '\',';			
			$vQry .= '' . $i+1 . ',';
			$vQry .= 'to_date('. $dataForm["fech"] . ',\'YYYYMMDDHH24MISS\'),';
			$vQry .= 'sysdate,';
			$vQry .=  $dataForm["lat"] . ',';
			$vQry .=  $dataForm["lng"] . '';
			$vQry .= ')';

			$stmt = $conn->prepare($vQry);
			if(!$stmt->execute()){
				$a_err = [];
		    	$a_err = $conn->errorInfo();
		    	if (strpos($a_err[2],'ORA') == false){
					$vMsj = $a_err[2];
					$vFlag = 1;
		    	}else{
		    		$vMsj = 'ERROR INSERTANDO DATOS';
		    		$vFlag = 1;
		    	}
		    }else{
				$vMsj = 'SUCCESS/';
		    }
		}

		if($vFlag==0){
			echo $vMsj . $dataForm["id_form"];
		}else{
			echo $vMsj;
		}
	}catch(Exception $e){		
		echo $e;
	}
}

function saveFileServer($arrFile){
	$vResult = '';
	$vMsj	= '';
	$vFlag = 0;
	$vIDs = array();

	try{		
		include 'db_bocload.php';

		//print_r($arrFile);
		//$json_result = JSON_decode($arrFile);

		$vQry = 'delete from tbl_app_files where id_file = \'' . $arrFile[0]['id_file'] . '\'';

		//if($conn->query($vQry)){

			for($i=0; $i<count($arrFile); $i++){
				$vQry = '';
				$vQry .= 'INSERT INTO tbl_app_files (id_file, nombre, tipo, correl, dtos, fecha_creacion)';
				$vQry .= 'VALUES(\'' . $arrFile[$i]['id_file']. '\'';
				$vQry .= ',\'' . $arrFile[$i]['nombre'] . '\'';
				$vQry .= ',\'' . $arrFile[$i]['tipo'] . '\'';
				$vQry .= ',' . $arrFile[$i]['corel'];
				$vQry .= ',\'' . $arrFile[$i]['dtos'] . '\', sysdate)';

				$stmt = $conn->prepare($vQry);
				if(!$stmt->execute()){
					$a_err = [];
			    	$a_err = $conn->errorInfo();
			    	if (strpos($a_err[2],'ORA') == false){
						$vMsj = $a_err[2];
						$vFlag = 1;
			    	}else{
			    		$vMsj = 'ERROR INSERTANDO DATOS';
			    		$vFlag = 1;
			    	}
			    }else{
					$vMsj = 'SUCCESS/';
			    }
			}
			
	    //}

		if($vFlag==0){
			echo $vMsj;
		}else{
			echo $vMsj;
		}
	}catch(Exception $e){		
		echo $e;
	}

}

function getFileServer($idFile){
	$json_result = array();
	$array_qs = array();
	try{
		include 'db_bocload.php';

		$vQry = 'SELECT  * from tbl_app_files where id_file = \'' . $idFile . '\' order by correl';

		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("id_file"=>$row[0], "name"=>$row[1], "tipo"=>$row[2], "corel"=>$row[3], "dtos"=>$row[4]);
	    	array_push($json_result, $temp_arr);
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}


function getDashHorus($user){
	$json_result = array("gps"=>array(), "recorridos"=>array());
	$arr_marcas = array();
	$arr_recorridos = array();
	try{
		include 'db_conection.php';
		/*$vQry = ' SELECT   usuario_vndr,';
        $vQry .= '         fecha_marca,';
        $vQry .= '         lat, lng, flag_ini, flag_plan ';
        $vQry .= ' FROM    vw_horus_gps ';
        $vQry .= ' WHERE   usr_super = \'' . strtoupper($user) . '\'';*/

        $vQry = ' SELECT   usuario_vndr,';
        $vQry .= '         fecha_marca,';
        $vQry .= '         lat, lng,'; 
        $vQry .= '  case flag_ini WHEN \'A01\' THEN 4';
        $vQry .= '                WHEN \'A02\' THEN 1';
        $vQry .= '                WHEN \'A03\' THEN 2';
        $vQry .= '                WHEN \'A04\' THEN 3 ';
        $vQry .= '                ELSE 0 END flag_ini,';
        $vQry .= '  case flag_plan WHEN \'B01\' THEN 1';
        $vQry .= '                WHEN \'B02\' THEN 2';
        $vQry .= '                WHEN \'B03\' THEN 3 ';
        $vQry .= '                ELSE 0 END flag_plan';
        $vQry .= ' FROM    vw_horus_gps ';
        $vQry .= ' WHERE   usr_super = \'' . strtoupper($user) . '\'';


		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("user"=>$row[0], "lat"=>$row[2], "lng"=>$row[3], "fech"=>$row[1], "flag_ini"=>$row[4], "flag_plan"=>$row[5]);
	    	array_push($json_result["gps"], $temp_arr);
	    }

	    $vQry = ' SELECT  b.usuario_vndr,';
		$vQry .= '        b.fecha_marca,';
		$vQry .= '        to_number(lat) lat, lng ';
		$vQry .= 'FROM    tbl_horus_sprvisor_vndr a,';
		$vQry .= '        tbl_horus_recorridos b, ';
		$vQry .= '		  (SELECT USUARIO_VNDR, max(substr(fecha_marca, 0,8)) as maxfech FROM tbl_horus_recorridos ';
        $vQry .= '         group by USUARIO_VNDR) c ';
		$vQry .= 'WHERE   a.usr_vndr = b.usuario_vndr';
		$vQry .= '        and a.usr_vndr = c.usuario_vndr ';
        $vQry .= '        and substr(b.fecha_marca, 0,8) = c.maxfech ';
		$vQry .= '        and a.usr_super = \'' . strtoupper($user) . '\' ';

		$vQry .= 'order by usuario_vndr, fecha_marca';

		foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("user"=>$row[0], "fech"=>$row[1], "lat"=>$row[2], "lng"=>$row[3]);
	    	array_push($json_result["recorridos"], $temp_arr);
	    }

	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}

function getReporteVentas($id_dms, $aniomes){
	$json_result = array();
	$array_qs = array();
	try{
		include 'db_conection.php';

		$vQry = 'SELECT * FROM vw_fordis09_app_dms';
		$vQry .= ' where anomes = ' . $aniomes;
		$vQry .= ' and id_dms = ' . $id_dms;

		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("particion"=>$row[0], "aniomes"=>$row[1], "id_dms"=>$row[2], "producto"=>$row[3], "unidad"=>$row[4], "meta"=>$row[5], "monto"=>$row[6]);
	    	array_push($json_result, $temp_arr);
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}

function setCierreVentas($user){
	$vMsj = '';
	try{
		include 'db_bocload.php';

		$vQry = 'INSERT INTO tbl_fordis04_vndr_cierre(particion, usuario, fecha_hora) values(';
		$vQry .= 'to_char(sysdate, \'YYYYMMDD\'),\'' . $user . '\', sysdate)';
		
		$stmt = $conn->prepare($vQry);

		if(!$stmt->execute()){
			$a_err = [];
	    	$a_err = $conn->errorInfo();
	    	if (strpos($a_err[2],'ORA') == false){
				$vMsj = $a_err[2];
				$vFlag = 1;
	    	}else{
	    		$vMsj = $a_err[2]; //'ERROR INSERTANDO DATOS';
	    		$vFlag = 1;
	    	}
	    }else{
			$vMsj = 'SUCCESS';
	    }

	}catch(PDOException $e){
		echo $e;
	}
	echo $vMsj;
}


function getPlanDMS($ui){
	$vQry = '';
	$json_result = array("plan"=>array(), "fichas"=>array());
	try{
		include 'db_conection.php';

		$vQry =  'SELECT * FROM vw_plan_fordis04_app ';
		$vQry .= ' WHERE  usuario =\'' . $ui . '\'';
		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	//$temp_arr = array("aniomes"=>$row[0], "semana_anio"=>$row[1], "usuario"=>$row[2], "cod_empleado_dms"=>$row[3], "circuit"=>$row[4], "nombre_circuito"=>$row[5], "id_pdv"=>$row[6],"nombre_pdv"=>$row[7],"dias_semana"=>$row[8],"ymd_dia"=>$row[9]);
	    	$temp_arr = array("aniomes"=>$row[0], "semana_anio"=>$row[1], "usuario"=>$row[2],"cod_empleado_dms"=>$row[3], "circuit"=>$row[4],"nombre_circuito"=>$row[5], "id_pdv"=>$row[6], "nombre_pdv"=>$row[7], "dias_semana"=>$row[8],"ymd_dia"=>$row[9]);
	    	array_push($json_result["plan"], utf8ize($temp_arr));
	    	//echo $row[7];
	    }

	    $vQry = 'SELECT  * ';
		$vQry .= ' FROM    vw_fordis04_ficha_pdv ';
		$vQry .= 'WHERE   ID_POINT_OF_SALE IN (';
        $vQry .= 'SELECT DISTINCT ID_PDV FROM vw_plan_fordis04_app ';
        $vQry .= 'where usuario=\'' . $ui .'\')';

		$temp_arr = array();
		
		foreach($conn->query($vQry) as $row){
	    	//$temp_arr = array("aniomes"=>$row[0], "semana_anio"=>$row[1], "usuario"=>$row[2], "cod_empleado_dms"=>$row[3], "circuit"=>$row[4], "nombre_circuito"=>$row[5], "id_pdv"=>$row[6],"nombre_pdv"=>$row[7],"dias_semana"=>$row[8],"ymd_dia"=>$row[9]);
	    	$temp_arr = array("id_pdv"=>$row[0], "nombre"=>$row[1], "duenio"=>$row[2], "dir"=>$row[3], "epin"=>$row[4], "tmy"=>$row[5], "segmento"=>$row[6]);
	    	array_push($json_result["fichas"], utf8ize($temp_arr));
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	    //echo "console.log( error: " . $e . "' );";	

	}
	//print_r($json_result);
	echo JSON_encode($json_result);
}


// EJecion por sucursal 
function getFordis09Sucursales($idDealer, $anomes){
	$json_result = array();
	$array_qs = array();
	try{
		include 'db_conection.php';

		$vQry = 'SELECT * FROM vw_fordis09_sucursales';
		$vQry .= ' where anomes = ' . $anomes;
		$vQry .= ' and id_dealer = ' . $idDealer;

		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("anomes"=>$row[0], "id_dealer"=>$row[1], "nombre_dealer"=>$row[2], "id_sucursal"=>$row[3], "nombre_sucursal"=>$row[4], "producto"=>$row[5], "ejecucion"=>$row[6], "meta"=>$row[7], "res"=>$row[8], "unidad"=>$row[9]);
	    	array_push($json_result, $temp_arr);
	    }
	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result);
}

function getFordis09SucursalesDet($idSucursal, $anomes){
	$json_result = array();
	$json_result_final = array();
	$arr_productos = array();
	$arr_dias = array();
	$array_qs = array();
	$arr_temp = array();
	try{
		include 'db_conection.php';

		for($i=1; $i<=31; $i++){
			array_push($arr_dias,$i);
		}

		$vQry = 'SELECT * FROM  vw_fordis09_sucursales_det';
		$vQry .= '	WHERE id_sucursal=' . $idSucursal;
		$vQry .= '	AND anomes = ' . $anomes;
		$vQry .= '	ORDER BY particion, producto';

		//$conn->prepare($vQry);
		//$stmt = 'SELECT * FROM tbl_scs_mrks_vdr';
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("particion"=>$row[1], "id_sucursal"=>$row[4], "nombre_sucursal"=>$row[5], "producto"=>$row[6], "unidad"=>$row[7], "ejecucion"=>$row[8]);
	    	array_push($json_result, $temp_arr);
	    }

	    $cantProd = count($json_result);
	    for($i=0; $i<$cantProd; $i++){
	    	if(!in_array($json_result[$i]["producto"],$arr_productos)){
	    		array_push($arr_productos, $json_result[$i]["producto"]);
	    	}
	    }

	    $flagx = 0;
	    for($i=0; $i<count($arr_productos); $i++){
	    	for($j=0; $j<count($arr_dias); $j++){
	    		for($k=0; $k<count($json_result); $k++){
	    			if($json_result[$k]["producto"]==$arr_productos[$i] && (int)substr($json_result[$k]["particion"], 6,2)==$arr_dias[$j]){
	    				array_push($arr_temp, (int)$json_result[$k]["ejecucion"]);
	    				$flagx = 1;
	    			}
	    		}
	    		if($flagx==0){
	    			array_push($arr_temp, null);
	    		}
	    		$flagx =0;
	    	}
	    	array_push($json_result_final, array("name"=>$arr_productos[$i], "data"=>$arr_temp));
	    	$arr_temp = array();
	    }


	    /*var_dump($row);*/
	}catch(PDOException $e){
		echo $e;
	}
	echo JSON_encode($json_result_final);
}

// Obtener la existencia de la referencia en las tablas de BOCHN
function getRefenciaDeposito($idReferencia){
	$json_result = array();
	try{
		include 'db_conection.php';
		$vQry = "SELECT  ID_ORDEN, TRUNC(FECHA_ACTUALIZACION) FECHA FROM TBL_MOD_FCT_CODS_REFERENCIAS WHERE REFERENCIA= '" . $idReferencia . "'";
		
		$temp_arr = array();
	    foreach($conn->query($vQry) as $row){
	    	$temp_arr = array("id_orden"=>$row[0], "fecha"=>$row[1], "referencia"=>$idReferencia);
	    	array_push($json_result, $temp_arr);
	    }
	    

	}catch(PDOException $e){
		echo $e;
	}

	if (count($temp_arr) >= 1) {
	    	echo '1';
	    }else {
	    	echo '0';
	    }
}

function utf8ize($d) { // evitar el problema con caracatres especiales
   
    if (is_array($d)) 
        foreach ($d as $k => $v) 
            $d[$k] = utf8ize($v);

     else if(is_object($d))
        foreach ($d as $k => $v) 
            $d->$k = utf8ize($v);

     else 
        return utf8_encode($d);

    return $d;
}


?>