<?php

include_once 'config.inc.php';
include_once 'ClaseBaseDatos4.php';

$objetoBaseDatos = new ClaseBaseDatos4();

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
$parametros = array(
    'connect' => false,
    'json' => false
);

$query = "SELECT TOP 100 us_login, us_nombres
  FROM wp_usuarios ";

$objetoBaseDatos->setParametros($parametros);
$result = $objetoBaseDatos->conectarse();
echo $result;
$result = $objetoBaseDatos->query($query);
echo $result;


//print_r($result);

