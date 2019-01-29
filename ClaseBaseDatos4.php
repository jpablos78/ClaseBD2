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
        'autocommit' => false,
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
            $this->autocommit($this->parametros['autocommit']);

            $result = ClaseJson2::getJson(array(
                        "success" => 'true',
                        "message" => utf8_encode("Conexión Exitosa"))
            );
        } else {
            $result = $this->getError();
        }

        if ($this->parametros['json']) {
            return ClaseJson2::getJson($result);
        }

        return $result;

//        if ($this->mssql) {
//            return ClaseJson2::getJson(array(
//                        "success" => 'true',
//                        "message" => utf8_encode("Conexión Exitosa"))
//            );
//        } else {
//            return ClaseJson2::getJson($this->getError());
//        }
    }

    public function desconectarse() {
        odbc_close($this->mssql);
    }

    public function query($query, $parametros = '') {
        print_r($this->parametros);

        if (is_array($parametros)) {
            $this->setParametros($parametros);
        }

        echo '<br>';

        print_r($this->parametros);

        if ($this->parametros['connect']) {
            $result = $this->conectarse();
        }

        if ($this->mssql) {
            $resp = odbc_exec($this->mssql, $query);

            $mensaje = '';
            $ok = '';

            if (!odbc_error()) {
                if ($this->parametros['autocommit']) {
                    $this->commit();
                }

                while ($row = odbc_fetch_array($resp)) {
                    $registros[] = array_map('utf8_encode', $row);
                }

                $result = array(
                    "success" => 'true',
                    "ok" => $ok,
                    "message" => $mensaje,
                    "data" => $registros
                );
            } else {
                $result = $this->getError();

                if ($this->parametros['autocommit']) {
                    $this->rollback();
                }
            }

            if ($this->parametros['json']) {
                $result = ClaseJson2::getJson($result);
            }

            if ($this->parametros['connect']) {
                $this->desconectarse();
            }
        }

        return $result;
    }

    private function getError() {
        return array(
            "success" => 'false',
            "message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg())
        );
    }

    private function autocommit($autocommit = false) {
        odbc_autocommit($this->mssql, $autocommit);
    }

    private function commit() {
        odbc_commit($this->mssql);
    }

    private function rollback() {
        odbc_rollback($this->mssql);
    }

    public function setParametros($parametros) {
        if (array_key_exists('connect', $parametros)) {
            $this->parametros['connect'] = $parametros['connect'];
        }

        if (array_key_exists('interfaz', $parametros)) {
            $this->parametros['interfaz'] = $parametros['interfaz'];
        }

        if (array_key_exists('autocommit', $parametros)) {
            $this->parametros['autocommit'] = $parametros['autocommit'];
        }

        if (array_key_exists('json', $parametros)) {
            $this->parametros['json'] = $parametros['json'];
        }

        if (array_key_exists('debug', $parametros)) {
            $this->parametros['debug'] = $parametros['debug'];
        }

        if (array_key_exists('verificarPermisos', $parametros)) {
            $this->parametros['verificarPermisos'] = $parametros['verificarPermisos'];
        }
    }

}
