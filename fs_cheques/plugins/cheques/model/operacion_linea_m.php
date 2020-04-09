<?php
//namespace FacturaScripts\model;

//require_once __DIR__ . '/../../extras/linea_documento_venta.php';


class operacion_linea_m extends \fs_model
{

   // use \linea_documento_venta;  
    public $idcheque_operacion;
    public $idoperaciones;
    public $cheques_nrocheque;
    public $cheques_codbanco;

 
    private static $albaranes;
    public $nrocuenta;
    public $fec_emision;
    public $fec_pago;   
    public $importe;
    public $aordende;
    public $nombrecliente;
    public $banconombre;
    
    

    public function __construct($data = FALSE)
    {
        parent::__construct('cheque_operacion');

        if (!isset(self::$albaranes)) {
            self::$albaranes = array();
        }

        if ($data) {
           // $this->load_data_trait($data);
            $this->idcheque_operacion = $this->intval($data['idcheque_operacion']);
            $this->idoperaciones = $this->intval($data['idoperaciones']);
            $this->cheques_nrocheque = $this->intval($data['cheques_nrocheque']);
            $this->cheques_codbanco = $this->intval($data['cheques_codbanco']);
            
            $this->nrocuenta = $data['nrocuenta'];
            $this->fec_emision = NULL;
            if ($data['fec_emision'] != '') {                
                $this->fec_emision = Date('d-m-Y', strtotime($data['fec_emision']));
            }
             $this->fec_pago = NULL;
           if ($data['fec_pago'] != '') {                
                $this->fec_pago = Date('d-m-Y', strtotime($data['fec_pago']));
            }

            $this->aordende = $data['aordende'];
            $this->importe = $data['importe'];
            $this->nombrecliente = $data['nombrecliente'];
            $this->banconombre = $data['banconombre'];
        } else {
            //$this->clear_trait();
            $this->idcheque_operacion = NULL;
            $this->idoperaciones = NULL;
            $this->cheques_nrocheque = NULL;
            $this->cheques_codbanco = NULL;
            $this->nrocuenta = '';
            $this->fec_emision = Date('d-m-Y');  //$this->fec_emision = date('d-m-Y H:i:s'); 
            $this->fec_pago = Date('d-m-Y');
            $this->aordende = '';
            $this->importe = 0;
            $this->nombrecliente = '';
            $this->banconombre = '';
        }
    }

    /**
     * Completa con los datos del albarán.
     */
    private function fill()
    {
        $encontrado = FALSE;
        foreach (self::$albaranes as $a) {
            if ($a->idoperaciones == $this->idoperaciones) {
                $this->cheques_codbanco = $a->cheques_codbanco;
               // $this->fecha = $a->fecha;
                $encontrado = TRUE;
                break;
            }
        }
        if (!$encontrado) {
            $alb = new \operaciones_();
            $alb = $alb->get($this->idoperaciones);
            if ($alb) {
                $this->cheques_codbanco = $alb->cheques_codbanco;
               // $this->fecha = $alb->fecha;
                self::$albaranes[] = $alb;
            }
        }
    }

    

    public function show_cheques_codbanco()
    {
        if (!isset($this->cheques_codbanco)) {
            $this->fill();
        }

        return $this->cheques_codbanco;
    }

   

    public function show_nombrecliente()
    {
        $nombre = 'desconocido';

        foreach (self::$albaranes as $a) {
            if ($a->idoperaciones == $this->idoperaciones) {
                $nombre = $a->nombrecliente;
                break;
            }
        }

        return $nombre;
    }

    public function url()
    {
        return 'index.php?page=operaciones_edit&id=' . $this->idoperaciones;
    }
    
   

    /**
     * Devuelve los datos de una linea.
     * @param type $idlinea
     * @return boolean|\linea_albaran_cliente
     */
    public function get($idlinea)
    {
        $data = $this->db->select("SELECT *,'' nrocuenta,'' fec_pago,'' fec_emision, '' aordende, 0 importe,'' nombrecliente, '' banconombre     "
                . "FROM " . $this->table_name . " "
                . "WHERE idcheque_operacion = " . $this->var2str($idlinea) . ";");
        if ($data) {
            return new \operacion_linea_m($data[0]);
        }

        return FALSE;
    }

