<?php

//error_reporting(0);
//
//function shutdown() {
//    $a = error_get_last();
//    if ($a == null)
//        echo "No errors";
//    else
//        print_r($a);
//}
//
//register_shutdown_function('shutdown');
//ini_set('max_execution_time', 1);
//while (1) {/* nothing */
//}
// will die after 1 sec and print error
error_reporting(0);

$servidor = "localhost";
//$base = "SistemaHomologacion";
$base = "BIZ_GEN";
$usuario = "sa";
$clave = "webpedidos*2013";

//echo $http_response_header;
//set_time_limit(10);
//ini_set('max_execution_time', 1);

echo '';

$conn = odbc_connect("Driver={SQL Server};Server=$servidor;Database=$base;", $usuario, $clave);

if ($conn) {
    echo 'conectado<br>';

    $query = 'SELECT top 10 cci_empresar, cci_sucursal FROM BIZ_GEN..TB_GEN_CLIPROV ';

    $result = odbc_exec($conn, $query);

    if (odbc_error()) {
        //echo 'error<br>';
        echo odbc_error() . ' - ' . odbc_errormsg();

        $resp = array(
            "success" => false,
            "data" => array(
                utf8_encode(odbc_error() . ' - ' . odbc_errormsg())
            )
        );


        //print_r($resp);
        echo json_encode($resp);
        
        //echo '<br>';

        echo utf8_decode(json_encode($resp));
    } else {
        echo 'correcto';

        //echo connection_status();
        //conn



        $registros = array();
        while ($row = odbc_fetch_array($result)) {
            $registros[] = array_map('utf8_encode', $row);
        }

        //print_r($registros);

        $resp = array(
            "success" => true,
            "total" => count($registros),
            "data" => $registros
        );

        echo json_encode($resp);
    }
} else {
    $result = array();
    $result['error'] = 'error';
    $result['data'] = array(array('msj' => odbc_error() . ' - ' . odbc_errormsg()));

    //print_r($result);

    echo(json_encode($result));
    echo'<br>';

    $result = array(
        "error" => "error",
        "data" => array('msj' => odbc_error() . ' - ' . odbc_errormsg())
    );

    echo(json_encode($result));
    echo'<br>';

    $result = array(
        "error" => "error",
        "data" => array(
            array('msj' => odbc_error() . ' - ' . odbc_errormsg())
        )
    );

    echo(json_encode($result));
    echo'<br>';

    //echo odbc_error() . ' - ' . odbc_errormsg();
}
?>

