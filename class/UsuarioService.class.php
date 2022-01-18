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
include 'Usuario.class.php';
class UsuarioService {

    private $usuario = null;

    public function __construct($id_usuario=null, $nombre=null ) {
        $this->initUsuario();
    }

    private function initUsuario() {
        if ($this->usuario == NULL) {
            $this->usuario = new Usuario();
        }
    }

    public function save(Usuario $item) {
        $this->initUsuario();
        $res = $this->usuario->save($item);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function update(Usuario $item) {
        $this->initUsuario();

        $res = $this->usuario->update($item);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

   public function delete(Usuario $item) { //TODO AL ELIMINAR UN USUARIO, COMPROBAR QUE NO TENGA NINGUNA ENTRADA ASOCIADA
        $this->initUsuario();

        $res = $this->usuario->delete($item);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function find($id) {
        $this->initUsuario();
        $usuario = $this->usuario->find($id);

        if (!$usuario) {
            return false;
        } else {
            return $usuario;
        }
    }

    public function findAll() {
        $this->initUsuario();
        return $this->usuario->findAll();
    }

    public function saveFromCSV($fichero) {
        $this->initUsuario();
        $res = $this->usuario->saveFromCSV($fichero);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

}
