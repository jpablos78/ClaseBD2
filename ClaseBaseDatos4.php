<?php

include_once 'ClaseJson2.php';

error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("America/Guayaquil");

/**
 * Description of ClaseBaseDatos4
 *
 * @author jpsanchez
 */
class ClaseBaseDatos4 {

    private $mssql;
    private $servidor;
    private $base;
    private $usuario;
    private $clave;
    private $result;
    private $autocommit;
    private $parametros = array(
        'connect' => true,
        'interfaz' => '',
        'autocommit' => true,
        'json' => true,
        'debug' => false,
        'verificarPermisos' => true
    );

    public function conectarse() {
        $this->servidor = _SERVIDOR;
        $this->base = _BASE;
        $this->usuario = _USUARIO;
        $this->clave = _CLAVE;

        $this->mssql = odbc_connect("Driver={SQL Server};Server=" . $this->servidor . ";Database=" . $this->base . ";", $this->usuario, $this->clave);

        if ($this->mssql) {
            return ClaseJson2::getJson(array(
                        "success" => 'true',
                        "message" => utf8_encode("Conexión Exitosa"))
            );
        } else {
            return ClaseJson2::getJson($this->getError());
        }
    }

    public function query($query) {
        $resp = odbc_exec($this->mssql, $query);

        if (!odbc_error()) {
            while ($row = odbc_fetch_array($resp)) {
                $registros[] = array_map('utf8_encode', $row);
            }

            $result = array(
                "error" => 'N',
                "ok" => $ok,
                "message" => $mensaje,
                "data" => $registros
            );
        } else {
            $result = $this->getError();
        }

        return $result;
    }

    private function getError() {
        return array(
            "success" => 'false',
            "message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg())
//"message" => (odbc_error() . ' - ' . odbc_errormsg())
        );
    }

}
