<?php

/*
  FECHA CREACION = 20/11/2019 AUTOR = GIOVANNI ZARATE
 */

/**
 * Description of 
 *
 * @author GIOVANNI
 */
class cheque_m extends fs_model {
    public $nrocheque;
    public $nrocuenta;
    public $fec_emision;
    public $fec_pago;   
    public $importe;
    public $aordende;
    public $idcliente;
    public $tipo;
    public $idbanco;
    
    public $banconombre;
    public $clientenombre;
    
    private static $search_tags;
    private static $cleaned_cache;


    public function __construct($c = FALSE) {
        parent::__construct('cheques');
        if ($c) {
            $this->nrocheque = $c['nrocheque'];
            $this->nrocuenta = $c['nrocuenta'];
            $this->fec_emision = NULL;
            if ($c['fec_emision'] != '') {                
                $this->fec_emision = Date('d-m-Y', strtotime($c['fec_emision']));
            }
             $this->fec_pago = NULL;
           if ($c['fec_pago'] != '') {                
                $this->fec_pago = Date('d-m-Y', strtotime($c['fec_pago']));
            }

            $this->aordende = $c['aordende'];
            $this->importe = $c['importe'];
            $this->idcliente = $c['idcliente'];
            $this->tipo = $c['tipo'];
            $this->idbanco = $c['idbanco'];
            $this->banconombre = $c['banconombre'];
            $this->clientenombre = $c['clientenombre'];
           
        } else {
            $this->nrocheque = 0;
            $this->nrocuenta = '';
            $this->fec_emision = Date('d-m-Y');  //$this->fec_emision = date('d-m-Y H:i:s'); 
            $this->fec_pago = Date('d-m-Y');
            $this->aordende = '';
            $this->importe = 0;
            $this->idcliente = 0;
            $this->tipo = '';
            $this->idbanco = 0;   
             $this->banconombre = '';
              $this->clientenombre = '';
            
        }
    }

    protected function install() {
        $this->clean_cache();
    }

    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " "
                . "WHERE nrocheque = " . $this->var2str($this->nrocheque) . " "
                . "and idbanco ". $this->var2str($this->idbanco). ";");
    }

    public function exists() {
        if (is_null($this->nrocheque)) {
            return FALSE;
        } else
            return $this->db->select("SELECT * FROM " . $this->table_name . ""
                    . " WHERE nrocheque = " . $this->var2str($this->nrocheque) . ";");
    }
    
    public function existsnrotarjeta($nro,$banco) {
        if ($this->db->select("SELECT * FROM " . $this->table_name . ""
                . " WHERE nrocheque = " . $this->var2str($nro) . " "
                . "and idbanco = " . $this->var2str($banco) . ";")) {
            return FALSE;
        } else
            return TRUE;
    }

    public function save() {
        if ($this->test()) {
            $this->clean_cache();
            $hoy =  date('Y-m-d H:i:s');  
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET " .                        
                        " nrocuenta=" . $this->var2str($this->nrocuenta) .
                        " ,fec_emision=" . $this->var2str($this->fec_emision) .
                        " ,fec_pago=" . $this->var2str($this->fec_pago) .
                        " ,aordende=" . $this->var2str($this->aordende) .
                        " ,idcliente=" . $this->var2str($this->idcliente) .
                        " ,importe=" . $this->var2str($this->importe) .
                        " ,tipo=" . $this->var2str($this->tipo) .                                            
                        ", idbanco = " . $this->var2str($this->idbanco) .                        
                        " WHERE nrocheque = " . $this->var2str($this->nrocheque) . ";";                                            
            } else {
                $sql = "INSERT INTO " . $this->table_name . " 
                       (nrocheque,nrocuenta,fec_emision,fec_pago, aordende, importe,idcliente, tipo, 
                        idbanco) 
                        VALUES
                       (" . $this->var2str($this->nrocheque)
                         . ",".$this->var2str($this->nrocuenta)
                        . "," .$this->var2str($this->fec_emision)
                        . "," .$this->var2str($this->fec_pago)
                        . "," . $this->var2str($this->aordende)
                        . "," . $this->var2str($this->importe)
                        . "," . $this->var2str($this->idcliente)
                        . "," . $this->var2str($this->tipo)
                        . "," . $this->var2str($this->idbanco)                                            
                        . ");";
            }
            if ($this->db->exec($sql)) {
                //$this->exists = TRUE;
                // $this->id_plan = $this->db->lastval();
                return TRUE;
            }
            //return $this->db->exec($sql);
        } else {
            return FALSE;
        }
    }

    /**
     * Comprueba los datos del pais, devuelve TRUE si son correctos
     * @return boolean
     */
    public function test() {
        $status = FALSE;

        $this->nrocheque = trim($this->nrocheque);
        $this->fec_emision = trim($this->no_html($this->fec_emision));
        $this->importe = trim($this->no_html($this->importe));
        $this->idcliente = trim($this->no_html($this->idcliente));
        $this->idbanco = trim($this->no_html($this->idbanco));
        
        if (empty($this->nrocheque)) {
            $this->new_error_msg("Numero de Cheque No puede estar Vacio");
        } else if (empty($this->fec_emision)) {
            $this->new_error_msg("Fecha Emisión No puede estar Vacio");
        }else if (empty ($this->importe)) {
            $this->new_error_msg("Importe no puede estar Vacio."); 
        }else if (empty ($this->idcliente)) {
            $this->new_error_msg("Cliente no puede estar Vacio.");   
        }else if (empty ($this->idbanco)) {
            $this->new_error_msg("Banco no puede estar Vacio."); 
        }else
            $status = TRUE;

        return $status;
    }

    /**
     * Limpiamos la caché
     */
    private function clean_cache() {
        $this->cache->delete('m_cheque_all');
    }

    ///SE CREA CUANDO VA A CARGAR LOS DATOS EN LA GRILLA PORQUE HACE REFERENCIA A EL
    /**
     * Devuelve la url donde se pueden ver/modificar estos datos
     * @return string
     */
    public function url() {
        if (is_null($this->nrocheque)) {
            return "index.php?page=cheque";
        } else
            return "index.php?page=cheque_edit&cod=" . $this->nrocheque;
    }

    /**
     * Devuelve el empleado/agente con codagente = $cod
     * @param type $cod
     * @return \agente|boolean
     */
    public function get($cod) {        
        $a = $this->db->select("SELECT nrocheque,nrocuenta,fec_emision,"
                . "fec_pago,aordende,importe,c.idcliente,tipo,c.idbanco, b.nombre banconombre,cli.nombre clientenombre  "                
                . "FROM " . $this->table_name . " c join bancos b on c.idbanco=b.idbanco "
                    . "join clientes cli on c.idcliente=cli.idcliente "
                . "WHERE nrocheque = " . $this->var2str($cod) . ";");
        if ($a) {
            return new cheque_m($a[0]);
        } else
            return FALSE;
    }
    
