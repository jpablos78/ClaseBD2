<?php

include_once 'ClaseBaseDatos.php';
include_once 'ClaseBaseDatos2.php';
include_once 'ClaseJson.php';
include_once 'config.inc.php';



$query = "SELECT TOP 10 cci_empresa, cci_sucursal
          FROM BIZ_GEN..TB_GEN_CLIPROV ";

//$query = "EXEC biz_fac..sp_pruebas";

$parametros = array(
    'interfaz' => '',
    'query' => $query
);

$objetoBaseDatos = new ClaseBaseDatos2($parametros);
$result = $objetoBaseDatos->getResult();




//echo json_encode($result);
//$result = $objetoBaseDatos->conectarse();
//echo $result;
//echo $result['success'];
//echo $result['data'];
//print_r($result);
//echo json_encode($result);
$result = ClaseJson::getJson($result);
echo $result;
//echo json_decode($result);
?>

