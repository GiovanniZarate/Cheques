<?php

/**
 * Description of gift
 *
 * @author GIOVANNI ZARATE : 20/11/2019
 */
//require_model('cheque_m.php');

class cheque extends fs_controller {

    public $cheque;
    public $resultados;
    public $total_resultados;
    public $allow_delete;
    


    public $nroche;
    
  
     //PARA COMBOS
    public $banco;
    public $cliente;
    
    public function __construct() {
        parent::__construct(__CLASS__, 'Cheques', 'Definiciones');
    }

    protected function private_core() {
        
         $this->banco = new banco_m();
         $this->cliente = new cliente_m();
        //Instancia el modelo
        $this->cheque = new cheque_m();
        
          

        //2 PARA LA PAGINACIÓN
        $this->offset = 0;
        if (isset($_GET['offset'])) {
            $this->offset = intval($_GET['offset']);
        }

        ///GRABAR DESDE LA OTRA PAGINA DE CARGA
        if (isset($_POST['vnumero'])) {
            $gift = new cheque_m();    
            //$this->new_advice("verrr ". $this->existenrotarjeta($_POST['vnumero']));
           
            if ($gift->exists_nrocheque_banco($_POST['vnumero'],$_POST['vbanco'])) {
                     $this->new_error_msg("Nro. de Cheque Ingresado Ya Existe", 'Buscar..', FALSE, FALSE);
            }else{
//                // $this->new_message("GRABAR");
                

                $gift->nrocheque = $_POST['vnumero'];           
                $this->nroche = $_POST['vnumero'];           

                $gift->idbanco = $_POST['vbanco'];           

                $gift->fec_emision = $_POST['vfechaemision'];
                $gift->fec_pago = $_POST['vfechapago'];
                $gift->aordende = $_POST['vordende'];
                $gift->importe = $_POST['vimporte'];
                $gift->nrocuenta = $_POST['vnrocuenta'];                
                $gift->idcliente = $_POST['vcliente'];
                $gift->tipo = $_POST['vtipo'];                
                ///GUARDA LOS DATOS SI NO DA ERROR
                if ($gift->save()) {
                    $this->new_message("Cheque " . $gift->nrocheque . " guardado correctamente.");
                    //PARA ENVIAR A LA PAGINA DE BUSQUEDA UNA VEZ GRABADO                   
                     //header('location: ' . 'index.php?page=cheque_edit&cod='. $this->nroche);
                } else
                    $this->new_error_msg("¡Imposible guardar!");
            }             
        } else if (isset($_GET['delete'])) {
            $gift = $this->cheque->get($_GET['delete']);
            if ($gift) {
                if ($gift->delete()) {
                    $this->new_message("Cheque " . $gift->nrocheque . " eliminado correctamente.");
                } else
                    $this->new_error_msg("¡Imposible eliminar!");
            } else
                $this->new_error_msg("¡Cheque no encontrado!");
        }
        //3-PARA ORDENAR DE ACUERDO A LOS PARAMETROS
        $this->orden = 'nrocheque ASC';
        if (isset($_REQUEST['orden'])) {
            $this->orden = $_REQUEST['orden'];
        }

        //4-FUNCION BUSCAR DATOS 
        $this->buscar();
    }

    //5-METODO PRIVADO PARA CONSULTAR LOS DATOS EN LA BASE DE DATOS, FILTRANDO ORDENANDO,ECT
    private function buscar() {
        $this->total_resultados = 0;
        $query = mb_strtolower($this->empresa->no_html($this->query), 'UTF8');
        $sql = " FROM cheques c
                join bancos b on c.idbanco=b.idbanco
                join clientes cli on c.idcliente=cli.idcliente";
        $and = ' WHERE ';
        //is_numeric = Comprueba si una variable es un número o un string numérico
        if (is_numeric($query)) {
            $sql .= $and . "(nrocheque LIKE '%" . $query . "%')";
            $and = ' AND ';
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= $and . "(lower(aordende) LIKE '%" . $buscar . "%'"
                    . "OR lower(aordende) LIKE '%" . $buscar . "%' "
                    . "OR nrocheque LIKE '%" . $buscar . "%')";
            $and = ' AND ';
        }
        $data = $this->db->select("SELECT COUNT(*) as total" . $sql . ';');
        if ($data) {
            $this->total_resultados = intval($data[0]['total']);

            $data2 = $this->db->select_limit("SELECT *, b.nombre banconombre,cli.nombre clientenombre, "
                    . "case when tipo='C' then 'CHEQUE' ELSE 'EFECTIVO'END tipo,'' nrooperacion,'' estado "
                    . "" . $sql . " "
                    . "ORDER BY " . $this->orden, FS_ITEM_LIMIT, $this->offset);
            if ($data2) {
                foreach ($data2 as $d) {
                    $this->resultados[] = new cheque_m($d);
                }
            }
        }
    }

    
    //VERIFICA SI EXISTE NRO DE TARJETA
//    private function existenrotarjeta($nrotarjeta) {
//       if ( $this->db->select("SELECT count(*) FROM cheque WHERE  trim(numero)=trim('".$nrotarjeta."');")  == 0) {
//            return 0;
//        } else {
//            return 1;
//        }  
//    }
//    
    
    
     
    //6- PARA HACER LA PAGINACION
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

}