//    
//    public function actualizamapasector($codigoestudio){
//        
//        $sql = "UPDATE " . $this->table_name . " SET " .                                            
//                 " mapa_sector = null ".               
//                "  WHERE id_plan = " . $this->var2str($codigoestudio) . ";";
//         return $this->db->exec($sql);
//    }
    
     
/*
     public function get_recarga()
    {
        $dir = new \chequerecarga_m;
        return $dir->all_from_chequerecarga($this->nrocheque);
    }
    
    
     public function get_by_tarjeta($vnrotarjeta) {
        $ec = $this->db->select("SELECT COUNT(*) cantidad,nrocuenta,idbanco "
                . "FROM " . $this->table_name . " "
                . "WHERE trim(nrocuenta) = " . trim($this->var2str($vnrotarjeta)) . " ;");
        if ($ec) {
            return new cheque_m($ec[0]);
        } else
            return FALSE;
    }
  */  

  

   
  
   public function all(){
      /// Leemos la lista de la caché
      $lista = $this->cache->get_array('m_cheque_all');
      if(!$lista)
      {
         /// si no encontramos los datos en caché, leemos de la base de datos
         $data = $this->db->select("SELECT * FROM ".$this->table_name." ORDER BY nrocheque ASC;");
         if($data)
         {
            foreach($data as $p)
            {
               $lista[] = new cheque_m($p);
            }
         }         
         /// guardamos la lista en caché
         $this->cache->set('m_cheque_all', $lista);
      }      
      return $lista;
   }
   
    public function get_new_codigo()
    {
       $sql = "SELECT MAX(".$this->db->sql_to_int('nrocheque').") as cod FROM ".$this->table_name.";";
       $cod = $this->db->select($sql);
       if($cod)
       {
          return 1 + intval($cod[0]['cod']);
       }
       else
          return 1;
    }
    
    
    
    
    /*
     public function actualiza_ultima_recarga($nro,$idbanco){
        $hoy =  date('Y-m-d H:i:s');
        $sql = "UPDATE " . $this->table_name . " SET " .                                            
                 " fechaultimarecarga = '" . $hoy. "' ".
                ", idbanco = idbanco + " . $idbanco. " ".
                "  WHERE nrocheque = " . $nro. ";";
         return $this->db->exec($sql);
    }
    */
    
      //Para verificar si ya existe
   public function exists_nrocheque_banco($vnro,$vbanco) {
        ///PUEDE SER TAMBIEN ASI
        $sql = "SELECT COUNT(*) as cod FROM cheques WHERE  "
                . "nrocheque=".trim($vnro)." and idbanco= ".$vbanco;
        $cod = $this->db->select($sql);
        if ($cod[0]['cod']==0) {
            return FALSE;
        } else
             return TRUE;
    } 

    //BUSCADOR DE CHEQUE EN OPERACION
    
