<?php

error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("America/Guayaquil");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClaseBaseDatos3
 *
 * @author jpsanchez
 */
class ClaseBaseDatos3 {

    private static $mssql;
    private static $servidor;
    private static $base;
    private static $usuario;
    private static $clave;
    private static $result;
    private static $autocommit;

    private static function conectarse($parametros) {
        $interfaz = '';
        $autocommit = false;

        /*
         * se busca si existe otra interfaz, caso contrario se coge la interfaz default
         */
        if (array_key_exists('interfaz', $parametros)) {
            $interfaz = $parametros['interfaz'];
        }

        /*
         * se busca si existe el parametro autocommit, para control de transacciones
         */
        if (array_key_exists('autocommit', $parametros)) {
            $interfaz = $parametros['autocommit'];
        }

        switch ($interfaz) {
            case '':
                self::$servidor = _SERVIDOR;
                self::$base = _BASE;
                self::$usuario = _USUARIO;
                self::$clave = _CLAVE;
                break;
            case 'I':
                self::$servidor = _SERVIDORI;
                self::$base = _BASEI;
                self::$usuario = _USUARIOI;
                self::$clave = _CLAVEI;
                break;
        }

        if ($autocommit) {
            self::$autocommit = true;
        }

        $conn = odbc_connect("Driver={SQL Server};Server=" . self::$servidor . ";Database=" . self::$base . ";", self::$usuario, self::$clave);

        if ($conn) {
            self::$mssql = $conn;

            $result = array(
                "error" => 'N',
                "message" => array("reason" => "Conexión Exitosa")
            );

            return $result;
        } else {
            return self::getError();
        }
    }

    private static function desconectarse() {
        odbc_close(self::$mssql);
    }

    public static function query($parametros) {
        $result = self::conectarse($parametros);
        $query = $parametros['query'];

        if ($result['error'] == 'N') {
            if (self::$autocommit) {
                self::autocommit(true);
            }

            $resp = odbc_exec(self::$mssql, $query);

            $mensaje = '';
            $ok = '';

            if ($resp[0]['mensaje']) {
                $mensaje = $resp[0]['mensaje'];
            }

            if (!odbc_error()) {
                if (self::$autocommit) {
                    self::commit();
                }

                while ($row = odbc_fetch_array($resp)) {
                    $registros[] = array_map('utf8_encode', $row);
                }

                if (array_key_exists('mensaje', $registros[0])) {
                    $mensaje = $registros[0]['mensaje'];
                }

                if (array_key_exists('ok', $registros[0])) {
                    $ok = $registros[0]['ok'];
                }

                $result = array(
                    "error" => 'N',
                    "ok" => $ok,
                    "message" => $mensaje,
                    "data" => $registros
                );

                self::desconectarse();
                return $result;
            } else {
                $result = self::getError();

                if (self::$autocommit) {
                    self::rollback();
                }

                self::desconectarse();
                return $result;
            }
        }

        self::desconectarse();
        return $result;
    }

    private static function getError() {
        $result = array(
            "error" => 'S',
            "message" => utf8_encode(odbc_error() . ' - ' . odbc_errormsg())
        );

        return $result;
    }

    private static function autocommit($autocommit = false) {
        odbc_autocommit(self::$mssql, $autocommit);
    }

    private static function commit() {
        odbc_commit(self::$mssql);
    }

    private static function rollback() {
        odbc_rollback(self::$mssql);
    }

}
