<?php

include_once 'ClaseBaseDatos3.php';
include_once 'config.inc.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$query = "SELECT TOP 100 cci_empresa, cci_sucursal
          FROM BIZ_GEN..TB_GEN_CLIPROV ";

$parametros = array(
    'interfaz' => '',
    'query' => $query
);

$result = ClaseBaseDatos3::query($parametros);

print_r($result);

//echo $result;

//$objetoBaseDatos = new ClaseBaseDatos3();
//$objetoBaseDatos->conectarse();
?>

