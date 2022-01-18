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
require_once 'Db.class.php';

class Usuario {

    private $id;
    private $nombre;
    private $user; //Usuario de acceso a la aplicación.
    private $pass; //Contraseña para usuario con acceso a la aplicación.
    private $errores;
    private $DB = null;

    public function __construct($id_usuario = null, $nombre = null) {
        $this->id = $id_usuario;
        $this->nombre = $nombre;
        $this->initDB();
    }

    private function initDB() {
        if ($this->DB == NULL) {
            $this->DB = new Db();
        }
    }

    public function limpiarDatos($texto) { //Este método limpia el texto obtenido de caracteres especiales.
        $textoLimpio = preg_replace('([^A-Za-z]+ [A-Za-z])', '', $texto);
        return $textoLimpio;
    }

    public function validaNombre($nombre) {
        $pattern = "^([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\']+[\s])+([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])+[\s]?([A-Za-zÁÉÍÓÚñáéíóúÑ]{0}?[A-Za-zÁÉÍÓÚñáéíóúÑ\'])?$^";
        if (preg_match($pattern, $nombre)) {
            return true;
        } else {
            return false;
        }
    }

    public function compruebaIdValido($id) {
        $pattern = "^[0-9]+$^"; //pattern fecha
        if (preg_match($pattern, $id)) {
            return true;
        } else {
            return false;
        }
    }

    public function save(Usuario $item) {
        $resul = false;
        $this->initDB();
        $sql = "INSERT INTO usuarios (id_usuario,nombre) values (:id_usuario,:nombre)";

        $valores = [
            ':id_usuario' => $item->id_usuario,
            ':nombre' => $item->nombre,
        ];

        try {
            $this->DB->iniciaTransaccion();
            $ejec = $this->DB->ejecuta($sql, $valores);
            $resul = $this->DB->ejecutaCommit();
            echo 'Usuario ' . $item->nombre . ' insertado.';
        } catch (PDOException $e) {
            $this->DB->ejecutaRollBack();
            $this->addError($e->getMessage());
            $resul = false;
            // echo $e->getMessage();
        }
        return $resul;
    }

    public function update(Usuario $item) {
        $result = false;
        $this->initDB();
        $sql = "UPDATE usuarios SET nombre=:nombre,id_usuario=:id_usuario WHERE id_usuario=:id_usuario";

        $valores = [
            ':id_usuario' => $item->id_usuario,
            ':nombre' => $item->nombre,
        ];
        try {

            $this->DB->iniciaTransaccion();
            $ejec = $this->DB->ejecuta($sql, $valores);
            $resul = $this->DB->ejecutaCommit();
            echo 'Usuario ' . $item->id_usuario . ' actualizado a ' . $item->nombre;
            echo '<br>';
        } catch (PDOException $e) {
            $this->DB->ejecutaRollBack();
            $resul = false;
        }
        return $resul;
    }

    public function delete($usuario) {
        $this->initDB();
        $sql = "DELETE FROM usuarios WHERE id=:id";

        try {
            $res = $this->DB->ejecuta($sql, ['id' => $usuario->id]);
        } catch (PDOException $ex) {
            
        }
    }

    public function find($id) {
        $this->initDB();
        $resul = NULL;
        $sql = "SELECT * FROM usuarios WHERE id_usuario=:id";

        try {
            $res = $this->DB->ejecuta($sql, [':id' => $id]);
            $resul = $this->DB->obtenerFilaSiguienteObjeto($res[1], 'Usuario');
            echo $resul->nombre;
        } catch (PDOException $e) {
            
        }
    }

    public function findAll() {
        $this->initDB();
        $sql = "SELECT id_usuario as id,nombre FROM usuarios";
        $items = null;
        try {
            $res = $this->DB->ejecuta($sql, NULL);
            $items = $this->DB->obtenerFilaTodoObjeto($res[1], 'Usuario');
        } catch (PDOException $e) {
            
        }
        return $items;
    }

    public function saveFromCSV($fichero) {//TODO pasar el fichero como argumento
        $this->initDB();
        $con = $this->DB->link();
        $registroError = null;
        $flag = true; //Bandera para eliminar la cabecera del csv.
        $usuarioInsertado = 0;

        $archivo = fopen($fichero, "r");

        while (($datos = fgetcsv($archivo, 1000, ",")) != FALSE) {
            if ($flag) {
                $flag = false;
                continue;
            } else {
                $linea[] = array('id' => $datos[1], 'usuario' => $datos[2]);
            }
        }
        fclose($archivo);

        foreach ($linea as $indice => $value) {
            $id = $value["id"];
            $usuario = utf8_encode($value["usuario"]);
            if ($this->validaNombre($usuario) && $this->compruebaIdValido($id)) {
                try {
                    $stmt = $con->prepare("INSERT INTO usuarios (id_usuario,nombre) values (:id_usuario,:nombre)");
                    if ($stmt->execute(array(":id_usuario" => $id, ":nombre" => $usuario))) {
                        $usuarioInsertado++;
                    }
                } catch (PDOException $ex) {
                    // echo $ex->getMessage() . "<br>";
                }
            } else {
                //TODO CREAR MENSAJE O POP UP DE ERROR¿?
            }
            // echo 'Usuarios insertados: ' . $usuarioInsertado;
        }
        
    }

    public function getUsuario($user) {
        $this->initDB();
        $resul = NULL;
        $sql = "SELECT * FROM usuario_acceso WHERE user=:user";

        try {
            $res = $this->DB->ejecuta($sql, [':user' => $user]);
            $resul = $this->DB->obtenerFilaSiguienteObjeto($res[1], 'Usuario');
        } catch (PDOException $e) {
            
        }
        return $resul;
    }

    public function __get($name) {

        return property_exists(__CLASS__, $name) ? $this->$name : NULL;
    }

    public function __set($name, $value) {

        if (property_exists(__CLASS__, $name)) {

            $this->$name = $value;
        } else
            echo 'ERROR: No existe el atributo ' . $name;
    }

}
