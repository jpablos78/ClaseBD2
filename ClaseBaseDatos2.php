<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClaseBaseDatos2
 *
 * @author jpsanchez
 */
class ClaseBaseDatos2 {

    private $mssql;
    private $servidor;
    private $base;
    private $usuario;
    private $clave;
    private $result;

    public function __construct($parametros) {
        $interfaz = '';
        $query = $parametros['query'];
        /*
         * se busca si existe otra interfaz, caso contrario se coge la interfaz default
         */
        if (array_key_exists('interfaz', $parametros)) {
            $interfaz = $parametros['interfaz'];
        }

        switch ($interfaz) {
            case '':
                $this->servidor = _SERVIDOR;
                $this->base = _BASE;
                $this->usuario = _USUARIO;
                $this->clave = _CLAVE;
                break;
            case 'I':
                $this->servidor = _SERVIDORI;
                $this->base = _BASEI;
                $this->usuario = _USUARIOI;
                $this->clave = _CLAVEI;
                break;
        }

        $result = $this->conectarse();

        if ($result['error'] == 'N') {
            $this->result = $this->query($query);
        } else {
            $this->result = $result;
        }

        $this->desconectarse();
    }

    private function conectarse() {
        $conn = odbc_connect("Driver={SQL Server};Server=$this->servidor;Database=$this->base;", $this->usuario, $this->clave);

        if ($conn) {
            $this->mssql = $conn;

            $result = array(
                "error" => 'N',
                "message" => array("reason" => "Conexión Exitosa")
            );
        } else {
            $result = $this->getError();
        }

        return $result;
    }

    private function desconectarse() {
        odbc_close($this->mssql);
    }

    private function query($query) {
        $resp = odbc_exec($this->mssql, $query);

        if (!odbc_error()) {
            while ($row = odbc_fetch_array($resp)) {
                $registros[] = array_map('utf8_encode', $row);
            }

            $result = array(
                "error" => 'N',
                "data" => $registros
            );

            return $result;
        } else {
            return $result = $this->getError();
        }
    }

    private function getError() {
        //$result['error'] = 'error';
        //$result['data'] = array(array('msj' => odbc_error() . ' - ' . odbc_errormsg()));


        $result = array(
            "error" => 'S',
            //"message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg()),
            "data" => array(
                array("message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg()))
            )
        );

        return $result;
    }

    function getResult() {
        return $this->result;
    }

    function setResult($result) {
        $this->result = $result;
    }

}
