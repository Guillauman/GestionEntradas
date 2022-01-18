<?php

/**
 * @author     Guillermo Rodríguez García
 */
?>
<?php

require_once 'Db.class.php';

class Entrada {

    private $nombre;
    private $id;
    private $fechaa;
    private $entrada;
    private $descanso;
    private $diario;
    private $DB = null;

    public function __construct($id_usuario = NULL, $fecha = NULL, $entrada = NULL, $descanso = NULL, $diario = NULL) {
        $this->id = $id_usuario;
        $this->fechaa = $fecha;
        $this->entrada = $entrada;
        $this->descanso = $descanso;
        $this->diario = $diario;
    }

    private function initDB() {
        if ($this->DB == NULL) {
            $this->DB = new Db();
        }
    }

    public function limpiarDatos($texto) { //Este método limpia el texto obtenido de caracteres especiales.
        $textoLimpio = preg_replace('([^A-Za-z0-9])', '', $texto);
        return $textoLimpio;
    }

    public function sumarHoras($horas) { //metodo que suma las horas del resumen.
        $total = 0;
        $hora = 0;
        $minuto = 0;
        $segundo = 00;
        foreach ($horas as $h) {
            $parts = explode(":", $h);
            $hora+=$parts[0];
            $minuto+=$parts[1];
            $segundo+=$parts[2];
            if ($minuto > 59) {
                $hora++;
                $minuto%=60;
            }if ($segundo > 59) {
                $minuto++;
                $segundo % 60;
            }
        }
        $total = $hora . ':' . $minuto . ':' . $segundo;
        return $total;
    }

    public function compruebaFechaValida($fecha) {
        $pattern = "^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$^"; //pattern fecha
        if (preg_match($pattern, $fecha)) {
            return true;
        } else {
            return false;
        }
    }

    public function compruebaIdValido($id) {
        $pattern = "^\d+$^"; //pattern fecha
        if (preg_match($pattern, $id)) {
            return true;
        } else {
            return false;
        }
    }

    public function compruebaTiempoValido($tiempo) {
        $pattern = "^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$^"; //pattern tiempo
        if (preg_match($pattern, $tiempo)) {
            return true;
        } else {
            return false;
        }
    }

    public function save(Entrada $item) {//inserta una entrada en la base de datos
        $resul = false;
        $this->initDB();
        $sql = "INSERT INTO entradas (id_usuario,fecha,entrada,descanso,diario) values (:id_usuario,:fecha,:entrada,:descanso,:diario)";

        $valores = [
            ':id_usuario' => $item->id,
            ':fecha' => $item->fechaa,
            ':entrada' => $item->entrada,
            ':descanso' => $item->descanso,
            ':diario' => $item->diario,
        ];
        try {
            $this->DB->iniciaTransaccion();
            $ejec = $this->DB->ejecuta($sql, $valores);
            $resul = $this->DB->ejecutaCommit();
        } catch (PDOException $ex) {
            $this->DB->ejecutaRollBack();
            $this->addError($ex->getMessage());
            $resul = false;
        }
        return $resul;
    }

    public function update(Entrada $item) {//actualiza una entrada de la base de datos.
        $resul = false;
        $this->initDB();
        $sql = "UPDATE entradas SET id_usuario=:id_usuario,fecha=:fecha,entrada=:entrada,descanso=:descanso,diario=:diario  WHERE id_usuario=:id_usuario and fecha=:fecha";

        $valores = [
            ':id_usuario' => $item->id_usuario,
            ':fecha' => $item->fecha,
            ':entrada' => $item->entrada,
            ':descanso' => $item->descanso,
            ':diario' => $item->diario,
        ];

        try {
            $this->DB->iniciaTransaccion();
            $ejec = $this->DB->ejecuta($sql, $valores);
            $resul = $this->DB->ejecutaCommit();
            echo 'Actualizdo el registro del usuario ' . $item->id_usuario . ' en la fecha ' . $item->fecha . ' a entrada: ' . $item->entrada . ' Descanso: ' . $item->descanso . ' Tiempo de trabajo: ' . $item->diario;
        } catch (PDOException $ex) {
            $this->DB->ejecutaRollBack();
            $resul = false;
        }
        return $resul;
    }

    public function delete($id, $fecha) {//borra una entrada de la base de datos.
        $this->initDB();
        $sql = "DELETE FROM entradas WHERE id_usuario=:id_usuario and fecha=:fecha";

        try {
            $res = $this->DB->ejecuta($sql, [':id_usuario' => $id, ':fecha' => $fecha]);
        } catch (PDOException $ex) {
            
        }
    }

    public function find($id_usuario) {//metodo que hace una consulta a la base de datos y devuelve una entrada.              //TODO Ver si se filtra por id o por fecha
        $this->initDB();
        $resul = NULL;
        $sql = "SELECT * FROM entradas WHERE id_usuario=:id_usuario";

        try {
            $res = $this->DB->ejecuta($sql, [':id_usuario' => $id_usuario]);
            $resul = $this->DB->obtenerFilaSiguienteObjeto($res[1], 'Entrada');
        } catch (PDOException $e) {
            
        }
    }

