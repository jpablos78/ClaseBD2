<?php

//phpinfo();
//
//die();

include_once 'config.inc.php';
include_once 'ClaseBaseDatos4.php';

//error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_WARNING);

echo '<hr>1._Conexion manual, se necesita instanciar a la clase<hr>';
//1._Conexion manual, se necesita instanciar a la clase
$objetoBaseDatos = new ClaseBaseDatos4();
//se deben definir los parametros
$parametros = array(
    'connect' => false,
    'disconnect' => false,
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
    'disconnect' => false,
    'json' => true
);

//setear los parametros
$objetoBaseDatos->setParametros($parametros);
$result = $objetoBaseDatos->conectarse();

$records = json_decode($result);

if (!$records->success) {
    echo $result;
} else {
    //echo $records->message;
    //echo 'cambio';
    $query = "SELECT TOP 100 us_login, us_nombres
              FROM wp_usuarios ";

    $result = $objetoBaseDatos->query($query);

    $records = json_decode($result);

    if (!$records->success) {
        echo $result;
    } else {
        echo $result;
        echo '<br>';

        //recorrer la data
        foreach ($records->data as $record) {
            echo $record->us_login . '<br>';
            echo $record->us_nombres . '<br>';
            echo '<hr>';
        }
    }

    $objetoBaseDatos->desconectarse();
}

echo '<hr>3._Conexion automatica, retorna json<hr>';

$objetoBaseDatos = new ClaseBaseDatos4();

$query = "SELECT TOP 100 us_login, us_nombres
          FROM wp_usuarios ";

$result = $objetoBaseDatos->query($query);

echo $result;


echo '<hr>4._Conexion automatica, retorna array<hr>';

$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'json' => false
);

$query = "SELECT TOP 100 us_login, us_nombres
          FROM wp_usuarios ";

$result = $objetoBaseDatos->query($query, $parametros);

print_r($result);

echo '<hr>5._Presentar query<hr>';

$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true
);

$query = "SELECT TOP 100 us_login, us_nombres
          FROM wp_usuarios ";

$result = $objetoBaseDatos->query($query, $parametros);

echo $result;


echo '<hr>5._2 o mas querys en una conexion<hr>';

$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true,
    'disconnect' => false
);
//se lo puede setear asi tambien, y no es necesario pasarlo como parametro en
//el metodo $objetoBaseDatos->query
//$objetoBaseDatos->setParametros($parametros);


$query = "SELECT TOP 100 us_login, us_nombres
          FROM wp_usuarios ";

$result = $objetoBaseDatos->query($query, $parametros);

$records = json_decode($result);

if (!$records->success) {
    echo $result;
} else {
    $query2 = "select pe_codigo, pe_desc
               from wp_perfil";

    $result2 = $objetoBaseDatos->query($query2, $parametros);

    $records2 = json_decode($result2);

    if (!$records2->success) {
        echo $result2;
    } else {
        echo $result . '<br>';
        echo $result2 . '<br>';
    }
}


$objetoBaseDatos->desconectarse();

echo '<hr>6._call store procedure<hr>';

$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true
);

$query = "
    exec P_WP_CONSULTA_STOCK_ITEMS
    @CCI_ITEM = '0500702276',
    @OPERACION = 'PCP'";

$result = $objetoBaseDatos->query($query, $parametros);

echo $result;

echo '<hr>7._manejo de transacciones una sola transaccion autocommit<hr>';
$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true,
    'autocommit' => true
);

$objetoBaseDatos->setParametros($parametros);

$query = "
    update wp_pedido_detalle
    set ces_nuevo_item = 'Z'
    where cci_item = '0100110343'
";

$result = $objetoBaseDatos->query($query);

echo $result;

echo '<hr>7._manejo de transacciones una sola transaccion commit manual<hr>';
$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true,
    'autocommit' => false,
    'disconnect' => false
);

$objetoBaseDatos->setParametros($parametros);

$query = "
    update wp_pedido_detalle
    set ces_nuevo_item = 'W'
    where cci_item = '0100110343'
";

$result = $objetoBaseDatos->query($query);

$records = json_decode($result);

if (!$records->success) {
    echo $result;
    $objetoBaseDatos->rollback();
} else {
    $objetoBaseDatos->commit();
    echo $result . '<br>';
}

$objetoBaseDatos->desconectarse();

echo $result;

echo '<hr>8._manejo de transacciones varias transacciones se usa commit manual<hr>';
$result = '';
$result2 = '';
$records = '';
$records2 = '';

$objetoBaseDatos = new ClaseBaseDatos4();

$parametros = array(
    'debug' => true,
    'autocommit' => false,
    'disconnect' => false
);

$objetoBaseDatos->setParametros($parametros);

$query = "
    update wp_pedido_detalle
    set ces_nuevo_itemw = 'P'
    where cci_item = '0100110343'
";

$result = $objetoBaseDatos->query($query);

$records = json_decode($result);

$error = 'N';

if (!$records->success) {
    $error = 'S';
}

if ($error == 'N') {
    $query = "
        update wp_cabecera_pedido
        set ctx_direccion = 'wewwwe'
        where cp_codigo = 99
    ";

    $result2 = $objetoBaseDatos->query($query);
    $records2 = json_decode($result2);

    if (!$records2->success) {
        $error = 'S';
    }
}

if ($error == 'S') {
    echo $result . '<br>';
    echo $result2 . '<br>';
    $objetoBaseDatos->rollback();
} else {
    $objetoBaseDatos->commit();
    echo $result . '<br>';
    echo $result2 . '<br>';
}

$objetoBaseDatos->desconectarse();

echo '<hr>9._uso mas de un servidor de conexion(DEBE DEFINIRSE EN EL ARCHIVO DE CONFIGURACIONES Y EN LA CLASE DE BASE DE DATOS LOS SERVIDORES A USARSE)<hr>';
$result = '';
$result2 = '';
$records = '';
$records2 = '';

echo $result;

$objetoBaseDatos = new ClaseBaseDatos4();
$objetoBaseDatosInterfaz = new ClaseBaseDatos4();

$parametrosInterfaz = array(
    'interfaz' => 'I',
    'debug' => true
);

$objetoBaseDatosInterfaz->setParametros($parametrosInterfaz);

$query = "select pe_codigo, pe_desc
               from wp_perfil";

$result = $objetoBaseDatos->query($query);

echo $result;

$records = json_decode($result);

if (!$records->success) {
    echo $result;
} else {
    $queryI = "select top 10 cci_cliente, cno_cliente from biz_fac..tb_fac_factura where cci_empresa = '009'";

    $resultInterfaz = $objetoBaseDatosInterfaz->query($queryI);

    print_r($resultInterfaz);
}


//echo $result;
//echo $query;

//$records = json_decode($result3);
//
//if (!$records->success) {
//    echo $result3;
//} else {
//    echo $result3 . '<br>';
//}

//if (!$records->success) {
//    echo $result;
//    $objetoBaseDatos->rollback();
//} else {
//    $objetoBaseDatos->commit();
//    echo $result . '<br>';
//}

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

