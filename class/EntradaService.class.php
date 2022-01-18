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
include 'Entrada.class.php';

class EntradaService {

    private $entrada = null;

    public function __construct() {
        $this->initEntrada();
    }

    private function initEntrada() {
        if ($this->entrada == NULL) {
            $this->entrada = new Entrada();
        }
    }

    public function save(Entrada $item) {
        $this->initEntrada();
        $res = $this->entrada->save($item);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function update(Entrada $item) {
        $this->initEntrada();

        $res = $this->entrada->update($item);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function delete($id, $fecha) {
        $this->initEntrada();
        $res = $this->entrada->delete($id, $fecha);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function find($id) { //TODO ver si se filtra por fecha o por id
        $this->initEntrada();
        $entrada = $this->entrada->find($id);

        if (!$id) {
            return false;
        } else {
            return $entrada;
        }
    }

    public function findFiltrado($id, $desde, $hasta) {
        $this->initEntrada();
        return $this->entrada->findFiltrado($id, $desde, $hasta);
    }

    public function findAll() {
        $this->initEntrada();
        return $this->entrada->findAll();
    }

    public function saveFromCSV($fichero) {
        $this->initEntrada();
        $res = $this->entrada->saveFromCSV($fichero);

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }

    public function resumenMes($total_resultados_pagina) {
        $this->initEntrada();
        return $this->entrada->resumenMes($total_resultados_pagina);
    }

}
