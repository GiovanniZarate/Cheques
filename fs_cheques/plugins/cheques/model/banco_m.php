<?php

/**
 * Description of sys_dpto_m
 *
 * @author GIOVANNI ZARATE : Fecha:31/05/2019
 */
class banco_m extends fs_model{
    
    public $idbanco;
    public $nombre;
    public $direccion;
    public $telefono;
    
    public function __construct($d=FALSE) {
        parent::__construct('bancos');
        if ($d) {
            $this->idbanco = $d['idbanco'];
            $this->nombre = $d['nombre'];
            $this->direccion = $d['direccion'];
            $this->telefono = $d['telefono'];
        } else {
            $this->idbanco = 0;
            $this->nombre = '';
            $this->direccion = '';
            $this->telefono = '';
        }
    }
    
    public function install() {
        $this->clean_cache();       
    }

    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE idbanco = " . $this->var2str($this->idbanco) . ";");
    }

    public function exists() {
        if (is_null($this->idbanco)) {
            return FALSE;
        } else
            return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idbanco = " . $this->var2str($this->idbanco) . ";");
    }

    public function save() {
        if ($this->test()) {
            $this->clean_cache();
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " "
                        . "SET "
                        . "nombre = " . $this->var2str($this->nombre) 
                        . ",direccion = " . $this->var2str($this->direccion) 
                        . ",telefono = " . $this->var2str($this->telefono) .
                        "  WHERE idbanco = " . $this->var2str($this->idbanco) . ";";
            } else {
                $sql = "INSERT INTO " . $this->table_name . " (nombre,direccion,telefono) VALUES
                        ("  . $this->var2str($this->nombre) . ", "
                        . ""  . $this->var2str($this->direccion) . ", "
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
        $this->cache->delete('m_banco_all');
    }

    
     /**
     * Comprueba los datos del pais, devuelve TRUE si son correctos
     * @return boolean
     */
    public function test() {
        $status = FALSE;

       // $this->idbanco = trim($this->idbanco);
        $this->nombre = $this->no_html($this->nombre);

        if (strlen($this->nombre) < 1 OR strlen($this->nombre) > 50) {
            $this->new_error_msg("Nombre no valido.");
        } else
            $status = TRUE;

        return $status;
    }
    
    /**
     * Devuelve la url donde se pueden ver/modificar estos datos
     * @return string
     */
    public function url() {
        if (is_null($this->idbanco)) {
            return "index.php?page=banco";
        } else
            return "index.php?page=banco_edit&cod=" . $this->idbanco;
    }
    
    /**
     * Devuelve un array con profesiones.
     * @return \dpto
     */
    public function all() {
        /// leemos esta lista de la caché
        $lista = $this->cache->get_array('m_banco_all');
        if (!$lista) {
            /// si no está en caché, leemos de la base de datos
            $data = $this->db->select("SELECT idbanco,trim(nombre) nombre,"
                    . "trim(direccion) direccion,trim(telefono) telefono "
                    . "FROM " . $this->table_name . "  ORDER BY nombre ASC;");

            if ($data) {
                foreach ($data as $a) {
                    $lista[] = new banco_m($a);
                }
            }

            /// guardamos la lista en caché
            $this->cache->set('m_banco_all', $lista);
        }

        return $lista;
    }
    
    /**
     * Devuelve el empleado/agente con codagente = $cod
     * @param type $cod
     * @return \|boolean
     */
    public function get($cod) {
        $a = $this->db->select("SELECT idbanco,trim(nombre) nombre,"
                . "trim(direccion) direccion,trim(telefono) telefono "
                . "FROM " . $this->table_name . " WHERE idbanco = " . $this->var2str($cod) . ";");
        if ($a) {
            return new banco_m($a[0]);
        } else
            return FALSE;
    }
    
  
    
    //PARA EL COMBO POR DEFECTO
   public function is_default(){
      return FALSE;
   }
}