    public function exists()
    {
        if (is_null($this->idcheque_operacion)) {
            return FALSE;
        }

        return $this->db->select("SELECT * FROM " . $this->table_name . " "
                . "WHERE idcheque_operacion = " . $this->var2str($this->idcheque_operacion) . ";");
    }

    public function save()
    {
        //if ($this->test()) {
            $this->clean_cache();

            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET "
                    . "idoperaciones = " . $this->var2str($this->idoperaciones)
                    . ", cheques_nrocheque = " . $this->var2str($this->cheques_nrocheque)
                    . ", cheques_codbanco = " . $this->var2str($this->cheques_codbanco)                                        
                    . "  WHERE idcheque_operacion = " . $this->var2str($this->idcheque_operacion) . ";";

                return $this->db->exec($sql);
            }

            $sql = "INSERT INTO " . $this->table_name . " (
                idoperaciones,cheques_nrocheque,cheques_codbanco) VALUES
                      (" . $this->var2str($this->idoperaciones)
                . "," . $this->var2str($this->cheques_nrocheque)
                . "," . $this->var2str($this->cheques_codbanco)
                 . ");";

            if ($this->db->exec($sql)) {
                $this->idlinea = $this->db->lastval();
                return TRUE;
            }
      //  }

        return FALSE;
    }

    public function delete()
    {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE idcheque_operacion = " . $this->var2str($this->idcheque_operacion) . ";");
    }

    public function clean_cache()
    {
        $this->cache->delete('albcli_top_articulos');
    }

    /**
     * Devuelve las líneas del albarán.
     * @param type $id
     * @return \linea_albaran_cliente
     */
    public function all_from_albaran($id)
    {
        
        $linealist = array();
        $sql = "SELECT idcheque_operacion,idoperaciones,cheques_nrocheque,cheques_codbanco,
                b.nombre banconombre,c.nrocuenta,c.fec_emision,c.fec_pago,
                c.importe,c.aordende,
                cli.nombre nombrecliente  "
                . "FROM " . $this->table_name . " o "
                . "join cheques c on o.cheques_nrocheque=c.nrocheque 
                    and o.cheques_codbanco=c.idbanco 
                join bancos b on o.cheques_codbanco=b.idbanco
                join clientes cli on c.idcliente=cli.idcliente "
                . "WHERE idoperaciones = " . $this->var2str($id)
            . " ORDER BY idcheque_operacion ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $l) {
                $linealist[] = new \operacion_linea_m($l);
            }
        }

        return $linealist;
    }
    
    //TRAE POR CHEQUE PARA CARGAR EL RESPALDO DE DICHO CHEQUE
    public function all_from_operaciondetaxcheque($id)
    {
        
        $linealist = array();
        $sql = "SELECT idcheque_operacion,idoperaciones,cheques_nrocheque,cheques_codbanco,
                b.nombre banconombre,c.nrocuenta,c.fec_emision,c.fec_pago,
                c.importe,c.aordende,
                cli.nombre nombrecliente  "
                . "FROM " . $this->table_name . " o "
                . "join cheques c on o.cheques_nrocheque=c.nrocheque 
                    and o.cheques_codbanco=c.idbanco 
                join bancos b on o.cheques_codbanco=b.idbanco
                join clientes cli on c.idcliente=cli.idcliente "
                . "WHERE cheques_nrocheque = " . $this->var2str($id)
            . " ORDER BY idcheque_operacion ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $l) {
                $linealist[] = new \operacion_linea_m($l);
            }
        }

        return $linealist;
    }

    private function all_from($sql, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $linealist = array();
        $data = $this->db->select_limit($sql, $limit, $offset);
        if ($data) {
            foreach ($data as $a) {
                $linealist[] = new \operacion_linea_m($a);
            }
        }

        return $linealist;
    }

    public function all_from_articulo($ref, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE referencia = " . $this->var2str($ref)
            . " ORDER BY idoperaciones DESC";

        return $this->all_from($sql, $offset, $limit);
    }

    /**
     * Busca todas las coincidencias de $query en las líneas.
     * @param string $query
     * @param integer $offset
     * @return \linea_pedido_cliente
     */
    public function search($query = '', $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE ";
        if (is_numeric($query)) {
            $sql .= "cheques_nrocheque LIKE '%" . $query . "%' ";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "cheques_nrocheque LIKE '%" . $buscar . "%' ";
        }
        $sql .= " ORDER BY idoperaciones DESC, idcheque_operacion ASC";

        return $this->all_from($sql, $offset);
    }

    /*public function search_from_cliente($codcliente, $query = '', $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE idoperaciones IN
         (SELECT idoperaciones FROM albaranescli WHERE codcliente = " . $this->var2str($codcliente) . ") AND ";
        if (is_numeric($query)) {
            $sql .= "(referencia LIKE '%" . $query . "%' OR descripcion LIKE '%" . $query . "%')";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "(lower(referencia) LIKE '%" . $buscar . "%' OR lower(descripcion) LIKE '%" . $buscar . "%')";
        }
        $sql .= " ORDER BY idoperaciones DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function search_from_cliente2($codcliente, $ref = '', $obs = '', $offset = 0)
    {
        $ref = mb_strtolower($this->no_html($ref), 'UTF8');
        $obs = mb_strtolower($this->no_html($obs), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE idoperaciones IN
         (SELECT idoperaciones FROM albaranescli WHERE codcliente = " . $this->var2str($codcliente) . "
         AND lower(observaciones) LIKE '" . $obs . "%') AND ";
        if (is_numeric($ref)) {
            $sql .= "(referencia LIKE '%" . $ref . "%' OR descripcion LIKE '%" . $ref . "%')";
        } else {
            $ref = str_replace(' ', '%', $ref);
            $sql .= "(lower(referencia) LIKE '%" . $ref . "%' OR lower(descripcion) LIKE '%" . $ref . "%')";
        }
        $sql .= " ORDER BY idoperaciones DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function last_from_cliente($codcliente, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE idoperaciones IN"
            . " (SELECT idoperaciones FROM albaranescli WHERE codcliente = " . $this->var2str($codcliente) . ")"
            . " ORDER BY idoperaciones DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }
*/
    public function count_by_articulo()
    {
        $data = $this->db->select("SELECT COUNT(DISTINCT cheques_nrocheque) as total FROM " . $this->table_name . ";");
        if ($data) {
            return intval($data[0]['total']);
        }

        return 0;
    }
    
    
     public function urlrespaldo($nroope,$nrocheque) {
         $this->new_advice("nroope ".$nroope." NRO CHE ".$nrocheque);
         
         $this->idoperaciones =$nroope;
         $this->cheques_nrocheque = $nrocheque;
        /*if (is_null($this->idoperaciones)) {
             return "index.php?page=operaciones_edit&id=" . $this->idoperaciones ."&vnrocheque=".$this->cheques_nrocheque."#detalles"; //&cliente=
        } else
           return "index.php?page=operaciones_edit&id=" . $this->idoperaciones ."&vnrocheque=".$this->cheques_nrocheque."#detalles";  
         * */
         //$this->all_from_operaciondetaxcheque($this->cheques_nrocheque);
         return "index.php?page=operaciones_edit&id=" . $nroope ."&vnrocheque=".$nrocheque."#detalles";  
               
    }
    
    public function get_respaldo()
    
    {
        //$this->new_advice("nroope ".$this->idoperaciones." NRO CHE ".$this->cheques_nrocheque);
        $dir = new \respaldo_m;
        return $dir->all_from_respaldo($this->idoperaciones,$this->cheques_nrocheque);
    }
    
    
//    public function get_lineas()
//    {
//        $linea = new \respaldo_m();
//        return $linea->all_from_respaldo($this->idoperaciones,$this->cheques_nrocheque);
//    }
    
}
