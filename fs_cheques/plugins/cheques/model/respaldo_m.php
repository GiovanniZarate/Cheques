<?php


/**
 * Description of estudio medico
 *
 * @author INFO_14_GIOVANNI  13/08/2019
 */
class respaldo_m extends fs_model{
    public $idrespaldo;
    public $idoperacion;    
    public $nrocheque;
    public $idbanco;
    public $importe;
    
    public $nombrebanco;
    
    
    
    public function __construct($o = FALSE) {
        parent::__construct('respaldos');
        if($o){
            $this->idrespaldo = $o['idrespaldo'];
            $this->idoperacion = $o['idoperacion'];
            $this->nrocheque = $o['nrocheque'];            
            $this->idbanco = $o['idbanco'];
            $this->importe = $o['importe'];
            $this->nombrebanco = $o['nombrebanco'];
            
            
        }else{
            $this->idrespaldo = NULL;
            $this->idoperacion = NULL;
            $this->nrocheque ='';
            $this->idbanco = NULL;
            $this->importe = 0;
            $this->nombrebanco = '';
            
            
        }
    }

    protected function install() {
        
    }

    public function delete() {
        
    }

    public function exists() {
       if (is_null($this->idrespaldo)) {
            return FALSE;
        } else {
            return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idrespaldo = " . $this->var2str($this->idrespaldo) . ";");
        }  
    }

    public function save() {
        if ($this->test()) {
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET  "
                        . "  nrocheque = " . $this->var2str($this->nrocheque)                        
                        . ", idbanco = " . $this->var2str($this->idbanco)
                        . ", importe = " . $this->var2str($this->importe)                        
                        . "  WHERE idrespaldo = " . $this->var2str($this->idrespaldo) . ";";

            } else {
                //$this->new_numero();
                $sql = "INSERT INTO " . $this->table_name . " 
                    (idoperacion,nrocheque,idbanco,importe)
                   VALUES (" . $this->var2str($this->idoperacion)
                        . "," . $this->var2str($this->nrocheque)
                        . "," . $this->var2str($this->idbanco)
                        . "," . $this->var2str($this->importe).");";        
            }
              return $this->db->exec($sql);
        }else {
            return FALSE;
        }
    }
    
    public function test() {
        $status = FALSE;

        $this->nrocheque = trim($this->nrocheque);
        $this->idbanco = trim($this->no_html($this->idbanco));
        $this->idoperacion = trim($this->no_html($this->idoperacion));

//        if ($this->idbanco!=0) {
//            if (empty($this->idbanco)){
//                   $this->new_error_msg("Médico no Puede estar vacio."); 
//            }            
//        } else 
        
        if (empty($this->idoperacion)) {
            $this->new_error_msg("Codigo no Puede estar vacio.");
        } else if (empty($this->nrocheque)) {
            $this->new_error_msg("Cheque no puede estar vacio.");        
        } else
            $status = TRUE;

        return $status;
    }
    
    public function url() {
        if (is_null($this->idrespaldo)) {
            return 'index.php?page=contingencia';
        } else
            return 'index.php?page=contingencia_edit&cod=' . trim ($this->idrespaldo);
    }

     /**
     * Devuelve el asiento con el $id solicitado.
     * @param type $id
     * @return \asiento|boolean
     */
    public function get($id) {
        if (isset($id)) {
            $repro = $this->db->select("SELECT * "
                    . "FROM " . $this->table_name . " h  "
                    . "WHERE idoperacion = " . $this->var2str($id) . ";");
            if ($repro) {
                return new respaldo_m($repro[0]);
            } else
                return FALSE;
        } else
            return FALSE; 
    }
    
    
   
    
    
    
    public function all() {
        /// leemos esta lista de la caché
        $lista = $this->cache->get_array('m_respaldo_all');
        if (!$lista) {
            /// si no está en caché, leemos de la base de datos
            $data = $this->db->select("SELECT * FROM " . $this->table_name . ";");

            if ($data) {
                foreach ($data as $a) {
                    $lista[] = new respaldo_m($a);
                }
            }

            /// guardamos la lista en caché
            $this->cache->set('m_respaldo_all', $lista);
        }

        return $lista;
    }
    
    
    public function getrespaldo($id) {
        if (isset($id)) {
            $repro = $this->db->select("SELECT *  "
                    . "FROM " . $this->table_name . "   "
                    . "WHERE idrespaldo = " . $this->var2str($id) . ";");
            if ($repro) {
                return new respaldo_m($repro[0]);
            } else
                return FALSE;
        } else
            return FALSE; 
    }
    
    public function all_from_respaldo($cod,$codcheque)
    {
                       
        $dirlist = array();
        $sql = "SELECT * ,b.nombre nombrebanco  "
                . "FROM " . $this->table_name . " r "
                . "join bancos b  on r.idbanco=b.idbanco "
                . " WHERE idoperacion = " . $this->var2str($cod) ." "
                . "and nrocheque = ". $this->var2str($codcheque)
            . " ORDER BY idrespaldo ;";

        
        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $dirlist[] = new \respaldo_m($d);
            }
        }

        return $dirlist;
    }
    
    

    
    
    public function elimina_respaldo($ciconyuge){       
        $sql = "DELETE FROM " . $this->table_name . " " .                                                             
                "  WHERE idrespaldo = " . $this->var2str($ciconyuge) . ";";
         return $this->db->exec($sql);
    }
   
    
    
    

}