public function get_search_tags()
    {
        if (!isset(self::$search_tags)) {
            self::$search_tags = $this->cache->get_array('articulos_searches');
        }

        return self::$search_tags;
    }    
 private function new_search_tag($tag)
    {
        $encontrado = FALSE;
        $actualizar = FALSE;

        if (strlen($tag) > 1) {
            /// obtenemos los datos de memcache
            $this->get_search_tags();

            foreach (self::$search_tags as $i => $value) {
                if ($value['tag'] == $tag) {
                    $encontrado = TRUE;
                    if (time() + 5400 > $value['expires'] + 300) {
                        self::$search_tags[$i]['count'] ++;
                        self::$search_tags[$i]['expires'] = time() + (self::$search_tags[$i]['count'] * 5400);
                        $actualizar = TRUE;
                    }
                    break;
                }
            }
            if (!$encontrado) {
                self::$search_tags[] = array('tag' => $tag, 'expires' => time() + 5400, 'count' => 1);
                $actualizar = TRUE;
            }

            if ($actualizar) {
                $this->cache->set('articulos_searches', self::$search_tags, 5400);
            }
        }

        return $encontrado;
    }
 public function search($query = '', $offset = 0, $codbanco = '', $codtipo = '')
    {
        $artilist = array();
        $query = $this->no_html(mb_strtolower($query, 'UTF8'));

        if ($query != '' && $offset == 0 && $codbanco == '' && $codtipo == '') {
            /// intentamos obtener los datos de memcache
            if ($this->new_search_tag($query)) {
                $artilist = $this->cache->get_array('articulos_search_' . $query);
            }
        }

        if (count($artilist) <= 1) {
            $sql = "SELECT *, b.nombre banconombre,cli.nombre clientenombre "
                    . "FROM " . $this->table_name . " c join bancos b on c.idbanco=b.idbanco "
                    . "join clientes cli on c.idcliente=cli.idcliente ";
            $separador = ' WHERE';

            if ($codbanco != '') {
                $sql .= $separador . " codbanco = " . $this->var2str($codbanco);
                $separador = ' AND';
            }

            if ($codtipo != '') {
                $sql .= $separador . " codtipo = " . $this->var2str($codtipo);
                $separador = ' AND';
            }

            

            if ($query == '') {
                /// nada
            } else if (is_numeric($query)) {
                $sql .= $separador . " (nrocheque = " . $this->var2str($query)
                    . " OR nrocheque LIKE '%" . $query . "%'"
                    . " OR nrocuenta LIKE '%" . $query . "%'"
                    . " OR aordende LIKE '%" . $query . "%'". ")";
                $separador = ' AND';
            } else {
                /// ¿La búsqueda son varias palabras?
                $palabras = explode(' ', $query);
                if (count($palabras) > 1) {
                    $sql .= $separador . " (lower(nrocheque) = " . $this->var2str($query)
                        . " OR lower(nrocheque) LIKE '%" . $query . "%'"
                        . " OR lower(nrocuenta) LIKE '%" . $query . "%'"
                        . " OR lower(aordende) LIKE '%" . $query . "%'"
                        . " OR (";

                    foreach ($palabras as $i => $pal) {
                        if ($i == 0) {
                            $sql .= "lower(nrocheque) LIKE '%" . $pal . "%'";
                        } else {
                            $sql .= " AND lower(nrocheque) LIKE '%" . $pal . "%'";
                        }
                    }

                    $sql .= "))";
                } else {
                    $sql .= $separador . " (lower(nrocheque) = " . $this->var2str($query)
                        . " OR lower(nrocheque) LIKE '%" . $query . "%'"
                        . " OR lower(nrocuenta) LIKE '%" . $query . "%'"
                        . " OR lower(aordende) LIKE '%" . $query . "%'" .")";
                }
            }

            if (strtolower(FS_DB_TYPE) == 'mysql') {
                $sql .= " ORDER BY lower(nrocheque) ASC";
            } else {
                $sql .= " ORDER BY nrocheque ASC";
            }

            $artilist = $this->all_from($sql, $offset);
        }

        return $artilist;
    }

    private function all_from($sql, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $artilist = array();
        $data = $this->db->select_limit($sql, $limit, $offset);
        if ($data) {
            foreach ($data as $a) {
                $artilist[] = new cheque_m($a);
            }
        }

        return $artilist;
    }
    
    /*
    //PARA ACTUALIZAR EL SALDO
     public function actualiza_idbanco_tarjeta($nro,$idbanco){
        $hoy =  date('Y-m-d');
        $sql = "UPDATE " . $this->table_name . " SET " .                                            
                 " fechaultimouso = '" . $hoy. "' ".
                ", tipo = tipo + " . $idbanco. " ".
                ", idbanco = idbanco - " . $idbanco. " ".
                "  WHERE trim(nrocuenta) = trim('" . $nro. "');";
         return $this->db->exec($sql);
    }
     * */
     
}
