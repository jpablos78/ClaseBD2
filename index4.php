<?php

include_once 'config.inc.php';
include_once 'ClaseBaseDatos4.php';

$objetoBaseDatos = new ClaseBaseDatos4();

$result = $objetoBaseDatos->conectarse();

echo $result;

$query = "SELECT TOP 100 cci_empresa, cci_sucursal
          FROM BIZ_GEN..TB_GEN_CLIPROV, ";

$result = $objetoBaseDatos->query($query);

//print_r($result);

echo $result;


//print_r($result);

