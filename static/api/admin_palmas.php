<?
session_start();
include('conexion.php');
$k=rand(1,999999);
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('display_errors', '0');
/*
$a=0;
foreach($_POST as $campo => $valor){
	$a++;
echo 	$a.' '.$campo.'= '.$valor.'<br>';
}
*/

function eliminar_simbolos($string){
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $string
    );
	/*
 $string = str_replace(
        array(' '),
        array('_'),
        $string
    );
	*/
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        ' ',
        $string
    );
return $string;
}



$_POST['mes01']='Ene.';
$_POST['mes02']='Feb.';
$_POST['mes03']='Mar.';
$_POST['mes04']='Abr.';
$_POST['mes05']='May.';
$_POST['mes06']='Jun.';
$_POST['mes07']='Jul.';
$_POST['mes08']='Ago.';
$_POST['mes09']='Sep.';
$_POST['mes10']='Oct.';
$_POST['mes11']='Nov.';
$_POST['mes12']='Dic.';
function escribir_fecha($f){
//list( $year, $month, $day) = split( '[- :]', $f);
$fxx=explode('-',$f);
$year=$fxx[0];
$month=$fxx[1];
$day=$fxx[2];

echo '<span style="white-space: nowrap;">'.$_POST['mes'.$month].' '.$day.' de '.$year.'</span>';	
}

function escribir_fechaB($f){
//list( $year, $month, $day, $hora, $minuto) = split( '[- :]', $f);
$fxx=explode('-',$f);
$year=$fxx[0];
$month=$fxx[1];
$day=$fxx[2];
echo '<span style="white-space: nowrap;">'.$_POST['mes'.$month].' de '.$year.'</span>';	
}


//// INGRESAR
if($_POST['ingresar']=='si'){
$clave2=md5($_POST['clave']);
$usuario=addslashes($_POST['usuario']);
$con = mysqli_query($conn,"SELECT id, nombre, cargo, email FROM usuarios WHERE usuario='$usuario' and clave='$clave2'") or die(mysqli_error($conn));
//es que si que existe esa conbinación usuario/contrasena
if (mysqli_num_rows($con)!=0){
$row = mysqli_fetch_array($con);
$usuario_id=$row['id'];
/// verificamos si tiene acceso
$con2 = mysqli_query($conn,"SELECT id, acceso FROM permisos WHERE usuario_id='$usuario_id' and activo='si' and acceso!='web'") or die(mysqli_error($conn));
if (mysqli_num_rows($con2)!=0){
	$row2 = mysqli_fetch_array($con2);
///
	$_SESSION['nombre_usuario']=$row["nombre"];
	$_SESSION['id_usuario']=$row["id"];
	$_SESSION['cargo_usuario']=$row["cargo"];
	$_SESSION['email_usuario']=$row["email"];
	$_SESSION['acceso_usuario']=$row2["acceso"];
	$_SESSION['administrador']='administrador';
	
}elseif (mysqli_num_rows($con2)==0){
$mensaje='No tiene acceso a esta sección!<br>Si tiene alguna pregunta por favor contactenos en informacion@gimnasiolaspalmas.edu.co';
}
}else{
$mensaje='ERROR en usuario o clave!';	
}
}
/// FIN DE INGRESAR







//// NUEVA CATEGORIA
if($_POST['registrar_nueva_categoria']=='si'){
$tipo=$_POST['categoria_productos'];
if($tipo=='nueva categoria'){
$categoria=$_POST['nueva_categoria'];
$Insertar="INSERT INTO categorias (categoria) VALUES ('$categoria')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
$mensaje='Categoria Registrada';
}elseif($tipo=='nueva subcategoria'){
$categoria_id=$_POST['nueva_subcategoria2'];
$categoria=$_POST['nueva_subcategoria'];
$Insertar="INSERT INTO categorias (categoria_id, categoria) VALUES ('$categoria_id', '$categoria')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
$mensaje='Subcategoria Registrada';
}
/// identificamos el id recien
$rb= mysqli_query($conn,"select id from categorias Where categoria='$categoria' Order by id DESC Limit 1") or die(mysqli_error($conn));
$rowb = mysqli_fetch_array($rb);
$_POST['categoria_productos']=$rowb['id'];
}
//// FIN NUEVA CATEGORIA












//// EDITAR / NUEVO DESTINO
if($_POST['editar_destino']!=''){
$p=0;
$total=$_POST['total_destinos'];
while($p<$total){
	$p++;
///

$destino=$_POST['destino_'.$p];
$color=$_POST['color_'.$p];
$zona_horaria=$_POST['zona_horaria_'.$p];
$descripcion=$_POST['descripcion_'.$p];
$incluye=$_POST['incluye_'.$p];
$no_incluye=$_POST['no_incluye_'.$p];
$nota_general=$_POST['nota_general_'.$p];
$hoteles=$_POST['hoteles_'.$p];
$visas=$_POST['visas_'.$p];
$visas_ecuador=$_POST['visas_ecuador_'.$p];
$tips=$_POST['tips_'.$p];
$id=$_POST['id_'.$p];
/// si es nuevo
if($id=='' and $destino!=''){
$Insertar="INSERT INTO destinos (destino, color, descripcion, incluye, no_incluye, nota_general, visas, visas_ecuador, tips, zona_horaria) VALUES ('$destino', '$color', '$descripcion',  '$incluye', '$no_incluye', '$nota_general', '$visas', '$visas_ecuador', '$tips', '$zona_horaria')";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
//
$rb= mysqli_query($conn,"select id from destinos Order by id DESC Limit 1") or die(mysqli_error($conn));
$rowb = mysqli_fetch_array($rb);
$id=$rowb['id'];
}else{
$Insertar="UPDATE destinos SET destino='$destino', color='$color',  descripcion='$descripcion', incluye='$incluye', no_incluye='$no_incluye', nota_general='$nota_general',visas='$visas', visas_ecuador='$visas_ecuador', tips='$tips', zona_horaria='$zona_horaria' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
//echo $Insertar;
}

$mensaje='Destino '.$destino.' guardado';	

//// si hay FOTO la subimos


}

}
/// FIN EDITAR / NUEVO DESTINO




/// BORRAR DESTINO
if($_POST['borrar_destino']!=''){
$p=$_POST['borrar_destino'];
$id=$_POST['id_'.$p];
$destino=$_POST['destino_'.$p];
$Insertar="DELETE FROM destinos WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
$Insertar="DELETE FROM programas WHERE destino_id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
$mensaje='Borrado Destino '.$destino.' y todos los programas asociados';
$ruta_foto='imagenes/destinos/'.$id.'.jpg';
$ruta_foto2='imagenes/destinos/mapa'.$id.'.jpg';
if(file_exists ($ruta_foto)){
unlink($ruta_foto);
}
if(file_exists ($ruta_foto2)){
unlink($ruta_foto2);
}
}
/////




////// EDITAR / NUEVO TIPS
if($_POST['editar_tip']!=''){
$p=$_POST['editar_tip'];
///
$destino=$_POST['destino_'.$p];
$destino_id=$_POST['destino_id_'.$p];
$capital=$_POST['capital_'.$p];
$gobierno=$_POST['gobierno_'.$p];
$idioma=$_POST['idioma_'.$p];
$poblacion=$_POST['poblacion_'.$p];
$nota=$_POST['nota_'.$p];
$equipaje=$_POST['equipaje_'.$p];
$puntualidad=$_POST['puntualidad_'.$p];
$transporte=$_POST['transporte_'.$p];
$alimentacion=$_POST['alimentacion_'.$p];
$clima=$_POST['clima_'.$p];
$moneda=$_POST['moneda_'.$p];
$vestuario=$_POST['vestuario_'.$p];
$comunicacion=$_POST['comunicacion_'.$p];
$electricidad=$_POST['electricidad_'.$p];
$comidas=$_POST['comidas_'.$p];
$propinas=$_POST['propinas_'.$p];
$salud=$_POST['salud_'.$p];
$protocolo=$_POST['protocolo_'.$p];
$datos_ciudades=$_POST['datos_ciudades_'.$p];
$id=$_POST['id_'.$p];
/// si es nuevo
if($id==''){
$Insertar="INSERT INTO tips (destino_id, capital, gobierno, idioma, poblacion, nota, equipaje, puntualidad, transporte, alimentacion, clima, moneda, vestuario, comunicacion, electricidad, comidas, propinas, salud, protocolo, datos_ciudades) VALUES ('$destino_id', '$capital', '$gobierno', '$idioma', '$poblacion', '$nota', '$equipaje', '$puntualidad', '$transporte', '$alimentacion', '$clima', '$moneda', '$vestuario', '$comunicacion', '$electricidad', '$comidas', '$propinas', '$salud', '$protocolo', '$datos_ciudades')";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
//echo $Insertar;
}else{
$Insertar="UPDATE tips SET capital='$capital', gobierno='$gobierno', idioma='$idioma', poblacion='$poblacion', nota='$nota', equipaje='$equipaje', puntualidad='$puntualidad', transporte='$transporte', alimentacion='$alimentacion', clima='$clima', moneda='$moneda', vestuario='$vestuario', comunicacion='$comunicacion', electricidad='$electricidad', comidas='$comidas', propinas='$propinas', salud='$salud', protocolo='$protocolo', datos_ciudades='$datos_ciudades' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
//echo $Insertar;
}

$mensaje='Tips '.$destino.' guardado';	

//// si hay FOTO la subimos





///****************************

}
/// FIN EDITAR / NUEVO TIPS


//// EDITAR PAGINA
if($_POST['editar_pagina']!=''){
$p=$_POST['editar_pagina'];
///

$boton=$_POST['boton_'.$p];
$texto=$_POST['texto_'.$p];
$id=$_POST['id_'.$p];

$Insertar="UPDATE menu SET boton='$boton', texto='$texto' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
//echo $Insertar;


$mensaje='Pagina '.$boton.' guardado';	

//// si hay FOTO la subimos

/// IMAGEN - IZQ
if($_FILES['foto2_'.$p]['name'] != ''){
if($_FILES['foto2_'.$p]['type'] != "image/pjpeg" and $_FILES['foto2_'.$p]['type'] != "image/jpeg"   ){
$mensaje=$mensaje.'<br>ERROR: the file not is jpg ';
}else{
	$ruta_full='imagenes/paginas/'.$id.'.jpg';
	$ruta_movil='imagenes/paginas/M'.$id.'.jpg';
	$ruta_temp='imagenes/paginas/'.$id.'B.jpg';
///copiamos la foto al tamano real
if(!copy($_FILES['foto2_'.$p]['tmp_name'], $ruta_temp)){
$mensaje=$mensaje."<br>ERROR ! ";
}else{
//echo 'AAA';
/// creamos una copia redimencionandola
$ancho=1200;
$alto=600;
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_full, 90);
///// version movil
$ancho=400;
$alto=200;
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_movil, 90);


unlink($ruta_temp) ;


}
}
}
// fin de IMAGEN



}
/// FIN EDITAR PAGINA










////EDITAR Productos Home
if($_POST['editar_programas_home']!=''){

$cantidad=6;
$p=0;
while($p<=$cantidad){
$p++;		
$id=$_POST['id_'.$p];
$programa_id=$_POST['programa_'.$p];
$texto=$_POST['texto_'.$p];

$Insertar="UPDATE home_programas SET programa_id='$programa_id', texto='$texto' Where id='$id'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
///
}
}
/// FIN Productos Home








//// EDITAR / NUEVO GALERIA
if($_POST['editar_galeria']!=''){
$p=$_POST['editar_galeria'];
///

$destino=$_POST['destino_'.$p];
$viajero=$_POST['viajero_'.$p];
$email=$_POST['email_'.$p];
$comentario=$_POST['comentario_'.$p];
$publicada=$_POST['publicada_'.$p];
$id=$_POST['id_'.$p];
/// si es nuevo
if($p==0){
$Insertar="INSERT INTO galeria (destino, viajero, email, comentario, publicada) VALUES ('$destino', '$viajero', '$email', '$comentario', '$publicada')";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
//
$rb= mysqli_query($conn,"select id from galeria Order by id DESC Limit 1") or die(mysqli_error($conn));
$rowb = mysqli_fetch_array($rb);
$id=$rowb['id'];
}else{
$Insertar="UPDATE galeria SET destino='$destino', viajero='$viajero', email='$mail', comentario='$comentario', publicada='$publicada' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
//echo $Insertar;
}

$mensaje='Galeria guardada';	

//// si hay FOTO la subimos
/// IMAGEN - MAPA
if($_FILES['foto_'.$p]['name'] != ''){
if($_FILES['foto_'.$p]['type'] != "image/pjpeg" and $_FILES['foto_'.$p]['type'] != "image/jpeg"   ){
$mensaje=$mensaje.'<br>ERROR: the file not is jpg ';
}else{
///copiamos la foto al tamano real
if(!copy($_FILES['foto_'.$p]['tmp_name'], 'imagenes/galeria/'.$id.'b.jpg')){
$mensaje=$mensaje."<br>ERROR ! ";
}else{
	
/// creamos una copia redimencionandola
$ruta='imagenes/galeria/'.$id.'b.jpg';
$ancho=760;
$alto=420;
$fuente = imagecreatefromjpeg($ruta);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);
//$imgAlto=($alto*$imgAncho)/$ancho;
//$imgAlto=(int)$imgAlto;
// centramos la imagen a lo alto para no cortarla mal
$coor=$imgAlto-(($alto*$imgAncho)/$ancho);
$coor2=$coor/2;
$imgAlto=$imgAlto-$coor;
imagecopyresampled($imagen,$fuente,0,0,0,$coor2,$ancho,$alto,$imgAncho,$imgAlto);
//Header("Content-type: image/jpeg");
//imageJpeg($imagen);

imagejpeg($imagen, 'imagenes/galeria/'.$id.'c.jpg', 90);
/////
unlink('imagenes/galeria/'.$id.'b.jpg') ;


//// ponemos el logo como marca
$ruta_marca='imagenes/galeria/marca_logo.png';
$ruta_foto='imagenes/galeria/'.$id.'c.jpg';
$ruta_fusion='imagenes/galeria/'.$id.'.jpg';

$wm = imagecreatefrompng($ruta_marca);
$w_wm = imagesx($wm);
$h_wm = imagesy($wm);
$im_name = $ruta_foto;
$ext = strtolower(substr($im_name, -3));

if($ext == 'png'){ $im = imagecreatefrompng($im_name); }
if($ext == 'gif'){ $im = imagecreatefromgif($im_name); }
if($ext == 'jpg'){ $im = imagecreatefromjpeg($im_name); }
if(!$im){     echo "error"; exit(1); }

$w_im = imagesx($im);
$h_im = imagesy($im);
$d1=$w_im-$w_wm;
$d2=$h_im-$h_wm;
imagecopy($im, $wm, $d1, $d2, 0, 0, $w_wm, $h_wm);
//header("Content-Type: image/png");
//imagepng($im);
//imagepng($im, substr($im_name,0,3).'_wm.png');
imagejpeg($im, $ruta_fusion, 90);
//imagedestroy($wm);
//imagedestroy($im);
unlink($ruta_foto) ;


}
}
}
	
// fin de IMAGEN



}
/// FIN EDITAR / NUEVO GALERIA




/// BORRAR GALERIA
if($_POST['borrar_galeria']!=''){
$p=$_POST['borrar_galeria'];
$id=$_POST['id_'.$p];
$Insertar="DELETE FROM galeria WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));

$mensaje='Galeria Borrada';
$ruta_foto='imagenes/galeria/'.$id.'.jpg';
if(file_exists ($ruta_foto)){
unlink($ruta_foto);
}

}
/////







////EDITAR USUARIO
if($_POST['editar_usuario']!=''){
$p=0;
$cantidad=$_POST['cantidad'];
while($p<=$cantidad){
$id=$_POST['id_'.$p];
$agencia=$_POST['agencia_'.$p];
$nombre=$_POST['nombre_'.$p];
$email=$_POST['email_'.$p];
$telefono=$_POST['telefono_'.$p];
$activo=$_POST['activo_'.$p];
//

$Insertar="UPDATE usuarios SET agencia='$agencia', nombre='$nombre', email='$email', telefono='$telefono', activo='$activo' Where id='$id'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));


$p++;	
}


}
/// FIN Usuarios



/// BORRAR USUARIO
if($_POST['borrar_usuario']!=''){
$p=$_POST['borrar_usuario'];
$id=$_POST['id_'.$p];
$nombre=$_POST['inombre_'.$p];
$Insertar="DELETE FROM usuarios WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
$mensaje='Usuario '.$nombre.' borrado';
}
/////



/// SOLICITAR CAMBIAR DE CLAVE
if($_POST['cambiar_clave']=='si'){
	/// consultamos para saber si este correo esta registrado
	$nuevo_email=$_POST['email'];
	$re = mysqli_query($conn,"select * from instaladores Where email='$nuevo_email'") or die(mysqli_error($conn));
if (mysqli_num_rows($re)==0){
$mensaje='El email '.$nuevo_email.' no esta registrado en nuestro Sistema!';
}else{
// actualizamos con la nueva clave
$rowe = mysqli_fetch_array($re);
$clave=$_POST['clave'];
$nueva_clave=md5($clave);
$user=$rowe['nombre'].' '.$rowe['apellido'];
$id=$rowe['id'];
$id2=md5($rowe['id'].'k6T8i');
///
//// se envia un correo para confirmacion del cambio
 $contenido='<strong>'.$user.'</strong><br><br>Se ha recibido su solicitud para cambiar las calves de acceso a nuestro sistema. Para ejecutar la solicitud por favor haga click en este vinculo: <br> <a href="http://www.sicomatel.com/instaladores/index.php?newpassword=si&np='.$nueva_clave.'&e='.$nuevo_email.'&i='.$id.'&i2='.$id2.'" target="_blank">Realizar Cambio de Clave</a><br><br>Email: <strong>'.$nuevo_email.'</strong><br>Nueva Clave: <strong>'.$clave.'</strong> <br><br> <br><strong>Departamento de Soporte</strong>';
$subjet = 'SICOMATEL: Cambio de Claves de Acceso';
ob_start();
include("mail.php");
$html=ob_get_contents();
ob_end_clean(); 
$codigohtml = $html;
//
$email_destino=$nuevo_email;
$cabeceras = "Content-type: text/html\r\n";
//dirección del remitente
$cabeceras .= 'From: SICOMATEL<ventas@sicomatel.com>';  
//////
mail($email_destino,$subjet,$codigohtml,$cabeceras);
//// fin de correo
$mensaje='Para realizar el cambio, siga las instrucciones que le hemos enviado a su correo '.$nuevo_email.' .';
}
 }
/// FIN SOLICITAR CAMBIAR DE CLAVE


//// CAMBIAR DE CLAVE
$n=rand(1,10000);
$clave_actualizada='';
 if(isset($_GET['newpassword']) and $_GET['newpassword']=='si' and $_POST['clave_actualizada']!='si' and !isset($_SESSION['id_usuario']) and !isset($_POST['usuario'])){
	 $i=$_GET['i'];
	 $i2=$_GET['i2'];
	 $i3=md5($i.'k6T8i');
	 $e=$_GET['e'];
	 $np=$_GET['np'];
if($i2==$i3){ 
$act= "UPDATE instaladores SET clave='$np' Where email='$e' and id='$i'";
$Res = mysqli_query($act) or die (mysqli_error($conn));
$mensaje='La Clave fue cambiada, ya puede utilizarla!';
$_GET['newpassword']='no';
}
 }
 //// FIN DE CAMBIAR LA CLAVE
 
 
 
 ///// BORRAR ARCHIVO
if($_POST['borrar_archivo']!=''){
$ruta=$_POST['borrar_archivo'];
if(file_exists ($ruta)){
unlink($ruta);
$mensaje='Archivo borrado';
}

}
/// FIN DE BORRAR ARCHIVO


