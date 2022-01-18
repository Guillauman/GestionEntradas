<?php

/**
 * @author     Guillermo Rodríguez García
 */

?>
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'config.php';

class Db {

    private $server;
    private $user;
    private $password;
    private $database;
    private $link;

    public function __construct() {
        $this->setConexion();
        $this->conectar();
    }

    private function setConexion() {
        $this->server = DB_HOST;
        $this->database = DB_NAME;
        $this->user = DB_USER;
        $this->password = DB_PASS;
        
       
    }

    private function conectar() {
        $dsn = 'mysql:host=' . $this->server . ';dbname=' . $this->database;
        try {
            $this->link = new PDO($dsn, $this->user, $this->password);
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->link->exec("set names utf8");
            
        } catch (PDOException $e) {
            echo 'Error al conectar con la base de datos: ' . $e->getMessage();
        }
    }

    public function ejecuta($sql, $valores) {
        $sth = $this->link->prepare($sql);
        $resul = $sth->execute($valores);
        return array($resul, $sth);
    }

    public function obtenerFilaSiguienteObjeto($sresul, $obj) {

        $sresul->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $obj);

        return $sresul->fetch();
    }

    public function obtenerFilaTodoObjeto($sresul, $obj) {

        $sresul->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $obj);

        return $sresul->fetchAll();
    }

    public function obtenerFilaSiguiente($sresul) {
        return $sresul->fetch();
    }

    public function obtenerTodo($sresul) {
        return $sresul->fetchAll();
    }

    public function iniciaTransaccion() {
        $this->link->beginTransaction();
    }

    public function ejecutaCommit() {
        return $this->link->commit();
    }

    public function ejecutaRollBack() {
        $this->link->rollBack();
    }

    public function get_link() {
        var_dump($this->link);
    }
    public function link(){
        return $this->link;
    }

}
