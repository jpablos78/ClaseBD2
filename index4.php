<?php

include_once 'config.inc.php';
include_once 'ClaseBaseDatos4.php';

//1._Conexion manual, se necesita instanciar a la clase
$objetoBaseDatos = new ClaseBaseDatos4();
//se deben definir los parametros
$parametros = array(
    'connect' => false,
    'json' => false
);
//setear los parametros
$objetoBaseDatos->setParametros($parametros);
$result = $objetoBaseDatos->conectarse();

if (!$result['success']) {
    //se presento un error en la conexion
    //se presenta el mensaje de error en formato json
    echo ClaseJson2::getJson($result);
} else {
    //conexion ok
    $query = "SELECT TOP 100 us_login, us_nombres
              FROM wp_usuarios ";

    $result = $objetoBaseDatos->query($query);

    //print_r($result);
    //verificar si hubo un error en la ejecucion de la sentencia sql.
    if (!$result['success']) {
        //presentar el error
        echo ClaseJson2::getJson($result);
    } else {
        //presentar el resultado en formato json
        echo ClaseJson2::getJson($result);

        echo '<hr>';

        //si se quiere presentar solo la data
        $data = $result['data'];
        //print_r($data);
        echo ClaseJson2::getJson($data);
    }

    //desconexion de la base
    $objetoBaseDatos->desconectarse();
}

echo '<hr>2._Conexion manual formato json<hr>';

//2._Conexion manual formato json
$objetoBaseDatos = new ClaseBaseDatos4();
//se deben definir los parametros
$parametros = array(
    'connect' => false,
    'json' => true
);

//setear los parametros
$objetoBaseDatos->setParametros($parametros);
$result = $objetoBaseDatos->conectarse();

$records = json_decode($result);

echo $records->message;

//var_dump(json_decode($result));
//
//foreach ($records as $record) {
//    echo 'animal inside';
//}

//print_r($records);
//
//$records[0]->message;
//
//
//
//$fake = array(
//    'codigo' => 1,
//    'nombre' => 'juan'
//);
//
//$fake = json_encode($fake);
//
//echo $fake;
//
//$r = json_decode($fake);
//
//print_r($r);

//echo $r->nombre;

//echo utf8_decode($records->message);

//print_r($result);


//$objetoBaseDatos = new ClaseBaseDatos4();

//$result = $objetoBaseDatos->conectarse();
//
//echo $result;
//
//$query = "SELECT TOP 100 cci_empresa, cci_sucursal
//          FROM BIZ_GEN..TB_GEN_CLIPROV ";
//
//$result = $objetoBaseDatos->query($query);
//
//print_r($result);
//
//echo $result;

/*
  $query = "SELECT TOP 100 us_login, us_nombres
  FROM wp_usuarios ";

  $parametros = array(
  'connect' => true
  );

  echo '<br>';

  $result = $objetoBaseDatos->query($query);

  print_r($result);

  echo $result;
 */
//$parametros = array(
//    'connect' => false,
//    'json' => false
//);
//
//$query = "SELECT TOP 100 us_login, us_nombres
//  FROM wp_usuarios ";
//
//$objetoBaseDatos->setParametros($parametros);
//$result = $objetoBaseDatos->conectarse();
//echo $result;
//$result = $objetoBaseDatos->query($query);
//echo $result;
//echo ClaseJson2::getJson($result);


//print_r($result);