// BORRAR REGISTRO
if($_POST['borrar_tabla']!=''){
$tabla=	$_POST['borrar_tabla'];
$id=	$_POST['borrar_id'];
///
$act= "DELETE FROM $tabla WHERE id='$id'";
mysqli_query($conn, $act) or die (mysqli_error($conn));
}
/// FIN BORRAR REGISTRO


//////TRM
if($_POST['actualizar_trm']=='si'){
$trm=$_POST['trm'];
$trm_euro=$_POST['trm_euro'];
$today=date('Y-m-d');	
$act= "UPDATE trm SET trm='$trm', trm_euros='$trm_euro', fecha='$today'";
$Res = mysqli_query($act) or die (mysqli_error($conn));
//echo $act.'<br>';	
}
//////
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Gimnasio Las Palmas - Administrador</title>

<link href="css/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet">
<link href="baseV5.css" rel="stylesheet">

<style type="text/css">

body,html{width:100%;}
body{font-family: 'Mallanna', sans-serif;
font-size:18px; margin:0px;
}		

.texto1{color:#000;}
.texto2{color:#fff;}
.titulo1{color:#000; font-size:18px;}
.titulo2{color:#fff;font-size:18px;}
.link{cursor:pointer; color:red}
.link:hover{color:#000;}
.link2{cursor:pointer;}
.link2:hover{color:#fff;}

.linkx{cursor:pointer; color:red}
.linkx:hover{color:#000;}

.principal td{border-bottom:dotted 1px #f00;}
</style>

<script src="scripts/jquery-3.1.1.min.js"></script>

    
    
    
   


   
   <?
	//// cargamos los productos
	$tipo=$_GET['tipo'];
	if($tipo==''){
	$tipo='Banner Home';
	}
	
	$filtro=$_GET['filtro'];
	//
	?>
     
    <script>
	///
function cargar_filtro(filtro){
window.location='admin_palmas.php?tipo=<?=$tipo?>&filtro='+filtro;	
}

function mostrar(ref){
var estado=document.getElementById('estado_'+ref).value;
if(estado=='cerrado'){
$('#'+ref).show();
var nuevo='abierto';
var tex='<strong>Cerrar</strong>';	
}
//
if(estado=='abierto'){
$('#'+ref).hide();
var nuevo='cerrado';
var tex='Ver';	
}
//
document.getElementById('estado_'+ref).value=nuevo;
$('#texto_'+ref).html(tex);
}

function cerrar_mensaje(){
$('#cortina').hide('');	
$('#mensaje').hide('');	
}

function cargar_mensaje(texto){
$('#zona_mensaje').html(texto);	
$('#cortina').show();
$('#mensaje').show();

var miSetOut = setTimeout( "cerrar_mensaje()" , 8000 );	
}
	</script>
    
    <link type="text/css" rel="stylesheet" href="scripts/jQuery-TE/jquery-te-1.4.0.css">
<script type="text/javascript" src="scripts/jQuery-TE/jquery-te-1.4.0.min.js" charset="utf-8"></script>


<script type="text/javascript" src="scripts/Zebra_Datepicker-master/public/javascript/zebra_datepicker.js"></script>
    <link rel="stylesheet" href="scripts/Zebra_Datepicker-master/public/css/default.css" type="text/css">
    
    <script>
	$(document).ready(function() {

    // assuming the controls you want to attach the plugin to 
    // have the "datepicker" class set
    $('input.campo_fecha').Zebra_DatePicker({
	onClose: function(){
		//alert('A') 
		filtrar_fecha(document.getElementById('fecha_viaje').value)
	}
})
 });
 </script>


<script src="scripts/ckeditor_full/ckeditor.js"></script>
	<script src="scripts/ckeditor_full/samples/js/sample.js"></script>
	<link rel="stylesheet" href="scripts/ckeditor_full/samples/css/samples.css">
 
</head>

<body>

<div id="cortina" style="position:fixed; width:100%; left:0px; top:0px; bottom:0px; background:rgba(0,0,0,0.8); z-index:50; display:none" onClick="cerrar_mensaje()"></div>
<div id="mensaje" style="position:fixed; top:200px; left:50%; margin-left:-150px;  width:300px; border-color:#333; border-width:1px; border-style:solid; z-index:51; display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="texto2" bgcolor="#000000"><div class="linkx" onclick="cerrar_mensaje()" style="margin-right:8px; text-align:right">cerrar</div></td>
  </tr>
  <tr>
    <td bgcolor="#990000" class="texto2"><div style="height:80px; margin:8px;" id="zona_mensaje"></div></td>
  </tr>
</table>

</div>




<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
  <tr>
    <td align="center"><img src="imagenes/administrador.jpg"></td>
    
  </tr>
</table>

    </td>
  </tr>
  <tr>
    <td>
    
    </td>
  </tr>

  <tr>
    <td style="background:#FFF; height:400px;" valign="top">
    
   <form name="uno" method="post" enctype="multipart/form-data">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  
  <tr>
    <td valign="top">
    <?
	/// formulario de ingreso
	if(!isset($_SESSION['id_usuario'])){
	?>
    <table width="350" border="0" cellspacing="1" cellpadding="2" align="center" style="padding-top:30px;">
  <tr>
    <td colspan="2" bgcolor="#990000" class="titulo2">Panel de Ingreso</td>
  </tr>
  <tr>
    <td width="106">Usuario:</td>
    <td width="383">
      <input type="text" name="usuario" id="usuario" class="campo1" required /></td>
  </tr>
  <tr>
    <td>Clave:</td>
    <td><label for="clave">
      <input type="password" name="clave" id="clave" class="campo1" required />
    </label></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="button" id="button" value="Ingresar" onClick="document.getElementById('ingresar').value='si';" />
    
     <input name="ingresar" id="ingresar" type="hidden" value="" />
    <script>
	function validar_ingresar(){
	if(document.getElementById('usuario').value==''){
	alert('falta el usuario');
	document.getElementById('usuario').focus();
	return	
	}	
	if(document.getElementById('clave').value==''){
	alert('falta la clave');
	document.getElementById('clave').focus();
	return	
	}
	
	//
	document.getElementById('ingresar').value='si';
	document.uno.submit();	
	}
	</script>
    </td>
  </tr>
  
  
    </table>

    <?
    }
	
	
if(isset($_SESSION['id_usuario']) and isset($_SESSION['administrador'])){
	
	/// borramos registro 
	if($_POST['tabla_borrar']!=''){
	$tabla_borrar=$_POST['tabla_borrar'];
	$ref=$_POST['ref_borrar'];
		/// 
$act= "DELETE FROM $tabla_borrar WHERE id = '$ref'";
mysqli_query($conn, $act) or die (mysqli_error($conn));
		//
		$mensaje='Registro borrado';
	}
	/// fin de borrar registro
	?>
 <script>
	 function abrir(zona){
	$('.'+zona).show();
	$('#'+zona).show();	 
	 }
	 
	 function cerrar_general(zona){
		 //alert(zona);
	$('.'+zona).hide();
	$('#'+zona).hide();	 
	 }
	 
	 function borrar_registro(tabla, ref){
		 if(confirm('desea borrar este Registro?')){
	$('#tabla_borrar').val(tabla);
	$('#ref_borrar').val(ref);
	document.uno.submit();	 
	 }
	 }
	 
	 function editar_pagina(ref){
	$('#ed_pagina').attr('src','');	 
	$('#ed_pagina').attr('src','editar_pagina.php?id='+ref);	
		 abrir('cortina_edicion');
		 abrir('editor_pagina');
	 }
	 
	 function abrir_editar_galeria(ref){
	$('#ed_pagina').attr('src','');	 
	$('#ed_pagina').attr('src','editar_galeria.php?id='+ref);	
		 abrir('cortina_edicion');
		 abrir('editor_pagina');
	 }
		</script>
   <input type="hidden" name="tabla_borrar" id="tabla_borrar" value="">
   <input type="hidden" name="ref_borrar" id="ref_borrar" value="">
       
               
<div class="cortina_edicion" style="position: fixed; width: 100%; top: 0px; bottom: 0px; left: 0px; background: rgba(0,0,0,0.9); z-index: 10; display: none" onClick="cerrar_general('cortina_edicion'); cerrar_general('editor_pagina');"></div>
   
<div class="editor_pagina" style=" background: white;  position: fixed; width: 900px; height: 600px; top: 100px; left: 50%; margin-left: -450px; z-index: 11; display: none" >
	
<iframe name="ed_pagina" id="ed_pagina" src="" width="100%" height="100%" frameborder="0" scrolling="auto" ></iframe>	
</div> 
  
      
   
    <div id="menu" style="background:#900; border-width:1px; border-style:solid; border-color:#333; color:#FFF; height:20px;" align="right">
    <strong>Administrador</strong> | <span class="linkx" onclick="window.location='salir.php';">cerrar sesión</span>
    </div>
  
  <table width="1000" height="400" border="0" cellspacing="0" cellpadding="0" align="center">
 
  
  <tr>
    <td valign="top" bgcolor="#999999" style="width:200px;">
    <div style="padding-left:10px; padding-right:10px; font-size:15px; color:black">
    <span class="titulo2" >Opciones:</span><br />
    <br />
    <div class="link2" onclick="cargar('Aviso Home')"><i class="fa fa-dot-circle-o"></i> Aviso Home</div>
	<div class="link2" onclick="cargar('Banner Home')"><i class="fa fa-dot-circle-o"></i> Banner Home</div>
    <div class="link2" onclick="cargar('Accesos Home')"><i class="fa fa-dot-circle-o"></i> Accesos Home</div>
	<div class="link2" onclick="cargar('Presentacion Home')"><i class="fa fa-dot-circle-o"></i> Presentación Home</div>
    <div class="link2" onclick="cargar('Paginas')"><i class="fa fa-dot-circle-o"></i> Páginas</div>
    <div class="link2" onclick="cargar('Galerias')"><i class="fa fa-dot-circle-o"></i> Galerias</div>
	<div class="link2" onclick="cargar('Noticias')"><i class="fa fa-dot-circle-o"></i> Noticias</div>
	<div class="link2" onclick="cargar('Footer')"><i class="fa fa-dot-circle-o"></i> Footer</div>
    <div class="link2" onclick="cargar('Palabras Clave')"><i class="fa fa-dot-circle-o"></i> Palabras Clave</div>
    
    <div class="link2" onclick="cargar('Email')"><i class="fa fa-dot-circle-o"></i> Email</div>
    
    <div class="link2" onclick="cargar('Postulantes')"><i class="fa fa-dot-circle-o"></i> Postulantes</div>
    <div class="link2" onclick="cargar('usuarios')"><i class="fa fa-dot-circle-o"></i> Usuarios</div>
    
    <div class="link2" onclick="cargar('Landing')"><i class="fa fa-dot-circle-o"></i> Landing Page</div>  
    
    <!--
    <div class="link2" onclick="cargar('Noticias')"><i class="fa fa-dot-circle-o"></i> Noticias</div>
    
    
    -->
    
    
    <!--
    <div class="link2" onclick="cargar('Idiomas')"><i class="fa fa-dot-circle-o"></i> Idiomas</div>
    <div class="link2" onclick="cargar('Categorias')"><i class="fa fa-dot-circle-o"></i> Categorias Menú</div>
    <div class="link2" onclick="cargar('Lineas de Producto')"><i class="fa fa-dot-circle-o"></i> Lineas Producto</div>
    <div class="link2" onclick="cargar('Sabores')"><i class="fa fa-dot-circle-o"></i> Sabores</div>
    <div class="link2" onclick="cargar('Productos')"><i class="fa fa-dot-circle-o"></i> Productos</div>
    <div class="link2" onclick="cargar('Tarjetas')"><i class="fa fa-dot-circle-o"></i> Tarjetas</div>
    <div class="link2" onclick="cargar('Cupones y Descuentos')"><i class="fa fa-dot-circle-o"></i> Cupones y Descuentos</div>
    <div class="link2" onclick="cargar('Costos Extras')"><i class="fa fa-dot-circle-o"></i> Costos Extras</div>
    <div class="link2" onclick="cargar('Costos de Envío')"><i class="fa fa-dot-circle-o"></i> Costos de Envío</div>
    -->
    
    
    



    
     <?
	 /*
	 <div class="link2" onclick="cargar('Clientes')" style="border-top:dotted 1px #CCCCCC; margin-top:10px;"><i class="fa fa-dot-circle-o"></i> Clientes</div> 

<div class="link2" onclick="cargar('Email Masivo')"><i class="fa fa-dot-circle-o"></i> Email masivo</div>


<div class="link2" onclick="cargar('Reservaciones')" style="border-top:dotted 1px #CCCCCC; margin-top:10px;"><i class="fa fa-dot-circle-o"></i> Reservaciones</div> 

  
	 */

	$rCl= mysqli_query($conn,"select color from colegio WHERE id='1'") or die(mysqli_error($conn));
	$rowCl = mysqli_fetch_array($rCl);
	 ?>

	 <hr>
	 Color Principal: (0B4E4F)<br>
	 <input type="text" name="color" id="color" value="<?=$rowCl['color']?>"> 
	 <input type="button" onclick="cambiarColor()" value="Cambiar Color" />
<div id="zn"></div>
<a href="https://es.wikipedia.org/wiki/Colores_web" target="_blank" rel="noopener noreferrer">Ver Listado Colores</a>
   
    </div>
    
    
    </td>
    
    
    
    <td valign="top">
    <div id="zona_productos" style="padding-left:20px; padding-right:20px;">
    
    <span class="titulo1"><?=$tipo?></span>
    
    <script>
		function cambiarColor(){
			if($('#color').val()==''){
				alert('falta el color');
			}else{
				$('#zn').load('varios.php',{tipo:'color',color: $('#color').val()} );
			}
		}
	</script>
    
    
   
   
   <?
	//// GALERIAS
		 
	if($tipo=='Galerias'){
		
	
	if($_POST['actualizar_paginas']=='si'){
		$total_categorias=$_POST['total_categorias'];
		$c=0;
		while($c<$total_categorias){
			$c++;
			$id=$_POST['id'.$c];
			$galeria=$_POST['galeria'.$c];
			$galeriav=$_POST['galeriav'.$c];
			$orden=$_POST['orden'.$c];
			$activa=$_POST['activa'.$c];
			if($id!=''){
$Insertar="UPDATE galerias SET galeria='$galeria', orden='$orden', activa='$activa' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
				//
			
			}else if($galeria!=''){
$Insertar="INSERT INTO galerias (galeria, orden,activa) VALUES ('$galeria', '$orden', 'si')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));

			}
			
			$p=0;
		
			
		}
		
		
		
		
		$mensaje='Galerias actualizadas.';
	}
	
	
/// primero categorias
$rc= mysqli_query($conn,"SELECT * FROM galerias ORDER BY orden ") or die (mysqli_error($conn));
	$c=0;
while($rowc = mysqli_fetch_array($rc)){	
	$c++;
	?>
	<input type="hidden" name="id<?=$c?>" value="<?=$rowc['id']?>">
	<input type="hidden" name="galeriav<?=$c?>" value="<?=$rowc['galeria']?>">
		<div class="categoria"><input type="text" name="orden<?=$c?>" id="orden<?=$c?>" value="<?=$c?>" style="width:30px">. <input type="text" name="galeria<?=$c?>" id="galeria<?=$c?>" value="<?=$rowc['galeria']?>" placeholder="Nombre de la Galeria" style="width: 400px;" > <select name="activa<?=$c?>" id="activa<?=$c?>" style="width: 120px">
			<option value="si">visible</option>
			<option value="no">oculta</option>
			</select>  | <i class="fa fa-edit" style="cursor: pointer; color: green;" onClick="abrir_editar_galeria('<?=$rowc['id']?>')"></i> | <i class="fa fa-trash-o" style="cursor: pointer; color: red;" onClick="borrar_registro('paginas', '<?=$rowc['id']?>')"></i>
			<script>
				$('#activa<?=$c?>').val('<?=$rowc['activa']?>');
			</script></div>
		
		<?
		//// cargamos las paginas de cada categoria
		
	    

	
}
	
$c++;
?>
	<input type="hidden" name="id<?=$c?>" value="">
		<div class="categoria"><input type="text" name="orden<?=$c?>" id="orden<?=$c?>" value="<?=$c?>" style="width:30px">. <input type="text" name="galeria<?=$c?>" id="galeria<?=$c?>" value="" placeholder="Nueva Galeria" style="width: 400px;" ></div>
		
		<br><br>
		<input type="hidden" name="total_categorias" id="total_categorias" value="<?=$c?>">
		<input type="hidden" name="actualizar_paginas" id="actualizar_paginas" value="">
		<input type="submit" value="Actualizar listado" onClick="document.getElementById('actualizar_paginas').value='si';">
	<?	

	}	 
		 /// FIN DE GALERIAS
		 
	
	
	

	

	////********************* 
	
	
	
	
	
	
	
	
	/// Palabras Clave *****************
	if($tipo=='Palabras Clave'){

	
	
/////////////////****
if($_POST['actualizar_palabras']=='si'){

$a++;	
$palabras=$_POST['palabras'];
$descripcion=$_POST['descripcion'];

$Insertar="UPDATE palabras_clave SET palabras='$palabras', descripcion='$descripcion' WHERE id='1'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));

//echo $Insertar.'<br>';

?>
<script>
alert('Palabras Clave actualizadas');
</script>
<?

}

///////////////*****		

	
			/// cargamos 
$rv2 = mysqli_query($conn,"select * from palabras_clave Limit 1") or die (mysqli_error($conn));

$i=0;

?>

 <br />

 

 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr class="texto2" bgcolor="#0F683A">
    <td bgcolor="#022D60">Palabras Clave separadas con coma (,)</td>
   <td bgcolor="#022D60">Descripcion</td>
  </tr>
<?
$rowv2 = mysqli_fetch_array($rv2);
	?>
     <tr valign="top">
    
    <td valign="top" style="background:#CCC">

     <textarea name="palabras" style="width:300px; height:150px;" ><?=$rowv2['palabras']?></textarea>
      
     </td>
     <td valign="top" style="background:#CCC">

     <textarea name="descripcion" style="width:300px; height:150px;" ><?=$rowv2['descripcion']?></textarea>
      
     </td>
     
  </tr>
  

    

   
</table>

<input type="button" value="Actualizar Palabras Clave" onClick="act_p()">



<input name="actualizar_palabras" id="actualizar_palabras" type="hidden" value="" />
<script>
function act_p(){
document.getElementById('actualizar_palabras').value='si';
document.uno.submit();	
}




</script>
 
      
      <?
	}
	/// fin de Palabras Clave
	

	
	/// Email *****************
	if($tipo=='Email'){
	
		
		///
if(isset($_POST['text_admisiones']) and $_POST['text_admisiones']!='' and $_POST['text_contactenos']!=''){
$ad=$_POST['text_admisiones'];
$cot=$_POST['text_contactenos'];
$Insertar="UPDATE emails_contenido SET texto='$ad' Where referencia='admisiones'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
$Insertar="UPDATE emails_contenido SET texto='$cot' Where referencia='contactenos'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
/// imagen
if($_FILES['imagen_email']['name'] != ''){
if($_FILES['imagen_email']['size'] >= 300000){
$mensaje=$mensaje."ERROR: La Imagen supera los 300k <br>";
}elseif($_FILES['imagen_email']['type'] != "image/pjpeg" and $_FILES['imagen_email']['type'] != "image/jpeg"){
$mensaje=$mensaje.'ERROR: La Imagen no es jpg<br>';
}else{
if(!copy($_FILES['imagen_email']['tmp_name'], '../mail/cabezote.jpg')){
$mensaje=$mensaje."ERROR al copiar la Imagen ! <br>";
}else{
$mensaje=$mensaje."La Imagen fue subida con exito!<br>";
}
}
}
/// fin de imagen
}
		//
		?>
		
		
	
	
		<table width="100%">
      <tr>
            <td colspan="2" bgcolor="#CCCCCC" class="texto1">
            <?
	/// carga la imagen si existe
	///////////////// ***********************************************OJO CON LA RUTA *****************************************
	$ruta='../mail/cabezote.jpg';
				  if(file_exists ($ruta)){
					  $n=rand(1,100000);
				  ?>
                  <img src="<?=$ruta?>?n=<?=$n?>" width="600" height="130" /><br />
                  <?
				  }
				  ?>
Todos los correos que el sistema genera y generó en el pasado, cargarán esta imagen <br />
	                <strong>Cargar Nueva Imagen</strong> (jpg 600 X 130 pixeles):
<input type="file" name="imagen_email" id="imagen_email" class="inp" />
	                
            </td></tr>
            <tr>
            <td valign="top" class="texto1"><strong>Texto en email Admisiones:</strong></td>
            <td class="texto1">
            Se ha registrado el formulario de preinscripcion para 'nombre del alumno'.<br /><br />
            <?
			$rm = mysqli_query($conn,"select * from emails_contenido Where referencia='admisiones'") or die (mysqli_error($conn));
$rowm = mysqli_fetch_array($rm);
$add= $rowm['texto'];
			?>
<textarea name="text_admisiones" cols="80" rows="12" id="text_admisiones"><?=$add?></textarea>
            </td>
            </tr>
            <tr>
            <td valign="top" class="texto1"><strong>Texto en email Contáctenos:</strong></td>
            <td class="texto1">
            <?
			$rm = mysqli_query($conn,"select * from emails_contenido Where referencia='contactenos'") or die (mysqli_error($conn));
$rowm = mysqli_fetch_array($rm);
$add= $rowm['texto'];
			?>
<textarea name="text_contactenos" cols="80" rows="12" id="text_contactenos"><?=$add?></textarea>
            </td>
            </tr>
            <tr>
            <td class="texto1">&nbsp;</td>
            <td class="texto1">
            <label>
	         <input name="button8" type="submit" class="bot" id="button8" value="Actualizar" />
              </label>
            </td>
            
            </tr>
            </table>
            
            <script>
		CKEDITOR.replace( 'text_admisiones', {
		// Define the toolbar groups as it is a more accessible solution.
		toolbarGroups: [
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		//{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		//{ name: 'document', groups: [ 'mode'] }, /* preview source*/
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },//, 'cleanup'
		{ name: 'paragraph', groups: [ 'list', 'align' ] }, //, 'blocks', 'bidi', 'paragraph', 'indent'
		{ name: 'colors', groups: [ 'colors' ] }
		/*
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'about', groups: [ 'about' ] }
		*/
		/*
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'document', groups: [ 'mode'] }, // preview source
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'colors', groups: [ 'colors' ] }
		*/
					],
		// Remove the redundant buttons from toolbar groups defined above.
		removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Save,Language,Print'
		//removeButtons = 'Underline,Subscript,Superscript,Anchor,Image,SpecialChar,Maximize,RemoveFormat,Blockquote,Styles,Format,About'
				} );
				
				
				CKEDITOR.replace( 'text_contactenos', {
		// Define the toolbar groups as it is a more accessible solution.
		toolbarGroups: [
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		//{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		//{ name: 'document', groups: [ 'mode'] }, /* preview source*/
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },//, 'cleanup'
		{ name: 'paragraph', groups: [ 'list', 'align' ] }, //, 'blocks', 'bidi', 'paragraph', 'indent'
		{ name: 'colors', groups: [ 'colors' ] }
		/*
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'about', groups: [ 'about' ] }
		*/
/*
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'document', groups: [ 'mode'] }, //preview source
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'colors', groups: [ 'colors' ] }
		*/			],
		// Remove the redundant buttons from toolbar groups defined above.
		removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Save,Language,Print'
		//removeButtons = 'Underline,Subscript,Superscript,Anchor,Image,SpecialChar,Maximize,RemoveFormat,Blockquote,Styles,Format,About'
				} );
	</script>
		<?
		
	}
	/// fin Email
	
	
	

	/// landing
	if($tipo=='Landing'){
		/// primero registramos los datos de las campañas con las URL para cada caso:
		
	
	if($_POST['total_campana']!=''){
		$a=0;
		$lim=$_POST['total_campana'];
		while($a<$lim){
			$a++;
		$campana_id=$_POST['campana_id'.$a];
		$ref=$_POST['ref'.$a];
			//
			if($campana_id=='' and $ref!=''){
			$Insertar="INSERT INTO campanas (ref) VALUES ('$ref')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
			}else if($campana_id!=''){
			$Insertar="UPDATE campanas SET ref='$ref' WHERE id='$campana_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
			}
			//echo $Insertar.'-'.$donde_id.':'.$ref.'<br>';
		}
		?>
	<script>
	alert('Datos guardados');	
		  </script>
		<?
	}
?>
<br>Listado de Campañas:
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
   <?
	$rx = mysqli_query($conn,"select * from campanas Order by id") or die(mysqli_error($conn));
	$limite=mysqli_num_rows($rx)+2;
	if($limite<5){
		$limite=5;
	}
	$i=0;
while($rowx = mysqli_fetch_array($rx) or $i<=$limite){
	$i++;
	?>
    <tr>
     <td><?=$i?></td>
      <td style="font-size: 13px;">
	<input type="hidden" name="campana_id<?=$i?>" value="<?=$rowx['id']?>">
  <input type="text" name="ref<?=$i?>" value="<?=$rowx['ref']?>" placeholder="Nombre Campaña">
  <?
		if($rowx['ref']!=''){
		$lk=eliminar_simbolos($rowx['ref']);
		echo 'URL: <a href="http://www.gimnasiolaspalmas.edu.co/landing/'.$lk.'" target="_blank">http://www.gimnasiolaspalmas.edu.co/landing/'.$lk.'</a>';	
		}
	
	$campana_id=$rowx['id'];
	$mes_actual=date('Y-m').'-01';
	$dia_actual=date('j');
	$now=time();
	$seg2=$now-(($dia_actual+1)*24*60*690);
	$mes_anterior=date('Y-m',$seg2).'-01';
	/// Totales
	$rlng = mysqli_query($conn,"select count(id) as total from landing WHERE landing_id='$campana_id' and landing_id!='0'") or die(mysqli_error($conn));
	$rowlng = mysqli_fetch_array($rlng);
	  //
	$rlngB = mysqli_query($conn,"select count(id) as total from alumnos WHERE landing_campana_id='$campana_id' and  landing_campana_id!='0'") or die(mysqli_error($conn));
	$rowlngB = mysqli_fetch_array($rlngB);
	/// del mes
	$rlngC = mysqli_query($conn,"select count(id) as total from landing WHERE landing_id='$campana_id' and landing_id!='0' and fecha>='$mes_actual'") or die(mysqli_error($conn));
	$rowlngC = mysqli_fetch_array($rlngC);
	  //
	$rlngD = mysqli_query($conn,"select count(id) as total from alumnos WHERE landing_campana_id='$campana_id' and  landing_campana_id!='0' and fecha_registro>='$mes_actual'") or die(mysqli_error($conn));
	$rowlngD = mysqli_fetch_array($rlngD);
	/// Mes anterior
	$rlngE = mysqli_query($conn,"select count(id) as total from landing WHERE landing_id='$campana_id' and landing_id!='0' and fecha>='$mes_anterior' and fecha<'$mes_actual'") or die(mysqli_error($conn));
	$rowlngE = mysqli_fetch_array($rlngE);
	  //
	$rlngF = mysqli_query($conn,"select count(id) as total from alumnos WHERE landing_campana_id='$campana_id' and  landing_campana_id!='0'  and fecha_registro>='$mes_anterior' and fecha_registro<'$mes_actual'") or die(mysqli_error($conn));
	$rowlngF = mysqli_fetch_array($rlngF);
		?>
		
		<br>TOTAL: Visitas <?=$rowlng['total']?> - Inscripciones <?=$rowlngB['total']?>| Mes Anterior: Visitas <?=$rowlngE['total']?> - Inscripciones <?=$rowlngF['total']?>| Mes Actual: Visitas <?=$rowlngC['total']?> - Inscripciones <?=$rowlngD['total']?>| 
   </td>
    </tr>
    <?
}
	?>
  </tbody>
</table>
<input type="submit" value="Guardar">
<input type="hidden" name="total_campana" value="<?=$i?>">

	
	<iframe src="editar_pagina.php?id=234" style="border:0px solid; width: 100%; height: 1900px; overflow-x: hidden; overflow-y: auto"></iframe>	
			<?	
	}
	/// fin landing
	
	
	
	
	
	//// Arbol de paginas
if($tipo=='Paginas'){	
	
	if($_POST['actualizar_paginas']=='si'){
		$total_categorias=$_POST['total_categorias'];
		$c=0;
		while($c<$total_categorias){
			$c++;
			$id=$_POST['id'.$c];
			$categoria=$_POST['categoria'.$c];
			$categoriav=$_POST['categoriav'.$c];
			$orden=$_POST['orden'.$c];
			$menu=$_POST['menu'.$c];
			$activa=$_POST['activa'.$c];
			if($id!=''){
$Insertar="UPDATE paginas SET categoria='$categoria', pagina='$categoria', orden='$orden', menu='$menu', activa='$activa' WHERE id='$id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
				//
$Insertar="UPDATE paginas SET categoria='$categoria' WHERE categoria='$categoriav'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));				
			}else if($categoria!=''){
$Insertar="INSERT INTO paginas (categoria, pagina, orden, menu, activa) VALUES ('$categoria', '$categoria', '$orden', '$menu', 'si')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
/// obtenemos el id recien inscrito
$rid = mysqli_query($conn,"select id from paginas Where categoria='$categoria' and orden='$orden'") or die(mysqli_error($conn));
$rowid = mysqli_fetch_array($rid);
$id=$rowid['id'];
			}
			
			$p=0;
			$total_paginas=$_POST['total_paginas'.$c];
			while($p<$total_paginas){
				$p++;
			$idp=$_POST['id'.$c.'_'.$p];
			$pagina=$_POST['pagina'.$c.'_'.$p];
			$ordenp=$_POST['orden'.$c.'_'.$p];
			$activap=$_POST['activa'.$c.'_'.$p];
			if($idp!=''){
$Insertar="UPDATE paginas SET pagina='$pagina', suborden='$ordenp', activa='$activap' WHERE id='$idp'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
				
			}else if($pagina!=''){
$Insertar="INSERT INTO paginas (categoria, pagina, suborden, activa) VALUES ('$categoria', '$pagina', '$ordenp', 'si')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));				
			}	
				
				//echo $Insertar.'<br><br>';
			}
		}
		
	
		
		$mensaje='paginas actualizadas.';
	}
	
	
/// primero categorias
$rc= mysqli_query($conn,"SELECT * FROM paginas WHERE suborden='0'  ORDER BY orden ") or die (mysqli_error($conn));
	$c=0;
while($rowc = mysqli_fetch_array($rc)){	
	$c++;
	?>
	<input type="hidden" name="id<?=$c?>" value="<?=$rowc['id']?>">
	<input type="hidden" name="categoriav<?=$c?>" value="<?=$rowc['categoria']?>">
		<div class="categoria"><input type="text" name="orden<?=$c?>" id="orden<?=$c?>" value="<?=$c?>" style="width:30px">. <input type="text" name="categoria<?=$c?>" id="categoria<?=$c?>" value="<?=$rowc['categoria']?>" placeholder="categoria" style="width: 400px;" > <select name="menu<?=$c?>" id="menu<?=$c?>">
			<option value="principal">Menú principal</option>
			<option value="superior">Menú superior</option>
			<option value="inferior">Menú inferior</option>
			</select> <select name="activa<?=$c?>" id="activa<?=$c?>">
			<option value="si">visible</option>
			<option value="no">oculta</option>
			</select> | <i class="fa fa-edit" style="cursor: pointer; color: green;" onClick="editar_pagina('<?=$rowc['id']?>')"></i> | <i class="fa fa-trash-o" style="cursor: pointer; color: red;" onClick="borrar_registro('paginas', '<?=$rowc['id']?>')"></i>
			<script>
				$('#menu<?=$c?>').val('<?=$rowc['menu']?>');
				$('#activa<?=$c?>').val('<?=$rowc['activa']?>');
			</script></div>
		
		<?
		//// cargamos las paginas de cada categoria
		$categoria=$rowc['categoria'];
	    
		$rp= mysqli_query($conn,"SELECT * FROM paginas WHERE categoria='$categoria' and suborden!='0' ORDER BY suborden ") or die (mysqli_error($conn));
	$p=0;
while($rowp = mysqli_fetch_array($rp)){	
	$p++;
	?>
	<input type="hidden" name="id<?=$c?>_<?=$p?>" value="<?=$rowp['id']?>">
		<div class="pagina" style="margin-left: 30px;"><input type="text" name="orden<?=$c?>_<?=$p?>" id="orden<?=$c?>_<?=$p?>" value="<?=$p?>" style="width:30px">. <input type="text" name="pagina<?=$c?>_<?=$p?>" id="pagina<?=$c?>_<?=$p?>" value="<?=$rowp['pagina']?>" placeholder="pagina" style="width: 400px;" > <select name="activa<?=$c?>_<?=$p?>" id="activa<?=$c?>_<?=$p?>">
			<option value="si">visible</option>
			<option value="no">oculta</option>
			</select> | <i class="fa fa-edit" style="cursor: pointer; color: green;" onClick="editar_pagina('<?=$rowp['id']?>')"></i> | <i class="fa fa-trash-o" style="cursor: pointer; color: red;" onClick="borrar_registro('paginas', '<?=$rowp['id']?>')"></i>
			<script>
				$('#activa<?=$c?>_<?=$p?>').val('<?=$rowp['activa']?>');
			</script>
		</div>
		
		
	<?
}
$p++;
?>
	<input type="hidden" name="id<?=$c?>_<?=$p?>" value="">
		<div class="pagina" style="margin-left: 30px;"><input type="text" name="orden<?=$c?>_<?=$p?>" id="orden<?=$c?>_<?=$p?>" value="<?=$p?>" style="width:30px">. <input type="text" name="pagina<?=$c?>_<?=$p?>" id="pagina<?=$c?>_<?=$p?>" value="" placeholder="pagina" style="width: 400px;" ></div>
		
		<input type="hidden" name="total_paginas<?=$c?>" id="total_paginas<?=$c?>" value="<?=$p?>">
	<?	
		///
	
}
	
$c++;
?>
	<input type="hidden" name="id<?=$c?>" value="">
		<div class="categoria"><input type="text" name="orden<?=$c?>" id="orden<?=$c?>" value="<?=$c?>" style="width:30px">. <input type="text" name="categoria<?=$c?>" id="categoria<?=$c?>" value="" placeholder="categoria" style="width: 400px;" ></div>
		
		<br><br>
		<input type="hidden" name="total_categorias" id="total_categorias" value="<?=$c?>">
		<input type="hidden" name="actualizar_paginas" id="actualizar_paginas" value="">
		<input type="submit" value="Actualizar listado" onClick="document.getElementById('actualizar_paginas').value='si';">
	<?	
	
}
	//// fin de arbol de páginas
	
	
	
	

	?>
    
    
    

      
    
  
    
    
    
    
   
    <?
	/// BANNER *****************
	if($tipo=='Banner Home'){

////EDITAR BANNER
if($_POST['editar_banner']!=''){

	
	//
	$tipo_slide=$_POST['tipo_slide'];
$Insertar="UPDATE home_configuracion SET tipo_slide='$tipo_slide'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
	//
	
$p=0;
$cantidad=$_POST['cantidad'];
while($p<$cantidad){
$p++;
$id=$_POST['banner_id'.$p];
$orden=$_POST['orden'.$p];
$link=$_POST['link'.$p];
$activo=$_POST['activo'.$p];
$youtube=$_POST['youtube'.$p];
$titulo=$_POST['titulo'.$p];
$texto=$_POST['texto'.$p];
//echo $_POST['activo'.$p].'+<br>';

if($activo==''){
$activo='si';	
}
/**/
if($id!=''){
$Insertar="UPDATE banner_home SET link='$link', orden='$orden', activo='$activo', youtube='$youtube', titulo='$titulo', texto='$texto' Where id='$id'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
}else if($id=='' and $link!=''){
$Insertar="INSERT INTO banner_home (link, orden, activo, youtube, titulo, texto) VALUES ('$link', '$orden', '$activo', '$youtube', '$titulo', '$texto')";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
/// identificamos el id recien
$rb= mysqli_query($conn,"select id from banner_home Where orden='$orden' Order by id DESC Limit 1") or die(mysqli_error($conn));
$rowb = mysqli_fetch_array($rb);
$id=$rowb['id'];
}
//echo $Insertar.'<br>';
///


/// IMAGEN 
//echo $p.'. '.$_FILES['foto'.$p]['name'].'*<br>';
if($_FILES['foto'.$p]['name'] != ''){
if($_FILES['foto'.$p]['type'] != "image/pjpeg" and $_FILES['foto'.$p]['type'] != "image/jpeg"   ){
$mensaje=$mensaje.'<br>ERROR: la imagen no es jpg ';
}else{
	$ruta_temp='imagenes/banner/'.$id.'B.jpg';
	$ruta_pc='imagenes/banner/'.$id.'.jpg';
	$ruta_movil='imagenes/banner/M'.$id.'.jpg';
///copiamos la foto al tamano real
if(!copy($_FILES['foto'.$p]['tmp_name'], $ruta_temp)){
$mensaje=$mensaje."<br>ERROR ! ";
}else{
	
$ancho=1600;//1200
$alto=700;//600
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_pc, 90);
///// version movil
$ancho=400;
$alto=200;
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_movil, 90);


unlink($ruta_temp) ;

//
$cod=time();
$Insertar="UPDATE banner_home SET cod='$cod' Where id='$id'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));

}
}

}
	//echo $p.'. '.$mensaje.'+<br>';
// fin de IMAGEN


	
}


}
/// FIN Banner

	
			/// cargamos 
$r = mysqli_query($conn,"select * from banner_home Order by orden, id ") or die (mysqli_error($conn));

		
		$rhc = mysqli_query($conn,"select * from home_configuracion Limit 1") or die (mysqli_error($conn));
		$rowhc = mysqli_fetch_array($rhc);
?>
<br>
Tipo de Contenido: <select name="tipo_slide" id="tipo_slide">
		<option value="video">video</option>
		<option value="imagenes">imagenes</option>
		</select>
		
		<script>
			$('#tipo_slide').val('<?=$rowhc['tipo_slide']?>');
		</script>
<br />
 <br />

 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr class="texto2">
    <td bgcolor="#990000">&nbsp;</td>
    <td bgcolor="#990000">Link</td>
    <td bgcolor="#990000">Imagenes</td>
    <td bgcolor="#990000">activo</td>
  </tr>
<?
/// primero cargamos el espacio para nuevo producto
$limite=mysqli_num_rows($r)+1;
$b=0;
while($row = mysqli_fetch_array($r) or $b<$limite){
	$b++;
	
	if($row['orden']==0 or $row['orden']==''){
	$row['orden']=$b;	
	}

	if($row['activo']==''){
	$row['activo']='si';
	}
?>
<input type="hidden" name="banner_id<?=$b?>" value="<?=$row['id']?>">
<tr valign="top">
<td style="background:#EACCCC; height:1px;"> 
<input type="text" name="orden<?=$b?>" id="orden<?=$b?>" value="<?=$row['orden']?>" style="width:30px">
</td>
  <td style="background:#EACCCC; padding:5px">
  
  
  <select name="link<?=$b?>" id="link<?=$b?>" class="campo1" style="width: 300px;" >
  <option value="">Sin Link</option>
  
 <?
	/// cargamos las categorias de productos
	$rp = mysqli_query($conn,"SELECT id, categoria, pagina FROM paginas Order by activa DESC, categoria ASC, pagina ASC") or die(mysqli_error($conn));
while($rowp = mysqli_fetch_array($rp)){
	?>
    <option value="/cont/<?=$rowp['categoria'].'/'.$rowp['pagina']?>"><?=$rowp['categoria'].': '.$rowp['pagina']?></option>
  
    <?
}
?>

 </select>
 <br>
 Youtube Código: <small>(Ej: 3kkjOmW8jFc)</small> <br>
 <input type="text" name="youtube<?=$b?>" id="youtube<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['youtube']?>">
 <br>
 Titulo:<br>
 <input type="text" name="titulo<?=$b?>" id="titulo<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['titulo']?>">
 <br>
 Texto<br>
 <textarea name="texto<?=$b?>" id="texto<?=$b?>" class="campo1" style="width: 100%; height: 80px;"><?=$row['texto']?></textarea>

<script>
document.getElementById('link<?=$b?>').value='<?=$row['link']?>';
</script>
  
  </td>
  
  <td style="background:#EACCCC;">
  <?

	$rutam='imagenes/banner/'.$row['id'].'.jpg';
	if(file_exists($rutam)){
		?>
  <img src="<?=$rutam?>?k=<?=$k?>" style="width: 350px; height: auto" />
       <br /><span class="linkx" onclick="borrar_file('<?=$rutam?>');">Borrar</span>
    <br />
    <?
	}
	?>
	<br> Imagen JPG 1600 x 700 px: <br>
    <input type="file" name="foto<?=$b?>"><br>
     
  </td>
  
  <td style="background:#EACCCC; height:1px;">
  <select name="activo<?=$b?>" id="activo<?=$b?>">
  <option value="si">si</option>
  <option value="no">no</option>
  </select>
  <script>
  document.getElementById('activo<?=$b?>').value='<?=$row['activo']?>';
  </script>
  </td>
  </tr>


    <?
}
?>
</table>
<input type="button" name="button2" id="button2" value="Actualizar Banner" onclick="document.getElementById('editar_banner').value='si'; document.uno.submit();" />
<input name="editar_banner" id="editar_banner" type="hidden" value="" />
<input name="cantidad" id="cantidad" type="hidden" value="<?=$b?>" />



    <?
	}
	/// fin de BANNER
	
	
	//// NOTICIAS
	if($tipo=='Noticias'){

		////EDITAR BANNER
		if($_POST['editar_banner']!=''){
		
					
		$p=0;
		$cantidad=$_POST['cantidad'];
		while($p<$cantidad){
		$p++;
		$id=$_POST['banner_id'.$p];
		$titulo=$_POST['titulo'.$p];
		$intro=$_POST['intro'.$p];
		$texto=$_POST['texto'.$p];
		$orden=$_POST['orden'.$p];
		$fecha=$_POST['fecha'.$p];
		$activo=$_POST['activo'.$p];
				
		//echo $_POST['activo'.$p].'+<br>';
		
		if($activo==''){
		$activo='si';	
		}
		/**/
		if($id!=''){
		$Insertar="UPDATE noticias SET titulo='$titulo', intro='$intro', texto='$texto', orden='$orden', fecha='$fecha', activo='$activo' Where id='$id'";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
		}else if($id=='' and $titulo!=''){
		$Insertar="INSERT INTO noticias (titulo, intro, texto, orden, fecha, activo) VALUES ('$titulo', '$intro', '$texto', '$orden', '$fecha', '$activo')";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		/// identificamos el id recien
		$rb= mysqli_query($conn,"select id from noticias Where orden='$orden' Order by id DESC Limit 1") or die(mysqli_error($conn));
		$rowb = mysqli_fetch_array($rb);
		$id=$rowb['id'];
		}
		//echo $Insertar.'***<br>';
		///
		
		
		/// IMAGEN 
		//echo $p.'. '.$_FILES['foto'.$p]['name'].'*<br>';
		if($_FILES['foto'.$p]['name'] != ''){
		if($_FILES['foto'.$p]['type'] != "image/pjpeg" and $_FILES['foto'.$p]['type'] != "image/jpeg"   ){
		$mensaje=$mensaje.'<br>ERROR: la imagen no es jpg ';
		}else{
			$ruta_temp='imagenes/noticias/'.$id.'B.jpg';
			$ruta_pc='imagenes/noticias/'.$id.'.jpg';
			$ruta_movil='imagenes/noticias/M'.$id.'.jpg';
		///copiamos la foto al tamano real
		if(!copy($_FILES['foto'.$p]['tmp_name'], $ruta_temp)){
		$mensaje=$mensaje."<br>ERROR ! ";
		}else{
			
		$ancho=1200;
		$alto=750;
		////******
		$fuente = imagecreatefromjpeg($ruta_temp);
		$imgAncho = imagesx ($fuente);
		$imgAlto =imagesy($fuente);
		$imagen = ImageCreateTrueColor($ancho,$alto);
		
		
		$coory=0;
		$coorx=0;
		$ancho_inicial=$imgAncho;
		$alto_inicial=($alto*$imgAncho)/$ancho;
		$alto_inicial=(int)$alto_inicial;
		if($alto_inicial<=$imgAlto){
		$coory=($imgAlto-$alto_inicial)/2;
		$coory=(int)$coory;
		}else{
		///
		$alto_inicial=$imgAlto;
		$ancho_inicial=($ancho*$imgAlto)/$alto;
		$ancho_inicial=(int)$ancho_inicial;
		
		$coorx=($imgAncho-$ancho_inicial)/2;
		$coorx=(int)$coorx;
		}
		//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
		imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
		/////*****
		imagejpeg($imagen, $ruta_pc, 90);
		///// version movil
$ancho=400;
$alto=250;
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_movil, 90);
		
		unlink($ruta_temp) ;
		
		}
		}
		
		}
			//echo $p.'. '.$mensaje.'+<br>';
		// fin de IMAGEN
		
		
			
		}
		
		
		}
		/// FIN Banner
		
			
					/// cargamos 
		$r = mysqli_query($conn,"select * from noticias Order by orden, id ") or die (mysqli_error($conn));
		
		
		?>
		
		<br />
		 <br />
		
		 <table width="100%" border="0" cellspacing="1" cellpadding="2">
		  <tr class="texto2">
			<td bgcolor="#990000">&nbsp;</td>
			<td bgcolor="#990000">Noticia</td>
			<td bgcolor="#990000">Imagen</td>
			<td bgcolor="#990000">activo</td>
		  </tr>
		<?
		/// primero cargamos el espacio para nuevo producto
		$limite=mysqli_num_rows($r)+1;
		$b=0;
		while($row = mysqli_fetch_array($r) or $b<$limite){
			$b++;
			
			if($row['orden']==0 or $row['orden']==''){
			$row['orden']=$b;	
			}
			if($row['activo']==''){
				$row['activo']='si';
				$row['fecha']=date('Y-m-d');
				}
		?>
		<input type="hidden" name="banner_id<?=$b?>" value="<?=$row['id']?>">
		<tr valign="top">
		<td style="background:#EACCCC; height:1px;"> 
		<input type="text" name="orden<?=$b?>" id="orden<?=$b?>" value="<?=$row['orden']?>" style="width:30px" class="campo1">
		</td>
		  <td style="background:#EACCCC;">
		  Fecha:<br>
		 <input type="date" name="fecha<?=$b?>" id="fecha<?=$b?>" class="campo1" style="width: 200px;" value="<?=$row['fecha']?>">
		 <br>
		  Titulo:<br>
		 <input type="text" name="titulo<?=$b?>" id="titulo<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['titulo']?>">
		  
		  <br>
		 Intro<br>
		 <textarea name="intro<?=$b?>" id="intro<?=$b?>" class="campo1" style="width: 100%; height: 80px" ><?=$row['intro']?></textarea>
		 
		 
		 <br>
		 Texto<br>
		 <textarea name="texto<?=$b?>" id="texto<?=$b?>" class="campo1" style="width: 100%; height: 150px"><?=$row['texto']?></textarea>
		
		<script>
		CKEDITOR.replace( 'texto<?=$b?>', {
		toolbarGroups: [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		//{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		//{ name: 'document', groups: [ 'mode'] }, /* preview source*/
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },//, 'cleanup'
		{ name: 'paragraph', groups: [ 'list', 'align' ] }, //, 'blocks', 'bidi', 'paragraph', 'indent'
		{ name: 'colors', groups: [ 'colors' ] }
					],
		// Remove the redundant buttons from toolbar groups defined above.
		removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Save,Language,Print'
		//removeButtons = 'Underline,Subscript,Superscript,Anchor,Image,SpecialChar,Maximize,RemoveFormat,Blockquote,Styles,Format,About'
				} );
		</script>
		  
		  </td>
		  
		  <td style="background:#EACCCC;">
		  <?
		
			$rutam='imagenes/noticias/'.$row['id'].'.jpg';
			if(file_exists($rutam)){
				?>
		  <img src="<?=$rutam?>?k=<?=$k?>" style="width: 350px; height: auto" />
			   <br /><span class="linkx" onclick="borrar_file('<?=$rutam?>');">Borrar</span>
			<br />
			<?
			}
			?>
			<br> Imagen JPG 1200 x 750 px: <br>
			<input type="file" name="foto<?=$b?>"><br>
			 
		  </td>
		  
		  <td style="background:#EACCCC; height:1px;">
		  <select name="activo<?=$b?>" id="activo<?=$b?>">
		  <option value="si">si</option>
		  <option value="no">no</option>
		  </select>
		  <script>
		  document.getElementById('activo<?=$b?>').value='<?=$row['activo']?>';
		  </script>
		  </td>
		  </tr>
		
		
			<?
		}
		?>
		</table>
		<input type="button" name="button2" id="button2" value="Actualizar" onclick="document.getElementById('editar_banner').value='si'; document.uno.submit();" />
		<input name="editar_banner" id="editar_banner" type="hidden" value="" />
		<input name="cantidad" id="cantidad" type="hidden" value="<?=$b?>" />
		
		
		
			<?
			}

	////FIN NOTICIAS

	//// Footer
	if($tipo=='Footer'){

		////EDITAR BANNER
		if($_POST['editar_banner']!=''){
		
						
		$p=0;
		$cantidad=$_POST['cantidad'];
		while($p<$cantidad){
		$p++;
		$id=$_POST['banner_id'.$p];
		$texto=$_POST['texto'.$p];
		$link=$_POST['link'.$p];
		$otra_ventana=$_POST['otra_ventana'.$p];
		$orden=$_POST['orden'.$p];
		
		
		//echo $_POST['activo'.$p].'+<br>';
		
		/**/
		if($id!=''){
		$Insertar="UPDATE footer SET texto='$texto', link='$link', otra_ventana='$otra_ventana', orden='$orden'  Where id='$id'";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
		}else if($id=='' and $link!=''){
		$Insertar="INSERT INTO footer (texto,link, otra_ventana,orden) VALUES ('$texto','$link', '$otra_ventana','$orden')";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		/// identificamos el id recien
		$rb= mysqli_query($conn,"select id from footer Where orden='$orden' Order by id DESC Limit 1") or die(mysqli_error($conn));
		$rowb = mysqli_fetch_array($rb);
		$id=$rowb['id'];
		}
		//echo $Insertar.'<br>';
		///
				
		
			
		}
		
		
		}
		/// FIN Banner
		
			
					/// cargamos 
		$r = mysqli_query($conn,"select * from footer Order by orden, id ") or die (mysqli_error($conn));
		
		
		?>
		
		<br />
		 <br />
		
		 <table width="100%" border="0" cellspacing="1" cellpadding="2">
		  <tr class="texto2">
			<td bgcolor="#990000">&nbsp;</td>
			<td bgcolor="#990000">Texto</td>
			<td bgcolor="#990000">Link</td>
			<td bgcolor="#990000">Cargar en</td>
		  </tr>
		<?
		/// primero cargamos el espacio para nuevo producto
		$limite=mysqli_num_rows($r)+1;
		$b=0;
		while($row = mysqli_fetch_array($r) or $b<$limite){
			$b++;
			
			if($row['orden']==0 or $row['orden']==''){
			$row['orden']=$b;	
			}
			if($row['activo']==''){
				$row['activo']='si';
				}
		?>
		<input type="hidden" name="banner_id<?=$b?>" value="<?=$row['id']?>">
		<tr valign="top">
		<td style="background:#EACCCC; height:1px;"> 
		<input type="text" name="orden<?=$b?>" id="orden<?=$b?>" value="<?=$row['orden']?>" style="width:30px">
		</td>
		  <td style="background:#EACCCC;">
		  
		 <input type="text" name="texto<?=$b?>" id="texto<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['texto']?>">
		 
		  </td>
		  
		  <td style="background:#EACCCC;">
		  <input type="text" name="link<?=$b?>" id="link<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['link']?>">
			 
		  </td>
		  
		  <td style="background:#EACCCC; height:1px;">
		  <select name="otra_ventana<?=$b?>" id="otra_ventana<?=$b?>">
		  <option value="no">Misma Ventana</option>
		  <option value="si">Otra Ventana</option>
		  </select>
		  <?php
		  if($row['otra_ventana']==''){
			$row['otra_ventana']='no'; 
		  }
		  ?>
		  <script>
		  document.getElementById('otra_ventana<?=$b?>').value='<?=$row['otra_ventana']?>';
		  </script>
		  </td>
		  </tr>
		
		
			<?
		}
		?>
		</table>
		<input type="button" name="button2" id="button2" value="Actualizar Footer" onclick="document.getElementById('editar_banner').value='si'; document.uno.submit();" />
		<input name="editar_banner" id="editar_banner" type="hidden" value="" />
		<input name="cantidad" id="cantidad" type="hidden" value="<?=$b?>" />
		
		
		
			<?
			}
	/// FIN Footer


	/// Aviso Home
	if($tipo=='Aviso Home'){

		////EDITAR Aviso
		if($_POST['editar_aviso']!=''){

			$link=$_POST['link'];
			$Insertar="UPDATE aviso_home SET link='$link' WHERE id='1'";
			mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
						
		/// IMAGEN 
		//echo $p.'. '.$_FILES['foto']['name'].'*<br>';
		if($_FILES['foto']['name'] != ''){
		if($_FILES['foto']['type'] != "image/pjpeg" and $_FILES['foto']['type'] != "image/jpeg"   ){
		$mensaje=$mensaje.'<br>ERROR: la imagen no es jpg ';
		}else{
			$ruta_temp='imagenes/aviso_homeB.jpg';
			$ruta_pc='imagenes/aviso_home.jpg';
			
		///copiamos la foto al tamano real
		if(!copy($_FILES['foto']['tmp_name'], $ruta_temp)){
		$mensaje=$mensaje."<br>ERROR ! ";
		}else{
			
		$ancho=400;
		$alto=600;
		////******
		$fuente = imagecreatefromjpeg($ruta_temp);
		$imgAncho = imagesx ($fuente);
		$imgAlto =imagesy($fuente);
		$imagen = ImageCreateTrueColor($ancho,$alto);
		
		
		$coory=0;
		$coorx=0;
		$ancho_inicial=$imgAncho;
		$alto_inicial=($alto*$imgAncho)/$ancho;
		$alto_inicial=(int)$alto_inicial;
		if($alto_inicial<=$imgAlto){
		$coory=($imgAlto-$alto_inicial)/2;
		$coory=(int)$coory;
		}else{
		///
		$alto_inicial=$imgAlto;
		$ancho_inicial=($ancho*$imgAlto)/$alto;
		$ancho_inicial=(int)$ancho_inicial;
		
		$coorx=($imgAncho-$ancho_inicial)/2;
		$coorx=(int)$coorx;
		}
		//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
		imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
		/////*****
		imagejpeg($imagen, $ruta_pc, 90);
				
		unlink($ruta_temp) ;
		
		}
		}
		
		}
			//echo $p.'. '.$mensaje.'+<br>';
		// fin de IMAGEN
		
		
			
		
		
		
		}
		/// FIN Editar Aviso

		if($_POST['borrar_aviso']!=''){
						
			/// IMAGEN 
			$ruta_pc='imagenes/aviso_home.jpg';
				
			///copiamos la foto al tamano real
			if(isset($ruta_pc)){
				unlink($ruta_pc) ;
			}
			}
			/// Fin Borrar Aviso
			
			
				
			
			
					/// cargamos 
		$r = mysqli_query($conn,"SELECT * FROM aviso_home WHERE id='1'") or die (mysqli_error($conn));
		$row = mysqli_fetch_array($r);
		?>
		<br>
		<small>Imagen JPG 400 x 600 px:</small>
		
		<br />
		<?
		
			$rutam='imagenes/aviso_home.jpg';
			if(file_exists($rutam)){
				?>
		  <img src="<?=$rutam?>?k=<?=$k?>" style="width: 400px; height: 600px" />
			   <br /><span class="linkx" onclick="borrar_file('<?=$rutam?>');">Borrar</span>
			<br />
			<?
			}
			?>
			<input type="file" name="foto"><br>
			Link URL:<br>
			<input type="text" name="link" id="link" class="campo1" style="width: 300px;" value="<?=$row['link']?>">
		 
		
		 <br><br>
		<input type="button" name="button2" id="button2" value="Actualizar Aviso" onclick="document.getElementById('editar_aviso').value='si'; document.uno.submit();" />
		<input name="editar_aviso" id="editar_aviso" type="hidden" value="" />
			
		
		
			<?
			}
	///FIN Aviso Home
	
	

	//// Presentacion Home
	if($tipo=='Presentacion Home'){

		////EDITAR BANNER
		if($_POST['editar_banner']!=''){
					
		$p=0;
		$cantidad=$_POST['cantidad'];
		while($p<$cantidad){
		$p++;
		$id=$_POST['banner_id'.$p];
		$orden=$_POST['orden'.$p];
		$link=$_POST['link'.$p];
		$activo=$_POST['activo'.$p];
		$youtube=$_POST['youtube'.$p];
		$titulo=$_POST['titulo'.$p];
		$texto=$_POST['texto'.$p];
				
		if($activo==''){
		$activo='si';	
		}
		/**/
		if($id!=''){
		$Insertar="UPDATE presentacion_home SET link='$link', orden='$orden', activo='$activo', youtube='$youtube', titulo='$titulo', texto='$texto' Where id='$id'";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
		}else if($id=='' and $titulo!=''){
		$Insertar="INSERT INTO presentacion_home (link, orden, activo, youtube, titulo, texto) VALUES ('$link', '$orden', '$activo', '$youtube', '$titulo', '$texto')";	
		mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		/// identificamos el id recien
		$rb= mysqli_query($conn,"select id from presentacion_home Where orden='$orden' Order by id DESC Limit 1") or die(mysqli_error($conn));
		$rowb = mysqli_fetch_array($rb);
		$id=$rowb['id'];
		}
		//echo $Insertar.'<br>';
		///
		
		
		/// IMAGEN 
		//echo $p.'. '.$_FILES['foto'.$p]['name'].'*<br>';
		if($_FILES['foto'.$p]['name'] != ''){
		if($_FILES['foto'.$p]['type'] != "image/pjpeg" and $_FILES['foto'.$p]['type'] != "image/jpeg"   ){
		$mensaje=$mensaje.'<br>ERROR: la imagen no es jpg ';
		}else{
			$ruta_temp='imagenes/presentacion/'.$id.'B.jpg';
			$ruta_pc='imagenes/presentacion/'.$id.'.jpg';
			$ruta_movil='imagenes/presentacion/M'.$id.'.jpg';
		///copiamos la foto al tamano real
		if(!copy($_FILES['foto'.$p]['tmp_name'], $ruta_temp)){
		$mensaje=$mensaje."<br>ERROR ! ";
		}else{
			
		$ancho=800;
		$alto=400;
		////******
		$fuente = imagecreatefromjpeg($ruta_temp);
		$imgAncho = imagesx ($fuente);
		$imgAlto =imagesy($fuente);
		$imagen = ImageCreateTrueColor($ancho,$alto);
		
		
		$coory=0;
		$coorx=0;
		$ancho_inicial=$imgAncho;
		$alto_inicial=($alto*$imgAncho)/$ancho;
		$alto_inicial=(int)$alto_inicial;
		if($alto_inicial<=$imgAlto){
		$coory=($imgAlto-$alto_inicial)/2;
		$coory=(int)$coory;
		}else{
		///
		$alto_inicial=$imgAlto;
		$ancho_inicial=($ancho*$imgAlto)/$alto;
		$ancho_inicial=(int)$ancho_inicial;
		
		$coorx=($imgAncho-$ancho_inicial)/2;
		$coorx=(int)$coorx;
		}
		//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
		imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
		/////*****
		imagejpeg($imagen, $ruta_pc, 90);
		///// version movil
		$ancho=400;
		$alto=200;
		////******
		$fuente = imagecreatefromjpeg($ruta_temp);
		$imgAncho = imagesx ($fuente);
		$imgAlto =imagesy($fuente);
		$imagen = ImageCreateTrueColor($ancho,$alto);
		
		
		$coory=0;
		$coorx=0;
		$ancho_inicial=$imgAncho;
		$alto_inicial=($alto*$imgAncho)/$ancho;
		$alto_inicial=(int)$alto_inicial;
		if($alto_inicial<=$imgAlto){
		$coory=($imgAlto-$alto_inicial)/2;
		$coory=(int)$coory;
		}else{
		///
		$alto_inicial=$imgAlto;
		$ancho_inicial=($ancho*$imgAlto)/$alto;
		$ancho_inicial=(int)$ancho_inicial;
		
		$coorx=($imgAncho-$ancho_inicial)/2;
		$coorx=(int)$coorx;
		}
		//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
		imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
		/////*****
		imagejpeg($imagen, $ruta_movil, 90);
		
		
		unlink($ruta_temp) ;
		
		}
		}
		
		}
			//echo $p.'. '.$mensaje.'+<br>';
		// fin de IMAGEN
		
		
			
		}
		
		
		}
		/// FIN Banner
		
			
					/// cargamos 
		$r = mysqli_query($conn,"select * from presentacion_home Order by orden, id ") or die (mysqli_error($conn));
		
				?>
		
		<br />
		 <br />
		
		 <table width="100%" border="0" cellspacing="1" cellpadding="2">
		  <tr class="texto2">
			<td bgcolor="#990000">&nbsp;</td>
			<td bgcolor="#990000">Link</td>
			<td bgcolor="#990000">Imagen</td>
			<td bgcolor="#990000">activo</td>
		  </tr>
		<?
		/// primero cargamos el espacio para nuevo producto
		$limite=mysqli_num_rows($r)+1;
		$b=0;
		while($row = mysqli_fetch_array($r) or $b<$limite){
			$b++;
			
			if($row['orden']==0 or $row['orden']==''){
			$row['orden']=$b;	
			}
			if($row['activo']==''){
				$row['activo']='si';
				}
		?>
		<input type="hidden" name="banner_id<?=$b?>" value="<?=$row['id']?>">
		<tr valign="top">
		<td style="background:#EACCCC; height:1px;"> 
		<input type="text" name="orden<?=$b?>" id="orden<?=$b?>" value="<?=$row['orden']?>" style="width:30px">
		</td>
		  <td style="background:#EACCCC;">
		  		 
		 Link URL: <br>
		 <input type="text" name="link<?=$b?>" id="link<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['link']?>">
		 <br>
		 Youtube Código: <small>(Ej: 3kkjOmW8jFc)</small> <br>
		 <input type="text" name="youtube<?=$b?>" id="youtube<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['youtube']?>">
		 <br>
		 Titulo:<br>
		 <input type="text" name="titulo<?=$b?>" id="titulo<?=$b?>" class="campo1" style="width: 300px;" value="<?=$row['titulo']?>">
		 <br>
		 Texto<br>
		 <textarea name="texto<?=$b?>" id="texto<?=$b?>" class="campo1" style="width: 100%; height: 80px" ><?=$row['texto']?></textarea>
		
		<script>
		document.getElementById('link<?=$b?>').value='<?=$row['link']?>';

		CKEDITOR.replace( 'texto<?=$b?>', {
		toolbarGroups: [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },				
		{ name: 'links', groups: [ 'links' ] },
		//{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		//{ name: 'document', groups: [ 'mode'] }, /* preview source*/
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },//, 'cleanup'
		{ name: 'paragraph', groups: [ 'list', 'align' ] }, //, 'blocks', 'bidi', 'paragraph', 'indent'
		{ name: 'colors', groups: [ 'colors' ] }
					],
		// Remove the redundant buttons from toolbar groups defined above.
		removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,Save,Language,Print'
		//removeButtons = 'Underline,Subscript,Superscript,Anchor,Image,SpecialChar,Maximize,RemoveFormat,Blockquote,Styles,Format,About'
				} );
		</script>
		  
		  </td>
		  
		  <td style="background:#EACCCC;">
		  <?
		
			$rutam='imagenes/presentacion/'.$row['id'].'.jpg';
			if(file_exists($rutam)){
				?>
		  <img src="<?=$rutam?>?k=<?=$k?>" style="width: 350px; height: auto" />
			   <br /><span class="linkx" onclick="borrar_file('<?=$rutam?>');">Borrar</span>
			<br />
			<?
			}
			?>
			<br> Imagen JPG 800 x 400 px: <br>
			<input type="file" name="foto<?=$b?>"><br>
			 
		  </td>
		  
		  <td style="background:#EACCCC; height:1px;">
		  <select name="activo<?=$b?>" id="activo<?=$b?>">
		  <option value="si">si</option>
		  <option value="no">no</option>
		  </select>
		  <script>
		  document.getElementById('activo<?=$b?>').value='<?=$row['activo']?>';
		  </script>
		  </td>
		  </tr>
		
		
			<?
		}
		?>
		</table>
		<input type="button" name="button2" id="button2" value="Actualizar" onclick="document.getElementById('editar_banner').value='si'; document.uno.submit();" />
		<input name="editar_banner" id="editar_banner" type="hidden" value="" />
		<input name="cantidad" id="cantidad" type="hidden" value="<?=$b?>" />
		
		
		
			<?
			}
	/// FIN Presentacion Home
	
	///// POSTULANTES ***************
	if($tipo=='Postulantes'){
		
		//// BORRAR HV ASPIRANTE
if(isset($_POST['borrar_aspirante']) and $_POST['borrar_aspirante']!=''){
	$aspirante_id=$_POST['borrar_aspirante'];
$Insertar="DELETE from alumnos Where id='$aspirante_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
$mensaje='Registro de Aspirante eliminado';
}
/// FIN DE BORRAR HV ASPIRANTE
		
	  ?>
     
      <table width="100%">
      <tr>
            <td>
              <select name="usuarios1" class="inp" id="usuarios1" onchange="if(this.value!=''){window.location='admin_palmas.php?tipo=Postulantes&id='+this.value;}">
<option value="" selected="selected">Listado completo de Postulantes</option>
<?
$rs = mysqli_query($conn,"select id, nombre_estudiante from alumnos Where  tipo='aspirante' Order by nombre_estudiante") or die(mysqli_error($conn));
while($rows = mysqli_fetch_array($rs)){
echo '<option value="'.$rows['id'].'">'.$rows['nombre_estudiante'].'</option>';
}
?>
</select>
<?
$r1 = mysqli_query($conn,"select id from alumnos Where tipo='aspirante' and estado=''") or die(mysqli_error($conn));
$n1=mysqli_num_rows($r1);
$r2 = mysqli_query($conn,"select id from alumnos Where tipo='aspirante' and estado!=''") or die(mysqli_error($conn));
$n2=mysqli_num_rows($r2);
?>
 | <span class="texto1"><strong><a href="admin_palmas.php?tipo=Postulantes&ref=por_revisar" class="texto1">Nuevos</a></strong> </span><span class="texto5">(<?=$n1?>)</span><span class="texto1">| <strong><a href="admin_palmas.php?tipo=Postulantes&ref=revisados" class="texto1">Revisados</a></strong></span><span class="texto5">(<?=$n2?>)
 <input type="hidden" name="id" id="id" />
 <input type="hidden" name="borrar_aspirante" id="borrar_aspirante" />
 </span> <span class="texto1">| <strong><a href="admin_palmas.php?tipo=Postulantes&ref=Donde se Entero" class="texto1">Donde se Entero?</a></strong></span></td>
          </tr>
                  <?
if($_GET['ref']=='Donde se Entero'){
	
	if($_POST['total_donde']!=''){
		$a=0;
		$lim=$_POST['total_donde'];
		while($a<$lim){
			$a++;
		$donde_id=$_POST['donde_id'.$a];
		$ref=$_POST['ref'.$a];
			//
			if($donde_id=='' and $ref!=''){
			$Insertar="INSERT INTO donde_entero (ref) VALUES ('$ref')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
			}else if($donde_id!=''){
			$Insertar="UPDATE donde_entero SET ref='$ref' WHERE id='$donde_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
			}
			//echo $Insertar.'-'.$donde_id.':'.$ref.'<br>';
		}
		?>
	<script>
	alert('Datos guardados');	
		  </script>
		<?
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
   <?
	$rx = mysqli_query($conn,"select * from donde_entero Order by id") or die(mysqli_error($conn));
	$limite=mysqli_num_rows($rx)+2;
	if($limite<5){
		$limite=5;
	}
	$i=0;
while($rowx = mysqli_fetch_array($rx) or $i<=$limite){
	$i++;
	?>
    <tr>
     <td><?=$i?></td>
      <td>
	<input type="hidden" name="donde_id<?=$i?>" value="<?=$rowx['id']?>">
  <input type="text" name="ref<?=$i?>" value="<?=$rowx['ref']?>">
   </td>
    </tr>
    <?
}
	?>
  </tbody>
</table>
<input type="submit" value="Guardar">
<input type="hidden" name="total_donde" value="<?=$i?>">
	<?	
}else if((!isset($_GET['id']) or $_GET['id']=='') and $_GET['ref']!='Donde se Entero'){
					  ////// PAGINADOR
					  $items_por_pagina=50;
		if(!isset($_GET['pagina_actual']) or $_GET['pagina_actual']==''){
		$_GET['pagina_actual']=1;							  
	}
$inicio=($_GET['pagina_actual']-1)*$items_por_pagina;
	$add="LIMIT $inicio, $items_por_pagina";
	////
					  //echo $_GET['tipo'].'<br>';
				if(!isset($_GET['ref']) or $_GET['ref']=='por_revisar'){
					//echo $_GET['tipo'].'*<br>';
					//echo "<br>select * from alumnos Where estado='' and tipo='aspirante' Order by id ASC $add";
$rp = mysqli_query($conn,"select * from alumnos Where estado='' and tipo='aspirante' Order by id ASC $add") or die(mysqli_error($conn));
					//
$rpb = mysqli_query($conn,"select * from alumnos Where estado='' and tipo='aspirante' ") or die(mysqli_error($conn));
	}else if($_GET['ref']=='revisados'){
$rp = mysqli_query($conn,"select * from alumnos Where estado!='' and tipo='aspirante' Order by id ASC $add") or die(mysqli_error($conn));
$rpb = mysqli_query($conn,"select * from alumnos Where estado!='' and tipo='aspirante' ") or die(mysqli_error($conn));
	}	  
			if (mysqli_num_rows($rp)!=0){	  
				  ?>
                  <tr>
                    <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr class="texto2">
                        <td bgcolor="#015E2F"><strong>Fecha</strong></td>
                        <td bgcolor="#015E2F"><strong>Nombre</strong></td>
                        <td bgcolor="#015E2F"><strong>Para Curso</strong></td>
                        <td bgcolor="#015E2F"><strong>Edad</strong></td>
                        <td bgcolor="#015E2F"><strong>Landing</strong></td>
                        <td bgcolor="#015E2F">&nbsp;</td>
                      </tr>
                      <?
	
		
while($rowp = mysqli_fetch_array($rp)){
					  ?>
                      <tr class="texto1">
                        <td bgcolor="#A5D39C"><?=$rowp['fecha_registro']?></td>
                        <td bgcolor="#A5D39C"><?=$rowp['nombre_estudiante']?></td>
                        <td bgcolor="#A5D39C"><?=$rowp['grado']?></td>
                        <td bgcolor="#A5D39C">
                        <?
						// calculamos la edad
						  $edad=$rowp['edad'];
	if($edad==0){
	$segundos_hoy=time();
	//list( $year, $month, $day  ) = split( '[/.-]', $rowp['fecha_nacimiento']);
$fxx=explode('-',$rowp['fecha_nacimiento']);
$year=$fxx[0];
$month=$fxx[1];
$day=$fxx[2];

$segundos_nacimiento=mktime(0,0,0,$month,$day,$year);
$segundos_edad=$segundos_hoy-$segundos_nacimiento;
$edad=$segundos_edad/(60*60*24*364);
$edad=number_format($edad,0);	
	}
						
echo '<strong>'.$edad.'</strong>';
						?>
                        </td>
                        <td bgcolor="#A5D39C"><?=$rowp['landing_ref']?></td>
                        
                        <td bgcolor="#A5D39C"><strong><a href="#" class="texto1" onclick="window.location='admin_palmas.php?tipo=Postulantes&id=<?=$rowp['id']?>';">Revisar</a> | <a href="#" class="texto1" onclick="if(confirm('Desea borrar definitivamente los datos de este registro?')){document.uno.borrar_aspirante.value='<?=$rowp['id']?>'; document.uno.submit();}">Borrar</a></strong></td>
                      </tr>
                      <?
}
			}
?>
                    </table>
                    
    <div align="center">
        <?
	$total_items=mysqli_num_rows($rpb);
	$total_paginas=$total_items/$items_por_pagina;
	// si da una fraccion le sumamos uno
	if (!is_int($total_paginas)) {
		$total_paginas++;
	}

	$a=1;
	while($a<=$total_paginas){
		if($a!=$_GET['pagina_actual']){
	?>
        [<span class="texto3"><strong><a href="admin_palmas.php?pagina_actual=<?=$a?>&accion=<?=$_GET['accion']?>&tipo=<?=$_GET['tipo']?>&ref=<?=$_GET['ref']?>" class="texto3"><?=$a?></a></strong></span>] 
  <?
		}else{
			echo '[<span class="texto3"><strong>'.$a.'</strong></span>] ';
		}
$a++;
	}
	?>
      </div>
    
                    </td>
                  </tr>
                  <?
}elseif(isset($_GET['id'])){
	/// actualizamos el estado
	$alumno_id=$_GET['id'];
	$Insertar="UPDATE alumnos SET estado='revisado' WHERE id='$alumno_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
/// 
/// cargamos los datos
$ra = mysqli_query($conn,"select * from alumnos Where id='$alumno_id'");
$rowa = mysqli_fetch_array($ra);
///
				  ?>
                  <tr>
                    <td>
<script>
function imprimir_pagina(){
$.jPrintArea('#hv');
}
</script>
                    <div id="hv">
                    <table width="600" border="0" align="center" cellpadding="3" cellspacing="0" >
            <tr>
            <td colspan="2" align="right" bgcolor="#000033">
              
              <strong><span class="texto2"> FORMULARIO DE PRE-INSCRIPCIÓN | <a href="#" onclick="imprimir_pagina();"><img src="imagenes/imprimir.png" width="25" height="21" border="0" /></a> | <a href="hv.php?id=<?=$alumno_id?>&tipo=excel" target="_blank"> <img src="imagenes/excel.png" width="25" height="21" border="0" /></a> | <a href="hv.php?id=<?=$alumno_id?>&tipo=word" target="_blank"><img src="imagenes/word.png" width="25" height="21" border="0" /></a> | <a href="#" class="texto2" onclick="if(confirm('Desea borrar definitivamente los datos de este registro?')){document.uno.borrar_aspirante.value='<?=$alumno_id?>'; document.uno.submit();}">Borrar</a></span></strong></td>
            </tr>
            
              
          
          <tr>
            <td width="50%" align="right" bgcolor="#B7D1DB">
            
              <span class="texto1">Nombre del Estudiante:</span></td>
            <td width="50%" align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?=$rowa['nombre_estudiante']?>
            </span></strong></td>
          </tr>
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">Edad:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            
            <?
            $edad=$rowa['edad'];
	if($edad==0 or $edad==''){
	$segundos_hoy=time();
//list( $year, $month, $day  ) = split( '[/.-]', $rowa['fecha_nacimiento']);
$fxx=explode('-',$rowa['fecha_nacimiento']);
$year=$fxx[0];
$month=$fxx[1];
$day=$fxx[2];
$segundos_nacimiento=mktime(0,0,0,$month,$day,$year);
$segundos_edad=$segundos_hoy-$segundos_nacimiento;
$edad=$segundos_edad/(60*60*24*364);
$edad=number_format($edad,0);	
	}
						
echo $edad;
	?>
            </span></strong></td>
          </tr>
          
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">Nivel al que desea entrar:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?=$rowa['grado']?>
            </span></strong></td>
          </tr>
          
          
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">Padre o Madre:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?
		if($rowa['padre_madre']!=''){
			echo $rowa['padre_madre'];
		}else{
			echo $rowa['nombre_padre'].'/'.$rowa['nombre_madre'];
		}
				?>
            </span></strong></td>
          </tr>
          
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">Telefono:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?
	if($rowa['telefono']!=''){
		echo $rowa['telefono'];		
	}else{
		$rowa['celular_padre'].'/'.$rowa['celular_madre'];
	}
				
				?>
            </span></strong></td>
          </tr>
          
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">email:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?
	if($rowa['email']!=''){
	echo $rowa['email'];
	}else{
	echo $rowa['email_padre'].'/'.$rowa['email_madre'];	
	}
	?>
            </span></strong></td>
          </tr>
          
          <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">Dirección donde reside:</span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?
	
		echo $rowa['direccion'];		
	
				?>
            </span></strong></td>
          </tr>
          
           <tr>
            <td align="right" bgcolor="#B7D1DB"><span class="texto1">
            <?
	if($rowa['landing_ref']!=''){
		echo 'Landing Page:';
	}else{
		?>
		Dónde se enteró de Nosotros?:
		<?
	}
	?>
            </span></td>
            <td align="left" bgcolor="#B7D1DB"><strong><span class="texto1">
            <?=$rowa['donde_entero']?>
            </span></strong></td>
          </tr>
          
          <tr>
            <td align="right" valign="top" bgcolor="#A3D6AF"><span class="texto1">Comentarios:</span></td>
            <td align="left" bgcolor="#A3D6AF"><strong><span class="texto1">
              <?=$rowa['comentarios']?>
            </span></strong></td>
          </tr>
        </table>
        </div>
                    </td></tr>
                      <?
}
/// fin de cargar hoja de alumno
?>
                
                  
        </table>
         
      <?
	  }
	///// FIN POSTULANTES ********
	

	
	
	///// USUARIOS ***************
	if($tipo=='usuarios'){
	  ?>
      
      <table width="100%">
      <tr>
                    <td>
                    <select name="usuarios1" class="inp" id="usuarios1" onchange="if(this.value!=''){window.location='admin_palmas.php?tipo=usuarios&ref=Usuario Registrado&id='+this.value;}">
<option value="" selected="selected">Listado completo de Usuarios</option>
<?
$rs = mysqli_query($conn,"select usuarios.id, usuarios.nombre from usuarios Where usuarios.nombre!='' and usuarios.id!='1' GROUP BY usuarios.nombre Order by usuarios.nombre") or die(mysqli_error($conn));
while($rows = mysqli_fetch_array($rs)){
echo '<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>';
}
?>
</select>
 | <select name="usuarios2" class="inp" id="usuarios2" onchange="if(this.value!=''){window.location='admin_palmas.php?tipo=usuarios&ref=Usuario Registrado&id='+this.value;}">
<option value="" selected="selected">Administradores</option>
<?
$rs = mysqli_query($conn,"select usuarios.id, usuarios.nombre, permisos.acceso from usuarios, permisos Where permisos.usuario_id=usuarios.id and permisos.acceso='administrador' and usuarios.id!='1' GROUP BY usuarios.nombre Order by usuarios.nombre") or die(mysqli_error($conn));
while($rows = mysqli_fetch_array($rs)){
echo '<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>';
}
?>
</select>
 <?
		/*
 | <select name="usuarios3" class="inp" id="usuarios3" onchange="if(this.value!=''){window.location='admin_palmas.php?tipo=usuarios&ref=Usuario Registrado&id='+this.value;}">
<option value="" selected="selected">Bloggers</option>
<?
$rs = mysqli_query($conn,"select usuarios.id, usuarios.nombre, permisos.acceso from usuarios, permisos Where permisos.usuario_id=usuarios.id and permisos.acceso='blogger' Order by usuarios.nombre") or die(mysqli_error($conn));
while($rows = mysqli_fetch_array($rs)){
echo '<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>';
}
?>
</select>
*/
 ?>
  | <select name="usuarios4" class="inp" id="usuarios4" onchange="if(this.value!=''){window.location='admin_palmas.php?tipo=usuarios&ref=Usuario Pagina Web&id='+this.value;}">
<option value="" selected="selected">Web</option>
<?
$rs = mysqli_query($conn,"select usuarios.id, usuarios.nombre, permisos.acceso from usuarios, permisos Where permisos.usuario_id=usuarios.id and permisos.acceso='web' GROUP BY usuarios.nombre Order by usuarios.nombre") or die(mysqli_error($conn));
while($rows = mysqli_fetch_array($rs)){
echo '<option value="'.$rows['id'].'">'.$rows['nombre'].'</option>';
}
?>
</select> | <a href="admin_palmas.php?tipo=usuarios&ref=Nuevo Usuario" class="texto3"><strong>Nuevo Usuario</strong></a>

                        <br />
<span class="titulo1">
<?
if(!isset($_GET['ref'])){
$_GET['ref']='Nuevo Usuario';	
}
echo $_GET['ref'];
///
if($_GET['ref']!='Nuevo Usuario'){
					  /// hacemos la consultas
$id=$_GET['id'];
$r = mysqli_query($conn,"select * from usuarios Where id='$id'") or die(mysqli_error($conn));
$row = mysqli_fetch_array($r);
				  ?>
     | Borrar este usuario? <select name="borrar" class="inp" onchange="if(confirm('Desea borra este usuario definitivamente? se borrarán también los artículos y comentarios que haya registrado en el Blog')){document.uno.borrar_usuario.value='<?=$id?>';document.uno.submit();}">
       <option value="no" selected="selected">no</option>
       <option value="si">si</option>
     </select>
     <input type="hidden" name="borrar_usuario" id="borrar_usuario" />
    <?
}

?>
</span> 
                    </td>
          </tr>
                  
                  <tr>
                    <td>
                    
                    <table width="100%" border="0" cellspacing="1" cellpadding="2">
      <tr class="texto2">
        <td width="27%" align="right" bgcolor="#CC0000"><strong>
          Usuario*
        </strong></td>
        <td width="27%"><strong><span class="texto1">
          <label>
            <input type="text" name="usuario_nuevo" id="usuario_nuevo" class="inp" value="<?=$row['usuario']?>" />
          </label>
          <input type="hidden" name="id" id="id" value="<?=$id?>" />
          <input type="hidden" name="usuario_o" id="usuario_o" value="<?=$row['usuario']?>" />
        </span></strong></td>
        <td align="right"><strong><span class="texto1">Celular</span></strong></td>
        <td><strong><span class="texto1">
          <input type="text" name="celular" id="celular" class="inp"  value="<?=$row['celular']?>"/>
        </span></strong></td>
        </tr>
        <tr>
        <td width="27%" align="right" bgcolor="#CC0000"><strong class="texto2">Nueva Clave*</strong></td>
        <td width="27%"><strong><span class="texto1">
          <input type="password" name="clave_nueva" id="clave_nueva" class="inp" />
        </span></strong></td>
        <td align="right"><strong><span class="texto1">Direccion</span></strong></td>
        <td><strong><span class="texto1">
        <input type="text" name="direccion" id="direccion" class="inp" value="<?=$row['direccion']?>" />
        </span></strong></td>
        </tr>
         <tr>
         
        <td width="27%" align="right"><strong><span class="texto1">
          Nombre*
        </span></strong></td>
        <td width="27%"><strong><span class="texto1">
          <label>
            <input type="text" name="nombre" id="nombre" class="inp" value="<?=$row['nombre']?>" />
          </label>
        </span></strong></td>
        <td align="right"><strong><span class="texto1">Ciudad</span></strong></td>
        <td><strong><span class="texto1">
        <input type="text" name="ciudad" id="ciudad" class="inp" value="<?=$row['ciudad']?>" />
        </span></strong></td>
        </tr>
        <tr>
        <td width="27%" align="right"><strong><span class="texto1">email*</span></strong></td>
        <td width="27%"><strong><span class="texto1">
          <input type="text" name="email" id="email" class="inp" value="<?=$row['email']?>" />
          <input type="hidden" name="email_o" id="email_o" value="<?=$row['email']?>" />
        </span></strong></td>
        <td align="right"><strong><span class="texto1">Documento</span></strong></td>
        <td><strong><span class="texto1">
        <input type="text" name="documento" id="documento" class="inp" value="<?=$row['documento']?>" />
        </span></strong></td>
        </tr>
      <tr>
        <td align="right"><strong><span class="texto1">Telefono</span></strong></td>
        <td><strong><span class="texto1">
          <input type="text" name="telefono" id="telefono" class="inp" value="<?=$row['telefono']?>" />
        </span></strong></td>
        <td align="right"><strong><span class="texto1">Cargo</span></strong></td>
        <td><strong><span class="texto1">
        <input type="text" name="cargo" id="cargo" class="inp" value="<?=$row['cargo']?>" />
        </span></strong></td>
        </tr>
        <tr>
        <td align="right"><strong><span class="texto1">Tipo de Usuario:</span></strong></td>
        <td><label>
          <select name="tipo_usuario" class="inp" id="tipo_usuario">
          <option selected="selected"><?=$row['tipo_usuario']?></option>
            <option>directivo</option>
            <option>profesor</option>
            <option>alumno</option>
            <option>padre</option>
          </select>
        </label></td>
        <td align="right">
        <?
		$ru = mysqli_query($conn,"select acceso from permisos Where usuario_id='$id'") or die(mysqli_error($conn));
		if (mysqli_num_rows($ru)!=0){
$rowu = mysqli_fetch_array($ru);
$acceso=$rowu['acceso'];
		}else{
		$acceso	='sin acceso';
		}
		if($_GET['ref']=='Nuevo Usuario'){
		$acceso	='blogger';	
		}
		
		/*
       <option value="blogger">blogger</option>
       */
?>
       
        <strong><span class="texto1">Tipo Acceso*</span></strong> 
        </td>
        <td>
        <label>
          <select name="nuevo_tipo_acceso" id="nuevo_tipo_acceso" class="inp">
            <option selected="selected" value="<?=$acceso?>"><?=$acceso?></option>
            
            <option value="administrador">administrador</option>
            <option value="web">web</option>
            <option value="sin acceso">sin acceso</option>
            </select>
          
        </label>
        </td>
        </tr>
     
      
      <tr>
        <td colspan="4" align="right"><script>
		function boton_guardar(){
		if(document.uno.usuario_nuevo.value==''){
			alert('falta el usuario!');
			document.uno.usuario_nuevo.focus();
			return;
		}
		if(document.uno.usuario_aceptado.value=='no'){
			alert('Debe cambiar el usuario, este ya esta siendo utilizado');
			document.uno.usuario_nuevo.focus();
			return;
		}
		if(document.uno.usuario_nuevo.value!=document.uno.usuario_o.value && document.uno.clave_nueva.value==''){
			alert('Si cambia el usuario, debe confirmar la clave');
			document.uno.clave_nueva.focus();
			return;
		}
		if(document.uno.nombre.value==''){
			alert('falta el nombre');
			document.uno.nombre.focus();
			return;
		}
		if(document.uno.email.value==''){
			alert('falta el email');
			document.uno.email.focus();
			return;
		}
		if(document.uno.email_aceptado.value=='no'){
			alert('Debe cambiar el email, este ya esta siendo utilizado por otro usuario');
			document.uno.email.focus();
			return;
		}
		
			document.uno.registrar.value='si';
			document.uno.submit();
				
		}
		//// funcion para verificar que el usuario y elk mail no esten ya registrados
		$("#usuario_nuevo").change(function () { 
								var u =document.uno.usuario_nuevo.value;
								var u2=document.uno.usuario_o.value;
								if(u!=u2){
								$("#comprobacion").load("comprobacion.php",{usuario: u});	
								}else{
								$("#comprobacion").load("comprobacion.php");	
								document.uno.usuario_aceptado.value='si';	
								}
									   })
			$("#email").change(function () { 
								var e =document.uno.email.value;
								var e2 =document.uno.email_o.value;
								if(e!=e2){
								$("#comprobacion2").load("comprobacion.php",{email: e});
								}else{
								$("#comprobacion2").load("comprobacion.php");
								document.uno.email_aceptado.value='si';	
								}
									   })						   
		</script>        
          <div id="comprobacion" class="error2" align="center"></div>
          <div id="comprobacion2" class="error2" align="center"></div>
          <input name="usuario_aceptado" type="hidden" value="si" />
          <input name="email_aceptado" type="hidden" value="si" />
          <input name="registrar" type="hidden" value="" />        </td>
        </tr>
      </table>
                    </td>
                  </tr>
                  
                
                  <tr>
                    <td align="center">
                    <?
					if($_GET['ref']=='Nuevo Usuario'){
					$etq='Registrar Usuario';	
					}else{
					$etq='Editar Usuario';	
					}
					?>
                  <input type="button" value="<?=$etq?>" onclick="boton_guardar();" class="bot" />
                   </td>
          </tr>
        </table>
                  
      <?
	  }
	///// FIN USUARIOS ****************
	
	
	
	
	/// Accesos Home *****************
	if($tipo=='Accesos Home'){

////EDITAR BANNER
if($_POST['editar_banner']!=''){
$accesos=$_POST['accesos'];
	$admisiones=$_POST['admisiones'];
$Insertar="UPDATE home_configuracion SET accesos='$accesos', admisiones='$admisiones'";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
	//
$Insertar="DELETE FROM home_accesos";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
//
$p=0;
$cantidad=$_POST['cantidad'];
while($p<=$cantidad){
$p++;
$link=$_POST['link'.$p];
$orden=$_POST['orden'.$p];	

$Insertar="INSERT INTO home_accesos (link, orden) VALUES ('$link','$orden')";	
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
///
	/// IMAGEN
	if($_FILES['foto'.$p]['name'] != ''){
if($_FILES['foto'.$p]['type'] != "image/pjpeg" and $_FILES['foto'.$p]['type'] != "image/jpeg"   ){
$mensaje=$mensaje.'<br>ERROR: la imagen no es jpg ';
}else{
	$ruta_pc='imagenes/home/'.$p.'.jpg';
	$ruta_temp='imagenes/home/'.$p.'B.jpg';
///copiamos la foto al tamano real
if(!copy($_FILES['foto'.$p]['tmp_name'], $ruta_temp)){
$mensaje=$mensaje."<br>ERROR ! ";
}else{
	
$ancho=400;
$alto=250;
////******
$fuente = imagecreatefromjpeg($ruta_temp);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
$imagen = ImageCreateTrueColor($ancho,$alto);


$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);
/////*****
imagejpeg($imagen, $ruta_pc, 90);

unlink($ruta_temp) ;

}
}

}
	// FIN IMAGEN
	
	
}


}
/// FIN 

	
			/// cargamos 
$r = mysqli_query($conn,"select paginas.* from home_accesos, paginas WHERE paginas.id=home_accesos.link Order by home_accesos.orden ") or die (mysqli_error($conn));

?>
<br />
 <br />
 <?
 $rhc = mysqli_query($conn,"select * from home_configuracion Limit 1") or die (mysqli_error($conn));
		$rowhc = mysqli_fetch_array($rhc);
?>
		Cantidad de Accesos: <input type="text" name="accesos" id="accesos" value="<?=$rowhc['accesos']?>" style="width:30px"> <span style="cursor: pointer" onClick="document.getElementById('button2').click();"> <i class="fa fa-refresh"></i> </span>
		
		<br><br>

 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr class="texto2">
    <td bgcolor="#990000">&nbsp;</td>
    <td bgcolor="#990000">Página</td>
    <td bgcolor="#990000">Imagen 400x250 pixeles</td>
  </tr>
<?
/// primero cargamos el espacio para nuevo producto
$b=0;
while($row = mysqli_fetch_array($r) or $b<$rowhc['accesos']){
	$b++;
?>
<tr valign="top">
<td style="background:#EACCCC; height:1px;"><input type="text" name="orden<?=$b?>" id="orden<?=$b?>" value="<?=$b?>" style="width:30px"></td>
  <td style="background:#EACCCC;">
  
  
  <select name="link<?=$b?>" id="link<?=$b?>" class="campo1" style="width: 300px;" >
  <option value="">Seleccione</option>
  
 <?
	/// cargamos las categorias de productos
	$rp = mysqli_query($conn,"SELECT id, categoria, pagina FROM paginas Order by activa DESC, categoria ASC, pagina ASC") or die(mysqli_error($conn));
while($rowp = mysqli_fetch_array($rp)){
	?>
    <option value="<?=$rowp['id']?>"><?=$rowp['categoria'].': '.$rowp['pagina']?></option>
  
    <?
}
?>

 </select>


<script>
document.getElementById('link<?=$b?>').value='<?=$row['id']?>';
</script>
  
  </td>
  
  <td style="background:#EACCCC;">
  <?

	$rutam='imagenes/home/'.$b.'.jpg';
	if(file_exists($rutam)){
		?>
  <img src="<?=$rutam?>?k=<?=$k?>" style="width: 400px; height: auto" />
       <br /><span class="linkx" onclick="borrar_file('<?=$rutam?>');">Borrar</span>
    <br />
    <?
	}
	?>
    <input type="file" name="foto<?=$b?>">
    
 
	</td>
  </tr>


    <?
}
?>
</table>
<input type="button" name="button2" id="button2" value="Actualizar Accesos" onclick="document.getElementById('editar_banner').value='si'; document.uno.submit();" />
<input name="editar_banner" id="editar_banner" type="hidden" value="" />
<input name="cantidad" id="cantidad" type="hidden" value="<?=$b?>" />

<br><br>
Mostrar acceso ADMISIONES: <select name="admisiones" id="admisiones" onChange="document.getElementById('button2').click();"><option value="si">si</option><option value="no">no</option></select>
<script>
	$('#admisiones').val('<?=$rowhc['admisiones']?>');
		</script>
 


    <?
	}
	/// fin de Accesos Home
	
	?>
    
   
    
    
    
    
   
    <?
	/// GALERIA *****************
	if($tipo=='Galeria'){
	
	  ////// PAGINADOR
		$items_por_pagina=20;
		//		
		if(!isset($_GET['pagina_actual']) or $_GET['pagina_actual']==''){
		$_GET['pagina_actual']=1;							  
	}
$inicio=($_GET['pagina_actual']-1)*$items_por_pagina;
	$add="LIMIT $inicio, $items_por_pagina";
	
	
	$filtro=$_GET['filtro'];
	if($filtro==''){
		$filtro='Por Revisar';
	}
	if($filtro=='Por Revisar'){
	$add2="publicada=''";	
	}elseif($filtro=='Publicados'){
	$add2="publicada='si'";	
	}elseif($filtro=='No Publicados'){
	$add2="publicada='no'";	
	}
?>
 <span class="titulo3"> - <?=$filtro?></span>
<?

	
			/// cargamos 
$rv2 = mysqli_query($conn,"select * from galeria Where $add2 Order by orden $add ") or die (mysqli_error($conn));
$rmb = mysqli_query($conn,"select * from galeria Where $add2 Order by orden") or die (mysqli_error($conn));
$i=0;

?>
<br />

 <br /> 
 <?
 /// totales
$r1 = mysqli_query($conn,"select id from galeria Where publicada=''") or die (mysqli_error($conn));
$r2 = mysqli_query($conn,"select id from galeria Where publicada='si'") or die (mysqli_error($conn));
$r3 = mysqli_query($conn,"select id from galeria Where publicada='no'") or die (mysqli_error($conn));
?>
 <br />   
<span class="linkx" onclick="cargar_filtro('Por Revisar')">Por Revisar (<?=mysqli_num_rows($r1)?>)</span> | <span class="linkx" onclick="cargar_filtro('Publicados')">Publicados (<?=mysqli_num_rows($r2)?>)</span> | <span class="linkx" onclick="cargar_filtro('No Publicados')">No Publicados (<?=mysqli_num_rows($r3)?>)</span> | 
 
 <span class="linkx" onclick="mostrar(0);">Nuevo Registro</span>
 <br />
 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr class="texto2">
    <td bgcolor="#990000">&nbsp;</td>
    <td bgcolor="#990000">Destino</td>
    <td bgcolor="#990000">Usuario</td>
    <td bgcolor="#990000">Publicada</td>
    <td bgcolor="#990000">&nbsp;</td>
  </tr>
<?
/// primero cargamos el espacio para nuevo producto
$i=0;
?>
<tr>
  <td colspan="5" style="background:#EACCCC; height:1px;">
  
  
  
  <div id="datos_<?=$i?>" class="invisible">
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr class="texto2">
    <td colspan="2" align="right" bgcolor="#666666" class="link2" onclick="ocultar(<?=$i?>);">Cerrar Editor</td>
     </tr>
    
  <tr class="texto1">
    <td>Destino:</td>
     <td>
     <input name="id_<?=$i?>" id="id_<?=$i?>" type="hidden" value="<?=$rowv2['id']?>" />
    <input name="destino_<?=$i?>" id="destino_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['destino']?>" /> 
     </td>
  </tr>
  <tr class="texto1">
    <td>Viajero:</td>
     <td>
     <input name="viajero_<?=$i?>" id="viajero_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['viajero']?>" /> 
     </td>
  </tr>
   <tr class="texto1">
    <td>Viajero email:</td>
     <td>
     <input name="email_<?=$i?>" id="email_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['email']?>" /> 
     </td>
  </tr>
   <tr class="texto1">
    <td>Imagen:</td>
     <td>
     
     <input name="foto_<?=$i?>" type="file" style="width:118px" />
     </td>
  </tr>
  
  <tr class="texto1">
    <td>Comentario:</td>
     <td>
      <textarea name="comentario_<?=$i?>" id="comentario_<?=$i?>" rows="5" class="campo1" ><?=$rowv2['comentario']?></textarea>
     </td>
  </tr>
  <tr class="texto1">
    <td>Publicado:</td>
     <td>
      <select name="publicada_<?=$i?>" id="publicada_<?=$i?>">
      <option value="">pendiente</option>
      <option value="no">no</option>
      <option value="si">si</option>
      </select>
      <script>
	  document.getElementById('publicada_<?=$i?>').value='<?=$rowv2['publicada']?>';
	  </script>
     </td>
  </tr>
   
  
  
 
  <tr class="texto1">
    <td>&nbsp;</td>
     <td><input type="button" name="button2" id="button2" value="Actualizar Galeria" onclick="actualizar(<?=$i?>);" /></td>
  </tr>
</table>
</div>



  
  </td>
  </tr>

<?

while($rowv2 = mysqli_fetch_array($rv2)){
	$i++;
	$ruta='imagenes/galeria/'.$rowv2['id'].'.jpg';
	?>
     <tr valign="top">
    <td bgcolor="#CCCCCC"><strong><?=$i+$inicio?></strong></td>
    <td bgcolor="#CCCCCC">
    <?=$rowv2['destino']?>
	</td>
   <td bgcolor="#CCCCCC">
    <?=$rowv2['viajero']?>
	</td>
    <td bgcolor="#CCCCCC">
    <?
	if($rowv2['publicada']==''){
	$publicada='pendiente';	
	}else{
	$publicada=$rowv2['publicada'];		
	}
	echo $publicada;
	
	/// class="invisible"
	?>
	</td>
    <td bgcolor="#CCCCCC">
    <span class="linkx" onclick="mostrar(<?=$i?>);">Editar</span> | <span class="linkx" onclick="borrar(<?=$i?>);">Borrar</span>
    </td>
  </tr>
  <tr>
  <td colspan="5" style="background:#EACCCC">
  
  
<div id="datos_<?=$i?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr class="texto2">
    <td colspan="2" align="right" bgcolor="#666666" class="link2" onclick="ocultar(<?=$i?>);">Cerrar Editor</td>
     </tr>
    
  <tr class="texto1">
    <td>Destino:</td>
     <td>
     <input name="id_<?=$i?>" id="id_<?=$i?>" type="hidden" value="<?=$rowv2['id']?>" />
    <input name="destino_<?=$i?>" id="destino_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['destino']?>" /> 
     </td>
  </tr>
  <tr class="texto1">
    <td>Viajero:</td>
     <td>
     <input name="viajero_<?=$i?>" id="viajero_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['viajero']?>" /> 
     </td>
  </tr>
   <tr class="texto1">
    <td>Viajero email:</td>
     <td>
     <input name="email_<?=$i?>" id="email_<?=$i?>" type="text" class="campo1" value="<?=$rowv2['email']?>" /> 
     </td>
  </tr>
   <tr class="texto1">
    <td>Imagen:</td>
     <td>
     
    
       <a href="<?=$ruta?>"><img src="<?=$ruta?>?k=<?=$k?>" /></a>
      
      
     <input name="foto_<?=$i?>" type="file" style="width:118px" />
     </td>
  </tr>
  
  <tr class="texto1">
    <td>Comentario:</td>
     <td>
      <textarea name="comentario_<?=$i?>" id="comentario_<?=$i?>" rows="5" class="campo1" ><?=$rowv2['comentario']?></textarea>
     </td>
  </tr>
  <tr class="texto1">
    <td>Publicado:</td>
     <td>
      <select name="publicada_<?=$i?>" id="publicada_<?=$i?>">
      <option value="">pendiente</option>
      <option value="no">no</option>
      <option value="si">si</option>
      </select>
      <script>
	  document.getElementById('publicada_<?=$i?>').value='<?=$rowv2['publicada']?>';
	  </script>
     </td>
  </tr>
   
  
  
 
  <tr class="texto1">
    <td>&nbsp;</td>
     <td><input type="button" name="button2" id="button2" value="Actualizar Galeria" onclick="actualizar(<?=$i?>);" /></td>
  </tr>
</table>
</div>




  
  </td>
  </tr>
    <?
}
?>
</table>
<input name="editar_galeria" id="editar_galeria" type="hidden" value="" />
<input name="borrar_galeria" id="borrar_galeria" type="hidden" value="" />
<script>
function mostrar(posicion){
$('#datos_'+posicion).show('slow');	
}
//
function ocultar(posicion){
$('#datos_'+posicion).hide('slow');	
}
////
function actualizar(posicion){
	
document.getElementById('editar_galeria').value=posicion;
document.uno.submit();	
}
///
function borrar(posicion){
	if(confirm('Desea borrar este item de la Galeria?')){
document.getElementById('borrar_galeria').value=posicion;
document.uno.submit();	
}
}

///


///
function ocultar_todos(total){
var i=0;
while(i<total){
i++;
ocultar(i);	
}
}
////
var miSetOut = setTimeout( "ocultar_todos(<?=$i?>)" , 1000 );
</script>
 <div align="center">
        <?php
	$total_items=mysqli_num_rows($rmb);
	$total_paginas=$total_items/$items_por_pagina;
	// si da una fraccion le sumamos uno
	if (!is_int($total_paginas)) {
		$total_paginas++;
	}

	$a=1;
	while($a<=$total_paginas){
		if($a!=$_GET['pagina_actual']){
	?>
        [<span class="texto1"><strong><a href="admin_palmas.php?tipo=<?=$_GET['tipo']?>&filtro=<?=$filtro?>&pagina_actual=<?php echo $a?>" class="texto1"><?php echo $a?></a></strong></span>] 
  <?php
		}else{
			echo '[<span class="texto4"><strong>'.$a.'</strong></span>] ';
		}
$a++;
	}
	?>
    </div>
    <?
	}
	/// fin de GALERIA
	
	?>
    
    
    
  <?
	/// USUARIOS *****************
	if($tipo=='Usuarios'){
	
	  ////// PAGINADOR
		$items_por_pagina=20;
		//		
		if(!isset($_GET['pagina_actual']) or $_GET['pagina_actual']==''){
		$_GET['pagina_actual']=1;							  
	}
$inicio=($_GET['pagina_actual']-1)*$items_por_pagina;
	$add="LIMIT $inicio, $items_por_pagina";
	
	
	$filtro=$_GET['filtro'];
	if($filtro==''){
		$filtro='Por Revisar';
	}
	if($filtro=='Por Revisar'){
	$add2="activo=''";	
	}elseif($filtro=='Activos'){
	$add2="activo='si'";	
	}elseif($filtro=='Bloqueados'){
	$add2="activo='no'";	
	}
?>
 <span class="titulo3"> - <?=$filtro?></span>
<?

	
			/// cargamos 
$rv2 = mysqli_query($conn,"select * from usuarios Where $add2 Order by agencia, nombre $add ") or die (mysqli_error($conn));
$rmb = mysqli_query($conn,"select * from usuarios Where $add2 Order by agencia, nombre") or die (mysqli_error($conn));
$i=0;

?>
<br />

 <br /> 
 <?
 /// totales
$r1 = mysqli_query($conn,"select id from usuarios Where activo=''") or die (mysqli_error($conn));
$r2 = mysqli_query($conn,"select id from usuarios Where activo='si'") or die (mysqli_error($conn));
$r3 = mysqli_query($conn,"select id from usuarios Where activo='no'") or die (mysqli_error($conn));
?>
 <br />   
<span class="linkx" onclick="cargar_filtro('Por Revisar')">Por Revisar (<?=mysqli_num_rows($r1)?>)</span> | <span class="linkx" onclick="cargar_filtro('Activos')">Activos (<?=mysqli_num_rows($r2)?>)</span> | <span class="linkx" onclick="cargar_filtro('Bloqueados')">Bloqueados (<?=mysqli_num_rows($r3)?>)</span> 
 <br />
 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr class="texto2">
    <td bgcolor="#990000">&nbsp;</td>
    <td bgcolor="#990000">Agencia</td>
    <td bgcolor="#990000">Nombre</td>
    <td bgcolor="#990000">Email</td>
    <td bgcolor="#990000">Telefono</td>
     <td bgcolor="#990000">Activo</td>
    <td bgcolor="#990000">&nbsp;</td>
  </tr>
<?
/// primero cargamos el espacio para nuevo producto
$i=0;
?>


<?

while($rowv2 = mysqli_fetch_array($rv2)){
	$i++;
	
	?>
     <tr valign="top">
    <td bgcolor="#CCCCCC"><strong><?=$i+$inicio?></strong></td>
    <td bgcolor="#CCCCCC">
   <input name="id_<?=$i?>" type="hidden" value="<?=$rowv2['id']?>" />
    <input name="agencia_<?=$i?>" id="agencia_<?=$i?>" class="campo2" type="text" value="<?=$rowv2['agencia']?>" />
	</td>
    <td bgcolor="#CCCCCC">
    <input name="nombre_<?=$i?>" id="nombre_<?=$i?>" class="campo2" type="text" value="<?=$rowv2['nombre']?>" />
	</td>
    <td bgcolor="#CCCCCC">
    <input name="email_<?=$i?>" id="email_<?=$i?>" class="campo2" type="text" value="<?=$rowv2['email']?>" />
	</td>
    <td bgcolor="#CCCCCC">
    <input name="telefono_<?=$i?>" id="telefono_<?=$i?>" class="campo2" type="text" value="<?=$rowv2['telefono']?>" />
	</td>
    <td bgcolor="#CCCCCC">
    <select name="activo_<?=$i?>" id="activo_<?=$i?>">
      <option value="">pendiente</option>
      <option value="no">no</option>
      <option value="si">si</option>
      </select>
      <script>
	  document.getElementById('activo_<?=$i?>').value='<?=$rowv2['activo']?>';
	  </script>
	</td>
    <td bgcolor="#CCCCCC">
    <span class="linkx" onclick="borrar(<?=$i?>);">Borrar</span>
    </td>
  </tr>
 
    <?
}
?>
</table>
<input type="button" name="button2" id="button2" value="Actualizar Usuarios" onclick="actualizar();" />
<input name="editar_usuario" id="editar_usuario" type="hidden" value="" />
<input name="cantidad" id="cantidad" type="hidden" value="<?=$i?>" />
<input name="borrar_usuario" id="borrar_usuario" type="hidden" value="" />
<script>
function mostrar(posicion){
$('#datos_'+posicion).show('slow');	
}
//
function ocultar(posicion){
$('#datos_'+posicion).hide('slow');	
}
////
function actualizar(posicion){
	
document.getElementById('editar_usuario').value=posicion;
document.uno.submit();	
}
///
function borrar(posicion){
	if(confirm('Desea borrar este Usuario?')){
document.getElementById('borrar_usuario').value=posicion;
document.uno.submit();	
}
}



</script>
 <div align="center">
        <?php
	$total_items=mysqli_num_rows($rmb);
	$total_paginas=$total_items/$items_por_pagina;
	// si da una fraccion le sumamos uno
	if (!is_int($total_paginas)) {
		$total_paginas++;
	}

	$a=1;
	while($a<=$total_paginas){
		if($a!=$_GET['pagina_actual']){
	?>
        [<span class="texto1"><strong><a href="admin_palmas.php?tipo=<?=$_GET['tipo']?>&filtro=<?=$filtro?>&pagina_actual=<?php echo $a?>" class="texto1"><?php echo $a?></a></strong></span>] 
  <?php
		}else{
			echo '[<span class="texto4"><strong>'.$a.'</strong></span>] ';
		}
$a++;
	}
	?>
    </div>
    <?
	}
	/// fin de USUARIOS
	
	
	
///////**********************
	if($tipo=='Clientes'){
		
		/// editar agencia
		if($_POST['editar_agencia']=='si'){
		$agencia_id=$_POST['agencia_id'];
		$agencia=$_POST['agencia'];
		$razon_social=$_POST['razon_social'];
		$nit=$_POST['nit'];
		$comision=$_POST['comision'];
		$tipo=$_POST['tipo'];
		$clasificacion=$_POST['clasificacion'];
		$telefono=$_POST['telefono'];
		$direccion=$_POST['direccion'];
		$ciudad=$_POST['ciudad'];
		$pais=$_POST['pais'];
		$web=$_POST['web'];
		$contacto=$_POST['contacto'];
		$cargo=$_POST['cargo'];
		$email=$_POST['email'];
		///
		if($agencia_id==0 or $agencia_id=='' ){
		$Insertar="INSERT INTO agencias (agencia, razon_social, nit, comision, tipo, clasificacion, telefono, direccion, ciudad, pais, web, contacto, cargo, email) VALUES ('$agencia', '$razon_social', '$nit', '$comision', '$tipo', '$clasificacion', '$telefono', '$direccion', '$ciudad', '$pais', '$web', '$contacto', '$cargo', '$email')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		}else{
		$Insertar="UPDATE agencias SET agencia='$agencia', razon_social='$razon_social', nit='$nit', tipo='$tipo', clasificacion='$clasificacion', telefono='$telefono', direccion='$direccion', ciudad='$ciudad', pais='$pais', web='$web', contacto='$contacto', cargo='$cargo', email='$email' WHERE id='$agencia_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		}
		//echo $Insertar.'<br>';
		
		//// RUT
if(isset($_FILES['rut']) and $_FILES['rut']['name']!=''){

$extension = explode(".",$_FILES['rut']['name']);
$num = count($extension)-1;
$ruta='RUT/RUT_'.$agencia_id.'.'.$extension[$num];
$archivo='RUT_'.$agencia_id.'.'.$extension[$num];

//// fin del nombre
if(!copy($_FILES['rut']['tmp_name'],$ruta)){
$mensaje=$mensaje."ERROR al copiar el archivo <br>";
}else{
//
$Insertar="UPDATE agencias SET rut='$archivo' WHERE id='$agencia_id'";
mysqli_query($conn, $Insertar);	
}

//}
}
/// FIN RUT

		}
		/// fin editar agencia
		
		
		/// editar_contacto
		if($_POST['editar_contacto']=='si'){
$agencia_id=$_POST['agencia_id'];
$contacto_id=$_POST['contacto_id'];
$tipo_contacto=$_POST['tipo_contacto'];
$nombre=$_POST['nombre'];
$cargo=$_POST['cargo'];
$email=$_POST['email'];
$telefono=$_POST['telefono'];
$direccion=$_POST['direccion'];
$ciudad=$_POST['ciudad'];
$pais=$_POST['pais'];
		///
		if(($contacto_id==0 or $contacto_id=='') and $agencia_id>0 ){
		$Insertar="INSERT INTO agencias_contactos (agencia_id, tipo_contacto, nombre, cargo, email, telefono, direccion, ciudad, pais) VALUES ('$agencia_id', '$tipo_contacto', '$nombre', '$cargo', '$email', '$telefono', '$direccion', '$ciudad', '$pais')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		}else if($contacto_id>0){
		$Insertar="UPDATE agencias_contactos SET tipo_contacto='$tipo_contacto', nombre='$nombre', cargo='$cargo', email='$email', telefono='$telefono', direccion='$direccion', ciudad='$ciudad', pais='$pais' WHERE id='$contacto_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		}
	//echo $Insertar.'<br>';	
		}
		/// fin editar contacto
			
	?>
    <style>
	.cont{ padding:12px; border:solid 1px #999;
	-webkit-border-radius: 10px;
-moz-border-radius: 10px;
border-radius: 10px;	
	}
	.resultados tr:nth-child(even) {
    background-color:#BBBBBB;
}
.resultados td{border-left:dotted 1px #999; padding:3px;}
.seccion{ border-bottom:dotted 1px #f00; margin-bottom:8px; padding-bottom:8px;}
.boton{cursor:pointer; padding-left:12px; padding-right:12px; background:rgba(0,51,102,1); color:#fff; border:#000; margin:4px; white-space:nowrap;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
}
.boton:hover{background:rgba(153,153,153,1);}
	</style>
    
    <select name="agencia_id" id="agencia_id" onchange="document.uno.submit();">
   <option value="">Nueva</option>
  
   <?
   $rag = mysqli_query($conn,"select id, agencia from agencias Order by agencia") or die (mysqli_error($conn));
  while($rowag = mysqli_fetch_array($rag)){ 
   ?>
   <option value="<?=$rowag['id']?>"><?=$rowag['agencia']?></option>
   <?
  }
  ?>
  </select>
  <script>
  document.getElementById('agencia_id').value='<?=$_POST['agencia_id']?>';
  
  
  
  </script>
  <div class="cont">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top" style="padding-right:5px; border-right:solid 2px #666">
    <strong>AGENCIA:</strong>
    <div id="contenido_agencia"></div>
    </td>
    <td valign="top" style="padding-left:5px;">
    <strong>CONTACTOS:</strong>
    <div id="contenido_contactos"></div>
    </td>
  </tr>
</table>
</div>
<script>
function cargar_agencia(campo,accion,agencia_id,contacto_id){
	$('#'+campo).load('editar_agencia.php',{accion:accion,agencia_id:agencia_id,contacto_id:contacto_id});  
  }
  <?
  if($_POST['agencia_id']>0){
  ?>
  cargar_agencia('contenido_agencia', 'agencia', '<?=$_POST['agencia_id']?>','');
  <?
  }else{
	  ?>
	cargar_agencia('contenido_agencia', 'agencia_editar', '<?=$_POST['agencia_id']?>','');  
	  <?
  }
  ?>
  cargar_agencia('contenido_contactos', 'contactos', '<?=$_POST['agencia_id']?>','');
</script>
  <?


	}
	
	
	
///////**********************
	if($tipo=='Email Masivo'){
		
	
			
	$items_por_pagina=50;
		//		
		if(!isset($_GET['pagina_actual']) or $_GET['pagina_actual']==''){
		$_GET['pagina_actual']=1;							  
	}
$inicio=($_GET['pagina_actual']-1)*$items_por_pagina;
	$add="LIMIT $inicio, $items_por_pagina";
	
	$r = mysqli_query($conn,"select * from email_masivo Order by id DESC $add") or die (mysqli_error($conn));
	$r1 = mysqli_query($conn,"select id from email_masivo Order by id DESC ") or die (mysqli_error($conn));
	?>
   
   <div>
    <span class="linkx" onclick="cargar_filtro('Creados')">Emails Creados:<?=mysqli_num_rows($r1)?></span> | <span class="linkx" onclick="cargar_filtro('Nuevo')">Crear Nuevo Email</span> 
    </div>
    
    
    <?
	if($filtro=='Creados' or $filtro==''){
	?>
	<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr bgcolor="#67112E" class="texto2" style="white-space:nowrap">
    <td>&nbsp;</td>
    <td>ID</td>
    <td>Fecha de Creación</td>
    <td>Fecha de Envio</td>
    <td>Asunto</td>
    <td>Destinatarios</td>
    <td>Mensaje</td>
        
  </tr>
<?
$i=0;
while($row = mysqli_fetch_array($r)){
$i++;


	?>
     <tr valign="top" >
    <td valign="top"><strong><?=$i+$inicio?></strong></td>
    <td><?=str_pad($row['id'], 5, "0", STR_PAD_LEFT)?></td>
    <td><?=$row['fecha']?></td>
    <td>
	<?
	if($row['fecha_envio']=='0000-00-00 00:00:00'){
		$cod=md5($row['id'].'Jk.');
	?>
    <span class="linkx" onclick="window.open('enviar_mail.php?id=<?=$row['id']?>&cod=<?=$cod?>');">Enviar Ahora</span>
    <?
	}else{
	echo $row['fecha_envio'];	
	}
	?>
    </td>
    <td>
    <?=$row['asunto']?>
    </td>
    <td><span class="linkx" onclick="mostrar('destinatarios<?=$i?>');" id="texto_destinatarios<?=$i?>">Ver</span>
    <div id="destinatarios<?=$i?>" style="display:none; margin-top:12px;">
    <?=$row['destinatarios']?>
    </div>
    <input type="hidden" id="estado_destinatarios<?=$i?>" value="cerrado" />
    </td>
    <td><span class="linkx" onclick="window.open('mensaje_mail.php?id=<?=$row['id']?>');">Ver</span></td>
  
  </tr>
  <?
}
?>
</table>

<div align="center">
        <?php
	$total_items=mysqli_num_rows($r1);
	$total_paginas=$total_items/$items_por_pagina;
	// si da una fraccion le sumamos uno
	if (!is_int($total_paginas)) {
		$total_paginas++;
	}

	$a=1;
	while($a<=$total_paginas){
		if($a!=$_GET['pagina_actual']){
	?>
        [<span class="texto1"><strong><a href="admin_palmas.php?tipo=<?=$_GET['tipo']?>&filtro=<?=$filtro?>&pagina_actual=<?php echo $a?>" class="texto1"><?php echo $a?></a></strong></span>] 
  <?php
		}else{
			echo '[<span class="texto4"><strong>'.$a.'</strong></span>] ';
		}
$a++;
	}
	?>
    </div>
    
    <?
	}
	
	if($filtro=='Nuevo'){
		
		if($_POST['crear_mail']=='si'){
		$asunto=$_POST['asunto'];
		$titulo=$_POST['titulo'];
		$texto1=$_POST['texto1'];
		$texto2=$_POST['texto2'];
		$nota=$_POST['nota'];
		$email_id=$_POST['email_id'];
		$hoy=date('Y-m-d H:i:s');
		//
		if($email_id==''){
		$Insertar="INSERT INTO email_masivo (fecha, asunto, titulo, texto1, texto2, nota) VALUES ('$hoy', '$asunto', '$titulo', '$texto1', '$texto2', '$nota')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));
/// identificamos el id recien
$rb= mysqli_query($conn,"select id from email_masivo Where asunto='$asunto' Order by id DESC Limit 1") or die(mysqli_error($conn));
$rowb = mysqli_fetch_array($rb);
$email_id=$rowb['id'];
$_POST['email_id']=$email_id;	
		}else{
	$Insertar="UPDATE email_masivo SET fecha='$hoy', asunto='$asunto', titulo='$titulo', texto1='$texto1', texto2='$texto2', nota='$nota' WHERE id='$email_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));		
		}
	
	
//////++++
	if($_FILES['imagen']['name']!=''){
if($_FILES['imagen']['type'] != "image/pjpeg" and $_FILES['imagen']['type'] != "image/jpeg" ){
$mensaje=$mensaje.'ERROR: La Imagen no es jpg<br>';
}else{
	$ext='jpg';
	$ruta='mail/promociones/'.$email_id.'.jpg';
	$ruta1='mail/promociones/'.$email_id.'_B.jpg';
	
	
	
if(!copy($_FILES['imagen']['tmp_name'], $ruta1)){
$mensaje=$mensaje."ERROR al copiar la Imagen! <br>";
}else{

$ancho=600;

$fuente = imagecreatefromjpeg($ruta1);
$imgAncho = imagesx ($fuente);
$imgAlto =imagesy($fuente);
//
$alto=($imgAlto*$ancho)/$imgAncho;
$alto=(int)$alto;
//
$imagen = ImageCreateTrueColor($ancho,$alto);

//$imgAlto=($alto*$imgAncho)/$ancho;
//$imgAlto=(int)$imgAlto;
// centramos la imagen a lo anchoo para no cortarla mal
$coory=0;
$coorx=0;
$ancho_inicial=$imgAncho;
$alto_inicial=($alto*$imgAncho)/$ancho;
$alto_inicial=(int)$alto_inicial;
if($alto_inicial<=$imgAlto){
$coory=($imgAlto-$alto_inicial)/2;
$coory=(int)$coory;
}else{
///
$alto_inicial=$imgAlto;
$ancho_inicial=($ancho*$imgAlto)/$alto;
$ancho_inicial=(int)$ancho_inicial;

$coorx=($imgAncho-$ancho_inicial)/2;
$coorx=(int)$coorx;
}
//echo $coorx.'+'.$coory.'/'.$ruta.'<br>';
imagecopyresampled($imagen,$fuente,0,0,$coorx,$coory,$ancho,$alto,$ancho_inicial,$alto_inicial);

imagejpeg($imagen, $ruta, 80);
/////
unlink($ruta1) ;
//echo '55555555';	
$mensaje=$mensaje."La Imagen fue subida con exito!";
}
}
}
/// fin de imagen i
////++++++++++++++
	///
$Insertar="DELETE FROM email_masivo_contenido WHERE email_id='$email_id'";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
///	
		
		$i=0;
		while($i<10){
		$i++;
		$programa_id=$_POST['programa'.$i];
		//
		if($programa_id	!=''){
		$Insertar="INSERT INTO email_masivo_contenido (email_id, programa_id) VALUES ('$email_id', '$programa_id')";
mysqli_query($conn, $Insertar) or die (mysqli_error($conn));	
		}
		
		}
		
		/// abrimos el preview
		?>
        <script>
		window.open('mensaje_mail.php?id=<?=$email_id?>');
		</script>
        <?
		}
		
	?>
    
    <input type="hidden" name="email_id" value="<?=$_POST['email_id']?>" />
   <table width="100%" border="0" cellspacing="2" cellpadding="1">
  <tr valign="top">
    <td>Asunto:</td>
    <td>
      <input type="text" name="asunto" id="asunto" required  value="<?=$_POST['asunto']?>"/></td>
  </tr>
  <tr valign="top">
    <td>Titulo</td>
    <td><input type="text" name="titulo" id="titulo" value="<?=$_POST['titulo']?>" /></td>
  </tr>
  
  <tr valign="top">
    <td>Arte JPG de promoción<br />
(Ancho 600px)
</td>
    <td>
    <?
	$ruta='mail/promociones/'.$_POST['email_id'].'.jpg';
	if(file_exists($ruta)){ 
	 ?>
       <img src="<?=$ruta?>?k=<?=$k?>" style="width:50%; height:auto" />
       <br /><span class="linkx" onclick="borrar_file(<?=$ruta?>);">Borrar</span>
    <br />
      <?
	}
	?>
     <input name="imagen" type="file" style="width:118px" />
    </td>
  </tr>
  
  <tr valign="top">
    <td>Texto Superior</td>
    <td>
    <textarea name="texto1" id="texto1" rows="5" class="jqte-test"><?=$_POST['texto1']?></textarea>
    </td>
  </tr>
  <tr valign="top">
    <td>Texto Inferior</td>
    <td> <textarea name="texto2" id="texto2" rows="5" class="jqte-test"><?=$_POST['texto2']?></textarea></td>
  </tr>
  <tr valign="top">
    <td>Nota</td>
    <td> <textarea name="nota" id="nota" rows="5" class="jqte-test"><?=$_POST['nota']?></textarea></td>
  </tr>
  <tr valign="top">
    <td>Productos</td>
    <td>
    <?
	$tx_prog='<option value=""></option>';
	$rpg = mysqli_query($conn,"select id, nombre1, nombre2, dias from programas Order by nombre1, nombre2") or die (mysqli_error($conn));
  while($rowpg = mysqli_fetch_array($rpg)){ 
  $tx_prog=$tx_prog.'<option value="'.$rowpg['id'].'">'.$rowpg['nombre1'].' '.$rowpg['nombre2'].' ('.$rowpg['dias'].' Dias)</option>';
  }
	/// cargamos hasta 10 programas
	$i=0;
	while($i<10){
		$i++;
	?>
    <div style="margin-bottom:5px;"><?=$i?>: <select name="programa<?=$i?>" id="programa<?=$i?>" style="width:300px" >
    <?=$tx_prog?>
    </select>
    </div>
    <script>
	document.getElementById('programa<?=$i?>').value='<?=$_POST['programa'.$i]?>';
	</script>
    <?
	}
	?>
    </td>
  </tr>
  <tr valign="top">
    <td>&nbsp;</td>
    <td><input type="submit" value="Guardar y Previsualizar" onclick="document.getElementById('crear_mail').value='si'" /> 
    <?
	if($_POST['email_id']!=''){
        $cod=md5($_POST['email_id'].'Jk.');
	?>
   
    
    <input type="button" value="Enviar ahora" onclick="window.open('enviar_mail.php?id=<?=$row['id']?>&cod=<?=$cod?>');" />
    <?
	}
	?>
    </td>
  </tr>
  
</table>
 <input type="hidden" name="crear_mail" id="crear_mail" />
    <?
	}
	?>
	
    
    
	<?
	}
	/////////////
	
	
	
	
	
	
	///////**********************
	if($tipo=='Reservaciones'){
	$sub=$_GET['sub'];
	  ////// PAGINADOR
		$items_por_pagina=500;
		//		
		if(!isset($_GET['pagina_actual']) or $_GET['pagina_actual']==''){
		$_GET['pagina_actual']=1;							  
	}
$inicio=($_GET['pagina_actual']-1)*$items_por_pagina;
	$add="LIMIT $inicio, $items_por_pagina";
	
		

	
			/// cargamos 




$i=0;

 /*
 <span class="linkx" onclick="mostrar(0);"><strong>+</strong> Nueva pagina</span>
 */
 ?>
  <div id="save"></div>
  Mes: <select name="mes" id="mes" onchange="document.uno.submit();">
  <?
  $year1=2015;
  $year2=date('Y');
  while($year1<$year2){
	$year1 ++;
	$mes=0;
	while($mes<12){
	$mes++;
	$mes2=$mes;
	if($mes<10){
	$mes2='0'.$mes;	
	}
	?>
    <option value="<?=$year1.'-'.$mes2?>"><?=escribir_fechaB($year1.'-'.$mes2)?></option>
    <?
	
	}
  }
  ?>
  </select> Filtro: <select name="filtro" id="filtro" onchange="document.uno.submit();">
   <option value="">Todos</option>
   <option value="agencia">Clientes</option>
   <?
   $rag = mysqli_query($conn,"select id, agencia from agencias Order by agencia") or die (mysqli_error($conn));
  while($rowag = mysqli_fetch_array($rag)){ 
   ?>
   <option value="<?=$rowag['id']?>"><?=$rowag['agencia']?></option>
   <?
  }
  ?>
  </select>
  
  <?
  if(!isset($_POST['mes'])){
	$_POST['mes']='2016-01';
  }
  $filtro=$_POST['filtro'];
  $filtro_add=$filtro;
  if($filtro=='agencia'){
	$filtro_add="and agencia_id!='0'";  
  }else if($filtro!=''){
	$filtro_add="and agencia_id='".$filtro."'";  
  }
  
  $fecha_seg=strtotime($_POST['mes'].'-01');
  $fecha_seg2=strtotime($_POST['mes'].'-31');
  
  $rv2 = mysqli_query($conn,"select * from reservas Where fecha>='$fecha_seg' and fecha<='$fecha_seg2' $filtro_add Order by id $add ") or die (mysqli_error($conn));
  //echo "select * from reservas Where fecha>='$fecha_seg' and fecha<='$fecha_seg2' Order by id $add <br>";
$rmb = mysqli_query($conn,"select * from reservas Where fecha>='$fecha_seg' and fecha<='$fecha_seg2' $filtro_add Order by id ") or die (mysqli_error($conn));
  ?>
  
  <script>
  document.getElementById('mes').value='<?=$_POST['mes']?>';
  document.getElementById('filtro').value='<?=$_POST['filtro']?>';
  </script>
  
  


<br />    
Total <?=$sub?>: <?=mysqli_num_rows($rmb)?><br />




 <br />

 
 <table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr bgcolor="#67112E" class="texto2" style="white-space:nowrap">
    <td>&nbsp;</td>
    <td>ID</td>
    <td>Fecha Compra</td>
    <td>Fecha Viaje</td>
    <td>Agencia</td>
    <td>Programa</td>
    <td>PAX</td>
    <td>Valor</td>
    <td>Pago</td>
    <td>Comisión</td>
    <td>Pago Comisión</td>
    <td>Estado</td>
    
  </tr>
<?
/// primero cargamos el espacio para nuevo producto
$i=0;
$total_comision=0;
$total_valor=0;

while($rowv2 = mysqli_fetch_array($rv2)){
	$i++;

$fecha=date('Y-m-d H:i',$rowv2['fecha']	);
$reserva_id=$rowv2['id'];
/// editar_pagina();
///items
$rit = mysqli_query($conn,"select * from reserva_item Where reserva_id='$reserva_id' Order by fecha_viaje,id ") or die (mysqli_error($conn));
$rowit = mysqli_fetch_array($rit);

$agencia_name='';
$comision=0;
if($rowv2['agencia_id']!=0){
	$ag_id=$rowv2['agencia_id'];
$rag = mysqli_query($conn,"select agencia, comision from agencias Where id='$ag_id' Limit 1") or die (mysqli_error($conn));
$rowag = mysqli_fetch_array($rag);
$agencia_name=$rowag['agencia'];
if($rowv2['comision']==0 or $rowv2['comision']==''){
$comision=($rowv2['total']*$rowag['comision']/100);
$comision=round($comision);	
}else{
$comision=$rowv2['comision'];	
}
}
$total_comision=$total_comision+$comision;
$total_valor=$total_valor+$rowv2['total'];
	?>
     <tr style="background:#CCC; white-space:nowrap" valign="top" class="link3" id="linea<?=$rowv2['id']?>">
    <td valign="top"><strong><?=$i+$inicio?></strong></td>
    <td><?=str_pad($rowv2['id'], 5, "0", STR_PAD_LEFT)?></td>
    <td><?=escribir_fecha($fecha)?></td>
    <td><?=escribir_fecha($rowit['fecha_viaje'])?></td>
    <td><?=$agencia_name?></td>
    <td><?=$rowit['programa_name']?></td>
  <td align="center">
  <?
  $pax=0;
  $pax=$pax+$rowit['sencilla']+($rowit['doble']*2)+($rowit['triple']*3)+($rowit['cuadruple']*4)+$rowit['ninos']+$rowit['junior']+$rowit['ninosB'];
  echo $pax;
  ?>
  </td>
    <td align="right"><?=$rowv2['moneda'].' '.number_format($rowv2['total'],0,',','.')?></td>
    <td >
 <select id="estado<?=$rowv2['id']?>" onChange="confirmar_pago(<?=$rowv2['id']?>,this.value);">
<option value="">Sin Confirmar</option>
<option value="si">Confirmado</option>
<option value="no">Rechazado</option>
</select> 
<script>
document.getElementById('estado<?=$rowv2['id']?>').value='<?=$rowv2['estado']?>';
</script> 
  </td>
  
    <td align="right"><?=$rowv2['moneda'].' '.number_format($comision,0,',','.')?></td>
  
  <td>
  <input type="hidden" name="comision<?=$rowv2['id']?>" id="comision<?=$rowv2['id']?>" value="<?=$comision?>" />
 <select id="estado_comision<?=$rowv2['id']?>" onChange="confirmar_pago_comision(<?=$rowv2['id']?>,this.value);">
<option value="">Sin Confirmar</option>
<option value="si">Confirmado</option>
<option value="no">Rechazado</option>
</select> 
<script>
document.getElementById('estado_comision<?=$rowv2['id']?>').value='<?=$rowv2['estado_comision']?>';
</script> 
  </td>
  
 

  
     <td style="background:#1FA0D7">
      <span id="texto_estado<?=$rowv2['id']?>" style="cursor:pointer; color:#FFF" onclick="editar_pagina(<?=$rowv2['id']?>);">Revisar</span>
      <input type="hidden" name="abierto<?=$rowv2['id']?>" id="abierto<?=$rowv2['id']?>" value="no" />
      <input type="hidden" name="cargado<?=$rowv2['id']?>" id="cargado<?=$rowv2['id']?>" value="no" />
     </td>
  </tr>
  <tr>
  <td colspan="11" id="zona_edicionA<?=$rowv2['id']?>" style="display:none" >
  <iframe src="" id="zona_edicion<?=$rowv2['id']?>" name="zona_edicion<?=$rowv2['id']?>" width="100%" height="550" frameborder="0"></iframe>
  </td>
  </tr>
  




    <?
}
/*
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="right"><?=$rowv2['moneda'].' '.number_format($total_valor,0,',','.')?></td>
    <td align="right"><?=$rowv2['moneda'].' '.number_format($total_comision,0,',','.')?></td>
<td>&nbsp;</td>
<td>&nbsp;</td>

</tr>
*/
?>

</table>

<script>
function editar_pagina(id){
	var estado=document.getElementById('abierto'+id).value;
	var cargado=document.getElementById('cargado'+id).value;
	if(cargado=='no'){
		
	$("#zona_edicion"+id).attr("src", 'reserva.php?reserva_id='+id);	
	document.getElementById('cargado'+id).value='si';
	}
	if(estado=='no'){
		$('#linea'+id).css('background','#f00');
	$("#zona_edicionA"+id).show();
	$("#texto_estado"+id).html('Cerrar');
	document.getElementById('abierto'+id).value='si';
	}else{
	$('#linea'+id).css('background','#ccc');	
	$("#zona_edicionA"+id).hide();
	$("#texto_estado"+id).html('Revisar');
	document.getElementById('abierto'+id).value='no';
	}
}
</script>
<?
/*
<input type="button" name="button2" id="button2" value="Guardar cambios" onclick="actualizar();" />
*/
?>

<input name="total_paginas" id="total_paginas" type="hidden" value="<?=$i?>" />
<input name="editar_paginas" id="editar_paginas" type="hidden" value="" />
<input name="borrar_pagina" id="borrar_pagina" type="hidden" value="" />
<script>
function mostrar(posicion){
$('#datos_'+posicion).show('slow');	
}
//
function ocultar(posicion){
$('#datos_'+posicion).hide('slow');	
}
////
function actualizar(){
document.getElementById('editar_paginas').value='si';
document.uno.submit();	
}
///
function borrar(posicion){
	if(confirm('Desea borrar definitivamente esta pagina con todos los productos registrados en ella?')){
document.getElementById('borrar_pagina').value=posicion;
document.uno.submit();	
}
}
</script>

<script>
 function confirmar_pago(id, valor){
if(confirm('Desea registrar la confirmación de pago para esta reserva?')){
$('#save').load('varios.php',{tipo:'confirmacion_pago',reserva_id:id,valor:valor});
/*	
$('#linea'+id).fadeOut('slow');
$('#lineaB'+id).fadeOut('slow');
*/
}else{
document.getElementById('estado'+id).value='';	
}
 }
 
 function confirmar_pago_comision(id, valor){
if(confirm('Desea registrar la confirmación de pago de comision para esta reserva?')){
$('#save').load('varios.php',{tipo:'confirmacion_comision',reserva_id:id,valor:valor,comision:document.getElementById('comision'+id).value});
/*	
$('#linea'+id).fadeOut('slow');
$('#lineaB'+id).fadeOut('slow');
*/
}else{
document.getElementById('estado_comision'+id).value='';	
}
 }
 
 
 ///
  function confirmar_envio(id,ped){
if(confirm('Desea registrar el envío para este pedido?')){
$('#save').load('varios.php',{tipo:'envio',pedido_id:id, ped:ped});	
/*
$('#linea'+id).fadeOut('slow');
$('#lineaB'+id).fadeOut('slow');
*/
}else{
document.getElementById('enviado'+id).value='';	
}
 }
 </script>
 <div align="center">
        <?php
	$total_items=mysqli_num_rows($rmb);
	$total_paginas=$total_items/$items_por_pagina;
	// si da una fraccion le sumamos uno
	if (!is_int($total_paginas)) {
		$total_paginas++;
	}

	$a=1;
	while($a<=$total_paginas){
		if($a!=$_GET['pagina_actual']){
	?>
        [<span class="texto1"><strong><a href="admin_palmas.php?tipo=<?=$_GET['tipo']?>&filtro=<?=$filtro?>&pagina_actual=<?php echo $a?>" class="texto1"><?php echo $a?></a></strong></span>] 
  <?php
		}else{
			echo '[<span class="texto4"><strong>'.$a.'</strong></span>] ';
		}
$a++;
	}
	?>
      </div>
      
      <?
	}
	/// fin de Reservaaciones
	?>
    
    </div>
    </td>
  </tr>
</table>

  
    
    
    <?
	}
	?>
    
    </td>
  </tr>
  
</table>

<input name="borrar_archivo" id="borrar_archivo" type="hidden" value="" />



<input name="borrar_tabla" id="borrar_tabla" type="hidden" value="" />
<input name="borrar_id" id="borrar_id" type="hidden" value="" />
</form>


    </td>
  </tr>
  
</table>









<div align="center" style="font-size:10px; padding-top:20px;">
Todos los derechos reservados <strong>Gimnasio Las Palmas <?=date('Y')?></strong> | Diseño y desarrollo<strong> INDEX Dream</strong></div>

<script>
function cargar(categoria){
	 window.location='admin_palmas.php?tipo='+categoria;
 }
 ///
function cerrar(){
$('#mensaje').hide();	
}

///
///
function borrar_file(ruta){
	if(confirm('Desea borrar este archivo?')){
document.getElementById('borrar_archivo').value=ruta;
document.uno.submit();	
	}
}


function borrar_registro(tabla,id){
	if(confirm('Desea borrar definitivamente este Registro?')){
document.getElementById('borrar_tabla').value=tabla;
document.getElementById('borrar_id').value=id;
document.uno.submit();	
}
}
</script>


    
    <?
if($mensaje!=''){
	?>
<script>
cargar_mensaje('<?=$mensaje?>');
</script>
<?
}
?>


    <script type="text/javascript">
     
	  
	  $('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
    </script>
</body>
</html>