<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClaseBaseDatos
 *
 * @author jpsanchez
 */
class ClaseBaseDatos {

    private $mssql;
    private $servidor;
    private $base;
    private $usuario;
    private $clave;

    //private $result;

    public function __construct($s = '') {
        switch ($s) {
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

        $this->conectarse();
    }

    private function conectarse() {
        $this->servidor = _SERVIDOR;
        $this->base = _BASE;
        $this->usuario = _USUARIO;
        $this->clave = _CLAVE;

        $conn = odbc_connect("Driver={SQL Server};Server=$this->servidor;Database=$this->base;", $this->usuario, $this->clave);

        if ($conn) {
            $this->mssql = $conn;

            $result = array(
                "success" => true,
                "data" => "okis",
                "message" => array("reason" => "Conexión Exitosa")
            );
        } else {
            $result = $this->getError();
        }

        return $result;
    }

    private function query($query) {
        $resp = odbc_exec($this->mssql, $query);

        if (!odbc_error()) {
            $result = array();
            while ($row = odbc_fetch_array($resp)) {
                $result[] = array_map('utf8_encode', $row);
            }

            return $result;
        } else {
            return $result = $this->getError();
        }
    }

    private function getError() {
        $result = array(
            "success" => false,
            "message" => array("reason" => odbc_error() . ' - ' . odbc_errormsg())
        );
    }

}