    public function findAll() {//método que hace una consulta a la base de datos y devuelve todas las entradas.
        $this->initDB();
        $sql = "SELECT
                        u.nombre AS nombre,
                        e.id_usuario AS id,
                        e.fecha AS fechaa,
                        e.entrada AS entrada,
                        e.descanso AS descanso,
                        e.diario AS diario
                    FROM
                        entradas e
                    INNER JOIN usuarios u ON
                        e.id_usuario = u.id_usuario  
                    ORDER BY `fechaa` ASC";
        $items = null;
        try {
            $res = $this->DB->ejecuta($sql, NULL);
            $items = $this->DB->obtenerFilaTodoObjeto($res[1], 'Entrada');
        } catch (PDOException $e) {
            
        }
        return $items;
    }

    public function findFiltrado($id_usuario, $desde, $hasta) { // metodo que recoge los parámetros introducidos en un formulario                                                       
        $this->initDB();                                                 //y ejecuta una consulta
        $con = $this->DB->link();
        $sql = "SELECT
                    u.nombre AS nombre,
                    e.id_usuario AS id,
                    e.fecha AS fechaa,
                    e.entrada AS entrada,
                    e.descanso AS descanso,
                    e.diario AS diario
                FROM
                    entradas e
                INNER JOIN usuarios u ON
                    e.id_usuario = u.id_usuario
                WHERE
                    e.id_usuario = :id_usuario
                HAVING
                    fechaa BETWEEN :desde AND :hasta
                ORDER BY
                    e.fecha";
        $items = null;
        $valores = [
            ':id_usuario' => $id_usuario,
            ':desde' => $desde,
            ':hasta' => $hasta,
        ];
        try {
            $res = $this->DB->ejecuta($sql, $valores);
            $items = $this->DB->obtenerFilaTodoObjeto($res[1], 'Entrada');
        } catch (PDOException $ex) {
            
        }
        return $items;
    }

    public function saveFromCSV($fichero) {//Este método vuelca un csv a la base de datos.
        $this->initDB();
        $con = $this->DB->link();

        $flag = true; //Bandera para eliminar la cabecera del csv.
        $registroInsertado = 0;

        $archivo = fopen($fichero, "r");

        while (($datos = fgetcsv($archivo, 1000, ",")) != FALSE) {
            if ($flag) {
                $flag = false;
                continue;
            } else {
                $linea[] = array('fecha' => $datos[0], 'id' => $datos[1], 'entrada' => $datos[3], 'diario' => $datos[8], 'descanso' => $datos[7]);
            }
        }
        fclose($archivo);

        foreach ($linea as $indice => $value) {

            $fecha = $value["fecha"];
            $id = $this->limpiarDatos($value["id"]);
            $entrada = $value["entrada"];
            $descanso = $value["descanso"];
            $diario = $value["diario"];
            if ($this->compruebaFechaValida($fecha) && $this->compruebaIdValido($id) && $this->compruebaTiempoValido($entrada) && $this->compruebaTiempoValido($descanso) && $this->compruebaTiempoValido($diario)) {
                try {
                    $stmt = $con->prepare("INSERT INTO entradas (id_usuario,fecha,entrada,descanso,diario) values (:id_usuario,STR_TO_DATE(REPLACE(:fecha,'/','.') ,GET_FORMAT(date,'EUR')),:entrada,:descanso,:diario)");
                    $valores = [
                        ':id_usuario' => $id,
                        ':fecha' => $fecha,
                        ':entrada' => $entrada,
                        ':descanso' => $descanso,
                        ':diario' => $diario,
                    ];
                    if ($stmt->execute($valores)) {
                        $registroInsertado++;
                    }
                } catch (PDOException $ex) {
                    // echo $ex->getMessage() . "<br>";
                }
            } else {
                
               
            }
        }
    }

    public function resumenMes($total_resultados_pagina) {//Este método ejecuta una consulta que muestra el las horas trabajadas y las de descanso por trabajador y mes.
        $this->initDB();
        $sql = "SELECT
                    u.id_usuario AS id,
                    u.nombre AS nombre,
                    e.fecha AS fechaa,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(e.diario))) AS diario,
                    SEC_TO_TIME(SUM(TIME_TO_SEC(e.descanso))) AS descanso
                FROM
                    usuarios u
                INNER JOIN entradas e ON
                    u.id_usuario = e.id_usuario
                WHERE
                    YEAR(e.fecha) = YEAR(
                        CURRENT_DATE - INTERVAL 1 MONTH
                    ) AND MONTH(e.fecha) = MONTH(
                        CURRENT_DATE - INTERVAL 1 MONTH
                    )
                GROUP BY
                    MONTH(fechaa),
                    YEAR(fechaa),
                    e.id_usuario
                ORDER BY
                    fechaa";

        $items = null;
        try {
            $res = $this->DB->ejecuta($sql, NULL);
            $items = $this->DB->obtenerFilaTodoObjeto($res[1], 'Entrada');
        } catch (PDOException $e) {
            
        }
        return $items;
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
