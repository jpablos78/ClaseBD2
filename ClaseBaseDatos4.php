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
        'disconnect' => true,
        'interfaz' => '',
        'autocommit' => false,
        'json' => true,
        'debug' => false,
        'verificarPermisos' => true
    );

    public function conectarse() {
        switch ($this->parametros['interfaz']) {
            case '':
                $this->servidor = _SERVIDOR;
                $this->base = _BASE;
                $this->usuario = _USUARIO;
                $this->clave = _CLAVE;
                break;
            case 'I':
                $this->servidor = _SERVIDOR_I;
                $this->base = _BASE_I;
                $this->usuario = _USUARIO_I;
                $this->clave = _CLAVE_I;
                break;
        }

        $this->mssql = odbc_connect("Driver={SQL Server};Server=" . $this->servidor . ";Database=" . $this->base . ";", $this->usuario, $this->clave);

        if ($this->mssql) {
            $this->autocommit($this->parametros['autocommit']);

            $result = array(
                "success" => true,
                "message" => utf8_encode("Conexión Exitosa")
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
        //print_r($this->parametros);

        if (is_array($parametros)) {
            $this->setParametros($parametros);
        }

        //echo '<br>';
        //print_r($this->parametros);

        if ($this->parametros['connect']) {
            $result = $this->conectarse();           
        }

        if ($this->parametros['debug']) {
            echo '<hr>' . $query . '<hr>';
        }
        
        if ($this->mssql) {            
            $resp = odbc_exec($this->mssql, $query);
            print_r($resp);
            $mensaje = '';
            $ok = '';

            if (!odbc_error()) {
                echo 'no erroe';
                if ($this->parametros['autocommit']) {
                    $this->commit();
                }

                $registros = array();
                while ($row = odbc_fetch_array($resp)) {
                    $registros[] = array_map('utf8_encode', $row);
                }

                $result = array(
                    "success" => true,
                    "ok" => $ok,
                    "message" => $mensaje,
                    "data" => $registros
                );
            } else {
                echo 'hubo error';
                $result = $this->getError();

                if ($this->parametros['autocommit']) {
                    $this->rollback();
                }
            }

            if ($this->parametros['json']) {
                $result = ClaseJson2::getJson($result);
            }

            if ($this->parametros['disconnect']) {
                $this->desconectarse();
            }
        }

        return $result;
    }

    private function getError() {
        return array(
            "success" => false,
            "message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg())
        );
    }

    public function autocommit($autocommit = false) {
        odbc_autocommit($this->mssql, $autocommit);
    }

    public function commit() {
        odbc_commit($this->mssql);
    }

    public function rollback() {
        odbc_rollback($this->mssql);
    }

    public function setParametros($parametros) {
        if (array_key_exists('connect', $parametros)) {
            $this->parametros['connect'] = $parametros['connect'];
        }

        if (array_key_exists('disconnect', $parametros)) {
            $this->parametros['disconnect'] = $parametros['disconnect'];
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
