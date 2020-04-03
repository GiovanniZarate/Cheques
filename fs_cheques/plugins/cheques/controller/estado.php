<?php

/**
 * Description of sys_distrito
 *
 * @author GIOVANNI ZARATE : FECHA: 31/05/2019
 */
require_model('estado_m.php');

class estado extends fs_controller {

    public $estado;
    public $total_resultados;
    public $resultados;


    public function __construct() {
        parent::__construct(__CLASS__, 'Estado', 'Definiciones', FALSE);
    }

    protected function private_core() {
 
        //INSTANCIA EL MODELO in_profesion_m 
        $this->estado = new estado_m();

        //PREGUNTA SI SE ENVIO UN CODIGO POR EL METODO POST
        if (isset($_POST['vnombre'])) {
            //CREA UNA NUEVA INSTANCIA DEL MODELO 
            $distrito = new estado_m();

            //$distrito->iddistrito = $distrito->get_new_codigo();
            $distrito->nombre = $_POST['vnombre'];


            ///GUARDA LOS DATOS SI NO DA ERROR
            if ($distrito->save()) {
                $this->new_message("Estado " . $distrito->nombre . " guardado correctamente.");
            } else
                $this->new_error_msg("¡Imposible guardar!");
        }else if (isset($_GET['delete'])) {

            $distrito = $this->estado->get($_GET['delete']);
            if ($distrito) {
                if ($distrito->delete()) {
                    $this->new_message("Estado " . $distrito->nombre . " eliminado correctamente.");
                } else
                    $this->new_error_msg("¡Imposible eliminar!");
            } else
                $this->new_error_msg("¡Estado no encontrado!");
        }

        //PARA HACER LA PAGINACION
        $this->offset = 0;
        if (isset($_GET['offset'])) {
            $this->offset = intval($_GET['offset']);
        }
        //PARA ORDENAR DE ACUERDO A LOS PARAMETROS
        $this->orden = 'nombre ASC';
        if (isset($_REQUEST['orden'])) {
            $this->orden = $_REQUEST['orden'];
        }

        $this->buscar();       
    }

    //METODO PARA BUSCAR DATOS
    private function buscar() {
        $this->total_resultados = 0;
        $query = mb_strtolower($this->empresa->no_html($this->query), 'UTF8');
        $sql = " FROM estado";
        $and = ' WHERE ';
        //is_numeric = Comprueba si una variable es un número o un string numérico
        if (is_numeric($query)) {
            $sql .= $and . "(idestado LIKE '%" . $query . "%'"
                    . " OR nombre LIKE '%" . $query . "%')";
            $and = ' AND ';
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= $and . "(lower(nombre) LIKE '%" . $buscar . "%')";
            $and = ' AND ';
        }

        $data = $this->db->select("SELECT COUNT(idestado) as total" . $sql . ';');
        if ($data) {
            $this->total_resultados = intval($data[0]['total']);

            $data2 = $this->db->select_limit("SELECT *" . $sql . " ORDER BY " . $this->orden, FS_ITEM_LIMIT, $this->offset);
            if ($data2) {
                foreach ($data2 as $d) {
                    $this->resultados[] = new estado_m($d);
                }
            }
        }
    }

    //PARA HACER LA PAGINACION
    public function paginas() {
        $url = $this->url() . "&query=" . $this->query
                //."&idpais=".$this->pais     
                . "&orden=" . $this->orden;

        $paginas = array();
        $i = 0;
        $num = 0;
        $actual = 1;

        /// añadimos todas la página
        while ($num < $this->total_resultados) {
            $paginas[$i] = array(
                'url' => $url . "&offset=" . ($i * FS_ITEM_LIMIT),
                'num' => $i + 1,
                'actual' => ($num == $this->offset)
            );

            if ($num == $this->offset) {
                $actual = $i;
            }

            $i++;
            $num += FS_ITEM_LIMIT;
        }

        /// ahora descartamos
        foreach ($paginas as $j => $value) {
            $enmedio = intval($i / 2);

            /**
             * descartamos todo excepto la primera, la última, la de enmedio,
             * la actual, las 5 anteriores y las 5 siguientes
             */
            if (($j > 1 AND $j < $actual - 5 AND $j != $enmedio) OR ( $j > $actual + 5 AND $j < $i - 1 AND $j != $enmedio)) {
                unset($paginas[$j]);
            }
        }

        if (count($paginas) > 1) {
            return $paginas;
        } else {
            return array();
        }
    }

    //nombre tipo profesion
//    public function muestra_nombre($cod) {
//        $nombre = '-';
//        //$this->new_advice('hola '.$cod);
//        foreach ($this->departamentos as $g) {
//            if (trim($g->id_ciudad) == trim($cod)) {
//                $nombre = trim($g->nombre_largo);
//                break;
//            }
//        }
//
//        return $nombre;
//    }

}
