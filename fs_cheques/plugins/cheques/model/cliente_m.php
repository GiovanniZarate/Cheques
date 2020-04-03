<?php

/**
 * Description of sys_dpto_m
 *
 * @author GIOVANNI ZARATE : Fecha:31/05/2019
 */
class cliente_m extends fs_model{
    
    public $idcliente;
    public $nombre;
    public $direccion;
    public $ruc;
    public $telefono;
    
    public function __construct($d=FALSE) {
        parent::__construct('clientes');
        if ($d) {
            $this->idcliente = $d['idcliente'];
            $this->nombre = $d['nombre'];
            $this->direccion = $d['direccion'];
            $this->ruc = $d['ruc'];
            $this->telefono = $d['telefono'];
        } else {
            $this->idcliente = 0;
            $this->nombre = '';
            $this->direccion = '';
            $this->ruc = '';
            $this->telefono = '';
        }
    }
    
    public function install() {
        $this->clean_cache();       
    }

    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " "
                . "WHERE idcliente = " . $this->var2str($this->idcliente) . ";");
    }

    public function exists() {
        if (is_null($this->idcliente)) {
            return FALSE;
        } else
            return $this->db->select("SELECT * FROM " . $this->table_name . 
                    " WHERE idcliente = " . $this->var2str($this->idcliente) . ";");
    }

    public function save() {
        if ($this->test()) {
            $this->clean_cache();
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " "
                        . "SET "
                        . "nombre = " . $this->var2str($this->nombre) 
                        . ",direccion = " . $this->var2str($this->direccion) 
                        . ",ruc = " . $this->var2str($this->ruc) 
                        . ",telefono = " . $this->var2str($this->telefono) .
                        "  WHERE idcliente = " . $this->var2str($this->idcliente) . ";";
            } else {
                $sql = "INSERT INTO " . $this->table_name . " (nombre,direccion,ruc,telefono) VALUES
                        ("  . $this->var2str($this->nombre) . ", "
                        . ""  . $this->var2str($this->direccion) . ", "
                        . ""  . $this->var2str($this->ruc) . ", "
                        . ""  . $this->var2str($this->telefono) . ");";
            }

            return $this->db->exec($sql);
        } else {
            return FALSE;
        }
    }
    
    
     /**
     * Limpiamos la caché
     */
    private function clean_cache() {
        $this->cache->delete('m_cliente_all');
    }

    
     /**
     * Comprueba los datos del pais, devuelve TRUE si son correctos
     * @return boolean
     */
    public function test() {
        $status = FALSE;

       // $this->idcliente = trim($this->idcliente);
        $this->nombre = $this->no_html($this->nombre);
        $this->ruc = $this->no_html($this->ruc);

        if (strlen($this->nombre) < 1 OR strlen($this->nombre) > 50) {
            $this->new_error_msg("Nombre no valido.");
        }else if(empty ($this->ruc))
            $this->new_error_msg("Ruc Vacio.");
        else
            $status = TRUE;

        return $status;
    }
    
    /**
     * Devuelve la url donde se pueden ver/modificar estos datos
     * @return string
     */
    public function url() {
        if (is_null($this->idcliente)) {
            return "index.php?page=cliente";
        } else
            return "index.php?page=cliente_edit&cod=" . $this->idcliente;
    }
    
    /**
     * Devuelve un array con profesiones.
     * @return \dpto
     */
    public function all() {
        /// leemos esta lista de la caché
        $lista = $this->cache->get_array('m_cliente_all');
        if (!$lista) {
            /// si no está en caché, leemos de la base de datos
            $data = $this->db->select("SELECT idcliente,trim(nombre) nombre,"
                    . "trim(direccion) direccion,trim(telefono) telefono,trim(ruc) ruc "
                    . "FROM " . $this->table_name . "  ORDER BY nombre ASC;");

            if ($data) {
                foreach ($data as $a) {
                    $lista[] = new cliente_m($a);
                }
            }

            /// guardamos la lista en caché
            $this->cache->set('m_cliente_all', $lista);
        }

        return $lista;
    }
    
    /**
     * Devuelve el empleado/agente con codagente = $cod
     * @param type $cod
     * @return \|boolean
     */
    public function get($cod) {
        $a = $this->db->select("SELECT idcliente,trim(nombre) nombre,"
                . "trim(direccion) direccion,trim(telefono) telefono,trim(ruc) ruc "
                . "FROM " . $this->table_name . " WHERE idcliente = " . $this->var2str($cod) . ";");
        if ($a) {
            return new cliente_m($a[0]);
        } else
            return FALSE;
    }
    
  
    
    //PARA EL COMBO POR DEFECTO
   public function is_default(){
      return FALSE;
   }
   
   
   
   public function search($query, $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $consulta = "SELECT * FROM " . $this->table_name . " WHERE  ";
        if (is_numeric($query)) {
            $consulta .= "(nombre LIKE '%" . $query . "%' OR ruc LIKE '%" . $query . "%')";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $consulta .= "(lower(nombre) LIKE '%" . $buscar . "%' OR lower(ruc) LIKE '%" . $buscar . "%')";
        }
        $consulta .= " ORDER BY lower(nombre) ASC";

        $data = $this->db->select_limit($consulta, FS_ITEM_LIMIT, $offset);
        return $this->all_from_data($data);
    }
    
     private function all_from_data(&$data)
    {
        $clilist = array();
        if ($data) {
            foreach ($data as $d) {
                $clilist[] = new \cliente_m($d);
            }
        }

        return $clilist;
    }
    
    
    public function get_by_cifnif($cifnif, $razon = FALSE)
    {
        if ($cifnif == '' && $razon) {
            $razon = $this->no_html(mb_strtolower($razon, 'UTF8'));
            $sql = "SELECT * FROM " . $this->table_name . " WHERE ruc = '' AND lower(nombre) = " . $this->var2str($razon) . ";";
        } else {
            $cifnif = mb_strtolower($cifnif, 'UTF8');
            $sql = "SELECT * FROM " . $this->table_name . " WHERE ruc = " . $this->var2str($cifnif) . ";";
        }

        $data = $this->db->select($sql);
        if ($data) {
            return new \cliente_m($data[0]);
        }

        return FALSE;
    }
}
